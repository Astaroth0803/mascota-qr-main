<?php

namespace App\Listeners;

use App\Events\CitaAceptada;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class EnviarNotificacionCitaAceptada implements ShouldQueue
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
    public function handle(CitaAceptada $event): void
    {
        // Crear notificación para el cliente
        Notification::create([
            'user_id' => $event->cliente->id,
            'type' => 'cita_aceptada',
            'title' => 'Cita Aceptada',
            'message' => $event->broadcastWith()['mensaje'],
            'data' => [
                'cita_id' => $event->cita->id,
                'veterinario' => $event->veterinario->name,
                'mascota' => $event->cita->mascota->nombre,
                'fecha' => $event->cita->fecha_asignada->format('d/m/Y H:i'),
                'url' => route('citas.show', $event->cita)
            ],
            'read_at' => null
        ]);

        // Crear notificación para el veterinario
        Notification::create([
            'user_id' => $event->veterinario->id,
            'type' => 'cita_aceptada',
            'title' => 'Cita Aceptada',
            'message' => 'Has aceptado la cita de ' . $event->cita->mascota->nombre,
            'data' => [
                'cita_id' => $event->cita->id,
                'cliente' => $event->cliente->name,
                'mascota' => $event->cita->mascota->nombre,
                'fecha' => $event->cita->fecha_asignada->format('d/m/Y H:i'),
                'url' => route('citas.show', $event->cita)
            ],
            'read_at' => null
        ]);
    }
}
