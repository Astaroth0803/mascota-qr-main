<?php

namespace App\Listeners;

use App\Events\VetRequestAccepted;
use App\Models\VetRequestNotification;

class SendVetRequestAcceptedNotification
{
    /**
     * Handle the event.
     */
    public function handle(VetRequestAccepted $event)
    {
        $solicitud = $event->solicitud;

        VetRequestNotification::create([
            'veterinario_id' => $solicitud->veterinario_id,
            'cliente_id' => $solicitud->mascota->user_id,
            'mascota_id' => $solicitud->mascota_id,
            'asignacion_id' => $solicitud->id,
            'tipo' => 'aceptada',
            'mensaje' => "El veterinario {$solicitud->veterinario->name} ha aceptado atender a {$solicitud->mascota->nombre} como " . ucfirst($solicitud->tipo_asignacion) . "."
        ]);
    }
}
