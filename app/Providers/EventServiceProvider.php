<?php

namespace App\Providers;

use App\Events\CitaAceptada;
use App\Events\CitaCancelada;
use App\Events\CitaFinalizada;
use App\Events\CitaModificada;
use App\Events\CitaRechazada;
use App\Events\VetRequestAccepted;
use App\Events\VetRequestRejected;
use App\Events\AppointmentAccepted;
use App\Events\AppointmentRejected;
use App\Listeners\EnviarNotificacionCitaAceptada;
use App\Listeners\EnviarNotificacionCitaCancelada;
use App\Listeners\EnviarNotificacionCitaFinalizada;
use App\Listeners\EnviarNotificacionCitaModificada;
use App\Listeners\EnviarNotificacionCitaRechazada;
use App\Listeners\SendVetRequestAcceptedNotification;
use App\Listeners\SendVetRequestRejectedNotification;
use App\Listeners\SendAppointmentAcceptedNotification;
use App\Listeners\SendAppointmentRejectedNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        CitaAceptada::class => [
            EnviarNotificacionCitaAceptada::class,
        ],
        CitaRechazada::class => [
            EnviarNotificacionCitaRechazada::class,
        ],
        CitaCancelada::class => [
            EnviarNotificacionCitaCancelada::class,
        ],
        CitaModificada::class => [
            EnviarNotificacionCitaModificada::class,
        ],
        CitaFinalizada::class => [
            EnviarNotificacionCitaFinalizada::class,
        ],
        VetRequestAccepted::class => [
            SendVetRequestAcceptedNotification::class,
        ],
        VetRequestRejected::class => [
            SendVetRequestRejectedNotification::class,
        ],
        AppointmentAccepted::class => [
            SendAppointmentAcceptedNotification::class,
        ],
        AppointmentRejected::class => [
            SendAppointmentRejectedNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
