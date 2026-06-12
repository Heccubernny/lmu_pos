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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('supplier')->nullable(); // Original project used string for supplier, I'll keep it or use foreignId
            $table->string('item_number')->nullable();
            $table->text('description')->nullable();
            $table->decimal('cost_price', 15, 2)->default(0);
            $table->decimal('unit_price', 15, 2)->default(0);
            $table->float('quantity')->default(0);
            $table->string('staff_id')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
