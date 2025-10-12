<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('mascota_veterinario', function (Blueprint $table) {
            // Eliminar la restricción única actual que incluye 'activo'
            $table->dropUnique(['mascota_id', 'veterinario_id', 'activo']);
        });
        
        // Crear una restricción única parcial que solo aplica cuando activo = true
        // Esto permite múltiples registros históricos con activo = false
        DB::statement('
            CREATE UNIQUE INDEX mascota_veterinario_mascota_id_veterinario_id_activo_unique 
            ON mascota_veterinario (mascota_id, veterinario_id) 
            WHERE activo = true
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Eliminar la restricción única parcial
        DB::statement('DROP INDEX IF EXISTS mascota_veterinario_mascota_id_veterinario_id_activo_unique');
        
        Schema::table('mascota_veterinario', function (Blueprint $table) {
            // Restaurar la restricción única original
            $table->unique(['mascota_id', 'veterinario_id', 'activo']);
        });
    }
};