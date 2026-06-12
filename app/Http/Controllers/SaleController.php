<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource (Admin search/history).
     */
    public function index(Request $request)
    {
        $stores = \App\Models\Store::all();
        $categories = \App\Models\Category::all();
        $cashiers = \App\Models\User::all();

        $search = $request->input('search');
        $storeId = $request->input('store_id');
        $staffId = $request->input('staff_id');
        $category = $request->input('category');
        $modePayment = $request->input('mode_payment');
        $status = $request->input('status');
        $period = $request->input('period');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $startTime = $request->input('start_time');
        $endTime = $request->input('end_time');

        // Select unique transaction rows by getting the maximum ID for each receipt number
        $maxIdsQuery = DB::table('pos_sales_items')
            ->selectRaw('MAX(id) as max_id');

        $maxIdsQuery = $this->applyFilters($maxIdsQuery, $request);

        $maxIdsQuery = $maxIdsQuery->groupBy('recipt_number');

        $sales = \App\Models\Sale::whereIn('id', $maxIdsQuery)
            ->with(['store'])
            ->latest('date')
            ->paginate(15);

        return view('sales.index', compact(
            'sales',
            'stores',
            'categories',
            'cashiers',
            'search',
            'storeId',
            'staffId',
            'category',
            'modePayment',
            'status',
            'period',
            'startDate',
            'endDate',
            'startTime',
            'endTime'
        ));
    }

    /**
     * Apply multi-store sales filters dynamically to a query builder.
     */
    private function applyFilters($query, Request $request)
    {
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('recipt_number', 'like', "%{$search}%")
                  ->orWhere('customer', 'like', "%{$search}%")
                  ->orWhere('staff_id', 'like', "%{$search}%");
            });
        }

        if ($storeId = $request->input('store_id')) {
            $query->where('store_id', $storeId);
        }

        if ($staffId = $request->input('staff_id')) {
            $query->where('staff_id', $staffId);
        }

        if ($category = $request->input('category')) {
            $query->where('category', $category);
        }

        if ($modePayment = $request->input('mode_payment')) {
            $query->where('mode_payment', $modePayment);
        }

        $status = $request->input('status');
        if ($status) {
            if ($status !== 'all') {
                $query->where('status', $status);
            }
        }

        $period = $request->input('period');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if ($period && $period !== 'custom') {
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
            }
        }

        if ($startDate) {
            $query->whereDate('date', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('date', '<=', $endDate);
        }

        if ($startTime = $request->input('start_time')) {
            $query->whereTime('date', '>=', $startTime);
        }
        if ($endTime = $request->input('end_time')) {
            $query->whereTime('date', '<=', $endTime);
        }

        return $query;
    }


    /**
     * Display cashier-specific sales list and periodic analytics.
     */
    public function cashierSales(Request $request)
    {
        $period = $request->input('period', 'daily');
        $staffId = auth()->user()->staff_id ?? 'STF001';

        $startDate = now()->startOfDay();
        $endDate = now()->endOfDay();

        if ($period === 'weekly') {
            $startDate = now()->startOfWeek();
            $endDate = now()->endOfWeek();
        } elseif ($period === 'monthly') {
            $startDate = now()->startOfMonth();
            $endDate = now()->endOfMonth();
        }

        // Get cashier transaction statistics
        $receipts = DB::table('pos_sales_items')
            ->where('staff_id', $staffId)
            ->where('status', 'completed')
            ->whereBetween('date', [$startDate, $endDate])
            ->select('recipt_number', DB::raw('MAX(total_amount) as total'))
            ->groupBy('recipt_number')
            ->get();

        $totalSales = $receipts->sum('total');
        $totalTransactions = $receipts->count();
        $avgOrderValue = $totalTransactions > 0 ? $totalSales / $totalTransactions : 0;

        // Get individual transactions list
        $maxIdsQuery = DB::table('pos_sales_items')
            ->where('staff_id', $staffId)
            ->whereBetween('date', [$startDate, $endDate])
            ->selectRaw('MAX(id) as max_id')
            ->groupBy('recipt_number');

        $sales = \App\Models\Sale::whereIn('id', $maxIdsQuery)
            ->latest('date')
            ->paginate(15);

        return view('sales.cashier_sales', compact('sales', 'totalSales', 'totalTransactions', 'avgOrderValue', 'period'));
    }

    /**
     * Show the form for creating a new resource (POS Interface).
     */
    public function create()
    {
        $user = auth()->user();
        $storeId = $user->store_id;

        if (!$storeId && session()->has('authorized_store')) {
            $storeId = session('authorized_store')['store_id'];
        }

        if (!$storeId) {
            $products = collect();
            $customers = collect();
            $store = null;
            $noStoreAssigned = true;
            return view('sales.create', compact('products', 'customers', 'store', 'noStoreAssigned'));
        }

        $store = \App\Models\Store::find($storeId);
        if (!$store) {
            $products = collect();
            $customers = collect();
            $store = null;
            $noStoreAssigned = true;
            return view('sales.create', compact('products', 'customers', 'store', 'noStoreAssigned'));
        }

        // Store details in session for scoping
        session([
            'authorized_store' => [
                'store_id' => $store->id,
                'name' => $store->name ?? ($store->host ?? 'Store'),
            ]
        ]);

        // Load products allocated to this hall from hall_stocks
        $products = \App\Models\Product::join('hall_stocks', 'pos_items.item_id', '=', 'hall_stocks.product_id')
            ->where('hall_stocks.store_id', $storeId)
            ->where('pos_items.status', '!=', 'inactive')
            ->where('hall_stocks.quantity', '>', 0)
            ->select('pos_items.item_id as id', 'pos_items.name', 'pos_items.unit_price', 'hall_stocks.quantity', 'pos_items.item_number')
            ->get();

        $customers = \App\Models\Customer::all(['person_id as id', 'name']);

        return view('sales.create', compact('products', 'customers', 'store'));
    }

    /**
     * Store a newly created resource in storage (Process Sale).
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $storeId = $user->store_id;

        if (!$storeId && session()->has('authorized_store')) {
            $storeId = session('authorized_store')['store_id'];
        }

        if (!$storeId) {
            return back()->with('error', 'No store/hall is assigned.');
        }

        $validated = $request->validate([
            'customer_id' => 'nullable|exists:tconnpos_customers,person_id',
            'mode_payment' => 'required|string',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'cart' => 'required|string', // JSON string of cart items

            // Cashless terminal parameters
            'merchant_reference' => 'nullable|string',
            'terminal_id' => 'nullable|string',
            'payment_reference' => 'nullable|string',
            'processing_status' => 'nullable|string',
            'raw_response' => 'nullable|string',
        ]);

        $cart = json_decode($validated['cart'], true);

        if (empty($cart)) {
            return back()->with('error', 'Cart is empty');
        }

        DB::beginTransaction();
        try {
            // Calculate total
            $subtotal = 0;
            foreach ($cart as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }

            $discountAmount = 0;
            $total_amount = $subtotal;
            if (!empty($validated['discount_percent'])) {
                $discountAmount = $subtotal * ($validated['discount_percent'] / 100);
                $total_amount -= $discountAmount;
            }

            // Generate receipt details
            $receiptNumber = 'REC-' . time() . rand(10, 99);
            $receiptUid = \Illuminate\Support\Str::uuid()->toString();
            $barcodeIdentifier = 'BC-' . rand(100000, 999999);

            // Fetch Customer Name
            $customerName = 'Walk-in Customer';
            if (!empty($validated['customer_id'])) {
                $customer = \App\Models\Customer::find($validated['customer_id']);
                if ($customer) {
                    $customerName = $customer->name;
                }
            }

            // Create individual sale item rows mapping transaction details on each item
            foreach ($cart as $item) {
                $product = \App\Models\Product::findOrFail($item['id']);

                // Verify stock availability in hall stock
                $hallStock = \App\Models\HallStock::where('store_id', $storeId)
                    ->where('product_id', $item['id'])
                    ->first();

                if (!$hallStock || $hallStock->quantity < $item['quantity']) {
                    throw new \Exception("Not enough stock for {$product->name} in this hall");
                }

                $saleItemData = [
                    'recipt_number' => $receiptNumber,
                    'store_id' => $storeId,
                    'item_id' => $product->item_id,
                    'category' => $product->category->name ?? $product->category ?? 'General',
                    'supplier' => $product->supplier ?? 'General',
                    'quantity_purchased' => $item['quantity'],
                    'quantity_left' => $hallStock->quantity - $item['quantity'],
                    'item_cost_price' => $product->cost_price,
                    'item_unit_price' => $item['price'],
                    'total_amount' => $total_amount,
                    'amount_paid' => $total_amount,
                    'mode_payment' => $validated['mode_payment'],
                    'description' => $product->description ?? '',
                    'discount_percent' => $validated['discount_percent'] ?? 0,
                    'staff_id' => $user->staff_id ?? 'STF001',
                    'status' => 'completed',
                    'status_location' => 'counter',
                    'status_secound' => 'sold',
                    'customer' => $customerName,
                ];

                \App\Models\SaleItem::create($saleItemData);

                // Decrement hall inventory
                $hallStock->decrement('quantity', $item['quantity']);
            }

            $storeObj = \App\Models\Store::find($storeId);
            $storeName = $storeObj ? $storeObj->name : 'Hall';

            // Cache receipt details internally
            \App\Models\PosReceipt::create([
                'receipt_number' => $receiptNumber,
                'receipt_uid' => $receiptUid,
                'barcode_identifier' => $barcodeIdentifier,
                'cashier_name' => $user->name,
                'store_name' => $storeName,
                'total_amount' => $total_amount,
                'payment_method' => $validated['mode_payment'],
                'moniepoint_ref' => $validated['payment_reference'] ?? null,
                'terminal_id' => $validated['terminal_id'] ?? null,
                'receipt_data' => [
                    'subtotal' => $subtotal,
                    'discount' => $validated['discount_percent'] ?? 0,
                    'discount_amount' => $discountAmount,
                    'total' => $total_amount,
                    'customer' => $customerName,
                    'items' => $cart,
                    'merchant_reference' => $validated['merchant_reference'] ?? null,
                    'processing_status' => $validated['processing_status'] ?? null,
                    'raw_response' => $validated['raw_response'] ?? null,
                    'date' => now()->format('Y-m-d H:i:s'),
                ]
            ]);

            DB::commit();

            // Retrieve the newly created transaction record to pass to redirect
            $newSale = \App\Models\Sale::where('recipt_number', $receiptNumber)->first();

            \App\Models\ActivityLog::log(
                'sales_completion',
                'Completed sale ' . $receiptNumber . ' in store ' . $storeName . ' for ₦' . number_format($total_amount, 2)
            );

            if ($user->isSalesRep()) {
                return redirect()->route('cashier.sales.show', $newSale)->with('success', 'Sale completed successfully!');
            }
            return redirect()->route('admin.sales.show', $newSale)->with('success', 'Sale completed successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource (Receipt).
     */
    public function show(\App\Models\Sale $sale)
    {
        $sale->load(['product', 'store']);
        $posReceipt = \App\Models\PosReceipt::where('receipt_number', $sale->recipt_number)->first();
        return view('sales.show', compact('sale', 'posReceipt'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        abort(404);
    }

    /**
     * Remove the specified resource from storage (Void Sale Request).
     */
    public function destroy(\App\Models\Sale $sale)
    {
        DB::beginTransaction();
        try {
            // Update status to pending_void for all items in the transaction
            \App\Models\SaleItem::where('recipt_number', $sale->recipt_number)->update(['status' => 'pending_void']);

            DB::commit();

            \App\Models\ActivityLog::log(
                'void_request',
                'Requested void for sale transaction ' . $sale->recipt_number . '.'
            );

            return redirect()->route('admin.sales.index')->with('success', 'Void request submitted and is pending Auditor approval.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error requesting void: ' . $e->getMessage());
        }
    }
}
