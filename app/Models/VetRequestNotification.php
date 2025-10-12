<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VetRequestNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'veterinario_id',
        'cliente_id',
        'mascota_id',
        'asignacion_id',
        'tipo',
        'mensaje',
        'leida',
        'leida_at'
    ];

    protected $casts = [
        'leida' => 'boolean',
        'leida_at' => 'datetime'
    ];

    /**
     * Relación con el veterinario
     */
    public function veterinario()
    {
        return $this->belongsTo(User::class, 'veterinario_id');
    }

    /**
     * Relación con el cliente
     */
    public function cliente()
    {
        return $this->belongsTo(User::class, 'cliente_id');
    }

    /**
     * Relación con la mascota
     */
    public function mascota()
    {
        return $this->belongsTo(Pet::class, 'mascota_id');
    }

    /**
     * Relación con la asignación
     */
    public function asignacion()
    {
        return $this->belongsTo(MascotaVeterinario::class, 'asignacion_id');
    }

    /**
     * Marcar como leída
     */
    public function marcarComoLeida()
    {
        $this->update([
            'leida' => true,
            'leida_at' => now()
        ]);
    }

    /**
     * Scope para notificaciones no leídas
     */
    public function scopeNoLeidas($query)
    {
        return $query->where('leida', false);
    }

    /**
     * Scope para notificaciones de un veterinario
     */
    public function scopeParaVeterinario($query, $veterinarioId)
    {
        return $query->where('veterinario_id', $veterinarioId);
    }

    /**
     * Scope para notificaciones de un cliente
     */
    public function scopeParaCliente($query, $clienteId)
    {
        return $query->where('cliente_id', $clienteId);
    }

    /**
     * Obtener el color del tipo de notificación
     */
    public function getColorAttribute()
    {
        return match($this->tipo) {
            'solicitud' => 'blue',
            'aceptada' => 'green',
            'rechazada' => 'red',
            'cancelada' => 'gray',
            default => 'gray'
        };
    }

    /**
     * Obtener el icono del tipo de notificación
     */
    public function getIconAttribute()
    {
        return match($this->tipo) {
            'solicitud' => 'M12 4v16m8-8H4',
            'aceptada' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
            'rechazada' => 'M6 18L18 6M6 6l12 12',
            'cancelada' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2',
            default => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
        };
    }
}