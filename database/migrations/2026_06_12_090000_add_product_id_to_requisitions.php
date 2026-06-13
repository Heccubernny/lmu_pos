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
        Schema::table('pos_store_requisition', function (Blueprint $table) {
            if (!Schema::hasColumn('pos_store_requisition', 'product_id')) {
                $table->integer('product_id')->nullable()->after('item_id');
            }
            if (!Schema::hasColumn('pos_store_requisition', 'store_id')) {
                $table->unsignedBigInteger('store_id')->nullable()->after('product_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pos_store_requisition', function (Blueprint $table) {
            if (Schema::hasColumn('pos_store_requisition', 'product_id')) {
                $table->dropColumn('product_id');
            }
            if (Schema::hasColumn('pos_store_requisition', 'store_id')) {
                $table->dropColumn('store_id');
            }
        });
    }
};
