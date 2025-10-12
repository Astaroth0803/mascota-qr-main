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
        Schema::create('vaccines', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_tecnico'); // Nombre técnico de la vacuna
            $table->string('nombre_comercial'); // Nombre comercial
            $table->string('laboratorio'); // Laboratorio fabricante
            $table->string('especie')->default('perro'); // perro, gato, etc.
            $table->text('descripcion')->nullable(); // Descripción adicional
            $table->boolean('activa')->default(true); // Si está disponible
            $table->timestamps();
            
            // Índices para búsquedas eficientes
            $table->index(['nombre_tecnico', 'laboratorio']);
            $table->index(['especie', 'activa']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vaccines');
    }
};
