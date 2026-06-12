<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $role = strtolower($user->role ?? '');

        if ($role === 'supervisor') {
            $products = \App\Models\Product::leftJoin('supervisor_stocks', function($join) use ($user) {
                    $join->on('pos_items.item_id', '=', 'supervisor_stocks.product_id')
                         ->where('supervisor_stocks.supervisor_id', '=', $user->person_id);
                })
                ->select('pos_items.*', \DB::raw('COALESCE(supervisor_stocks.quantity, 0) as quantity'))
                ->with('category')
                ->latest('pos_items.created_at')
                ->paginate(10);
        } else {
            $products = \App\Models\Product::with('category')->latest()->paginate(10);
        }

        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (strtolower(auth()->user()->role ?? '') === 'supervisor') {
            abort(403, 'Supervisors cannot manage the global product catalog.');
        }

        $categories = \App\Models\Category::all();
        $suppliers = \App\Models\Supplier::all();
        return view('products.create', compact('categories', 'suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (strtolower(auth()->user()->role ?? '') === 'supervisor') {
            abort(403, 'Supervisors cannot manage the global product catalog.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:category,id',
            'supplier' => 'nullable|string|max:255',
            'item_number' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'cost_price' => 'numeric|min:0',
            'unit_price' => 'numeric|min:0',
            'quantity' => 'numeric|min:0',
            'status' => 'nullable|string|max:255',
        ]);

        $validated['staff_id'] = auth()->user()->staff_id ?? 'STF001';
        $category = \App\Models\Category::find($validated['category_id']);
        $validated['category'] = $category ? $category->name : null;
        unset($validated['category_id']);

        $validated['description'] = $validated['description'] ?? '';
        $validated['supplier'] = $validated['supplier'] ?? '';
        $validated['status'] = $validated['status'] ?? 'active';
        $validated['cost_price'] = $validated['cost_price'] ?? 0;
        $validated['unit_price'] = $validated['unit_price'] ?? 0;
        $validated['quantity'] = $validated['quantity'] ?? 0;

        \App\Models\Product::create($validated);

        \App\Models\ActivityLog::log('product_creation', 'Created global product: ' . $validated['name']);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(\App\Models\Product $product)
    {
        if (strtolower(auth()->user()->role ?? '') === 'supervisor') {
            abort(403, 'Supervisors cannot manage the global product catalog.');
        }

        $categories = \App\Models\Category::all();
        $suppliers = \App\Models\Supplier::all();
        return view('products.edit', compact('product', 'categories', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, \App\Models\Product $product)
    {
        if (strtolower(auth()->user()->role ?? '') === 'supervisor') {
            abort(403, 'Supervisors cannot manage the global product catalog.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:category,id',
            'supplier' => 'nullable|string|max:255',
            'item_number' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'cost_price' => 'numeric|min:0',
            'unit_price' => 'numeric|min:0',
            'quantity' => 'numeric|min:0',
            'status' => 'nullable|string|max:255',
        ]);

        $category = \App\Models\Category::find($validated['category_id']);
        $validated['category'] = $category ? $category->name : null;
        unset($validated['category_id']);

        $validated['description'] = $validated['description'] ?? '';
        $validated['supplier'] = $validated['supplier'] ?? '';
        $validated['status'] = $validated['status'] ?? 'active';
        $validated['cost_price'] = $validated['cost_price'] ?? 0;
        $validated['unit_price'] = $validated['unit_price'] ?? 0;
        $validated['quantity'] = $validated['quantity'] ?? 0;

        $product->update($validated);

        \App\Models\ActivityLog::log('product_update', 'Updated global product: ' . $product->name);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(\App\Models\Product $product)
    {
        if (strtolower(auth()->user()->role ?? '') === 'supervisor') {
            abort(403, 'Supervisors cannot manage the global product catalog.');
        }

        $name = $product->name;
        $product->delete();

        \App\Models\ActivityLog::log('product_deletion', 'Deleted global product: ' . $name);

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}
