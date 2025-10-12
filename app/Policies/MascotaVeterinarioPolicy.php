<?php

namespace App\Policies;

use App\Models\User;
use App\Models\MascotaVeterinario;

class MascotaVeterinarioPolicy
{
    /**
     * Determina si el veterinario puede acceder a la solicitud.
     */
    public function access(User $user, MascotaVeterinario $solicitud)
    {
        return $user->id === $solicitud->veterinario_id;
    }

    /**
     * Determina si el veterinario puede aceptar la solicitud.
     */
    public function accept(User $user, MascotaVeterinario $solicitud)
    {
        return $user->id === $solicitud->veterinario_id && !$solicitud->activo;
    }

    /**
     * Determina si el veterinario puede rechazar la solicitud.
     */
    public function reject(User $user, MascotaVeterinario $solicitud)
    {
        return $user->id === $solicitud->veterinario_id && !$solicitud->activo;
    }
}
