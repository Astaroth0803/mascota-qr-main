<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    use HasFactory;

    // Especifica el nombre correcto de la tabla
    protected $table = 'solicitudes';

    protected $fillable = [
        'nombre_owner',
        'apellido_owner',
        'correo_owner',
        'telefono_owner',
        'nombre',
        'especie',
        'raza',
        'edad_anios',
        'edad_meses',
        'sexo',
        'id_pago_yappy',
    ];
}
