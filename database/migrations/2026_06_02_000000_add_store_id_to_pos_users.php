<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pos_users', function (Blueprint $table) {
            if (!Schema::hasColumn('pos_users', 'store_id')) {
                $table->unsignedBigInteger('store_id')->nullable()->after('creator');
                if (Schema::hasTable('stores')) {
                    $table->foreign('store_id')->references('id')->on('stores')->onDelete('set null');
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pos_users', function (Blueprint $table) {
            if (Schema::hasColumn('pos_users', 'store_id')) {
                try {
                    $table->dropForeign(['store_id']);
                } catch (\Exception $e) {}
                $table->dropColumn('store_id');
            }
        });
    }
};
