<?php

namespace App\Events;

use App\Models\Appointment;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AppointmentRejected
{
    use Dispatchable, SerializesModels;

    public $appointment;
    public $reason;

    public function __construct(Appointment $appointment, string $reason)
    {
        $this->appointment = $appointment;
        $this->reason = $reason;
    }
}
