<?php

namespace App\Http\Controllers;

use App\Models\ReturnIn;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturnInController extends Controller
{
    public function index()
    {
        $returns = ReturnIn::latest('date')->paginate(15);
        return view('returns.index', compact('returns'));
    }

    public function create()
    {
        $products = Product::all(['name']);
        return view('returns.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'itemname' => 'required|string|exists:pos_items,name',
            'quantity' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $product = Product::where('name', $validated['itemname'])->firstOrFail();
            
            ReturnIn::create([
                'itemname' => $validated['itemname'],
                'quantity' => $validated['quantity'],
                'staff_id' => auth()->user()->staff_id ?? 'STF001',
                'date' => now(),
            ]);

            // Update product quantity
            $product->increment('quantity', $validated['quantity']);

            DB::commit();
            return redirect()->route('admin.returns.index')->with('success', 'Return processed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error processing return: ' . $e->getMessage());
        }
    }
}
