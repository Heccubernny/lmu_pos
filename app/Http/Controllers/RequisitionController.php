<?php

namespace App\Http\Controllers;

use App\Models\Requisition;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ActivityLog;

class RequisitionController extends Controller
{
    /**
     * Display a listing of requisitions.
     */
    public function index()
    {
        $user = auth()->user();
        $query = Requisition::with(['product', 'store', 'cashier']);

        // Scope to supervisor's stores if they are a Supervisor
        if ($user->isSupervisor()) {
            $storeIds = $user->stores()->pluck('stores.id');
            if ($storeIds->isEmpty()) {
                $storeIds = Store::where('supervisor_id', $user->person_id)->pluck('id');
            }
            $query->whereIn('store_id', $storeIds);
        }

        $requisitions = $query->latest()->paginate(15);
        return view('requisitions.index', compact('requisitions'));
    }

    /**
     * Show form to create new requisition.
     */
    public function create()
    {
        $user = auth()->user();
        $products = Product::where('status', '!=', 'inactive')->get(['item_id', 'name', 'category', 'quantity']);
        $categories = Category::all(['name']);
        $staff = User::all();

        // Scope stores to this supervisor (if supervisor) or all stores (if admin/auditor/accountant)
        if ($user->isSupervisor()) {
            $stores = $user->stores()->where('status', 'active')->get();
            if ($stores->isEmpty()) {
                $stores = Store::where('supervisor_id', $user->person_id)->where('status', 'active')->get();
            }
        } else {
            $stores = Store::where('status', 'active')->get();
        }

        $departments = \App\Models\Department::where('status', 'active')->orderBy('name')->get(['name']);

        return view('requisitions.create', compact('products', 'categories', 'staff', 'stores', 'departments'));
    }

    /**
     * Store the requisition request.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:pos_items,item_id',
            'store_id' => 'required|exists:stores,id',
            'quantity' => 'required|numeric|min:0.01',
            'collectedby' => 'required|string',
            'department' => 'required|string',
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $store = Store::findOrFail($validated['store_id']);

        Requisition::create([
            'product_id' => $product->item_id,
            'store_id' => $store->id,
            'name' => $product->name,
            'category' => $product->category->name ?? $product->category ?? 'General',
            'quantity' => $validated['quantity'],
            'collectedby' => $validated['collectedby'],
            'department' => $validated['department'],
            'ty' => 'Store',
            'staff_id' => auth()->user()->staff_id ?? 'STF001',
            'manager_approved' => 'pending',
            'status' => 'pending',
            'branch' => $store->name,
        ]);

        if (auth()->user()->isSupervisor()) {
            return redirect()->route('supervisor.requisitions.index')->with('success', 'Requisition created successfully.');
        }

        return redirect()->route('admin.requisitions.index')->with('success', 'Requisition created successfully.');
    }

    /**
     * Approve the requisition and move stock from warehouse to branch shelf.
     */
    public function approve(Requisition $requisition)
    {
        $product = Product::findOrFail($requisition->product_id);
        $store = Store::findOrFail($requisition->store_id);
        
        // Validate stock availability in central warehouse
        if ($product->quantity < $requisition->quantity) {
            return back()->with('error', "Approval failed: Insufficient stock in central warehouse. Current available stock is {$product->quantity} units.");
        }

        DB::beginTransaction();
        try {
            // 1. Decrement central warehouse stock
            $product->decrement('quantity', $requisition->quantity);

            // 2. Increment store stock in hall_stocks
            $hallStock = \App\Models\HallStock::firstOrCreate(
                ['store_id' => $store->id, 'product_id' => $product->item_id],
                ['quantity' => 0]
            );
            $hallStock->increment('quantity', $requisition->quantity);

            // 3. Log stock allocation
            \App\Models\StockAllocation::create([
                'supervisor_id' => auth()->user()->person_id ?? 1,
                'store_id' => $store->id,
                'product_id' => $product->item_id,
                'quantity' => $requisition->quantity,
            ]);

            // 4. Update Requisition status
            $requisition->update([
                'manager_approved' => 'approved',
                'status' => 'approved'
            ]);

            ActivityLog::log(
                'requisition_approval',
                "Approved Requisition #{$requisition->item_id} for Store {$store->name}: Transferred {$requisition->quantity} of {$product->name} from warehouse to store shelf."
            );

            DB::commit();
            return back()->with('success', 'Requisition approved and stock successfully transferred.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Approval failed: ' . $e->getMessage());
        }
    }

    /**
     * Decline the requisition request.
     */
    public function decline(Requisition $requisition)
    {
        $requisition->update([
            'manager_approved' => 'declined',
            'status' => 'declined'
        ]);

        ActivityLog::log(
            'requisition_rejection',
            "Declined Requisition #{$requisition->item_id} for store branch: {$requisition->branch}."
        );

        return back()->with('success', 'Requisition declined.');
    }
}
