<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use PDO;

class FirstLaunchRedirect
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Skip checking for static assets or API routes if needed
        if ($request->is('_debugbar*') || $request->is('vendor/*') || $request->is('css/*') || $request->is('js/*')) {
            return $next($request);
        }

        $dbName = config('database.connections.mysql.database');
        $host = config('database.connections.mysql.host');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');

        try {
            // Check if connection works
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            // DB does not exist, or connection failed due to missing DB
            try {
                $pdo = new PDO("mysql:host=$host", $username, $password);
                $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                
                // Clear DB connection cache
                DB::purge();
                
                // Run migrations
                Artisan::call('migrate', ['--force' => true]);
            } catch (\Exception $ex) {
                return response("Database Setup Error: " . $ex->getMessage(), 500);
            }
        }

        // Now, check if pos_users table exists and has any users
        try {
            if (!Schema::hasTable('pos_users')) {
                Artisan::call('migrate', ['--force' => true]);
            }

            $userCount = DB::table('pos_users')->count();
            if ($userCount === 0) {
                if ($request->is('register-admin*') || $request->is('_store-role')) {
                    return $next($request);
                }
                return redirect()->route('register-admin');
            } else {
                if ($request->is('register-admin*')) {
                    return redirect()->route('login');
                }
            }
        } catch (\Exception $e) {
            return response("System Setup Error: " . $e->getMessage(), 500);
        }

        return $next($request);
    }
}
