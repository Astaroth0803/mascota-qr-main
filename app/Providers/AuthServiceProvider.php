<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Pet;
use App\Models\Appointment;
use App\Models\MascotaVeterinario;
use App\Models\AppointmentNotification;
use App\Policies\PetPolicy;
use App\Policies\MedicalHistoryPolicy;
use App\Policies\AppointmentPolicy;
use App\Policies\MascotaVeterinarioPolicy;
use App\Policies\AppointmentNotificationPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Pet::class => MedicalHistoryPolicy::class,
        Appointment::class => AppointmentPolicy::class,
        MascotaVeterinario::class => MascotaVeterinarioPolicy::class,
        AppointmentNotification::class => AppointmentNotificationPolicy::class,
    ];
}