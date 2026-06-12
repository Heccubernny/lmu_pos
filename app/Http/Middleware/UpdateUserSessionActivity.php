<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UpdateUserSessionActivity
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $currentSessionId = session()->getId();

            if ($user->current_session_id && $user->current_session_id !== $currentSessionId) {
                // Mismatched session (e.g. logged in elsewhere)
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('login')->with('error', 'Your session has been terminated because you logged in from another device.');
            }

            // Update last activity timestamp
            $user->last_activity = now();
            $user->save();
        }

        return $next($request);
    }
}
