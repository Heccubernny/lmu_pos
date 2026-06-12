<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SaleItem;
use App\Models\Sale;
use App\Models\DamagedExpiredItem;
use App\Models\HallStock;
use App\Models\SupervisorStock;
use App\Models\Store;
use App\Models\Product;
use App\Models\ActivityLog;
use App\Models\SupplierReceipt;
use App\Models\StockAllocation;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AuditController extends Controller
{
    /**
     * Display the Auditor Dashboard.
     */
    public function auditorDashboard()
    {
        $user = auth()->user();
        if (!$user->isAuditor() && !$user->isITAdmin()) {
            return redirect('/')->with('error', 'Unauthorized access to Auditor Dashboard.');
        }

        // Fetch pending void requests (grouped by receipt number)
        $pendingVoids = Sale::where('status', 'pending_void')
            ->with(['store', 'product'])
            ->get()
            ->groupBy('recipt_number');

        // Fetch pending write-off requests
        $pendingWriteOffs = DamagedExpiredItem::where('status', 'pending')
            ->with(['product', 'store', 'user.person'])
            ->get();

        // Fetch recent activity logs
        $logs = ActivityLog::with('user.person')->latest()->take(30)->get();

        return view('audit.auditor_dashboard', compact('pendingVoids', 'pendingWriteOffs', 'logs'));
    }

    /**
     * Approve a void sale request.
     */
    public function approveVoid($receiptNumber)
    {
        $user = auth()->user();
        if (!$user->isAuditor() && !$user->isITAdmin()) {
            return back()->with('error', 'Unauthorized action.');
        }

        $saleItems = SaleItem::where('recipt_number', $receiptNumber)->where('status', 'pending_void')->get();

        if ($saleItems->isEmpty()) {
            return back()->with('error', 'No pending void transaction found for receipt: ' . $receiptNumber);
        }

        DB::beginTransaction();
        try {
            foreach ($saleItems as $item) {
                // Restore stock to the hall stock
                $hallStock = HallStock::firstOrCreate(
                    ['store_id' => $item->store_id, 'product_id' => $item->item_id],
                    ['quantity' => 0]
                );
                $hallStock->increment('quantity', $item->quantity_purchased);
            }

            // Update status to voided
            SaleItem::where('recipt_number', $receiptNumber)->update(['status' => 'voided']);

            DB::commit();

            ActivityLog::log(
                'void_approval',
                'Approved void for receipt ' . $receiptNumber . '. Stock restored to hall inventory.',
                $user->person_id
            );

            return back()->with('success', 'Sale void request approved and stock restored.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error approving void: ' . $e->getMessage());
        }
    }

    /**
     * Reject a void sale request.
     */
    public function rejectVoid($receiptNumber)
    {
        $user = auth()->user();
        if (!$user->isAuditor() && !$user->isITAdmin()) {
            return back()->with('error', 'Unauthorized action.');
        }

        DB::beginTransaction();
        try {
            SaleItem::where('recipt_number', $receiptNumber)
                ->where('status', 'pending_void')
                ->update(['status' => 'completed']);

            DB::commit();

            ActivityLog::log(
                'void_rejection',
                'Rejected void for receipt ' . $receiptNumber . '.',
                $user->person_id
            );

            return back()->with('success', 'Sale void request rejected. Status restored to completed.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error rejecting void: ' . $e->getMessage());
        }
    }

    /**
     * Approve a write-off request.
     */
    public function approveWriteOff($id)
    {
        $user = auth()->user();
        if (!$user->isAuditor() && !$user->isITAdmin()) {
            return back()->with('error', 'Unauthorized action.');
        }

        $item = DamagedExpiredItem::findOrFail($id);
        if ($item->status !== 'pending') {
            return back()->with('error', 'Write-off request is not pending.');
        }

        DB::beginTransaction();
        try {
            // Decrement the appropriate stock
            if ($item->store_id) {
                // Decrement from Hall Stock
                $stock = HallStock::where('store_id', $item->store_id)
                    ->where('product_id', $item->product_id)
                    ->first();

                if (!$stock || $stock->quantity < $item->quantity) {
                    throw new \Exception('Insufficient stock in the hall to write-off.');
                }
                $stock->decrement('quantity', $item->quantity);
            } else {
                // Decrement from Supervisor Store Stock
                $stock = SupervisorStock::where('supervisor_id', $item->user_id)
                    ->where('product_id', $item->product_id)
                    ->first();

                if (!$stock || $stock->quantity < $item->quantity) {
                    throw new \Exception('Insufficient stock in supervisor private store to write-off.');
                }
                $stock->decrement('quantity', $item->quantity);
            }

            // Update request status
            $item->update([
                'status' => 'approved',
                'approved_by' => $user->person_id,
            ]);

            DB::commit();

            $product = Product::find($item->product_id);
            $sourceName = $item->store_id ? Store::find($item->store_id)->name : 'Supervisor Private Stock';

            ActivityLog::log(
                'writeoff_approval',
                'Approved write-off for ' . $item->quantity . ' of ' . ($product ? $product->name : 'Product') . ' (' . $item->type . ') from ' . $sourceName . '.',
                $user->person_id
            );

            return back()->with('success', 'Write-off request approved and stock written off.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error approving write-off: ' . $e->getMessage());
        }
    }

    /**
     * Reject a write-off request.
     */
    public function rejectWriteOff($id)
    {
        $user = auth()->user();
        if (!$user->isAuditor() && !$user->isITAdmin()) {
            return back()->with('error', 'Unauthorized action.');
        }

        $item = DamagedExpiredItem::findOrFail($id);
        if ($item->status !== 'pending') {
            return back()->with('error', 'Write-off request is not pending.');
        }

        DB::beginTransaction();
        try {
            $item->update([
                'status' => 'rejected',
                'approved_by' => $user->person_id,
            ]);

            DB::commit();

            $product = Product::find($item->product_id);
            ActivityLog::log(
                'writeoff_rejection',
                'Rejected write-off for ' . $item->quantity . ' of ' . ($product ? $product->name : 'Product') . '.',
                $user->person_id
            );

            return back()->with('success', 'Write-off request rejected.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error rejecting write-off: ' . $e->getMessage());
        }
    }

    /**
     * Display the Accountant Dashboard and Reconciliation.
     */
    public function accountantDashboard(Request $request)
    {
        $user = auth()->user();
        if (!$user->isAccountant() && !$user->isITAdmin()) {
            return redirect('/')->with('error', 'Unauthorized access to Accountant Dashboard.');
        }

        $stores = Store::all();
        $supervisors = \App\Models\User::whereIn(DB::raw('lower(position)'), ['supervisor'])->get();

        // Filters
        $storeId = $request->input('store_id');
        $supervisorId = $request->input('supervisor_id');
        $period = $request->input('period', 'today');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if ($period !== 'custom') {
            switch ($period) {
                case 'today':
                    $startDate = now()->startOfDay()->toDateString();
                    $endDate = now()->endOfDay()->toDateString();
                    break;
                case 'yesterday':
                    $startDate = now()->subDay()->startOfDay()->toDateString();
                    $endDate = now()->subDay()->endOfDay()->toDateString();
                    break;
                case 'this_week':
                    $startDate = now()->startOfWeek()->toDateString();
                    $endDate = now()->endOfWeek()->toDateString();
                    break;
                case 'this_month':
                    $startDate = now()->startOfMonth()->toDateString();
                    $endDate = now()->endOfMonth()->toDateString();
                    break;
                case 'last_30_days':
                    $startDate = now()->subDays(30)->toDateString();
                    $endDate = now()->toDateString();
                    break;
                default:
                    $startDate = now()->toDateString();
                    $endDate = now()->toDateString();
                    break;
            }
        } else {
            $startDate = $startDate ?: now()->toDateString();
            $endDate = $endDate ?: now()->toDateString();
        }

        // 1. Gross Sales and Units Sold
        $salesQuery = DB::table('pos_sales_items')
            ->where('status', 'completed')
            ->whereBetween('date', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);

        if ($storeId) {
            $salesQuery->where('store_id', $storeId);
        }

        $grossSales = $salesQuery->sum('total_amount');
        $unitsSold = $salesQuery->sum('quantity_purchased');

        // 2. POS Matched Count (Moniepoint transactions with non-null moniepoint_ref)
        $posMatchedQuery = DB::table('pos_receipts')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->whereNotNull('moniepoint_ref');

        if ($storeId) {
            $storeObj = Store::find($storeId);
            if ($storeObj) {
                $posMatchedQuery->where('store_name', $storeObj->name);
            }
        }
        $posMatchedCount = $posMatchedQuery->count();

        // 3. Opening, Received, Allocated, Damaged, and Closing Stock Report
        // We compile a breakdown per product
        $reportData = [];

        // Fetch products
        $products = Product::where('status', '!=', 'inactive')->get();

        foreach ($products as $product) {
            // Get current closing stock
            if ($storeId) {
                // Scoped to a specific hostel hall
                $closingStock = HallStock::where('store_id', $storeId)
                    ->where('product_id', $product->item_id)
                    ->value('quantity') ?? 0;

                // Received is 0 for halls (stock is allocated, not received directly from suppliers)
                $received = 0;

                // Allocated stock to this hall during this period
                $allocated = StockAllocation::where('store_id', $storeId)
                    ->where('product_id', $product->item_id)
                    ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                    ->sum('quantity') ?? 0;

                // Damaged/expired approved write-offs from this hall during this period
                $damaged = DamagedExpiredItem::where('store_id', $storeId)
                    ->where('product_id', $product->item_id)
                    ->where('status', 'approved')
                    ->whereBetween('updated_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                    ->sum('quantity') ?? 0;

                // Units sold from this hall during this period
                $sold = DB::table('pos_sales_items')
                    ->where('store_id', $storeId)
                    ->where('item_id', $product->item_id)
                    ->where('status', 'completed')
                    ->whereBetween('date', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                    ->sum('quantity_purchased') ?? 0;

                // Opening Stock calculation:
                // Closing Stock = Opening Stock + Allocated - Sold - Damaged
                // Opening Stock = Closing Stock + Sold + Damaged - Allocated
                $openingStock = $closingStock + $sold + $damaged - $allocated;

            } elseif ($supervisorId) {
                // Scoped to a supervisor's private store stock
                $closingStock = SupervisorStock::where('supervisor_id', $supervisorId)
                    ->where('product_id', $product->item_id)
                    ->value('quantity') ?? 0;

                // Received from suppliers in this period
                $received = SupplierReceipt::where('supervisor_id', $supervisorId)
                    ->where('product_id', $product->item_id)
                    ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                    ->sum('quantity') ?? 0;

                // Allocated to halls in this period
                $allocated = StockAllocation::where('supervisor_id', $supervisorId)
                    ->where('product_id', $product->item_id)
                    ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                    ->sum('quantity') ?? 0;

                // Damaged/expired approved write-offs from private stock in this period
                $damaged = DamagedExpiredItem::whereNull('store_id')
                    ->where('user_id', $supervisorId)
                    ->where('product_id', $product->item_id)
                    ->where('status', 'approved')
                    ->whereBetween('updated_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                    ->sum('quantity') ?? 0;

                // Private stock does not sell directly
                $sold = 0;

                // Opening Stock calculation:
                // Closing Stock = Opening Stock + Received - Allocated - Damaged
                // Opening Stock = Closing Stock + Allocated + Damaged - Received
                $openingStock = $closingStock + $allocated + $damaged - $received;

            } else {
                // Global system aggregate (Sum of all supervisors + all halls)
                $hallClosing = HallStock::where('product_id', $product->item_id)->sum('quantity') ?? 0;
                $supervisorClosing = SupervisorStock::where('product_id', $product->item_id)->sum('quantity') ?? 0;
                $closingStock = $hallClosing + $supervisorClosing;

                // Received globally from suppliers in this period
                $received = SupplierReceipt::where('product_id', $product->item_id)
                    ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                    ->sum('quantity') ?? 0;

                // Allocation is internal transfer, so it doesn't change global inventory.
                $allocated = StockAllocation::where('product_id', $product->item_id)
                    ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                    ->sum('quantity') ?? 0;

                // Damaged/expired approved write-offs globally (halls + private stocks)
                $damaged = DamagedExpiredItem::where('product_id', $product->item_id)
                    ->where('status', 'approved')
                    ->whereBetween('updated_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                    ->sum('quantity') ?? 0;

                // Units sold globally
                $sold = DB::table('pos_sales_items')
                    ->where('item_id', $product->item_id)
                    ->where('status', 'completed')
                    ->whereBetween('date', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                    ->sum('quantity_purchased') ?? 0;

                // Opening Stock calculation:
                // Closing Stock = Opening Stock + Received - Sold - Damaged
                // Opening Stock = Closing Stock + Sold + Damaged - Received
                $openingStock = $closingStock + $sold + $damaged - $received;
            }

            // Only list items with activity or stock to keep reports clean
            if ($openingStock > 0 || $closingStock > 0 || $received > 0 || $allocated > 0 || $damaged > 0 || $sold > 0) {
                $reportData[] = [
                    'product_name' => $product->name,
                    'opening_stock' => $openingStock,
                    'received_stock' => $received,
                    'allocated_stock' => $allocated,
                    'damaged_stock' => $damaged,
                    'sold_stock' => $sold,
                    'closing_stock' => $closingStock,
                ];
            }
        }

        return view('audit.accountant_dashboard', compact(
            'grossSales',
            'unitsSold',
            'posMatchedCount',
            'reportData',
            'stores',
            'supervisors',
            'storeId',
            'supervisorId',
            'period',
            'startDate',
            'endDate'
        ));
    }
}
