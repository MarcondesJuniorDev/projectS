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
        Schema::create('service_order_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_order_id')
                ->constrained('service_orders')
                ->onDelete('cascade');
            $table->foreignId('material_id')
                ->constrained('materials')
                ->onDelete('cascade');
            $table->integer('quantity_used');
            $table->decimal('cost_price_at_use', 10, 2);
            $table->timestamps();

            $table->unique(['service_order_id', 'material_id'], 'so_material_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_order_materials');
    }
};
