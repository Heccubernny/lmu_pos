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
        Schema::create('requisitions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category');
            $table->float('quantity')->default(0);
            $table->string('collectedby')->nullable();
            $table->string('department')->nullable();
            $table->string('ty')->nullable();
            $table->string('staff_id')->nullable();
            $table->string('manager_approved')->nullable();
            $table->string('status')->nullable();
            $table->string('branch')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requisitions');
    }
};
