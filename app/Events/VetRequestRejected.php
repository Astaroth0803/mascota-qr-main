<?php

namespace App\Events;

use App\Models\MascotaVeterinario;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VetRequestRejected
{
    use Dispatchable, SerializesModels;

    public $solicitud;
    public $motivo;

    public function __construct(MascotaVeterinario $solicitud, ?string $motivo = null)
    {
        $this->solicitud = $solicitud;
        $this->motivo = $motivo;
    }
}
