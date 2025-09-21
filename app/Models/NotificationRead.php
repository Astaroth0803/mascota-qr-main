<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationRead extends Model
{
    protected $fillable = [
        'user_id',
        'notification_type',
        'notification_key',
        'read_at'
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    /**
     * Relación con el usuario
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Marcar una notificación como leída
     */
    public static function markAsRead($userId, $notificationType, $notificationKey)
    {
        return self::updateOrCreate(
            [
                'user_id' => $userId,
                'notification_type' => $notificationType,
                'notification_key' => $notificationKey,
            ],
            [
                'read_at' => now()
            ]
        );
    }

    /**
     * Verificar si una notificación está leída
     */
    public static function isRead($userId, $notificationType, $notificationKey)
    {
        return self::where('user_id', $userId)
            ->where('notification_type', $notificationType)
            ->where('notification_key', $notificationKey)
            ->exists();
    }

    /**
     * Marcar todas las notificaciones de un usuario como leídas
     */
    public static function markAllAsRead($userId)
    {
        // Marcar todas las notificaciones existentes como leídas usando un enfoque más directo
        $now = now();
        
        // Vacunas próximas
        $upcomingVaccines = \App\Models\VaccinationRecord::whereHas('pet', function($query) use ($userId) {
            $query->where('user_id', $userId);
        })
        ->where('next_date', '>=', now())
        ->where('next_date', '<=', now()->addDays(7))
        ->get();
        
        foreach ($upcomingVaccines as $vaccine) {
            $key = md5('vaccine_upcoming_' . $vaccine->id . '_' . $vaccine->next_date->format('Y-m-d'));
            self::markAsRead($userId, 'info', $key);
        }
        
        // Vacunas vencidas
        $overdueVaccines = \App\Models\VaccinationRecord::whereHas('pet', function($query) use ($userId) {
            $query->where('user_id', $userId);
        })
        ->where('next_date', '<', now())
        ->where('next_date', '!=', null)
        ->get();
        
        foreach ($overdueVaccines as $vaccine) {
            $key = md5('vaccine_overdue_' . $vaccine->id . '_' . $vaccine->next_date->format('Y-m-d'));
            self::markAsRead($userId, 'warning', $key);
        }
        
        // Mascotas sin QR
        $petsWithoutQR = \App\Models\Pet::where('user_id', $userId)->whereNull('qr_code')->get();
        if ($petsWithoutQR->count() > 0) {
            $key = md5('qr_pending_' . $userId . '_' . $petsWithoutQR->count());
            self::markAsRead($userId, 'info', $key);
        }
        
        // Solicitudes de mascotas pendientes
        $pendingPetRequests = \App\Models\PetRequest::where('user_id', $userId)
            ->where('status', 'pending')
            ->get();
            
        foreach ($pendingPetRequests as $request) {
            $key = md5('pet_request_pending_' . $request->id);
            self::markAsRead($userId, 'info', $key);
        }
        
        // Solicitudes de mascotas aprobadas
        $approvedPetRequests = \App\Models\PetRequest::where('user_id', $userId)
            ->where('status', 'approved')
            ->where('reviewed_at', '>=', now()->subDays(7))
            ->get();
            
        foreach ($approvedPetRequests as $request) {
            $key = md5('pet_request_approved_' . $request->id);
            self::markAsRead($userId, 'success', $key);
        }
        
        // Solicitudes de mascotas rechazadas
        $rejectedPetRequests = \App\Models\PetRequest::where('user_id', $userId)
            ->where('status', 'rejected')
            ->where('reviewed_at', '>=', now()->subDays(7))
            ->get();
            
        foreach ($rejectedPetRequests as $request) {
            $key = md5('pet_request_rejected_' . $request->id);
            self::markAsRead($userId, 'error', $key);
        }
    }
}
