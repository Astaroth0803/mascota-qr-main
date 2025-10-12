<?php

namespace App\Listeners;

use App\Events\CitaFinalizada;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class EnviarNotificacionCitaFinalizada implements ShouldQueue
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
    public function handle(CitaFinalizada $event): void
    {
        // Crear notificación para el cliente
        Notification::create([
            'user_id' => $event->cliente->id,
            'type' => 'cita_finalizada',
            'title' => 'Cita Finalizada',
            'message' => $event->broadcastWith()['mensaje'],
            'data' => [
                'cita_id' => $event->cita->id,
                'veterinario' => $event->veterinario->name,
                'mascota' => $event->cita->mascota->nombre,
                'fecha' => $event->cita->fecha_asignada->format('d/m/Y H:i'),
                'diagnostico' => $event->diagnostico,
                'url' => route('citas.show', $event->cita)
            ],
            'read_at' => null
        ]);

        // Crear notificación para el veterinario
        Notification::create([
            'user_id' => $event->veterinario->id,
            'type' => 'cita_finalizada',
            'title' => 'Cita Finalizada',
            'message' => 'Has finalizado la cita de ' . $event->cita->mascota->nombre,
            'data' => [
                'cita_id' => $event->cita->id,
                'cliente' => $event->cliente->name,
                'mascota' => $event->cita->mascota->nombre,
                'fecha' => $event->cita->fecha_asignada->format('d/m/Y H:i'),
                'diagnostico' => $event->diagnostico,
                'url' => route('citas.show', $event->cita)
            ],
            'read_at' => null
        ]);
    }
}
