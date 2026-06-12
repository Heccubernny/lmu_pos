<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currentUser = auth()->user();
        $query = \App\Models\User::query();
        $role = strtolower($currentUser->role ?? '');

        if ($role === 'head') {
            // Head can only see Supervisor, Auditor, Accountant
            $query->whereIn(DB::raw('lower(position)'), ['supervisor', 'auditor', 'accountant']);
        } elseif ($role === 'it administrator' || $role === 'administrator') {
            // IT Admin can see all, but hide protected admins if current user is not protected
            if (!$currentUser->is_protected) {
                $query->where('is_protected', false);
            }
        } else {
            $query->whereRaw('1 = 0');
        }

        // Always hide protected users from everyone except the protected user themselves
        if (!$currentUser->is_protected) {
            $query->where('is_protected', false);
        }

        $users = $query->with(['store', 'stores'])->latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $stores = \App\Models\Store::all();
        $currentUser = auth()->user();
        $role = strtolower($currentUser->role ?? '');

        if ($role === 'head') {
            $roles = ['Supervisor', 'Auditor', 'Accountant'];
        } else {
            $roles = ['IT Administrator', 'Head', 'Supervisor', 'Sales Representative', 'Auditor', 'Accountant'];
        }

        return view('users.create', compact('stores', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $currentUser = auth()->user();
        $currentUserRole = strtolower($currentUser->role ?? '');

        if ($currentUserRole === 'head') {
            $roleValidation = 'required|string|in:Supervisor,Auditor,Accountant,supervisor,auditor,accountant';
        } else {
            $roleValidation = 'required|string|in:IT Administrator,Head,Supervisor,Sales Representative,Auditor,Accountant,it administrator,head,supervisor,sales representative,auditor,accountant';
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:pos_people,email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => $roleValidation,
            'store_id' => 'nullable|exists:stores,id',
            'store_ids' => 'nullable|array',
            'store_ids.*' => 'exists:stores,id',
        ]);

        DB::beginTransaction();
        try {
            // Generate automatic staff_id
            $maxPersonId = DB::table('pos_people')->max('person_id') ?? 0;
            $staff_id = 'STF' . str_pad($maxPersonId + 1, 3, '0', STR_PAD_LEFT);

            // Split name
            $parts = explode(' ', $validated['name']);
            $firstName = $parts[0] ?? $validated['name'];
            $lastName = isset($parts[1]) ? implode(' ', array_slice($parts, 1)) : '';

            // Create Person Details
            $person = \App\Models\Person::create([
                'staff_id' => $staff_id,
                'title' => 'Mr./Ms.',
                'first_name' => $firstName,
                'last_name' => $lastName,
                'sex' => 'N/A',
                'dob' => '2000-01-01',
                'mstatus' => 'Single',
                'religion' => 'None',
                'phone_number' => $request->input('phone', '1234567890'),
                'email' => $validated['email'],
                'address' => 'N/A',
                'state' => 'N/A',
                'country' => 'N/A',
                'nok' => 'N/A',
                'nok_address' => 'N/A',
                'nok_contact' => 'N/A',
                'nok_email' => 'nok@example.com',
                'nok_rela' => 'N/A',
                'comments' => 'Created via User portal',
            ]);

            // Create User account credentials
            $user = \App\Models\User::create([
                'person_id' => $person->person_id,
                'staff_id' => $staff_id,
                'password' => Hash::make($validated['password']),
                'position' => $validated['role'],
                'creator' => $currentUser->name ?? 'system',
                'store_id' => null,
            ]);

            $role = strtolower($validated['role']);
            if ($role === 'sales representative' || $role === 'operator') {
                $storeId = $validated['store_id'] ?? null;
                $user->store_id = $storeId;
                $user->save();
                $user->stores()->sync($storeId ? [$storeId] : []);
            } else {
                $storeIds = $request->input('store_ids', []);
                $user->store_id = !empty($storeIds) ? $storeIds[0] : null;
                $user->save();
                $user->stores()->sync($storeIds);
            }

            DB::commit();

            \App\Models\ActivityLog::log('user_creation', 'Created user account for ' . $validated['email'] . ' with role ' . $validated['role'] . '.');

            return redirect()->route('admin.users.index')->with('success', 'Staff member created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error creating user: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(\App\Models\User $user)
    {
        $currentUser = auth()->user();

        if ($user->is_protected && !$currentUser->is_protected) {
            abort(403, 'Unauthorized action.');
        }

        $stores = \App\Models\Store::all();
        $role = strtolower($currentUser->role ?? '');

        if ($role === 'head') {
            if (in_array(strtolower($user->role), ['it administrator', 'administrator', 'head'])) {
                abort(403, 'Unauthorized action.');
            }
            $roles = ['Supervisor', 'Auditor', 'Accountant'];
        } else {
            $roles = ['IT Administrator', 'Head', 'Supervisor', 'Sales Representative', 'Auditor', 'Accountant'];
        }

        return view('users.edit', compact('user', 'stores', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, \App\Models\User $user)
    {
        $currentUser = auth()->user();
        $currentUserRole = strtolower($currentUser->role ?? '');

        if ($user->is_protected && !$currentUser->is_protected) {
            abort(403, 'Unauthorized action.');
        }

        if ($currentUserRole === 'head') {
            if (in_array(strtolower($user->role), ['it administrator', 'administrator', 'head'])) {
                abort(403, 'Unauthorized action.');
            }
            $roleValidation = 'required|string|in:Supervisor,Auditor,Accountant,supervisor,auditor,accountant';
        } else {
            $roleValidation = 'required|string|in:IT Administrator,Head,Supervisor,Sales Representative,Auditor,Accountant,it administrator,head,supervisor,sales representative,auditor,accountant';
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:pos_people,email,' . $user->person_id . ',person_id',
            'role' => $roleValidation,
            'store_id' => 'nullable|exists:stores,id',
            'store_ids' => 'nullable|array',
            'store_ids.*' => 'exists:stores,id',
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);
        }

        DB::beginTransaction();
        try {
            $parts = explode(' ', $validated['name']);
            $firstName = $parts[0] ?? $validated['name'];
            $lastName = isset($parts[1]) ? implode(' ', array_slice($parts, 1)) : '';

            if ($user->person) {
                $user->person->update([
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $validated['email'],
                ]);
            }

            $user->position = $validated['role'];
            
            $role = strtolower($validated['role']);
            if ($role === 'sales representative' || $role === 'operator') {
                $storeId = $validated['store_id'] ?? null;
                $user->store_id = $storeId;
                $user->stores()->sync($storeId ? [$storeId] : []);
            } else {
                $storeIds = $request->input('store_ids', []);
                $user->store_id = !empty($storeIds) ? $storeIds[0] : null;
                $user->stores()->sync($storeIds);
            }

            if ($request->filled('password')) {
                $user->password = Hash::make($request->input('password'));
            }
            $user->save();

            DB::commit();

            \App\Models\ActivityLog::log('user_editing', 'Updated user details for ' . $validated['email'] . '.');

            return redirect()->route('admin.users.index')->with('success', 'Staff details updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error updating user: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(\App\Models\User $user)
    {
        $currentUser = auth()->user();

        if ($user->is_protected) {
            return back()->with('error', 'The primary protected administrator cannot be deleted.');
        }

        if (auth()->id() == $user->person_id) {
            return back()->with('error', 'You cannot delete yourself.');
        }

        if (strtolower($currentUser->role ?? '') === 'head') {
            if (in_array(strtolower($user->role), ['it administrator', 'administrator', 'head'])) {
                abort(403, 'Unauthorized action.');
            }
        }

        DB::beginTransaction();
        try {
            $email = $user->email;
            $person = $user->person;
            $user->delete();
            if ($person) {
                $person->delete();
            }

            DB::commit();

            \App\Models\ActivityLog::log('user_deletion', 'Deleted user ' . $email . '.');

            return redirect()->route('admin.users.index')->with('success', 'Staff member deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error deleting user: ' . $e->getMessage());
        }
    }
}
