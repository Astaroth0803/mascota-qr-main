<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'pet_id',
        'veterinarian_id', 
        'client_id',
        'status',
        'record_type',
        'requested_datetime',
        'scheduled_datetime',
        'location',
        'diagnosis_treatment',
        'observations',
        'vaccine_type',
        'vaccine_name',
        'technical_name',
        'laboratory',
        'lot_number',
        'creation_date',
        'expiry_date',
        'cancellation_reason',
        'rejection_reason',
        'reschedule_reason',
        'notes'
    ];

    protected $casts = [
        'requested_datetime' => 'datetime',
        'scheduled_datetime' => 'datetime',
        'creation_date' => 'date',
        'expiry_date' => 'date',
        'cancelled_at' => 'datetime'
    ];

    // Estados disponibles
    const STATUS_PENDING = 'pendiente';
    const STATUS_ACCEPTED = 'aceptado';
    const STATUS_REJECTED = 'rechazado';
    const STATUS_SCHEDULED = 'agendada';
    const STATUS_IN_PROGRESS = 'en_progreso';
    const STATUS_COMPLETED = 'finalizada';
    const STATUS_CANCELLED = 'cancelada';
    const STATUS_RESCHEDULED = 'reagendada';

    // Tipos de registro
    const TYPE_VACCINE = 'vacuna';
    const TYPE_OPERATION = 'operacion';
    const TYPE_EMERGENCY = 'emergencia';
    const TYPE_CHECKUP = 'checkeo';

    /**
     * Relaciones
     */
    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }

    public function veterinarian()
    {
        return $this->belongsTo(User::class, 'veterinarian_id');
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
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

    public function scopeToday($query)
    {
        return $query->whereDate('scheduled_datetime', Carbon::today());
    }

    public function scopeUpcoming($query)
    {
        return $query->where('scheduled_datetime', '>', Carbon::now())
                    ->whereIn('status', [self::STATUS_SCHEDULED, self::STATUS_IN_PROGRESS]);
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

    public function isScheduled()
    {
        return $this->status === self::STATUS_SCHEDULED;
    }

    public function isInProgress()
    {
        return $this->status === self::STATUS_IN_PROGRESS;
    }

    public function isCompleted()
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isCancelled()
    {
        return $this->status === self::STATUS_CANCELLED;
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

    public function canBeScheduled()
    {
        return $this->isAccepted();
    }

    public function canBeInProgress()
    {
        return $this->isScheduled();
    }

    public function canBeCompleted()
    {
        return $this->isInProgress();
    }

    public function canBeCancelled()
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_ACCEPTED, self::STATUS_SCHEDULED]);
    }

    public function canBeRescheduled()
    {
        return in_array($this->status, [self::STATUS_ACCEPTED, self::STATUS_SCHEDULED]);
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
            self::STATUS_SCHEDULED => 'Agendada',
            self::STATUS_IN_PROGRESS => 'En Progreso',
            self::STATUS_COMPLETED => 'Finalizada',
            self::STATUS_CANCELLED => 'Cancelada',
            self::STATUS_RESCHEDULED => 'Reagendada',
            default => 'Desconocido'
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            self::STATUS_PENDING => 'yellow',
            self::STATUS_ACCEPTED => 'green',
            self::STATUS_REJECTED => 'red',
            self::STATUS_SCHEDULED => 'blue',
            self::STATUS_IN_PROGRESS => 'purple',
            self::STATUS_COMPLETED => 'green',
            self::STATUS_CANCELLED => 'red',
            self::STATUS_RESCHEDULED => 'purple',
            default => 'gray'
        };
    }

    public function getRecordTypeLabelAttribute()
    {
        return match($this->record_type) {
            self::TYPE_VACCINE => 'Vacunación',
            self::TYPE_OPERATION => 'Operación',
            self::TYPE_EMERGENCY => 'Emergencia',
            self::TYPE_CHECKUP => 'Chequeo',
            default => ucfirst($this->record_type)
        };
    }

    /**
     * Obtener opciones de tipos de cita
     */
    public static function getTypeOptions()
    {
        return [
            'consulta' => 'Consulta General',
            'vacuna' => 'Vacunación',
            'operacion' => 'Cirugía',
            'emergencia' => 'Emergencia',
            'checkeo' => 'Chequeo Rutinario',
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
        
        if ($this->canBeScheduled()) {
            $available[self::STATUS_SCHEDULED] = 'Agendar';
        }
        
        if ($this->canBeInProgress()) {
            $available[self::STATUS_IN_PROGRESS] = 'Iniciar Consulta';
        }
        
        if ($this->canBeCompleted()) {
            $available[self::STATUS_COMPLETED] = 'Finalizar';
        }
        
        if ($this->canBeCancelled()) {
            $available[self::STATUS_CANCELLED] = 'Cancelar';
        }
        
        if ($this->canBeRescheduled()) {
            $available[self::STATUS_RESCHEDULED] = 'Reagendar';
        }
        
        return $available;
    }

    /**
     * Métodos de transición de estado
     */
    public function accept($scheduledDatetime = null, $notes = null)
    {
        if (!$this->canBeAccepted()) {
            throw new \Exception('No se puede aceptar esta cita en su estado actual');
        }

        $this->update([
            'status' => self::STATUS_ACCEPTED,
            'scheduled_datetime' => $scheduledDatetime ?? $this->requested_datetime,
            'notes' => $notes
        ]);
    }

    public function reject($reason = null)
    {
        if (!$this->canBeRejected()) {
            throw new \Exception('No se puede rechazar esta cita en su estado actual');
        }

        $this->update([
            'status' => self::STATUS_REJECTED,
            'rejection_reason' => $reason
        ]);
    }

    public function schedule($scheduledDatetime = null)
    {
        if (!$this->canBeScheduled()) {
            throw new \Exception('No se puede agendar esta cita en su estado actual');
        }

        $this->update([
            'status' => self::STATUS_SCHEDULED,
            'scheduled_datetime' => $scheduledDatetime ?? $this->scheduled_datetime
        ]);
    }

    public function start()
    {
        if (!$this->canBeInProgress()) {
            throw new \Exception('No se puede iniciar esta cita en su estado actual');
        }

        $this->update(['status' => self::STATUS_IN_PROGRESS]);
    }

    public function complete()
    {
        if (!$this->canBeCompleted()) {
            throw new \Exception('No se puede completar esta cita en su estado actual');
        }

        $this->update(['status' => self::STATUS_COMPLETED]);
    }

    public function cancel($reason = null)
    {
        if (!$this->canBeCancelled()) {
            throw new \Exception('No se puede cancelar esta cita en su estado actual');
        }

        $this->update([
            'status' => self::STATUS_CANCELLED,
            'cancellation_reason' => $reason
        ]);
    }

    public function reschedule($newDatetime, $reason = null)
    {
        if (!$this->canBeRescheduled()) {
            throw new \Exception('No se puede reagendar esta cita en su estado actual');
        }

        $this->update([
            'status' => self::STATUS_RESCHEDULED,
            'scheduled_datetime' => $newDatetime,
            'reschedule_reason' => $reason
        ]);
    }

    /**
     * Verificar si requiere campos de vacuna
     */
    public function requiresVaccineFields()
    {
        return $this->record_type === self::TYPE_VACCINE && $this->isInProgress();
    }
}