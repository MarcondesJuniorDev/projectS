<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Laravel\Prompts\text;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('service_orders', function (Blueprint $table) {
            $table->id();
            $table->string('os_number')->unique();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_location_id')->constrained()->onDelete('cascade');
            $table->string('requester_name')->nullable();
            $table->string('requester_phone')->nullable();
            $table->string('requester_email')->nullable();
            $table->text('problem_description')->nullable();
            $table->enum('priority', allowed: ['baixa', 'media', 'alta', 'critica'])->default('media');
            $table->enum('status', [
                'aberto',
                'em_analise',
                'aguardando_materiais',
                'em_andamento',
                'aguardando_aprovacao',
                'concluido',
                'cancelado',
                'reaberto'
            ])->default('aberto');

            $table->timestamp('opened_at')->useCurrent();
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            $table->foreignId(  'assigned_technician_id')->nullable()->constrained('technicians')->onDelete('set null');
            $table->text('service_performed_description')->nullable();
            $table->decimal('time_spent_hours', 8,2)->nullable();

            $table->string('customer_signature_path')->nullable();
            $table->string('customer_feedback')->nullable();
            $table->integer('customer_rating')->nullable()->comment('Classificação de 1 a 5 estrelas');

            $table->text('internal_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_orders');
    }
};
