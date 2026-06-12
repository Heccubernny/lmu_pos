<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $role = strtolower($user->role ?? '');

        if ($role === 'supervisor') {
            // Only show suppliers with activity in supervisor's scope
            $suppliers = \App\Models\Supplier::whereExists(function ($q) use ($user) {
                $q->select(\DB::raw(1))
                  ->from('supplier_receipts')
                  ->where('supervisor_id', $user->person_id)
                  ->whereColumn('supplier_receipts.supplier_name', 'tconnpos_suppliers.company_name');
            })->paginate(10);

            foreach ($suppliers as $supplier) {
                $debit = \App\Models\SupplierReceipt::where('supervisor_id', $user->person_id)
                    ->where('supplier_name', $supplier->company_name)
                    ->where('payment_status', 'Paid')
                    ->sum('total_cost');

                $credit = \App\Models\SupplierReceipt::where('supervisor_id', $user->person_id)
                    ->where('supplier_name', $supplier->company_name)
                    ->where('payment_status', 'Credit')
                    ->sum('total_cost');

                $supplier->debit = $debit;
                $supplier->credit = $credit;
                $supplier->balance = $credit - $debit;
            }
        } else {
            // Admin/Auditor/Accountant sees all suppliers
            $suppliers = \App\Models\Supplier::paginate(10);

            foreach ($suppliers as $supplier) {
                $debit = \App\Models\SupplierReceipt::where('supplier_name', $supplier->company_name)
                    ->where('payment_status', 'Paid')
                    ->sum('total_cost');

                $credit = \App\Models\SupplierReceipt::where('supplier_name', $supplier->company_name)
                    ->where('payment_status', 'Credit')
                    ->sum('total_cost');

                $supplier->debit = $debit;
                $supplier->credit = $credit;
                $supplier->balance = $credit - $debit;
            }
        }

        return view('suppliers.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (strtolower(auth()->user()->role ?? '') === 'supervisor') {
            abort(403, 'Supervisors cannot manage global supplier list.');
        }
        return view('suppliers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (strtolower(auth()->user()->role ?? '') === 'supervisor') {
            abort(403, 'Supervisors cannot manage global supplier list.');
        }

        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'branch' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
        ]);

        \App\Models\Supplier::create($validated);

        \App\Models\ActivityLog::log('supplier_creation', 'Created global supplier: ' . $validated['company_name']);

        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier created successfully.');
    }

    /**
     * Display the specified resource (Supplier History).
     */
    public function show(\App\Models\Supplier $supplier, Request $request)
    {
        $user = auth()->user();
        $role = strtolower($user->role ?? '');

        $query = \App\Models\SupplierReceipt::where('supplier_name', $supplier->company_name)
            ->with(['product', 'supervisor']);

        if ($role === 'supervisor') {
            $query->where('supervisor_id', $user->person_id);
        }

        if ($status = $request->input('payment_status')) {
            $query->where('payment_status', $status);
        }

        if ($startDate = $request->input('start_date')) {
            $query->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate = $request->input('end_date')) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $receipts = $query->latest()->paginate(15);

        // Calculations
        $calcQuery = \App\Models\SupplierReceipt::where('supplier_name', $supplier->company_name);
        if ($role === 'supervisor') {
            $calcQuery->where('supervisor_id', $user->person_id);
        }

        $debit = (clone $calcQuery)->where('payment_status', 'Paid')->sum('total_cost');
        $credit = (clone $calcQuery)->where('payment_status', 'Credit')->sum('total_cost');
        $balance = $credit - $debit;

        return view('suppliers.show', compact('supplier', 'receipts', 'debit', 'credit', 'balance'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(\App\Models\Supplier $supplier)
    {
        if (strtolower(auth()->user()->role ?? '') === 'supervisor') {
            abort(403, 'Supervisors cannot manage global supplier list.');
        }
        return view('suppliers.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, \App\Models\Supplier $supplier)
    {
        if (strtolower(auth()->user()->role ?? '') === 'supervisor') {
            abort(403, 'Supervisors cannot manage global supplier list.');
        }

        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'branch' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
        ]);

        $supplier->update($validated);

        \App\Models\ActivityLog::log('supplier_update', 'Updated supplier: ' . $supplier->company_name);

        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(\App\Models\Supplier $supplier)
    {
        if (strtolower(auth()->user()->role ?? '') === 'supervisor') {
            abort(403, 'Supervisors cannot manage global supplier list.');
        }

        $name = $supplier->company_name;
        $supplier->delete();

        \App\Models\ActivityLog::log('supplier_deletion', 'Deleted supplier: ' . $name);

        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier deleted successfully.');
    }
}
