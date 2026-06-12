<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Person;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;

class RegisterAdminController extends Controller
{
    /**
     * Show the first administrator registration form.
     */
    public function showRegistrationForm()
    {
        // Double check if admin already exists
        if (User::count() > 0) {
            return redirect()->route('login');
        }

        return view('auth.register-admin');
    }

    /**
     * Handle registration of the first administrator.
     */
    public function register(Request $request)
    {
        // Double check if admin already exists
        if (User::count() > 0) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:pos_people,email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => 'nullable|string|max:20',
        ]);

        DB::beginTransaction();
        try {
            $staff_id = 'STF001';

            // Split name
            $parts = explode(' ', $validated['name']);
            $firstName = $parts[0] ?? $validated['name'];
            $lastName = isset($parts[1]) ? implode(' ', array_slice($parts, 1)) : '';

            // Create Person details
            $person = Person::create([
                'staff_id' => $staff_id,
                'title' => 'Mr./Ms.',
                'first_name' => $firstName,
                'last_name' => $lastName,
                'sex' => 'N/A',
                'dob' => '2000-01-01',
                'mstatus' => 'Single',
                'religion' => 'None',
                'phone_number' => $validated['phone'] ?? '1234567890',
                'email' => $validated['email'],
                'address' => 'N/A',
                'state' => 'N/A',
                'country' => 'N/A',
                'nok' => 'N/A',
                'nok_address' => 'N/A',
                'nok_contact' => 'N/A',
                'nok_email' => 'nok@example.com',
                'nok_rela' => 'N/A',
                'comments' => 'First protected administrator',
            ]);

            // Create User account with role 'IT Administrator' and is_protected = true
            $user = User::create([
                'person_id' => $person->person_id,
                'staff_id' => $staff_id,
                'password' => Hash::make($validated['password']),
                'position' => 'IT Administrator',
                'creator' => 'system',
                'is_protected' => true,
            ]);

            DB::commit();

            // Log activity
            ActivityLog::log('first_admin_registration', 'First administrator account (' . $validated['email'] . ') was registered.', $user->person_id);

            // Log the user in
            Auth::login($user);

            // Store session
            $user->current_session_id = session()->getId();
            $user->last_activity = now();
            $user->save();

            return redirect()->route('admin.dashboard')->with('success', 'First administrator registered successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error during registration: ' . $e->getMessage())->withInput();
        }
    }
}
