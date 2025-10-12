<?php

namespace App\Policies;

use App\Models\User;
use App\Models\AppointmentNotification;

class AppointmentNotificationPolicy
{
    /**
     * Determina si el usuario puede ver la notificación.
     */
    public function view(User $user, AppointmentNotification $notification)
    {
        return $user->id === $notification->user_id;
    }

    /**
     * Determina si el usuario puede marcar la notificación como leída.
     */
    public function markAsRead(User $user, AppointmentNotification $notification)
    {
        return $user->id === $notification->user_id;
    }

    /**
     * Determina si el usuario puede acceder a sus propias notificaciones.
     */
    public function access(User $user)
    {
        return true; // Todos los usuarios autenticados pueden acceder a sus notificaciones
    }
}
