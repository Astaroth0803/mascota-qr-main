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

class CitaModificada implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $cita;
    public $veterinario;
    public $cliente;
    public $fechaAnterior;
    public $fechaNueva;
    public $cambios;

    /**
     * Create a new event instance.
     */
    public function __construct(Cita $cita, User $veterinario, User $cliente, $fechaAnterior, $fechaNueva, $cambios = [])
    {
        $this->cita = $cita;
        $this->veterinario = $veterinario;
        $this->cliente = $cliente;
        $this->fechaAnterior = $fechaAnterior;
        $this->fechaNueva = $fechaNueva;
        $this->cambios = $cambios;
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
        return [
            'type' => 'cita_modificada',
            'cita_id' => $this->cita->id,
            'mensaje' => 'Tu cita ha sido modificada. Nueva fecha: ' . $this->fechaNueva->format('d/m/Y H:i'),
            'veterinario' => $this->veterinario->name,
            'mascota' => $this->cita->mascota->nombre,
            'fecha_anterior' => $this->fechaAnterior->format('d/m/Y H:i'),
            'fecha_nueva' => $this->fechaNueva->format('d/m/Y H:i'),
            'cambios' => $this->cambios,
            'url' => route('citas.show', $this->cita),
            'timestamp' => now()->toISOString()
        ];
    }
}
