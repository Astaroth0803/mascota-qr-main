<?php

namespace App\Services;

use App\Models\Pet;
use App\Models\VaccinationRecord;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class DashboardService
{
    protected $cacheMinutes;

    public function __construct()
    {
        $this->cacheMinutes = config('cache.dashboard_ttl', 10);
    }

    public function getUserPets($userId, $userEmail)
    {
        return $this->fetchUserPets($userId, $userEmail);
    }

    public function getUserStatistics($userId)
    {
        return $this->calculateUserStatistics($userId);
    }

    protected function generateCacheKey($userId, $userEmail)
    {
        $lastUpdated = $this->getLastUpdateTimestamp($userId, $userEmail);
        return "user_pets:{$userId}:{$lastUpdated}";
    }

    protected function getLastUpdateTimestamp($userId, $userEmail)
    {
        $timestamp = Pet::where(function ($query) use ($userId, $userEmail) {
            $query->where('user_id', $userId)
                  ->orWhere('correo_owner', $userEmail);
        })->max('updated_at');

        return $timestamp ? Carbon::parse($timestamp)->timestamp : 'no_pets';
    }

    protected function fetchUserPets($userId, $userEmail)
    {
        return Pet::with(['payment', 'vaccinationRecords'])
            ->where(function ($query) use ($userId, $userEmail) {
                $query->where('user_id', $userId)
                      ->orWhere('correo_owner', $userEmail);
            })
            ->latest()
            ->get();
    }

    protected function calculateUserStatistics($userId)
    {
        return [
            'total_pets' => Pet::where('user_id', $userId)->count(),
            'pending_vaccinations' => $this->getPendingVaccinations($userId),
            'upcoming_appointments' => $this->getUpcomingAppointments($userId),
            'recent_activities' => $this->getRecentActivities($userId),
        ];
    }

    protected function getPendingVaccinations($userId)
    {
        // Como no existe la columna next_vaccination_date, usamos una alternativa
        // o devolvemos un valor por defecto hasta que se implemente correctamente
        return VaccinationRecord::whereHas('pet', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
        ->count();

        /*
        // Nota: Cuando la estructura de la base de datos esté lista, puedes descomentar
        // y adaptar esto según la estructura real de tu tabla vaccination_records
        return VaccinationRecord::whereHas('pet', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
        ->where('fecha_proxima', '<=', now()->addMonths(1))  // Ajusta el nombre de la columna
        ->count();
        */
    }

    protected function getUpcomingAppointments($userId)
    {
        // Implementar lógica para citas próximas
        return [];
    }

    protected function getRecentActivities($userId)
    {
        // Implementar lógica para actividades recientes
        return [];
    }
}
