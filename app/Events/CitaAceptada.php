<?php

namespace App\Events;

use App\Models\Cita;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CitaAceptada implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $cita;
    public $veterinario;
    public $cliente;
    public $fechaOriginal;
    public $fechaNueva;

    /**
     * Create a new event instance.
     */
    public function __construct(Cita $cita, User $veterinario, User $cliente, $fechaOriginal = null, $fechaNueva = null)
    {
        $this->cita = $cita;
        $this->veterinario = $veterinario;
        $this->cliente = $cliente;
        $this->fechaOriginal = $fechaOriginal;
        $this->fechaNueva = $fechaNueva;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->cliente->id),
            new PrivateChannel('user.' . $this->veterinario->id),
        ];
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        $mensaje = $this->fechaNueva && $this->fechaOriginal 
            ? "Tu cita ha sido aceptada pero con cambio de horario. Nueva fecha: " . $this->fechaNueva->format('d/m/Y H:i')
            : "Tu cita ha sido aceptada para el " . $this->cita->fecha_asignada->format('d/m/Y H:i');

        return [
            'type' => 'cita_aceptada',
            'cita_id' => $this->cita->id,
            'mensaje' => $mensaje,
            'veterinario' => $this->veterinario->name,
            'mascota' => $this->cita->mascota->nombre,
            'fecha' => $this->cita->fecha_asignada->format('d/m/Y H:i'),
            'url' => route('citas.show', $this->cita),
            'timestamp' => now()->toISOString()
        ];
    }
}
