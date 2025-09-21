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
        Schema::table('pets', function (Blueprint $table) {
            $table->string('nombre_owner')->nullable(); // Agregar columna
            $table->string('apellido_owner')->nullable(); // Agregar columna
            $table->string('telefono_owner')->nullable(); // Agregar columna
            $table->string('correo_owner')->nullable(); // Agregar columna
            $table->string('id_pago_yappy')->nullable(); // Agregar columna
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pets', function (Blueprint $table) {
            $table->dropColumn('nombre_owner'); // Eliminar columna
            $table->dropColumn('apellido_owner'); // Eliminar columna
            $table->dropColumn('telefono_owner'); // Eliminar columna
            $table->dropColumn('correo_owner'); // Eliminar columna
            $table->dropColumn('id_pago_yappy'); // Eliminar columna
        });
    }
};