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
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('sku')->unique()->nullable();
            $table->enum('unit_of_measurement', ['unit', 'box', 'roll', 'kg', 'liter', 'meter', 'piece', 'other'])->default('unit');
            $table->decimal('cost_price', 10, 2)->default(0.00);
            $table->integer('min_stock_level')->default(0);
            $table->integer('max_stock_level')->nullable();
            $table->integer('current_stock')->default(0);
            $table->text('description')->nullable();
            $table->string('location_in_warehouse')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
