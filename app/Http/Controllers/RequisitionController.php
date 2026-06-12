<?php

namespace App\Http\Controllers;

use App\Models\Requisition;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RequisitionController extends Controller
{
    public function index()
    {
        $requisitions = Requisition::latest()->paginate(15);
        return view('requisitions.index', compact('requisitions'));
    }

    public function create()
    {
        $products = Product::all(['name']);
        $categories = Category::all(['name']);
        $staff = User::all(); // Assuming all users are staff for simplicity
        return view('requisitions.create', compact('products', 'categories', 'staff'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'category' => 'required|string',
            'quantity' => 'required|numeric|min:0.01',
            'collectedby' => 'required|string',
            'department' => 'required|string',
        ]);

        Requisition::create([
            'name' => $validated['name'],
            'category' => $validated['category'],
            'quantity' => $validated['quantity'],
            'collectedby' => $validated['collectedby'],
            'department' => $validated['department'],
            'ty' => 'Store',
            'staff_id' => auth()->user()->staff_id ?? 'STF001',
            'manager_approved' => 'pending',
            'status' => 'pending',
            'branch' => session('authorized_store.name', 'Main'),
        ]);

        return redirect()->route('admin.requisitions.index')->with('success', 'Requisition created successfully.');
    }

    public function approve(Requisition $requisition)
    {
        $requisition->update([
            'manager_approved' => 'approved',
            'status' => 'approved'
        ]);

        return back()->with('success', 'Requisition approved.');
    }
}
