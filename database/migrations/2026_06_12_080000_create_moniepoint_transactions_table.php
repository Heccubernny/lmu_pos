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
        Schema::create('moniepoint_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->double('amount', 15, 2);
            $table->string('payment_method'); // 'Card' or 'Transfer'
            $table->string('terminal_id')->nullable();
            $table->string('status')->default('pending'); // 'pending', 'successful', 'failed'
            $table->string('customer_name')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('card_brand')->nullable();
            $table->string('card_last_4')->nullable();
            $table->integer('sale_id')->nullable();
            $table->string('recipt_number')->nullable();
            $table->unsignedBigInteger('store_id')->nullable();
            $table->integer('cashier_id')->nullable();
            $table->json('payload')->nullable();
            $table->timestamps();

            $table->foreign('store_id')->references('id')->on('stores')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('moniepoint_transactions');
    }
};
