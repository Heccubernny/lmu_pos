<?php

namespace App\Http\Controllers;

use App\Models\BadDamage;
use App\Models\Product;
use App\Models\StoreShelve;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BadDamageController extends Controller
{
    public function index()
    {
        $items = BadDamage::latest('date')->paginate(15);
        return view('bad_damages.index', compact('items'));
    }

    public function create()
    {
        $products = Product::all(['name']);
        return view('bad_damages.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'qty' => 'required|numeric|min:0.01',
            'from_dept' => 'required|string',
            'description' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            BadDamage::create([
                'name' => $validated['name'],
                'qty' => $validated['qty'],
                'from_dept' => $validated['from_dept'],
                'description' => $validated['description'] ?? '',
                'staff_id' => auth()->user()->staff_id ?? 'STF001',
                'date' => now(),
            ]);

            // Logic from tconn: if from Cashier, update pos_items (Products)
            // if from Logistics, update pos_store_shelve (StoreShelve)
            if ($validated['from_dept'] === 'Cashier') {
                $product = Product::where('name', $validated['name'])->first();
                if ($product) {
                    $product->decrement('quantity', $validated['qty']);
                }
            } else if ($validated['from_dept'] === 'Logistics') {
                $shelveItem = StoreShelve::where('name', $validated['name'])->first();
                if ($shelveItem) {
                    $shelveItem->decrement('quantity', $validated['qty']);
                }
            }

            DB::commit();
            return redirect()->route('admin.bad-damages.index')->with('success', 'Bad/Damage item recorded.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error recording bad/damage item: ' . $e->getMessage());
        }
    }
}
