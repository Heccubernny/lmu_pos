<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RestrictOperator
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        if ($user && in_array(strtolower($user->role ?? ''), ['operator', 'sales representative'])) {
            return redirect()->route('cashier.sales.create')->with('error', 'You do not have permission to access that area.');
        }

        return $next($request);
    }
}
