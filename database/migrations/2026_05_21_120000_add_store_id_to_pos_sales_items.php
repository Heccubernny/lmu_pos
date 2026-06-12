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
        Schema::table('pos_sales_items', function (Blueprint $table) {
            if (!Schema::hasColumn('pos_sales_items', 'store_id')) {
                $table->unsignedBigInteger('store_id')->nullable()->after('recipt_number');
                // If stores table exists, add FK; otherwise leave as nullable int
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
        Schema::table('pos_sales_items', function (Blueprint $table) {
            if (Schema::hasColumn('pos_sales_items', 'store_id')) {
                // Drop foreign key first if exists
                $sm = Schema::getConnection()->getDoctrineSchemaManager();
                $doctrineTable = $sm->listTableDetails($table->getTable());
                if ($doctrineTable->hasForeignKey('pos_sales_items_store_id_foreign')) {
                    $table->dropForeign('pos_sales_items_store_id_foreign');
                }
                $table->dropColumn('store_id');
            }
        });
    }
};
