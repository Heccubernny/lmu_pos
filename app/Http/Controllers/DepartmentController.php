<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Store;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of departments.
     */
    public function index()
    {
        $departments = Department::latest()->paginate(10);
        return view('departments.index', compact('departments'));
    }

    /**
     * Show the form for creating a new department.
     */
    public function create()
    {
        $stores = Store::where('status', 'active')->orderBy('name')->get();
        return view('departments.create', compact('stores'));
    }

    /**
     * Store a newly created department.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'branch' => 'required|string|max:255',
            'status' => 'required|string|in:active,inactive',
        ]);

        Department::create($validated);

        ActivityLog::log(
            'department_creation',
            "Created department \"{$validated['name']}\" for branch \"{$validated['branch']}\"."
        );

        return redirect()->route('admin.departments.index')
            ->with('success', 'Department created successfully.');
    }

    /**
     * Show the form for editing the specified department.
     */
    public function edit(Department $department)
    {
        $stores = Store::where('status', 'active')->orderBy('name')->get();
        return view('departments.edit', compact('department', 'stores'));
    }

    /**
     * Update the specified department in storage.
     */
    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'branch' => 'required|string|max:255',
            'status' => 'required|string|in:active,inactive',
        ]);

        $department->update($validated);

        ActivityLog::log(
            'department_update',
            "Updated department \"{$validated['name']}\" for branch \"{$validated['branch']}\"."
        );

        return redirect()->route('admin.departments.index')
            ->with('success', 'Department updated successfully.');
    }

    /**
     * Remove the specified department from storage.
     */
    public function destroy(Department $department)
    {
        $name = $department->name;
        $department->delete();

        ActivityLog::log(
            'department_deletion',
            "Deleted department \"{$name}\"."
        );

        return redirect()->route('admin.departments.index')
            ->with('success', 'Department deleted successfully.');
    }
}
