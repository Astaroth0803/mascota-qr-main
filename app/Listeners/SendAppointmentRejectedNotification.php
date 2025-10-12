<?php

namespace App\Listeners;

use App\Events\AppointmentRejected;
use App\Models\VetRequestNotification;

class SendAppointmentRejectedNotification
{
    /**
     * Handle the event.
     */
    public function handle(AppointmentRejected $event)
    {
        $appointment = $event->appointment;

        VetRequestNotification::create([
            'veterinario_id' => $appointment->veterinarian_id,
            'cliente_id' => $appointment->client_id,
            'mascota_id' => $appointment->pet_id,
            'asignacion_id' => null, // No es una asignación, es una cita
            'tipo' => 'cita_rechazada',
            'mensaje' => "El veterinario {$appointment->veterinarian->name} ha rechazado tu solicitud de cita para {$appointment->pet->nombre}. Motivo: {$event->reason}"
        ]);
    }
}
