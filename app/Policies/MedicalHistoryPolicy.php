<?php

namespace App\Policies;

use App\Models\Pet;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MedicalHistoryPolicy
{
    use HandlesAuthorization;

    /**
     * Determinar si el usuario puede ver el historial médico
     */
    public function view(User $user, Pet $pet)
    {
        // Administradores pueden ver todo
        if ($user->isAdmin()) {
            return true;
        }

        // Clientes pueden ver si es su mascota
        if ($user->isCliente() && $pet->user_id === $user->id) {
            return true;
        }

        // Veterinarios pueden ver si están asignados a la mascota
        if ($user->isVeterinario() && $pet->veterinariosActivos()->where('veterinario_id', $user->id)->exists()) {
            return true;
        }

        return false;
    }

    /**
     * Determinar si el usuario puede gestionar el historial médico
     */
    public function manage(User $user, Pet $pet)
    {
        // Solo administradores y veterinarios asignados pueden gestionar
        if ($user->isAdmin()) {
            return true;
        }

        // Veterinarios solo si están asignados activamente a la mascota
        if ($user->isVeterinario() && $pet->veterinariosActivos()->where('veterinario_id', $user->id)->exists()) {
            return true;
        }

        return false;
    }

    /**
     * Determinar si el usuario puede asignar veterinarios
     */
    public function assign(User $user)
    {
        // Solo administradores pueden asignar veterinarios
        return $user->isAdmin();
    }

    /**
     * Determinar si el usuario puede ver mascotas asignadas
     */
    public function viewAssignedPets(User $user)
    {
        // Solo veterinarios y administradores pueden ver mascotas asignadas
        return $user->isVeterinario() || $user->isAdmin();
    }

    /**
     * Determinar si el usuario puede crear registros médicos
     */
    public function create(User $user, Pet $pet)
    {
        return $this->manage($user, $pet);
    }

    /**
     * Determinar si el usuario puede actualizar registros médicos
     */
    public function update(User $user, Pet $pet)
    {
        return $this->manage($user, $pet);
    }

    /**
     * Determinar si el usuario puede eliminar registros médicos
     */
    public function delete(User $user, Pet $pet)
    {
        // Solo administradores pueden eliminar registros médicos
        return $user->isAdmin();
    }
}