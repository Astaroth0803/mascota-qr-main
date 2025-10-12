<?php

namespace App\Listeners;

use App\Events\VetRequestRejected;
use App\Models\VetRequestNotification;

class SendVetRequestRejectedNotification
{
    /**
     * Handle the event.
     */
    public function handle(VetRequestRejected $event)
    {
        $solicitud = $event->solicitud;

        VetRequestNotification::create([
            'veterinario_id' => $solicitud->veterinario_id,
            'cliente_id' => $solicitud->mascota->user_id,
            'mascota_id' => $solicitud->mascota_id,
            'asignacion_id' => $solicitud->id,
            'tipo' => 'rechazada',
            'mensaje' => "El veterinario {$solicitud->veterinario->name} ha rechazado atender a {$solicitud->mascota->nombre} como " . ucfirst($solicitud->tipo_asignacion) . "." . ($event->motivo ? " Motivo: {$event->motivo}" : '')
        ]);
    }
}
