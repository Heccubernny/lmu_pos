<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class StoreConnector
{
    public static function connect($host)
    {
        // Read store DB credentials from environment variables.
        // Allows each store to potentially use different credentials or share a common set.
        $storeDbPort = env('STORE_DB_PORT', 3306);
        $storeDbName = env('STORE_DB_NAME', 'tconndb');
        $storeDbUsername = env('STORE_DB_USERNAME', 'tconn_user');
        $storeDbPassword = env('STORE_DB_PASSWORD', 'Admin!23');

        config([
            'database.connections.store_dynamic' => [
                'driver' => 'mysql',
                'host' => $host,
                'port' => $storeDbPort,
                'database' => $storeDbName,
                'username' => $storeDbUsername,
                'password' => $storeDbPassword,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ]
        ]);

        DB::purge('store_dynamic');
        DB::reconnect('store_dynamic');

        return DB::connection('store_dynamic');
    }
}