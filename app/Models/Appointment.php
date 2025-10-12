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
        'cancellation_reason'
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
    const STATUS_SCHEDULED = 'agendada';
    const STATUS_IN_PROGRESS = 'en_progreso';
    const STATUS_COMPLETED = 'finalizada';
    const STATUS_CANCELLED = 'cancelada';

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

    /**
     * Validaciones de flujo de estados
     */
    public function canBeScheduled()
    {
        return $this->isPending();
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
        // Solo se puede cancelar 1 día antes de la cita agendada
        if (!$this->scheduled_datetime) {
            return true; // Si no está agendada, se puede cancelar
        }
        
        $dayBeforeAppointment = $this->scheduled_datetime->subDay();
        return Carbon::now()->lte($dayBeforeAppointment) && 
               in_array($this->status, [self::STATUS_PENDING, self::STATUS_SCHEDULED]);
    }

    /**
     * Métodos de utilidad
     */
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            self::STATUS_PENDING => 'Pendiente',
            self::STATUS_SCHEDULED => 'Agendada',
            self::STATUS_IN_PROGRESS => 'En Progreso',
            self::STATUS_COMPLETED => 'Finalizada',
            self::STATUS_CANCELLED => 'Cancelada',
            default => 'Desconocido'
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            self::STATUS_PENDING => 'yellow',
            self::STATUS_SCHEDULED => 'blue',
            self::STATUS_IN_PROGRESS => 'purple',
            self::STATUS_COMPLETED => 'green',
            self::STATUS_CANCELLED => 'red',
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
        
        return $available;
    }

    /**
     * Verificar si requiere campos de vacuna
     */
    public function requiresVaccineFields()
    {
        return $this->record_type === self::TYPE_VACCINE && $this->isInProgress();
    }
}