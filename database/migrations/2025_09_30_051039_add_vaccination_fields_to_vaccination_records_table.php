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
        Schema::table('vaccination_records', function (Blueprint $table) {
            // Campos específicos para vacunación
            $table->string('nombre_tecnico')->nullable()->after('vaccine_name');
            $table->string('nombre_comercial')->nullable()->after('nombre_tecnico');
            $table->string('lote')->nullable()->after('nombre_comercial');
            $table->string('laboratorio')->nullable()->after('lote');
            $table->date('fecha_caducidad')->nullable()->after('laboratorio');
            $table->date('fecha_expedicion')->nullable()->after('fecha_caducidad');
            $table->date('fecha_aplicacion')->nullable()->after('fecha_expedicion');
            $table->string('consulta_subtipo')->nullable()->after('fecha_aplicacion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vaccination_records', function (Blueprint $table) {
            $table->dropColumn([
                'nombre_tecnico',
                'nombre_comercial',
                'lote',
                'laboratorio',
                'fecha_caducidad',
                'fecha_expedicion',
                'fecha_aplicacion',
                'consulta_subtipo'
            ]);
        });
    }
};
