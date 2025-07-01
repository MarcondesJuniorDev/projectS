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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('company_name')->unique();
            $table->string('corporate_name')->nullable();
            $table->string('cnpj', 18)->unique()->nullable();
            $table->string('state_registration',20)->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state', 2)->nullable();
            $table->string('zip_code', 10)->nullable();
            $table->string('phone', 15)->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('contact_person_name')->nullable();
            $table->string('contact_person_role')->nullable();
            $table->string('contact_person_phone', 15)->nullable();
            $table->text('products_services_offered')->nullable();
            $table->text('payment_terms')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
