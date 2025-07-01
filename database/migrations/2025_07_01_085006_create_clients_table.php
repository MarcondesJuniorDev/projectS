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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Nome do cliente
            $table->string('corporate_name')->nullable(); // Razão social - Caso seja diferente do nome
            $table->string('cnpj',18)->nullable(); // CNPJ do cliente
            $table->string('state_registration', 20)->nullable(); // Inscrição estadual
            $table->string('address')->nullable(); // Endereço do cliente
            $table->string('city')->nullable(); // Cidade do cliente
            $table->string('state', 2)->nullable(); // Estado do cliente (sigla)
            $table->string('zip_code', 10)->nullable(); // CEP do cliente
            $table->string('phone',20)->nullable(); // Telefone do cliente
            $table->string('email')->nullable(); // E-mail do cliente
            $table->string('contact_person_name')->nullable(); // Nome da pessoa de contato
            $table->string('contact_person_phone')->nullable(); // Telefone da pessoa de contato
            $table->string('contact_person_email')->nullable(); // E-mail da pessoa de contato
            $table->string('notes')->nullable(); // Observações
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
