<?php

namespace App\Policies;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AppointmentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['veterinario', 'cliente_qr']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Appointment $appointment): bool
    {
        // Veterinario puede ver sus citas
        if ($user->hasRole('veterinario') && $appointment->veterinarian_id === $user->id) {
            return true;
        }
        
        // Cliente puede ver sus citas
        if ($user->hasRole('cliente_qr') && $appointment->client_id === $user->id) {
            return true;
        }
        
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['veterinario', 'cliente_qr']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Appointment $appointment): bool
    {
        // Solo el veterinario asignado puede actualizar la cita
        if ($user->hasRole('veterinario') && $appointment->veterinarian_id === $user->id) {
            return true;
        }
        
        // Cliente puede cancelar su cita si está permitido
        if ($user->hasRole('cliente_qr') && 
            $appointment->client_id === $user->id && 
            $appointment->canBeCancelled()) {
            return true;
        }
        
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Appointment $appointment): bool
    {
        // Solo el veterinario asignado puede eliminar la cita
        return $user->hasRole('veterinario') && $appointment->veterinarian_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Appointment $appointment): bool
    {
        return $user->hasRole('veterinario') && $appointment->veterinarian_id === $user->id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Appointment $appointment): bool
    {
        return $user->hasRole('veterinario') && $appointment->veterinarian_id === $user->id;
    }
}