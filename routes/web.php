<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ReturnInController;
use App\Http\Controllers\BadDamageController;
use App\Http\Controllers\RequisitionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RegisterAdminController;
use App\Http\Controllers\DepartmentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->isSalesRep()) {
            return redirect()->route('cashier.dashboard');
        }
        if ($user->isSupervisor()) {
            return redirect()->route('supervisor.dashboard');
        }
        if ($user->isAuditor()) {
            return redirect()->route('auditor.dashboard');
        }
        if ($user->isAccountant()) {
            return redirect()->route('accountant.dashboard');
        }
        if ($user->isITAdmin() || $user->isHead()) {
            return redirect()->route('admin.dashboard');
        }
    }
    return view('welcome');
});

// Legacy dashboard alias to prevent broken links
Route::get('/dashboard', function() {
    return redirect('/');
})->name('dashboard')->middleware('auth');

// Authorize a logged-in operator to operate for a specific store
Route::post('/authorize-store', function (Request $request) {
    $request->validate([
        'store_id' => 'required|exists:stores,id',
    ]);

    $store = \App\Models\Store::findOrFail($request->store_id);

    if (! ($store->authorized ?? true)) {
        return redirect('/')->with('error', 'The selected store is not authorized to operate. Please contact an administrator.');
    }

    session([
        'authorized_store' => [
            'store_id' => $store->id,
            'name' => $store->name ?? ($store->host ?? 'Store'),
        ]
    ]);

    return back()->with('success', 'Authorized to sell for ' . ($store->name ?? 'selected store'));
})->middleware('auth');

// Deauthorize / switch store
Route::post('/deauthorize-store', function (Request $request) {
    session()->forget('authorized_store');
    return redirect('/')->with('success', 'Store deauthorized.');
})->middleware('auth');


// ==========================================
// 1. Cashier Group (Prefix: cashier/dashboard)
// ==========================================
Route::middleware(['auth'])->prefix('cashier/dashboard')->group(function () {
    Route::get('/', function() {
        return redirect()->route('cashier.sales.create');
    })->name('cashier.dashboard');

    Route::get('sales/create', [SaleController::class, 'create'])->name('cashier.sales.create');
    Route::post('sales', [SaleController::class, 'store'])->name('cashier.sales.store');
    Route::get('sales/{sale}', [SaleController::class, 'show'])->name('cashier.sales.show');
    Route::get('sales-history', [SaleController::class, 'cashierSales'])->name('cashier.sales.history');
});


