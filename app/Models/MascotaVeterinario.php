<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MascotaVeterinario extends Model
{
    use HasFactory;

    protected $table = 'mascota_veterinario';

    protected $fillable = [
        'mascota_id',
        'veterinario_id',
        'fecha_asignacion',
        'activo',
        'tipo_asignacion',
        'notas'
    ];

    protected $casts = [
        'fecha_asignacion' => 'date',
        'activo' => 'boolean'
    ];

    /**
     * Relación con la mascota
     */
    public function mascota()
    {
        return $this->belongsTo(Pet::class, 'mascota_id');
    }

    /**
     * Relación con el veterinario
     */
    public function veterinario()
    {
        return $this->belongsTo(User::class, 'veterinario_id');
    }

    /**
     * Scope para asignaciones activas
     */
    public function scopeActivas($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope para veterinario principal
     */
    public function scopePrincipal($query)
    {
        return $query->where('tipo_asignacion', 'principal');
    }

    /**
     * Scope para especialistas
     */
    public function scopeEspecialistas($query)
    {
        return $query->where('tipo_asignacion', 'especialista');
    }

    /**
     * Scope para emergencias
     */
    public function scopeEmergencias($query)
    {
        return $query->where('tipo_asignacion', 'emergencia');
    }
}