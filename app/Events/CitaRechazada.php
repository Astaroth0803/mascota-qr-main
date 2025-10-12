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

class CitaRechazada implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $cita;
    public $veterinario;
    public $cliente;
    public $motivo;

    /**
     * Create a new event instance.
     */
    public function __construct(Cita $cita, User $veterinario, User $cliente, $motivo = null)
    {
        $this->cita = $cita;
        $this->veterinario = $veterinario;
        $this->cliente = $cliente;
        $this->motivo = $motivo;
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
            'type' => 'cita_rechazada',
            'cita_id' => $this->cita->id,
            'mensaje' => 'Tu cita ha sido rechazada' . ($this->motivo ? ': ' . $this->motivo : ''),
            'veterinario' => $this->veterinario->name,
            'mascota' => $this->cita->mascota->nombre,
            'fecha' => $this->cita->fecha_solicitada->format('d/m/Y H:i'),
            'motivo' => $this->motivo,
            'url' => route('citas.show', $this->cita),
            'timestamp' => now()->toISOString()
        ];
    }
}