// ==========================================
// 2. Supervisor Group (Prefix: supervisor/dashboard)
// ==========================================
Route::middleware(['auth', 'restrict.operator'])->prefix('supervisor/dashboard')->group(function () {
    Route::get('/', function() {
        return redirect()->route('supervisor.stock.allocate.form');
    })->name('supervisor.dashboard');

    // Receiving
    Route::get('stock/receive', [\App\Http\Controllers\StockController::class, 'receiveForm'])->name('supervisor.stock.receive.form');
    Route::post('stock/receive', [\App\Http\Controllers\StockController::class, 'receive'])->name('supervisor.stock.receive');

    // Allocating
    Route::get('stock/allocate', [\App\Http\Controllers\StockController::class, 'allocateForm'])->name('supervisor.stock.allocate.form');
    Route::post('stock/allocate', [\App\Http\Controllers\StockController::class, 'allocate'])->name('supervisor.stock.allocate');

    // Requisitions for Supervisors
    Route::get('requisitions', [\App\Http\Controllers\RequisitionController::class, 'index'])->name('supervisor.requisitions.index');
    Route::get('requisitions/create', [\App\Http\Controllers\RequisitionController::class, 'create'])->name('supervisor.requisitions.create');
    Route::post('requisitions', [\App\Http\Controllers\RequisitionController::class, 'store'])->name('supervisor.requisitions.store');

    // Damaged/Expired
    Route::resource('damaged-expired', \App\Http\Controllers\DamagedExpiredController::class)
        ->only(['index', 'create', 'store'])
        ->names([
            'index' => 'supervisor.damaged-expired.index',
            'create' => 'supervisor.damaged-expired.create',
            'store' => 'supervisor.damaged-expired.store',
        ]);

    // Scoped Suppliers
    Route::resource('suppliers', \App\Http\Controllers\SupplierController::class)
        ->only(['index', 'show'])
        ->names([
            'index' => 'supervisor.suppliers.index',
            'show' => 'supervisor.suppliers.show',
        ]);

    // Reports
    Route::get('reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('supervisor.reports.index');
});


// ==========================================
// 3. Auditor Group (Prefix: auditor/dashboard)
// ==========================================
Route::middleware(['auth', 'restrict.operator'])->prefix('auditor/dashboard')->group(function () {
    Route::get('/', [\App\Http\Controllers\AuditController::class, 'auditorDashboard'])->name('auditor.dashboard');
    Route::post('void/{receipt}/approve', [\App\Http\Controllers\AuditController::class, 'approveVoid'])->name('auditor.void.approve');
    Route::post('void/{receipt}/reject', [\App\Http\Controllers\AuditController::class, 'rejectVoid'])->name('auditor.void.reject');
    Route::post('writeoff/{id}/approve', [\App\Http\Controllers\AuditController::class, 'approveWriteOff'])->name('auditor.writeoff.approve');
    Route::post('writeoff/{id}/reject', [\App\Http\Controllers\AuditController::class, 'rejectWriteOff'])->name('auditor.writeoff.reject');

    // Recent Transactions review
    Route::get('sales', [SaleController::class, 'index'])->name('auditor.sales.index');
    Route::get('sales/{sale}', [SaleController::class, 'show'])->name('auditor.sales.show');
    Route::get('damaged-expired', [\App\Http\Controllers\DamagedExpiredController::class, 'index'])->name('auditor.damaged-expired.index');
    Route::get('reports', [ReportController::class, 'index'])->name('auditor.reports.index');
});


// ==========================================
// 4. Accountant Group (Prefix: accountant/dashboard)
// ==========================================
Route::middleware(['auth', 'restrict.operator'])->prefix('accountant/dashboard')->group(function () {
    Route::get('/', [\App\Http\Controllers\AuditController::class, 'accountantDashboard'])->name('accountant.dashboard');
    Route::get('sales', [SaleController::class, 'index'])->name('accountant.sales.index');
    Route::get('sales/{sale}', [SaleController::class, 'show'])->name('accountant.sales.show');
    Route::get('damaged-expired', [\App\Http\Controllers\DamagedExpiredController::class, 'index'])->name('accountant.damaged-expired.index');
    Route::get('reports', [ReportController::class, 'index'])->name('accountant.reports.index');
});


// ==========================================
// 5. IT Administrator & Head Group (Prefix: admin/dashboard)
// ==========================================
Route::middleware(['auth', 'admin'])->prefix('admin/dashboard')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::resource('categories', CategoryController::class)->names('admin.categories');
    Route::resource('suppliers', SupplierController::class)->names('admin.suppliers');
    Route::resource('products', ProductController::class)->names('admin.products');
    Route::post('products/{product}/assign', [ProductController::class, 'assignStore'])->name('admin.products.assign');
    Route::resource('users', UserController::class)->names('admin.users');
    Route::resource('departments', DepartmentController::class)->names('admin.departments');
    Route::resource('customers', CustomerController::class)->names('admin.customers');
    Route::get('customers/{customer}/wallet', [CustomerController::class, 'wallet'])->name('admin.customers.wallet');
    Route::resource('returns', ReturnInController::class)->names('admin.returns');
    Route::resource('bad-damages', BadDamageController::class)->names('admin.bad-damages');
    Route::resource('damaged-expired', \App\Http\Controllers\DamagedExpiredController::class)->only(['index'])->names('admin.damaged-expired');
    Route::resource('requisitions', RequisitionController::class)->names('admin.requisitions');
    Route::post('requisitions/{requisition}/approve', [RequisitionController::class, 'approve'])->name('admin.requisitions.approve');
    Route::post('requisitions/{requisition}/decline', [RequisitionController::class, 'decline'])->name('admin.requisitions.decline');

    Route::get('sales', [SaleController::class, 'index'])->name('admin.sales.index');
    Route::delete('sales/{sale}', [SaleController::class, 'destroy'])->name('admin.sales.destroy');
    Route::get('sales/create', [SaleController::class, 'create'])->name('admin.sales.create');
    Route::post('sales', [SaleController::class, 'store'])->name('admin.sales.store');
    Route::get('sales/{sale}', [SaleController::class, 'show'])->name('admin.sales.show');

    Route::get('reports', [ReportController::class, 'index'])->name('admin.reports.index');
    Route::get('reports/daily-sales', [ReportController::class, 'dailySales'])->name('admin.reports.daily_sales');
    Route::get('reports/category-sales', [ReportController::class, 'categorySales'])->name('admin.reports.category_sales');
    Route::get('reports/item-analysis', [ReportController::class, 'itemAnalysis'])->name('admin.reports.item_analysis');

    // Admin connection / setup toggles
    Route::get('/connect-portal', [AdminController::class, 'index'])->name('admin.connect.index');
    Route::post('/connect', [AdminController::class, 'connect'])->name('admin.connect.submit');
    Route::post('/update-user', [AdminController::class, 'updateUser'])->name('admin.update-user');

    Route::post('store/{store}/toggle-status', function (\App\Models\Store $store, Request $request) {
        $newStatus = $request->input('status', ($store->status ?? 'active'));
        $store->status = $newStatus;
        $store->save();
        return back()->with('success', 'Store status updated.');
    })->name('admin.stores.toggle-status');

    Route::post('store/{store}/authorize', function (\App\Models\Store $store, Request $request) {
        if (!\Illuminate\Support\Facades\Schema::hasColumn('stores', 'authorized')) {
            return back()->with('error', "Store authorization column is missing. Run migrations.");
        }
        $request->validate(['authorized' => 'required|boolean']);
        $store->authorized = $request->boolean('authorized');
        $store->save();
        return back()->with('success', 'Store authorization updated.');
    })->name('admin.stores.authorize');

    // Store management CRUD
    Route::get('stores', [AdminController::class, 'storesIndex'])->name('admin.stores.index');
    Route::get('stores/create', [AdminController::class, 'createStore'])->name('admin.stores.create');
    Route::post('stores', [AdminController::class, 'storeStore'])->name('admin.stores.store');
    Route::get('stores/{store}/edit', [AdminController::class, 'editStore'])->name('admin.stores.edit');
    Route::put('stores/{store}', [AdminController::class, 'updateStore'])->name('admin.stores.update');
    Route::delete('stores/{store}', [AdminController::class, 'destroyStore'])->name('admin.stores.destroy');

    // Moniepoint POS transaction logs for Admin
    Route::get('moniepoint-transactions', [\App\Http\Controllers\MoniepointController::class, 'adminIndex'])->name('admin.moniepoint.index');
});


// Custom Manual Auth Routes
Route::get('register-admin', [RegisterAdminController::class, 'showRegistrationForm'])->name('register-admin');
Route::post('register-admin', [RegisterAdminController::class, 'register'])->name('register-admin.submit');

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::get('admin/login', [AuthController::class, 'showAdminLoginForm'])->name('admin.login');
    Route::post('admin/login', [AuthController::class, 'adminLogin'])->name('admin.login.submit');
});

Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
Route::post('/_store-role', function (Request $request) {
    $request->validate(['role' => 'nullable|string']);
    session(['pre_logout_role' => $request->role]);
    return response()->json(['ok' => true]);
})->middleware('auth');