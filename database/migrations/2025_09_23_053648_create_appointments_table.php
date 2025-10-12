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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            
            // Relaciones
            $table->foreignId('pet_id')->constrained('pets')->onDelete('cascade');
            $table->foreignId('veterinarian_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            
            // Información básica de la cita
            $table->enum('status', ['pendiente', 'agendada', 'en_progreso', 'finalizada', 'cancelada'])
                  ->default('pendiente');
            $table->string('record_type'); // vacuna, operacion, emergencia, checkeo
            $table->datetime('requested_datetime'); // Fecha y hora solicitada por el cliente
            $table->datetime('scheduled_datetime')->nullable(); // Fecha y hora agendada por el veterinario
            $table->string('location')->nullable();
            
            // Campos que se llenan cuando está en progreso
            $table->text('diagnosis_treatment')->nullable(); // Campo unificado
            $table->text('observations')->nullable();
            
            // Campos específicos para vacunas (solo cuando record_type = 'vacuna')
            $table->string('vaccine_type')->nullable(); // Vacuna Múltiple, etc.
            $table->string('vaccine_name')->nullable(); // Nombre de la vacuna seleccionada
            $table->string('technical_name')->nullable(); // Nombre técnico
            $table->string('laboratory')->nullable(); // Laboratorio
            $table->string('lot_number')->nullable(); // Número de lote
            $table->date('creation_date')->nullable(); // F. creación
            $table->date('expiry_date')->nullable(); // F. vencimiento
            
            // Metadatos
            $table->text('cancellation_reason')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();
            
            // Índices para optimizar consultas
            $table->index(['veterinarian_id', 'scheduled_datetime']);
            $table->index(['pet_id', 'status']);
            $table->index(['status', 'scheduled_datetime']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};