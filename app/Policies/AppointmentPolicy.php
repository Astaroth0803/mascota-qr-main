<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Appointment;

class AppointmentPolicy
{
    /**
     * Determina si el veterinario puede acceder a la cita.
     */
    public function access(User $user, Appointment $appointment)
    {
        return $user->id === $appointment->veterinarian_id;
    }

    /**
     * Determina si el veterinario puede aceptar la cita.
     */
    public function accept(User $user, Appointment $appointment)
    {
        return $user->id === $appointment->veterinarian_id && $appointment->canBeScheduled();
    }

    /**
     * Determina si el veterinario puede rechazar la cita.
     */
    public function reject(User $user, Appointment $appointment)
    {
        return $user->id === $appointment->veterinarian_id && $appointment->canBeCancelled();
    }
}