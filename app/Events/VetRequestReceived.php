<?php

namespace App\Events;

use App\Models\User;
use App\Models\MascotaVeterinario;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VetRequestReceived implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $veterinarian;
    public $request;

    /**
     * Create a new event instance.
     */
    public function __construct(User $veterinarian, MascotaVeterinario $request)
    {
        $this->veterinarian = $veterinarian;
        $this->request = $request;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('veterinarian.' . $this->veterinarian->id),
        ];
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'request' => [
                'id' => $this->request->id,
                'mascota_nombre' => $this->request->mascota->nombre,
                'cliente_nombre' => $this->request->mascota->user->name,
                'tipo_asignacion' => $this->request->tipo_asignacion,
                'created_at' => $this->request->created_at,
            ],
            'veterinarian' => [
                'id' => $this->veterinarian->id,
                'name' => $this->veterinarian->name,
            ]
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'vet.request.received';
    }
}
