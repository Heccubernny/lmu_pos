<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        // If there is no authenticated user, let auth middleware handle redirect to login.
        if (! $user) {
            return redirect()->guest(route('login'));
        }

        // Friendly redirect based on specific staff roles to prevent loops
        if ($user->isSupervisor()) {
            return redirect()->route('supervisor.stock.allocate.form')->with('error', 'You are not authorized to access the admin dashboard.');
        }
        if ($user->isAuditor()) {
            return redirect()->route('auditor.dashboard')->with('error', 'You are not authorized to access the admin dashboard.');
        }
        if ($user->isAccountant()) {
            return redirect()->route('accountant.dashboard')->with('error', 'You are not authorized to access the admin dashboard.');
        }

        // Friendly redirect for non-admin users (operators) instead of 403.
        $role = strtolower($user->role ?? '');
        if (! in_array($role, ['it administrator', 'administrator', 'head'])) {
            return redirect('/')->with('error', 'You are not authorized to access the admin area.');
        }

        return $next($request);
    }
}
