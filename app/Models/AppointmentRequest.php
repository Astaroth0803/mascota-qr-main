<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AppointmentRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'pet_id',
        'client_id',
        'veterinarian_id',
        'appointment_id',
        'status',
        'requested_datetime',
        'scheduled_datetime',
        'appointment_type',
        'description',
        'notes',
        'rejection_reason',
        'cancellation_reason',
        'reschedule_reason',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'requested_datetime' => 'datetime',
        'scheduled_datetime' => 'datetime',
    ];

    // Estados de la solicitud
    const STATUS_PENDING = 'pendiente';
    const STATUS_ACCEPTED = 'aceptado';
    const STATUS_REJECTED = 'rechazado';
    const STATUS_APPOINTMENT_COMPLETED = 'cita_terminada';
    const STATUS_APPOINTMENT_CANCELLED = 'cita_cancelada';
    const STATUS_RESCHEDULED = 'cita_reagendada';

    // Tipos de cita
    const TYPE_CONSULTATION = 'consulta';
    const TYPE_VACCINATION = 'vacunacion';
    const TYPE_SURGERY = 'cirugia';
    const TYPE_EMERGENCY = 'emergencia';
    const TYPE_CHECKUP = 'chequeo';

    /**
     * Relaciones
     */
    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function veterinarian()
    {
        return $this->belongsTo(User::class, 'veterinarian_id');
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function notifications()
    {
        return $this->hasMany(AppointmentNotification::class);
    }

    /**
     * Scopes
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByVeterinarian($query, $veterinarianId)
    {
        return $query->where('veterinarian_id', $veterinarianId);
    }

    public function scopeByClient($query, $clientId)
    {
        return $query->where('client_id', $clientId);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeAccepted($query)
    {
        return $query->where('status', self::STATUS_ACCEPTED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_APPOINTMENT_COMPLETED);
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', self::STATUS_APPOINTMENT_CANCELLED);
    }

    public function scopeRescheduled($query)
    {
        return $query->where('status', self::STATUS_RESCHEDULED);
    }

    /**
     * Métodos de estado
     */
    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isAccepted()
    {
        return $this->status === self::STATUS_ACCEPTED;
    }

    public function isRejected()
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function isCompleted()
    {
        return $this->status === self::STATUS_APPOINTMENT_COMPLETED;
    }

    public function isCancelled()
    {
        return $this->status === self::STATUS_APPOINTMENT_CANCELLED;
    }

    public function isRescheduled()
    {
        return $this->status === self::STATUS_RESCHEDULED;
    }

    /**
     * Validaciones de flujo de estados
     */
    public function canBeAccepted()
    {
        return $this->isPending();
    }

    public function canBeRejected()
    {
        return $this->isPending();
    }

    public function canBeCompleted()
    {
        return $this->isAccepted() && $this->appointment && $this->appointment->isCompleted();
    }

    public function canBeCancelled()
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_ACCEPTED]);
    }

    public function canBeRescheduled()
    {
        return $this->isAccepted() && $this->appointment;
    }

    /**
     * Métodos de transición de estado
     */
    public function accept($scheduledDatetime = null, $notes = null)
    {
        if (!$this->canBeAccepted()) {
            throw new \Exception('No se puede aceptar esta solicitud en su estado actual');
        }

        $this->update([
            'status' => self::STATUS_ACCEPTED,
            'scheduled_datetime' => $scheduledDatetime,
            'notes' => $notes
        ]);

        // Crear la cita automáticamente con status agendada
        $appointment = Appointment::create([
            'pet_id' => $this->pet_id,
            'veterinarian_id' => $this->veterinarian_id,
            'client_id' => $this->client_id,
            'status' => Appointment::STATUS_SCHEDULED, // Cambiar a agendada
            'record_type' => $this->appointment_type,
            'requested_datetime' => $this->requested_datetime,
            'scheduled_datetime' => $scheduledDatetime ?? $this->requested_datetime,
            'observations' => $this->description,
            'location' => $this->veterinarian->ubicacion ?? 'Ubicación por definir'
        ]);

        $this->update(['appointment_id' => $appointment->id]);

        return $appointment;
    }

    /**
     * Obtener etiqueta del tipo de cita
     */
    public function getAppointmentTypeLabelAttribute()
    {
        $labels = [
            self::TYPE_CONSULTATION => 'Consulta General',
            self::TYPE_VACCINATION => 'Vacunación',
            self::TYPE_SURGERY => 'Cirugía',
            self::TYPE_EMERGENCY => 'Emergencia',
            self::TYPE_CHECKUP => 'Chequeo Rutinario',
        ];
        
        return $labels[$this->appointment_type] ?? ucfirst($this->appointment_type);
    }

    /**
     * Agregar la cita al historial médico de la mascota
     */
    private function addToMedicalHistory($appointment)
    {
        // Crear registro en el historial médico
        \App\Models\VaccinationRecord::create([
            'pet_id' => $appointment->pet_id,
            'veterinarian_id' => $appointment->veterinarian_id,
            'record_type' => $appointment->record_type,
            'vaccine_name' => $this->getAppointmentTypeLabelAttribute(),
            'date' => $appointment->scheduled_datetime ?? now(),
            'vet_name' => $appointment->veterinarian->name ?? 'Veterinario',
            'observations' => $appointment->observations ?? $this->description,
            'diagnosis' => $appointment->diagnosis_treatment,
            'treatment' => $appointment->observations ?? 'Tratamiento aplicado',
            'location' => $appointment->location ?? 'Clínica Veterinaria'
        ]);
    }

    public function reject($reason = null)
    {
        if (!$this->canBeRejected()) {
            throw new \Exception('No se puede rechazar esta solicitud en su estado actual');
        }

        $this->update([
            'status' => self::STATUS_REJECTED,
            'rejection_reason' => $reason
        ]);
    }

    public function complete()
    {
        if (!$this->canBeCompleted()) {
            throw new \Exception('No se puede completar esta solicitud en su estado actual');
        }

        $this->update(['status' => self::STATUS_APPOINTMENT_COMPLETED]);
    }

    public function cancel($reason = null)
    {
        if (!$this->canBeCancelled()) {
            throw new \Exception('No se puede cancelar esta solicitud en su estado actual');
        }

        $this->update([
            'status' => self::STATUS_APPOINTMENT_CANCELLED,
            'cancellation_reason' => $reason
        ]);

        // Cancelar la cita asociada si existe
        if ($this->appointment) {
            $this->appointment->update(['status' => Appointment::STATUS_CANCELLED]);
        }
    }

    public function reschedule($newDatetime, $reason = null)
    {
        if (!$this->canBeRescheduled()) {
            throw new \Exception('No se puede reagendar esta solicitud en su estado actual');
        }

        $this->update([
            'status' => self::STATUS_RESCHEDULED,
            'scheduled_datetime' => $newDatetime,
            'reschedule_reason' => $reason
        ]);

        // Actualizar la cita asociada
        if ($this->appointment) {
            $this->appointment->update(['scheduled_datetime' => $newDatetime]);
        }
    }

    /**
     * Métodos de utilidad
     */
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            self::STATUS_PENDING => 'Pendiente',
            self::STATUS_ACCEPTED => 'Aceptado',
            self::STATUS_REJECTED => 'Rechazado',
            self::STATUS_APPOINTMENT_COMPLETED => 'Cita Terminada',
            self::STATUS_APPOINTMENT_CANCELLED => 'Cita Cancelada',
            self::STATUS_RESCHEDULED => 'Cita Reagendada',
            default => 'Desconocido'
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            self::STATUS_PENDING => 'yellow',
            self::STATUS_ACCEPTED => 'green',
            self::STATUS_REJECTED => 'red',
            self::STATUS_APPOINTMENT_COMPLETED => 'blue',
            self::STATUS_APPOINTMENT_CANCELLED => 'red',
            self::STATUS_RESCHEDULED => 'purple',
            default => 'gray'
        };
    }

    /**
     * Obtener opciones de tipos de cita
     */
    public static function getAppointmentTypeOptions()
    {
        return [
            self::TYPE_CONSULTATION => 'Consulta General',
            self::TYPE_VACCINATION => 'Vacunación',
            self::TYPE_SURGERY => 'Cirugía',
            self::TYPE_EMERGENCY => 'Emergencia',
            self::TYPE_CHECKUP => 'Chequeo Rutinario',
        ];
    }

    /**
     * Obtener estados disponibles para transición
     */
    public function getAvailableStatuses()
    {
        $available = [];
        
        if ($this->canBeAccepted()) {
            $available[self::STATUS_ACCEPTED] = 'Aceptar';
        }
        
        if ($this->canBeRejected()) {
            $available[self::STATUS_REJECTED] = 'Rechazar';
        }
        
        if ($this->canBeCompleted()) {
            $available[self::STATUS_APPOINTMENT_COMPLETED] = 'Marcar como Terminada';
        }
        
        if ($this->canBeCancelled()) {
            $available[self::STATUS_APPOINTMENT_CANCELLED] = 'Cancelar';
        }
        
        if ($this->canBeRescheduled()) {
            $available[self::STATUS_RESCHEDULED] = 'Reagendar';
        }
        
        return $available;
    }

    /**
     * Verificar si la mascota está asignada temporalmente al veterinario
     */
    public function isPetAssignedToVeterinarian()
    {
        return $this->isAccepted() && !$this->isCompleted() && !$this->isCancelled();
    }

    /**
     * Obtener la asignación temporal de la mascota
     */
    public function getTemporaryAssignment()
    {
        if (!$this->isPetAssignedToVeterinarian()) {
            return null;
        }

        return MascotaVeterinario::where('mascota_id', $this->pet_id)
            ->where('veterinario_id', $this->veterinarian_id)
            ->where('activo', true)
            ->first();
    }
}
