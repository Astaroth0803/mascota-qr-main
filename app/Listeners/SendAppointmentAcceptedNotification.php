<?php

namespace App\Listeners;

use App\Events\AppointmentAccepted;
use App\Models\VetRequestNotification;

class SendAppointmentAcceptedNotification
{
    /**
     * Handle the event.
     */
    public function handle(AppointmentAccepted $event)
    {
        $appointment = $event->appointment;

        VetRequestNotification::create([
            'veterinario_id' => $appointment->veterinarian_id,
            'cliente_id' => $appointment->client_id,
            'mascota_id' => $appointment->pet_id,
            'asignacion_id' => null, // No es una asignación, es una cita
            'tipo' => 'cita_aceptada',
            'mensaje' => "El veterinario {$appointment->veterinarian->name} ha aceptado tu solicitud de cita para {$appointment->pet->nombre}. Fecha: " . $event->scheduledDatetime
        ]);
    }
}
