<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Store;
use App\Models\Supplier;
use App\Models\SupervisorStock;
use App\Models\HallStock;
use App\Models\SupplierReceipt;
use App\Models\StockAllocation;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    /**
     * Show form to receive stock from supplier.
     */
    public function receiveForm()
    {
        $products = Product::where('status', '!=', 'inactive')->get();
        $suppliers = Supplier::where('status', '!=', 'inactive')->get();
        return view('stock.receive', compact('products', 'suppliers'));
    }

    /**
     * Store supplier receiving stock.
     */
    public function receive(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:pos_items,item_id',
            'supplier_name' => 'required|string|max:255',
            'payment_status' => 'required|string|in:Paid,Credit',
            'unit_cost' => 'required|numeric|min:0',
            'quantity' => 'required|numeric|min:0.01',
        ]);

        $supervisorId = auth()->user()->person_id;
        $totalCost = $validated['unit_cost'] * $validated['quantity'];

        DB::beginTransaction();
        try {
            // Record the receipt
            $receipt = SupplierReceipt::create([
                'supervisor_id' => $supervisorId,
                'supplier_name' => $validated['supplier_name'],
                'product_id' => $validated['product_id'],
                'unit_cost' => $validated['unit_cost'],
                'quantity' => $validated['quantity'],
                'total_cost' => $totalCost,
                'payment_status' => $validated['payment_status'],
            ]);

            // Increment supervisor's private store stock
            $stock = SupervisorStock::firstOrCreate(
                ['supervisor_id' => $supervisorId, 'product_id' => $validated['product_id']],
                ['quantity' => 0]
            );
            $stock->increment('quantity', $validated['quantity']);

            DB::commit();

            $product = Product::find($validated['product_id']);
            ActivityLog::log(
                'stock_receiving',
                'Received ' . $validated['quantity'] . ' of ' . ($product ? $product->name : 'Product') . ' from ' . $validated['supplier_name'] . ' (' . $validated['payment_status'] . ', Total: ₦' . number_format($totalCost, 2) . ').'
            );

            return redirect()->route('supervisor.stock.receive.form')->with('success', 'Stock received and logged to Store Stock.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error receiving stock: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show form to allocate stock to assigned halls.
     */
    public function allocateForm()
    {
        $user = auth()->user();
        
        // Scope stores to this supervisor (via pivot or supervisor_id fallback)
        $stores = $user->stores()->where('status', 'active')->get();
        if ($stores->isEmpty()) {
            $stores = Store::where('supervisor_id', $user->person_id)->where('status', 'active')->get();
        }

        // Get supervisor's active stocks
        $stocks = SupervisorStock::where('supervisor_id', $user->person_id)
            ->where('quantity', '>', 0)
            ->with('product')
            ->get();

        return view('stock.allocate', compact('stores', 'stocks'));
    }

    /**
     * Process stock allocation.
     */
    public function allocate(Request $request)
    {
        $validated = $request->validate([
            'store_id' => 'required|exists:stores,id',
            'allocations' => 'required|array',
            'allocations.*.product_id' => 'required|exists:pos_items,item_id',
            'allocations.*.quantity' => 'required|numeric|min:0.01',
        ]);

        $supervisorId = auth()->user()->person_id;

        // Ensure the store is assigned to this supervisor (via pivot or supervisor_id fallback)
        $store = auth()->user()->stores()->where('stores.id', $validated['store_id'])->first();
        if (!$store) {
            $store = Store::where('id', $validated['store_id'])
                ->where('supervisor_id', $supervisorId)
                ->firstOrFail();
        }

        DB::beginTransaction();
        try {
            foreach ($validated['allocations'] as $alloc) {
                $productId = $alloc['product_id'];
                $qty = $alloc['quantity'];

                // Check supervisor stock
                $supStock = SupervisorStock::where('supervisor_id', $supervisorId)
                    ->where('product_id', $productId)
                    ->first();

                if (!$supStock || $supStock->quantity < $qty) {
                    $product = Product::find($productId);
                    throw new \Exception('Insufficient stock for product: ' . ($product ? $product->name : 'ID ' . $productId));
                }

                // Decrement supervisor stock
                $supStock->decrement('quantity', $qty);

                // Increment hall/store stock
                $hallStock = HallStock::firstOrCreate(
                    ['store_id' => $store->id, 'product_id' => $productId],
                    ['quantity' => 0]
                );
                $hallStock->increment('quantity', $qty);

                // Log allocation transaction
                StockAllocation::create([
                    'supervisor_id' => $supervisorId,
                    'store_id' => $store->id,
                    'product_id' => $productId,
                    'quantity' => $qty,
                ]);

                $product = Product::find($productId);
                ActivityLog::log(
                    'stock_allocation',
                    'Allocated ' . $qty . ' of ' . ($product ? $product->name : 'Product') . ' to hall/store: ' . $store->name
                );
            }

            DB::commit();
            return redirect()->route('supervisor.stock.allocate.form')->with('success', 'Stock allocated successfully to ' . $store->name);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Allocation failed: ' . $e->getMessage())->withInput();
        }
    }
}
