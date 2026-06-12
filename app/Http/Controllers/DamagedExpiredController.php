<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DamagedExpiredItem;
use App\Models\Product;
use App\Models\Store;
use App\Models\SupervisorStock;
use App\Models\HallStock;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;

class DamagedExpiredController extends Controller
{
    /**
     * Display a listing of reported damaged/expired items.
     */
    public function index()
    {
        $user = auth()->user();
        $query = DamagedExpiredItem::with(['product', 'store', 'user.person', 'approver.person']);

        // Scope to supervisor if the user is a supervisor
        if ($user->isSupervisor()) {
            $query->where('user_id', $user->person_id);
        }

        $items = $query->latest()->paginate(15);

        return view('damaged_expired.index', compact('items'));
    }

    /**
     * Show the form for creating a new report.
     */
    public function create()
    {
        $user = auth()->user();
        
        // Supervisor's assigned halls (via pivot or supervisor_id fallback)
        $stores = $user->stores()->where('status', 'active')->get();
        if ($stores->isEmpty()) {
            $stores = Store::where('supervisor_id', $user->person_id)->where('status', 'active')->get();
        }

        // Supervisor's private store stock with quantity > 0
        $privateStocks = SupervisorStock::where('supervisor_id', $user->person_id)
            ->where('quantity', '>', 0)
            ->with('product')
            ->get();

        // Hall stocks with quantity > 0 for supervisor's assigned stores
        $hallStocks = HallStock::whereIn('store_id', $stores->pluck('id'))
            ->where('quantity', '>', 0)
            ->with(['product', 'store'])
            ->get();

        return view('damaged_expired.create', compact('stores', 'privateStocks', 'hallStocks'));
    }

    /**
     * Store a newly created report.
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'source_type' => 'required|string|in:private,hall',
            'store_id' => 'required_if:source_type,hall|nullable|exists:stores,id',
            'product_id' => 'required|exists:pos_items,item_id',
            'quantity' => 'required|numeric|min:0.01',
            'type' => 'required|string|in:damaged,expired',
        ]);

        $storeId = $validated['source_type'] === 'hall' ? $validated['store_id'] : null;

        // Perform validation to ensure stock is actually available in that source
        if ($validated['source_type'] === 'private') {
            $stock = SupervisorStock::where('supervisor_id', $user->person_id)
                ->where('product_id', $validated['product_id'])
                ->first();
            
            if (!$stock || $stock->quantity < $validated['quantity']) {
                return back()->with('error', 'Insufficient quantity in private stock.')->withInput();
            }
        } else {
            // Ensure the store is assigned to this supervisor (via pivot or supervisor_id fallback)
            $store = $user->stores()->where('stores.id', $storeId)->first();
            if (!$store) {
                $store = Store::where('id', $storeId)
                    ->where('supervisor_id', $user->person_id)
                    ->first();
            }

            if (!$store) {
                return back()->with('error', 'Unauthorized store assignment.')->withInput();
            }

            $stock = HallStock::where('store_id', $storeId)
                ->where('product_id', $validated['product_id'])
                ->first();

            if (!$stock || $stock->quantity < $validated['quantity']) {
                return back()->with('error', 'Insufficient quantity in selected hall stock.')->withInput();
            }
        }

        DB::beginTransaction();
        try {
            DamagedExpiredItem::create([
                'user_id' => $user->person_id,
                'product_id' => $validated['product_id'],
                'store_id' => $storeId,
                'quantity' => $validated['quantity'],
                'type' => $validated['type'],
                'status' => 'pending',
            ]);

            DB::commit();

            $product = Product::find($validated['product_id']);
            $sourceName = $storeId ? Store::find($storeId)->name : 'Supervisor Private Stock';

            ActivityLog::log(
                'report_damaged_expired',
                'Reported ' . $validated['quantity'] . ' of ' . ($product ? $product->name : 'Product') . ' as ' . $validated['type'] . ' from ' . $sourceName . '.'
            );

            return redirect()->route('supervisor.damaged-expired.index')->with('success', 'Damaged/expired item reported and pending auditor review.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error reporting item: ' . $e->getMessage())->withInput();
        }
    }
}
