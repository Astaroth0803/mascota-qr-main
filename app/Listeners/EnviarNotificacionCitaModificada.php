<?php

namespace App\Listeners;

use App\Events\CitaModificada;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class EnviarNotificacionCitaModificada implements ShouldQueue
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
    public function handle(CitaModificada $event): void
    {
        // Crear notificación para el cliente
        Notification::create([
            'user_id' => $event->cliente->id,
            'type' => 'cita_modificada',
            'title' => 'Cita Modificada',
            'message' => $event->broadcastWith()['mensaje'],
            'data' => [
                'cita_id' => $event->cita->id,
                'veterinario' => $event->veterinario->name,
                'mascota' => $event->cita->mascota->nombre,
                'fecha_anterior' => $event->fechaAnterior->format('d/m/Y H:i'),
                'fecha_nueva' => $event->fechaNueva->format('d/m/Y H:i'),
                'cambios' => $event->cambios,
                'url' => route('citas.show', $event->cita)
            ],
            'read_at' => null
        ]);

        // Crear notificación para el veterinario
        Notification::create([
            'user_id' => $event->veterinario->id,
            'type' => 'cita_modificada',
            'title' => 'Cita Modificada',
            'message' => 'Has modificado la cita de ' . $event->cita->mascota->nombre,
            'data' => [
                'cita_id' => $event->cita->id,
                'cliente' => $event->cliente->name,
                'mascota' => $event->cita->mascota->nombre,
                'fecha_anterior' => $event->fechaAnterior->format('d/m/Y H:i'),
                'fecha_nueva' => $event->fechaNueva->format('d/m/Y H:i'),
                'cambios' => $event->cambios,
                'url' => route('citas.show', $event->cita)
            ],
            'read_at' => null
        ]);
    }
}
