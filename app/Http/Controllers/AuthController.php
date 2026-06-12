<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Show the custom login form.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect('/');
        }
        return view('auth.login');
    }

    /**
     * Show the admin login form at /admin/login
     */
    public function showAdminLoginForm()
    {
        if (Auth::check()) {
            return redirect('/');
        }

        return view('auth.admin-login');
    }

    /**
     * Handle manual authentication attempt.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $person = \App\Models\Person::where('email', $credentials['email'])->first();

        if ($person) {
            $user = \App\Models\User::where('person_id', $person->person_id)->first();

            if ($user && Hash::check($credentials['password'], $user->password)) {
                $role = strtolower($user->role ?? '');

                if ($role !== 'sales representative' && $role !== 'operator') {
                    \App\Models\ActivityLog::log('blocked_login', 'Blocked login attempt to Sales POS for non-sales user ' . $user->email . '.', $user->person_id);
                    return back()->withErrors(['email' => 'This account cannot sign in here. Please use the admin login.'])->onlyInput('email');
                }

                // Check for concurrent/duplicate session
                if (!empty($user->current_session_id) && !empty($user->last_activity)) {
                    $lifetime = config('session.lifetime', 120) * 60;
                    $lastActive = \Carbon\Carbon::parse($user->last_activity);
                    if ($lastActive->diffInSeconds(now()) < $lifetime) {
                        \App\Models\ActivityLog::log('duplicate_session_attempt', 'Duplicate login attempt blocked for user ' . $user->email . '.', $user->person_id);
                        return back()->withErrors(['email' => 'This user is already logged in on another session. Duplicate session attempts are blocked.'])->onlyInput('email');
                    }
                }

                Auth::login($user, $request->boolean('remember'));
                $request->session()->regenerate();

                // Save session in DB
                $user->current_session_id = session()->getId();
                $user->last_activity = now();
                $user->save();

                \App\Models\ActivityLog::log('login', 'User ' . $user->email . ' logged in successfully (Sales Rep).', $user->person_id);

                $userStoreId = $user->store_id;
                if (empty($userStoreId)) {
                    $firstAssignedStore = $user->stores()->first();
                    if ($firstAssignedStore) {
                        $userStoreId = $firstAssignedStore->id;
                    }
                }

                if (!empty($userStoreId)) {
                    $store = \App\Models\Store::find($userStoreId);
                    if ($store) {
                        session([
                            'authorized_store' => [
                                'store_id' => $store->id,
                                'name' => $store->name ?? ($store->host ?? 'Store'),
                            ]
                        ]);
                    }
                }
                return redirect('/')->with('success', 'Welcome back!');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Handle admin login submissions (separate endpoint)
     */
    public function adminLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $person = \App\Models\Person::where('email', $credentials['email'])->first();

        if ($person) {
            $user = \App\Models\User::where('person_id', $person->person_id)->first();

            if ($user && \Illuminate\Support\Facades\Hash::check($credentials['password'], $user->password)) {
                $role = strtolower($user->role ?? '');

                if ($role === 'sales representative' || $role === 'operator') {
                    \App\Models\ActivityLog::log('blocked_login', 'Blocked login attempt to Admin Portal for Sales Representative user ' . $user->email . '.', $user->person_id);
                    return back()->withErrors(['email' => 'Access denied for operator/sales representative accounts on this page.']);
                }

                // Check for concurrent/duplicate session
                if (!empty($user->current_session_id) && !empty($user->last_activity)) {
                    $lifetime = config('session.lifetime', 120) * 60;
                    $lastActive = \Carbon\Carbon::parse($user->last_activity);
                    if ($lastActive->diffInSeconds(now()) < $lifetime) {
                        \App\Models\ActivityLog::log('duplicate_session_attempt', 'Duplicate login attempt blocked for user ' . $user->email . '.', $user->person_id);
                        return back()->withErrors(['email' => 'This user is already logged in on another session. Duplicate session attempts are blocked.'])->onlyInput('email');
                    }
                }

                Auth::login($user, $request->boolean('remember'));
                $request->session()->regenerate();

                // Save session in DB
                $user->current_session_id = session()->getId();
                $user->last_activity = now();
                $user->save();

                $userStoreId = $user->store_id;
                if (empty($userStoreId)) {
                    $firstAssignedStore = $user->stores()->first();
                    if ($firstAssignedStore) {
                        $userStoreId = $firstAssignedStore->id;
                    }
                }

                if (!empty($userStoreId)) {
                    $store = \App\Models\Store::find($userStoreId);
                    if ($store) {
                        session([
                            'authorized_store' => [
                                'store_id' => $store->id,
                                'name' => $store->name ?? ($store->host ?? 'Store'),
                            ]
                        ]);
                    }
                }

                \App\Models\ActivityLog::log('login', 'User ' . $user->email . ' logged in successfully (Admin/Staff: ' . $user->role . ').', $user->person_id);

                return redirect('/')->with('success', 'Welcome back, ' . $user->role . '!');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Log out the user manually.
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            $role = strtolower($user->role ?? '');
            $user->current_session_id = null;
            $user->last_activity = null;
            $user->save();

            \App\Models\ActivityLog::log('logout', 'User ' . $user->email . ' logged out.', $user->person_id);
        } else {
            $role = 'operator';
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $redirect = '/login';
        if ($role !== 'operator' && $role !== 'sales representative') {
            $redirect = '/admin/login';
        }

        return redirect($redirect)->with('success', 'You have been logged out.');
    }
}
