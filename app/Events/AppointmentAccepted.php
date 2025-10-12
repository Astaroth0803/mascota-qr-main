<?php

namespace App\Events;

use App\Models\Appointment;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AppointmentAccepted
{
    use Dispatchable, SerializesModels;

    public $appointment;
    public $scheduledDatetime;
    public $location;

    public function __construct(Appointment $appointment, string $scheduledDatetime, ?string $location = null)
    {
        $this->appointment = $appointment;
        $this->scheduledDatetime = $scheduledDatetime;
        $this->location = $location;
    }
}
