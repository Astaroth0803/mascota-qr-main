<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSolicitudesTable extends Migration
{
    public function up()
    {
        Schema::create('solicitudes', function (Blueprint $table) {
            $table->id();

            // Datos del dueño (solicitante)
            $table->string('nombre_owner');
            $table->string('apellido_owner');
            $table->string('correo_owner')->unique();
            $table->string('telefono_owner')->nullable();

            // Datos de la mascota
            $table->string('nombre');
            $table->string('especie');
            $table->string('raza')->nullable();
            $table->string('edad')->nullable();
            $table->string('sexo')->nullable();

            // Datos del pago, si es necesario
            $table->string('id_pago_yappy')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('solicitudes');
    }
}
