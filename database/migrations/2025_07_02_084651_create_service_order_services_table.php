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
        Schema::create('service_order_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_order_id')
                ->constrained('service_orders')
                ->onDelete('cascade');
            $table->foreignId('service_template_id')
                ->constrained('service_templates')
                ->onDelete('cascade');
            $table->string('description');
            $table->decimal('price', 10, 2)->comment('Preço do serviço no momento da execução');
            $table->decimal('time_spent_hours', 8, 2)->nullable()->comment('Horas gastas na execução do serviço');
            $table->timestamps();

            $table->unique(['service_order_id', 'service_template_id', 'description'], 'so_service_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_order_services');
    }
};
