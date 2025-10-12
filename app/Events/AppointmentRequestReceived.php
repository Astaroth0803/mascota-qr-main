<?php

namespace App\Events;

use App\Models\User;
use App\Models\Appointment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AppointmentRequestReceived implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $veterinarian;
    public $appointment;

    /**
     * Create a new event instance.
     */
    public function __construct(User $veterinarian, Appointment $appointment)
    {
        $this->veterinarian = $veterinarian;
        $this->appointment = $appointment;
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
            'appointment' => [
                'id' => $this->appointment->id,
                'pet_name' => $this->appointment->pet->nombre,
                'client_name' => $this->appointment->client->name,
                'appointment_type' => $this->appointment->appointment_type,
                'status' => $this->appointment->status,
                'created_at' => $this->appointment->created_at,
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
        return 'appointment.request.received';
    }
}
