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
        Schema::create('appointment_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pet_id')->constrained('pets')->onDelete('cascade');
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('veterinarian_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('appointment_id')->nullable()->constrained('appointments')->onDelete('set null');
            $table->enum('status', [
                'pendiente',
                'aceptado', 
                'rechazado',
                'cita_terminada',
                'cita_cancelada',
                'cita_reagendada'
            ])->default('pendiente');
            $table->datetime('requested_datetime');
            $table->datetime('scheduled_datetime')->nullable();
            $table->enum('appointment_type', [
                'consulta',
                'vacunacion', 
                'cirugia',
                'emergencia',
                'chequeo'
            ]);
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->text('reschedule_reason')->nullable();
            $table->timestamps();
            
            // Índices
            $table->index(['status', 'created_at']);
            $table->index(['veterinarian_id', 'status']);
            $table->index(['client_id', 'status']);
            $table->index(['pet_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointment_requests');
    }
};
