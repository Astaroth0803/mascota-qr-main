<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MascotaVeterinario extends Model
{
    use HasFactory;

    // Constantes para los tipos de asignación
    const TIPO_AUXILIAR = 'auxiliar';
    const TIPO_TECNICO = 'tecnico';
    const TIPO_LICENCIADO = 'licenciado';

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
     * Obtener las opciones de tipos de asignación
     */
    public static function getTiposAsignacion()
    {
        return [
            self::TIPO_AUXILIAR => 'Auxiliar de Vet',
            self::TIPO_TECNICO => 'Tec. Veterinario',
            self::TIPO_LICENCIADO => 'Lic. Veterinario',
        ];
    }

    /**
     * Obtener el nombre del tipo de asignación
     */
    public function getTipoAsignacionNombreAttribute()
    {
        $tipos = self::getTiposAsignacion();
        return $tipos[$this->tipo_asignacion] ?? 'No especificado';
    }

    /**
     * Scope para veterinario principal (Licenciado)
     */
    public function scopePrincipal($query)
    {
        return $query->where('tipo_asignacion', self::TIPO_LICENCIADO);
    }

    /**
     * Scope para especialistas (Técnico)
     */
    public function scopeEspecialistas($query)
    {
        return $query->where('tipo_asignacion', self::TIPO_TECNICO);
    }

    /**
     * Scope para emergencias (Auxiliar)
     */
    public function scopeEmergencias($query)
    {
        return $query->where('tipo_asignacion', self::TIPO_AUXILIAR);
    }

    /**
     * Scope para auxiliares
     */
    public function scopeAuxiliares($query)
    {
        return $query->where('tipo_asignacion', self::TIPO_AUXILIAR);
    }

    /**
     * Scope para técnicos
     */
    public function scopeTecnicos($query)
    {
        return $query->where('tipo_asignacion', self::TIPO_TECNICO);
    }

    /**
     * Scope para licenciados
     */
    public function scopeLicenciados($query)
    {
        return $query->where('tipo_asignacion', self::TIPO_LICENCIADO);
    }
}