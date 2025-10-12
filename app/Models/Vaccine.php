<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vaccine extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre_tecnico',
        'nombre_comercial',
        'laboratorio',
        'especie',
        'descripcion',
        'activa'
    ];

    protected $casts = [
        'activa' => 'boolean'
    ];

    // Scopes
    public function scopeActivas($query)
    {
        return $query->where('activa', true);
    }

    public function scopePorEspecie($query, $especie)
    {
        return $query->where('especie', $especie);
    }

    public function scopePorLaboratorio($query, $laboratorio)
    {
        return $query->where('laboratorio', $laboratorio);
    }

    // Método para obtener vacunas por nombre técnico
    public static function getByNombreTecnico($nombreTecnico)
    {
        return self::where('nombre_tecnico', $nombreTecnico)
                  ->where('activa', true)
                  ->get();
    }

    // Método para obtener laboratorios únicos
    public static function getLaboratorios()
    {
        return self::activas()
                   ->distinct()
                   ->pluck('laboratorio')
                   ->sort()
                   ->values();
    }

    // Método para obtener nombres técnicos únicos
    public static function getNombresTecnicos()
    {
        return self::activas()
                   ->distinct()
                   ->pluck('nombre_tecnico')
                   ->sort()
                   ->values();
    }
}
