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
        // Alter pos_users to add session tracking and protection columns
        Schema::table('pos_users', function (Blueprint $table) {
            if (!Schema::hasColumn('pos_users', 'current_session_id')) {
                $table->string('current_session_id')->nullable()->after('store_id');
            }
            if (!Schema::hasColumn('pos_users', 'last_activity')) {
                $table->timestamp('last_activity')->nullable()->after('current_session_id');
            }
            if (!Schema::hasColumn('pos_users', 'is_protected')) {
                $table->boolean('is_protected')->default(false)->after('last_activity');
            }
        });

        // Alter stores to add supervisor_id for scoping
        Schema::table('stores', function (Blueprint $table) {
            if (!Schema::hasColumn('stores', 'supervisor_id')) {
                $table->integer('supervisor_id')->nullable()->after('authorized');
            }
        });

        // Create supervisor_stocks table
        Schema::create('supervisor_stocks', function (Blueprint $table) {
            $table->id();
            $table->integer('supervisor_id');
            $table->integer('product_id');
            $table->float('quantity')->default(0);
            $table->timestamps();

            $table->foreign('supervisor_id')->references('person_id')->on('pos_users')->onDelete('cascade');
            $table->foreign('product_id')->references('item_id')->on('pos_items')->onDelete('cascade');
        });

        // Create hall_stocks table
        Schema::create('hall_stocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_id');
            $table->integer('product_id');
            $table->float('quantity')->default(0);
            $table->timestamps();

            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->foreign('product_id')->references('item_id')->on('pos_items')->onDelete('cascade');
        });

        // Create supplier_receipts table
        Schema::create('supplier_receipts', function (Blueprint $table) {
            $table->id();
            $table->integer('supervisor_id');
            $table->string('supplier_name');
            $table->integer('product_id');
            $table->double('unit_cost', 15, 2);
            $table->float('quantity');
            $table->double('total_cost', 15, 2);
            $table->string('payment_status'); // Paid or Credit
            $table->timestamps();

            $table->foreign('supervisor_id')->references('person_id')->on('pos_users')->onDelete('cascade');
            $table->foreign('product_id')->references('item_id')->on('pos_items')->onDelete('cascade');
        });

        // Create stock_allocations table
        Schema::create('stock_allocations', function (Blueprint $table) {
            $table->id();
            $table->integer('supervisor_id');
            $table->unsignedBigInteger('store_id');
            $table->integer('product_id');
            $table->float('quantity');
            $table->timestamps();

            $table->foreign('supervisor_id')->references('person_id')->on('pos_users')->onDelete('cascade');
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->foreign('product_id')->references('item_id')->on('pos_items')->onDelete('cascade');
        });

        // Create damaged_expired_items table
        Schema::create('damaged_expired_items', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('product_id');
            $table->unsignedBigInteger('store_id')->nullable(); // If null, from supervisor store stock
            $table->float('quantity');
            $table->string('type'); // damaged or expired
            $table->string('status')->default('pending'); // pending or approved
            $table->integer('approved_by')->nullable(); // auditor user ID
            $table->timestamps();

            $table->foreign('user_id')->references('person_id')->on('pos_users')->onDelete('cascade');
            $table->foreign('product_id')->references('item_id')->on('pos_items')->onDelete('cascade');
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('set null');
        });

        // Create pos_receipts table
        Schema::create('pos_receipts', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_number')->unique();
            $table->string('receipt_uid')->unique();
            $table->string('barcode_identifier');
            $table->string('cashier_name');
            $table->string('store_name');
            $table->double('total_amount', 15, 2);
            $table->string('payment_method');
            $table->string('moniepoint_ref')->nullable();
            $table->string('terminal_id')->nullable();
            $table->json('receipt_data');
            $table->timestamps();
        });

        // Create activity_logs table
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('action');
            $table->text('description');
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('pos_receipts');
        Schema::dropIfExists('damaged_expired_items');
        Schema::dropIfExists('stock_allocations');
        Schema::dropIfExists('supplier_receipts');
        Schema::dropIfExists('hall_stocks');
        Schema::dropIfExists('supervisor_stocks');

        Schema::table('stores', function (Blueprint $table) {
            if (Schema::hasColumn('stores', 'supervisor_id')) {
                $table->dropColumn('supervisor_id');
            }
        });

        Schema::table('pos_users', function (Blueprint $table) {
            if (Schema::hasColumn('pos_users', 'current_session_id')) {
                $table->dropColumn('current_session_id');
            }
            if (Schema::hasColumn('pos_users', 'last_activity')) {
                $table->dropColumn('last_activity');
            }
            if (Schema::hasColumn('pos_users', 'is_protected')) {
                $table->dropColumn('is_protected');
            }
        });
    }
};
