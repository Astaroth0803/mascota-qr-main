<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_request_id',
        'user_id',
        'sender_id',
        'type',
        'title',
        'message',
        'data',
        'is_read',
        'read_at'
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime'
    ];

    // Tipos de notificación
    const TYPE_REQUEST_CREATED = 'request_created';
    const TYPE_REQUEST_ACCEPTED = 'request_accepted';
    const TYPE_REQUEST_REJECTED = 'request_rejected';
    const TYPE_APPOINTMENT_RESCHEDULED = 'appointment_rescheduled';
    const TYPE_APPOINTMENT_CANCELLED = 'appointment_cancelled';

    /**
     * Relaciones
     */
    public function appointmentRequest()
    {
        return $this->belongsTo(AppointmentRequest::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Scopes
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Marcar como leída
     */
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now()
        ]);
    }

    /**
     * Crear notificación
     */
    public static function createNotification($appointmentRequestId, $userId, $senderId, $type, $title, $message, $data = null)
    {
        return self::create([
            'appointment_request_id' => $appointmentRequestId,
            'user_id' => $userId,
            'sender_id' => $senderId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data
        ]);
    }
}
