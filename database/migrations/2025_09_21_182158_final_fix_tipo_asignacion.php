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
        // Eliminar la restricción check temporalmente
        DB::statement('ALTER TABLE mascota_veterinario DROP CONSTRAINT IF EXISTS mascota_veterinario_tipo_asignacion_check');
        
        // Actualizar los datos
        DB::statement("
            UPDATE mascota_veterinario 
            SET tipo_asignacion = CASE 
                WHEN tipo_asignacion = 'principal' THEN 'licenciado'
                WHEN tipo_asignacion = 'especialista' THEN 'tecnico'
                WHEN tipo_asignacion = 'emergencia' THEN 'auxiliar'
                ELSE tipo_asignacion
            END
        ");
        
        // Agregar la nueva restricción check
        DB::statement("ALTER TABLE mascota_veterinario ADD CONSTRAINT mascota_veterinario_tipo_asignacion_check CHECK (tipo_asignacion IN ('auxiliar', 'tecnico', 'licenciado'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Eliminar la nueva restricción
        DB::statement('ALTER TABLE mascota_veterinario DROP CONSTRAINT IF EXISTS mascota_veterinario_tipo_asignacion_check');
        
        // Revertir los datos
        DB::statement("
            UPDATE mascota_veterinario 
            SET tipo_asignacion = CASE 
                WHEN tipo_asignacion = 'licenciado' THEN 'principal'
                WHEN tipo_asignacion = 'tecnico' THEN 'especialista'
                WHEN tipo_asignacion = 'auxiliar' THEN 'emergencia'
                ELSE tipo_asignacion
            END
        ");
        
        // Restaurar la restricción original
        DB::statement("ALTER TABLE mascota_veterinario ADD CONSTRAINT mascota_veterinario_tipo_asignacion_check CHECK (tipo_asignacion IN ('principal', 'especialista', 'emergencia'))");
    }
};