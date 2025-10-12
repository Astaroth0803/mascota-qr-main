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
        Schema::create('vet_request_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('veterinario_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('cliente_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('mascota_id')->constrained('pets')->onDelete('cascade');
            $table->foreignId('asignacion_id')->constrained('mascota_veterinario')->onDelete('cascade');
            $table->enum('tipo', ['solicitud', 'aceptada', 'rechazada', 'cancelada'])->default('solicitud');
            $table->text('mensaje');
            $table->boolean('leida')->default(false);
            $table->timestamp('leida_at')->nullable();
            $table->timestamps();
            
            // Índices
            $table->index(['veterinario_id', 'leida']);
            $table->index(['cliente_id', 'leida']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vet_request_notifications');
    }
};