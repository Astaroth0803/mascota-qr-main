<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pets', function (Blueprint $table) {
            $table->id(); // Genera un campo id autoincrementable
            $table->string('nombre'); // Campo para el nombre de la mascota
            $table->string('especie'); // Campo para la especie
            $table->string('raza'); // Campo para la raza
            $table->string('edad'); // Campo para la edad
            $table->string('sexo'); // Campo para el sexo
            $table->timestamps(); // Campos created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pets');
    }

    
}
