<?php

namespace App\Services;

use App\Models\AppointmentNotification;
use App\Models\AppointmentRequest;
use App\Models\User;

class AppointmentNotificationService
{
    /**
     * Crear notificación cuando se crea una solicitud
     */
    public static function notifyRequestCreated(AppointmentRequest $appointmentRequest)
    {
        // Notificar al veterinario
        AppointmentNotification::createNotification(
            $appointmentRequest->id,
            $appointmentRequest->veterinarian_id,
            $appointmentRequest->client_id,
            AppointmentNotification::TYPE_REQUEST_CREATED,
            'Nueva solicitud de cita',
            "El cliente {$appointmentRequest->client->name} ha solicitado una cita para {$appointmentRequest->pet->nombre}",
            [
                'pet_name' => $appointmentRequest->pet->nombre,
                'appointment_type' => $appointmentRequest->appointment_type,
                'requested_date' => $appointmentRequest->requested_datetime->format('d/m/Y H:i')
            ]
        );
    }

    /**
     * Crear notificación cuando se acepta una solicitud
     */
    public static function notifyRequestAccepted(AppointmentRequest $appointmentRequest)
    {
        // Notificar al cliente
        AppointmentNotification::createNotification(
            $appointmentRequest->id,
            $appointmentRequest->client_id,
            $appointmentRequest->veterinarian_id,
            AppointmentNotification::TYPE_REQUEST_ACCEPTED,
            'Solicitud de cita aceptada',
            "El veterinario {$appointmentRequest->veterinarian->name} ha aceptado tu solicitud de cita para {$appointmentRequest->pet->nombre}",
            [
                'pet_name' => $appointmentRequest->pet->nombre,
                'appointment_type' => $appointmentRequest->appointment_type,
                'scheduled_date' => $appointmentRequest->scheduled_datetime ? $appointmentRequest->scheduled_datetime->format('d/m/Y H:i') : null
            ]
        );
    }

    /**
     * Crear notificación cuando se rechaza una solicitud
     */
    public static function notifyRequestRejected(AppointmentRequest $appointmentRequest, $reason = null)
    {
        // Notificar al cliente
        AppointmentNotification::createNotification(
            $appointmentRequest->id,
            $appointmentRequest->client_id,
            $appointmentRequest->veterinarian_id,
            AppointmentNotification::TYPE_REQUEST_REJECTED,
            'Solicitud de cita rechazada',
            "El veterinario {$appointmentRequest->veterinarian->name} ha rechazado tu solicitud de cita para {$appointmentRequest->pet->nombre}" . ($reason ? ". Razón: {$reason}" : ""),
            [
                'pet_name' => $appointmentRequest->pet->nombre,
                'appointment_type' => $appointmentRequest->appointment_type,
                'rejection_reason' => $reason
            ]
        );
    }

    /**
     * Crear notificación cuando se reagenda una cita
     */
    public static function notifyAppointmentRescheduled(AppointmentRequest $appointmentRequest, $oldDate, $newDate)
    {
        // Notificar al cliente
        AppointmentNotification::createNotification(
            $appointmentRequest->id,
            $appointmentRequest->client_id,
            $appointmentRequest->veterinarian_id,
            AppointmentNotification::TYPE_APPOINTMENT_RESCHEDULED,
            'Cita reagendada',
            "El veterinario {$appointmentRequest->veterinarian->name} ha reagendado tu cita para {$appointmentRequest->pet->nombre}",
            [
                'pet_name' => $appointmentRequest->pet->nombre,
                'appointment_type' => $appointmentRequest->appointment_type,
                'old_date' => $oldDate,
                'new_date' => $newDate
            ]
        );
    }

    /**
     * Crear notificación cuando se cancela una cita
     */
    public static function notifyAppointmentCancelled(AppointmentRequest $appointmentRequest, $reason = null)
    {
        // Notificar al cliente
        AppointmentNotification::createNotification(
            $appointmentRequest->id,
            $appointmentRequest->client_id,
            $appointmentRequest->veterinarian_id,
            AppointmentNotification::TYPE_APPOINTMENT_CANCELLED,
            'Cita cancelada',
            "El veterinario {$appointmentRequest->veterinarian->name} ha cancelado tu cita para {$appointmentRequest->pet->nombre}" . ($reason ? ". Razón: {$reason}" : ""),
            [
                'pet_name' => $appointmentRequest->pet->nombre,
                'appointment_type' => $appointmentRequest->appointment_type,
                'cancellation_reason' => $reason
            ]
        );
    }

    /**
     * Obtener notificaciones para un usuario
     */
    public static function getNotificationsForUser($userId, $limit = 10)
    {
        return AppointmentNotification::forUser($userId)
            ->with(['appointmentRequest.pet', 'sender'])
            ->orderBy('created_at', 'desc')
            ->paginate($limit);
    }

    /**
     * Obtener notificaciones no leídas para un usuario
     */
    public static function getUnreadNotificationsForUser($userId, $limit = null)
    {
        $query = AppointmentNotification::forUser($userId)
            ->unread()
            ->with(['appointmentRequest.pet', 'sender'])
            ->orderBy('created_at', 'desc');
            
        return $limit ? $query->limit($limit)->get() : $query->get();
    }

    /**
     * Obtener conteo de notificaciones no leídas para un usuario (optimizado)
     */
    public static function getUnreadCountForUser($userId)
    {
        return AppointmentNotification::forUser($userId)
            ->unread()
            ->count();
    }

    /**
     * Marcar todas las notificaciones como leídas para un usuario
     */
    public static function markAllAsReadForUser($userId)
    {
        $unreadNotifications = AppointmentNotification::forUser($userId)->unread();
        $count = $unreadNotifications->count();
        
        $unreadNotifications->update([
            'is_read' => true,
            'read_at' => now()
        ]);
        
        return $count;
    }
}
