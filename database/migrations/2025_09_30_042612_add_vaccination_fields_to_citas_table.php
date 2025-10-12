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
        Schema::table('citas', function (Blueprint $table) {
            // Campos para subtipos de consulta
            $table->string('consulta_subtipo')->nullable()->after('observaciones');
            
            // Campos específicos para vacunación
            $table->string('nombre_tecnico')->nullable()->after('consulta_subtipo');
            $table->string('nombre_comercial')->nullable()->after('nombre_tecnico');
            $table->string('lote')->nullable()->after('nombre_comercial');
            $table->string('laboratorio')->nullable()->after('lote');
            $table->date('fecha_caducidad')->nullable()->after('laboratorio');
            $table->date('fecha_expedicion')->nullable()->after('fecha_caducidad');
            $table->date('fecha_aplicacion')->nullable()->after('fecha_expedicion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('citas', function (Blueprint $table) {
            $table->dropColumn([
                'consulta_subtipo',
                'nombre_tecnico',
                'nombre_comercial',
                'lote',
                'laboratorio',
                'fecha_caducidad',
                'fecha_expedicion',
                'fecha_aplicacion'
            ]);
        });
    }
};
