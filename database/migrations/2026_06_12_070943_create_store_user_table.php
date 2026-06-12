<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates a many-to-many pivot between pos_users (staff) and stores.
     * Also adds a 'status' column to stores if it is missing.
     */
    public function up(): void
    {
        // 1. Add status to stores table if not already there
        if (!Schema::hasColumn('stores', 'status')) {
            Schema::table('stores', function (Blueprint $table) {
                $table->string('status')->default('active')->after('host');
            });
        }

        // 2. Create the many-to-many pivot table for staff <-> stores
        if (!Schema::hasTable('store_user')) {
            Schema::create('store_user', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('store_id');
                $table->unsignedInteger('user_id'); // references pos_users.person_id
                $table->timestamps();

                $table->unique(['store_id', 'user_id']);
                $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
                // No FK on user_id since pos_users uses non-standard int PK
            });
        }

        // 3. Migrate existing single store_id assignments into the pivot table
        if (Schema::hasColumn('pos_users', 'store_id') && Schema::hasTable('store_user')) {
            $existing = \Illuminate\Support\Facades\DB::table('pos_users')
                ->whereNotNull('store_id')
                ->select('person_id', 'store_id')
                ->get();

            foreach ($existing as $row) {
                \Illuminate\Support\Facades\DB::table('store_user')->insertOrIgnore([
                    'store_id'   => $row->store_id,
                    'user_id'    => $row->person_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_user');

        if (Schema::hasColumn('stores', 'status')) {
            Schema::table('stores', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};
