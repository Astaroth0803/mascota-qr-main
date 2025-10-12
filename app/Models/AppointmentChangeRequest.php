<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentChangeRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'client_id',
        'veterinarian_id',
        'requested_date',
        'requested_time',
        'reason',
        'status',
        'vet_notes',
        'vet_response_at',
    ];

    protected $casts = [
        'requested_date' => 'date',
        'requested_time' => 'datetime:H:i:s',
        'vet_response_at' => 'datetime',
    ];

    /**
     * Relación con la cita
     */
    public function appointment()
    {
        return $this->belongsTo(VaccinationRecord::class, 'appointment_id');
    }

    /**
     * Relación con el cliente
     */
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * Relación con el veterinario
     */
    public function veterinarian()
    {
        return $this->belongsTo(User::class, 'veterinarian_id');
    }

    /**
     * Scope para solicitudes pendientes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope para solicitudes aprobadas
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope para solicitudes rechazadas
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope para solicitudes de un veterinario
     */
    public function scopeForVeterinarian($query, $veterinarianId)
    {
        return $query->where('veterinarian_id', $veterinarianId);
    }

    /**
     * Scope para solicitudes de un cliente
     */
    public function scopeForClient($query, $clientId)
    {
        return $query->where('client_id', $clientId);
    }

    /**
     * Marcar como aprobada
     */
    public function approve($notes = null)
    {
        $this->update([
            'status' => 'approved',
            'vet_notes' => $notes,
            'vet_response_at' => now(),
        ]);
    }

    /**
     * Marcar como rechazada
     */
    public function reject($notes = null)
    {
        $this->update([
            'status' => 'rejected',
            'vet_notes' => $notes,
            'vet_response_at' => now(),
        ]);
    }

    /**
     * Obtener el color del estado
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'yellow',
            'approved' => 'green',
            'rejected' => 'red',
            default => 'gray'
        };
    }

    /**
     * Obtener el icono del estado
     */
    public function getStatusIconAttribute()
    {
        return match($this->status) {
            'pending' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
            'approved' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
            'rejected' => 'M6 18L18 6M6 6l12 12',
            default => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
        };
    }
}