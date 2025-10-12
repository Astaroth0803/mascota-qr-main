<?php

namespace App\Events;

use App\Models\MascotaVeterinario;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VetRequestAccepted
{
    use Dispatchable, SerializesModels;

    public $solicitud;

    public function __construct(MascotaVeterinario $solicitud)
    {
        $this->solicitud = $solicitud;
    }
}
