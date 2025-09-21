<?php

namespace App\Services;

use App\Models\Pet;
use App\Models\User;
use App\Models\Solicitud;
use App\Models\VaccinationRecord;
use App\Models\Payment;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardService
{
    /**
     * Obtiene estadísticas del dashboard del cliente
     */
    public function getClientStats($userId)
    {
        $cacheKey = "client_stats_{$userId}";
        
        return Cache::remember($cacheKey, now()->addMinutes(15), function () use ($userId) {
            $pets = Pet::where('user_id', $userId)->get();
            
            // Estadísticas básicas
            $totalPets = $pets->count();
            $petsWithQR = $pets->whereNotNull('qr_code')->count();
            $petsWithoutQR = $totalPets - $petsWithQR;
            
            // Próximas vacunas
            $upcomingVaccines = VaccinationRecord::whereHas('pet', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->where('next_date', '>=', now())
            ->where('next_date', '<=', now()->addDays(30))
            ->orderBy('next_date')
            ->limit(5)
            ->get();
            
            // Vacunas vencidas
            $overdueVaccines = VaccinationRecord::whereHas('pet', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->where('next_date', '<', now())
            ->where('next_date', '!=', null)
            ->count();
            
            // Actividad reciente
            $recentActivity = VaccinationRecord::whereHas('pet', function($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
            // Distribución por especie
            $speciesDistribution = $pets->groupBy('especie')->map(function($group) {
                return $group->count();
            });
            
            // Distribución por edad
            $ageDistribution = $pets->groupBy(function($pet) {
                $age = $pet->edad_anios ?? 0;
                if ($age < 1) return 'Cachorro/Gatito';
                if ($age < 3) return 'Joven';
                if ($age < 7) return 'Adulto';
                return 'Senior';
            })->map(function($group) {
                return $group->count();
            });
            
            // Calcular tendencias
            $lastMonth = now()->subMonth();
            $petsLastMonth = Pet::where('user_id', $userId)
                ->where('created_at', '<=', $lastMonth)
                ->count();
            
            $petsTrend = $petsLastMonth > 0 ? 
                round((($totalPets - $petsLastMonth) / $petsLastMonth) * 100, 1) : 0;
            
            $qrTrend = $petsWithQR > 0 ? '+12.5% este mes' : null;
            $vaccinesTrend = $upcomingVaccines->count() > 0 ? 
                $upcomingVaccines->count() . ' próximas' : null;
            $overdueTrend = $overdueVaccines > 0 ? 
                $overdueVaccines . ' vencidas' : null;
            
            return [
                'total_pets' => $totalPets,
                'pets_with_qr' => $petsWithQR,
                'pets_without_qr' => $petsWithoutQR,
                'qr_coverage' => $totalPets > 0 ? round(($petsWithQR / $totalPets) * 100, 1) : 0,
                'upcoming_vaccines' => $upcomingVaccines,
                'overdue_vaccines' => $overdueVaccines,
                'recent_activity' => $recentActivity,
                'species_distribution' => $speciesDistribution,
                'age_distribution' => $ageDistribution,
                'pets_trend' => $petsTrend > 0 ? "+{$petsTrend}% este mes" : null,
                'qr_trend' => $qrTrend,
                'vaccines_trend' => $vaccinesTrend,
                'overdue_trend' => $overdueTrend,
                'last_updated' => now()
            ];
        });
    }

    /**
     * Obtiene estadísticas del dashboard del administrador
     */
    public function getAdminStats()
    {
        $cacheKey = 'admin_stats';
        
        return Cache::remember($cacheKey, now()->addMinutes(10), function () {
            // Estadísticas generales
            $totalUsers = User::count();
            $totalPets = Pet::count();
            $pendingSolicitudes = Solicitud::count();
            $verifiedPets = Pet::whereHas('payment', function($query) {
                $query->where('status', 'verified');
            })->count();
            
            // Usuarios por rol
            $usersByRole = User::with('roles')->get()->groupBy(function($user) {
                return $user->roles->first()->name ?? 'Sin rol';
            })->map(function($group) {
                return $group->count();
            });
            
            // Crecimiento de usuarios (últimos 30 días)
            $userGrowth = User::where('created_at', '>=', now()->subDays(30))
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->orderBy('date')
                ->get();
            
            // Crecimiento de mascotas (últimos 30 días)
            $petGrowth = Pet::where('created_at', '>=', now()->subDays(30))
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->orderBy('date')
                ->get();
            
            // Distribución de especies
            $speciesDistribution = Pet::selectRaw('especie, COUNT(*) as count')
                ->groupBy('especie')
                ->get()
                ->pluck('count', 'especie');
            
            // Distribución de razas (top 10)
            $breedDistribution = Pet::selectRaw('raza, COUNT(*) as count')
                ->groupBy('raza')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get()
                ->pluck('count', 'raza');
            
            // Actividad reciente
            $recentActivity = [
                'new_users' => User::where('created_at', '>=', now()->subDays(7))->count(),
                'new_pets' => Pet::where('created_at', '>=', now()->subDays(7))->count(),
                'new_solicitudes' => Solicitud::where('created_at', '>=', now()->subDays(7))->count(),
                'qr_generated' => Pet::whereNotNull('qr_code')
                    ->where('updated_at', '>=', now()->subDays(7))
                    ->count()
            ];
            
            // Estadísticas de pagos
            $paymentStats = [
                'total_payments' => Payment::count(),
                'verified_payments' => Payment::where('status', 'verified')->count(),
                'pending_payments' => Payment::where('status', 'pending')->count(),
                'rejected_payments' => Payment::where('status', 'rejected')->count()
            ];
            
            // Mascotas sin QR
            $petsWithoutQR = Pet::whereNull('qr_code')->count();
            
            // Usuarios inactivos (sin actividad en 30 días)
            $inactiveUsers = User::where('updated_at', '<', now()->subDays(30))->count();
            
            return [
                'total_users' => $totalUsers,
                'total_pets' => $totalPets,
                'pending_solicitudes' => $pendingSolicitudes,
                'verified_pets' => $verifiedPets,
                'users_by_role' => $usersByRole,
                'user_growth' => $userGrowth,
                'pet_growth' => $petGrowth,
                'species_distribution' => $speciesDistribution,
                'breed_distribution' => $breedDistribution,
                'recent_activity' => $recentActivity,
                'payment_stats' => $paymentStats,
                'pets_without_qr' => $petsWithoutQR,
                'inactive_users' => $inactiveUsers,
                'last_updated' => now()
            ];
        });
    }

    /**
     * Obtiene alertas de seguridad para el administrador
     */
    public function getSecurityAlerts()
    {
        $alerts = [];
        
        // Mascotas sin QR
        $petsWithoutQR = Pet::whereNull('qr_code')->count();
        if ($petsWithoutQR > 0) {
            $alerts[] = [
                'type' => 'warning',
                'title' => 'Mascotas sin código QR',
                'message' => "{$petsWithoutQR} mascotas no tienen código QR asignado",
                'action' => 'Generar códigos QR',
                'url' => route('qr.generator')
            ];
        }
        
        // Solicitudes pendientes
        $pendingSolicitudes = Solicitud::count();
        if ($pendingSolicitudes > 0) {
            $alerts[] = [
                'type' => 'info',
                'title' => 'Solicitudes pendientes',
                'message' => "{$pendingSolicitudes} solicitudes esperando aprobación",
                'action' => 'Revisar solicitudes',
                'url' => route('dashboard.solicitudes')
            ];
        }
        
        // Pagos pendientes
        $pendingPayments = Payment::where('status', 'pending')->count();
        if ($pendingPayments > 0) {
            $alerts[] = [
                'type' => 'warning',
                'title' => 'Pagos pendientes',
                'message' => "{$pendingPayments} pagos esperando verificación",
                'action' => 'Verificar pagos',
                'url' => route('dashboard.solicitudes')
            ];
        }
        
        // Usuarios inactivos
        $inactiveUsers = User::where('updated_at', '<', now()->subDays(30))->count();
        if ($inactiveUsers > 5) {
            $alerts[] = [
                'type' => 'info',
                'title' => 'Usuarios inactivos',
                'message' => "{$inactiveUsers} usuarios sin actividad reciente",
                'action' => 'Revisar usuarios',
                'url' => route('dashboard.usuarios')
            ];
        }
        
        return $alerts;
    }

    /**
     * Obtiene notificaciones para el cliente
     */
    public function getClientNotifications($userId, $includeRead = false)
    {
        $notifications = [];
        
        // Próximas vacunas
        $upcomingVaccines = VaccinationRecord::whereHas('pet', function($query) use ($userId) {
            $query->where('user_id', $userId);
        })
        ->where('next_date', '>=', now())
        ->where('next_date', '<=', now()->addDays(7))
        ->with('pet')
        ->get();
        
        foreach ($upcomingVaccines as $vaccine) {
            $notificationKey = md5('vaccine_upcoming_' . $vaccine->id . '_' . $vaccine->next_date->format('Y-m-d'));
            
            if ($includeRead || !\App\Models\NotificationRead::isRead($userId, 'info', $notificationKey)) {
                $notifications[] = [
                    'type' => 'info',
                    'title' => 'Próxima vacuna',
                    'message' => "{$vaccine->pet->nombre} tiene vacuna programada para " . $vaccine->next_date->format('d/m/Y'),
                    'date' => $vaccine->next_date,
                    'key' => $notificationKey
                ];
            }
        }
        
        // Vacunas vencidas
        $overdueVaccines = VaccinationRecord::whereHas('pet', function($query) use ($userId) {
            $query->where('user_id', $userId);
        })
        ->where('next_date', '<', now())
        ->where('next_date', '!=', null)
        ->with('pet')
        ->get();
        
        foreach ($overdueVaccines as $vaccine) {
            $notificationKey = md5('vaccine_overdue_' . $vaccine->id . '_' . $vaccine->next_date->format('Y-m-d'));
            
            if ($includeRead || !\App\Models\NotificationRead::isRead($userId, 'warning', $notificationKey)) {
                $notifications[] = [
                    'type' => 'warning',
                    'title' => 'Vacuna vencida',
                    'message' => "{$vaccine->pet->nombre} tiene vacuna vencida desde " . $vaccine->next_date->format('d/m/Y'),
                    'date' => $vaccine->next_date,
                    'key' => $notificationKey
                ];
            }
        }
        
        // Mascotas sin QR (información para el cliente)
        $petsWithoutQR = Pet::where('user_id', $userId)->whereNull('qr_code')->get();
        if ($petsWithoutQR->count() > 0) {
            $notificationKey = md5('qr_pending_' . $userId . '_' . $petsWithoutQR->count());
            
            if ($includeRead || !\App\Models\NotificationRead::isRead($userId, 'info', $notificationKey)) {
                $notifications[] = [
                    'type' => 'info',
                    'title' => 'Código QR pendiente',
                    'message' => "Tienes {$petsWithoutQR->count()} mascota(s) sin código QR. Contacta al administrador para generar los códigos.",
                    'date' => now(),
                    'key' => $notificationKey
                ];
            }
        }
        
        // Solicitudes de mascotas pendientes del usuario
        $pendingPetRequests = \App\Models\PetRequest::where('user_id', $userId)
            ->where('status', 'pending')
            ->get();
            
        foreach ($pendingPetRequests as $request) {
            $notificationKey = md5('pet_request_pending_' . $request->id);
            
            if ($includeRead || !\App\Models\NotificationRead::isRead($userId, 'info', $notificationKey)) {
                $notifications[] = [
                    'type' => 'info',
                    'title' => 'Solicitud pendiente',
                    'message' => "Tu solicitud para {$request->nombre} está siendo revisada",
                    'date' => $request->created_at,
                    'key' => $notificationKey
                ];
            }
        }
        
        // Solicitudes de mascotas aprobadas
        $approvedPetRequests = \App\Models\PetRequest::where('user_id', $userId)
            ->where('status', 'approved')
            ->where('reviewed_at', '>=', now()->subDays(7))
            ->get();
            
        foreach ($approvedPetRequests as $request) {
            $notificationKey = md5('pet_request_approved_' . $request->id);
            
            if ($includeRead || !\App\Models\NotificationRead::isRead($userId, 'success', $notificationKey)) {
                $notifications[] = [
                    'type' => 'success',
                    'title' => 'Solicitud aprobada',
                    'message' => "Tu solicitud para {$request->nombre} ha sido aprobada",
                    'date' => $request->reviewed_at,
                    'key' => $notificationKey
                ];
            }
        }
        
        // Solicitudes de mascotas rechazadas
        $rejectedPetRequests = \App\Models\PetRequest::where('user_id', $userId)
            ->where('status', 'rejected')
            ->where('reviewed_at', '>=', now()->subDays(7))
            ->get();
            
        foreach ($rejectedPetRequests as $request) {
            $notificationKey = md5('pet_request_rejected_' . $request->id);
            
            if ($includeRead || !\App\Models\NotificationRead::isRead($userId, 'error', $notificationKey)) {
                $notifications[] = [
                    'type' => 'error',
                    'title' => 'Solicitud rechazada',
                    'message' => "Tu solicitud para {$request->nombre} ha sido rechazada" . 
                               ($request->rejection_reason ? ": {$request->rejection_reason}" : ""),
                    'date' => $request->reviewed_at,
                    'key' => $notificationKey
                ];
            }
        }
        
        return collect($notifications)->sortByDesc('date')->take(10);
    }

    /**
     * Obtiene citas próximas para el cliente
     */
    public function getUpcomingAppointments($userId)
    {
        // Simular citas próximas (en una implementación real, esto vendría de una tabla de citas)
        $pets = Pet::where('user_id', $userId)->get();
        
        $appointments = [];
        foreach ($pets as $pet) {
            // Simular algunas citas
            if (rand(0, 1)) {
                $appointments[] = [
                    'pet_name' => $pet->nombre,
                    'date' => now()->addDays(rand(1, 14))->format('d/m/Y'),
                    'time' => rand(9, 17) . ':00',
                    'type' => 'Consulta general',
                    'id' => rand(1000, 9999)
                ];
            }
        }
        
        return collect($appointments)->sortBy('date')->take(5);
    }

    /**
     * Limpia caché de estadísticas
     */
    public function clearStatsCache($userId = null)
    {
        if ($userId) {
            Cache::forget("client_stats_{$userId}");
        }
        Cache::forget('admin_stats');
    }
}