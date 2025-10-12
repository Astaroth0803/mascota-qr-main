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

class CitaCancelada implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $cita;
    public $veterinario;
    public $cliente;
    public $motivo;
    public $canceladoPor; // 'cliente' o 'veterinario'

    /**
     * Create a new event instance.
     */
    public function __construct(Cita $cita, User $veterinario, User $cliente, $motivo = null, $canceladoPor = 'cliente')
    {
        $this->cita = $cita;
        $this->veterinario = $veterinario;
        $this->cliente = $cliente;
        $this->motivo = $motivo;
        $this->canceladoPor = $canceladoPor;
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
        $mensaje = $this->canceladoPor === 'cliente' 
            ? "El cliente ha cancelado la cita" . ($this->motivo ? ': ' . $this->motivo : '')
            : "El veterinario ha cancelado la cita" . ($this->motivo ? ': ' . $this->motivo : '');

        return [
            'type' => 'cita_cancelada',
            'cita_id' => $this->cita->id,
            'mensaje' => $mensaje,
            'veterinario' => $this->veterinario->name,
            'cliente' => $this->cliente->name,
            'mascota' => $this->cita->mascota->nombre,
            'fecha' => $this->cita->fecha_asignada ? $this->cita->fecha_asignada->format('d/m/Y H:i') : $this->cita->fecha_solicitada->format('d/m/Y H:i'),
            'motivo' => $this->motivo,
            'cancelado_por' => $this->canceladoPor,
            'url' => route('citas.show', $this->cita),
            'timestamp' => now()->toISOString()
        ];
    }
}
