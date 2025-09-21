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
        Schema::create('mascota_veterinario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mascota_id')->constrained('pets')->onDelete('cascade');
            $table->foreignId('veterinario_id')->constrained('users')->onDelete('cascade');
            $table->date('fecha_asignacion');
            $table->boolean('activo')->default(true);
            $table->enum('tipo_asignacion', ['principal', 'especialista', 'emergencia'])->default('principal');
            $table->text('notas')->nullable();
            $table->timestamps();
            
            // Índice único para evitar duplicados activos
            $table->unique(['mascota_id', 'veterinario_id', 'activo']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mascota_veterinario');
    }
};