<?php

namespace App\Listeners;

use App\Events\CitaRechazada;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class EnviarNotificacionCitaRechazada implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(CitaRechazada $event): void
    {
        // Crear notificación para el cliente
        Notification::create([
            'user_id' => $event->cliente->id,
            'type' => 'cita_rechazada',
            'title' => 'Cita Rechazada',
            'message' => $event->broadcastWith()['mensaje'],
            'data' => [
                'cita_id' => $event->cita->id,
                'veterinario' => $event->veterinario->name,
                'mascota' => $event->cita->mascota->nombre,
                'fecha' => $event->cita->fecha_solicitada->format('d/m/Y H:i'),
                'motivo' => $event->motivo,
                'url' => route('citas.show', $event->cita)
            ],
            'read_at' => null
        ]);

        // Crear notificación para el veterinario
        Notification::create([
            'user_id' => $event->veterinario->id,
            'type' => 'cita_rechazada',
            'title' => 'Cita Rechazada',
            'message' => 'Has rechazado la cita de ' . $event->cita->mascota->nombre,
            'data' => [
                'cita_id' => $event->cita->id,
                'cliente' => $event->cliente->name,
                'mascota' => $event->cita->mascota->nombre,
                'fecha' => $event->cita->fecha_solicitada->format('d/m/Y H:i'),
                'motivo' => $event->motivo,
                'url' => route('citas.show', $event->cita)
            ],
            'read_at' => null
        ]);
    }
}
