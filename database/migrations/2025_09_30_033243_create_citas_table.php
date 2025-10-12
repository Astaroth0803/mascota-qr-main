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
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cliente_id');
            $table->unsignedBigInteger('veterinario_id');
            $table->unsignedBigInteger('mascota_id');
            $table->datetime('fecha_solicitada');
            $table->datetime('fecha_asignada')->nullable();
            $table->enum('estado', ['pendiente', 'agendada', 'finalizada', 'cancelada'])->default('pendiente');
            $table->text('observaciones')->nullable();
            $table->text('motivo_rechazo')->nullable();
            $table->text('diagnostico_tratamiento')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('cliente_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('veterinario_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('mascota_id')->references('id')->on('pets')->onDelete('cascade');

            // Indexes
            $table->index(['estado', 'fecha_solicitada']);
            $table->index(['veterinario_id', 'estado']);
            $table->index(['cliente_id', 'estado']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
