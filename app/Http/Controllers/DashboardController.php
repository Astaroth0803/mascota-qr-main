<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request as HttpRequest;
use App\Models\User;
use App\Models\Pet;
use App\Models\Solicitud;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalPets = Pet::count();
        $pendingRequests = Solicitud::where('status', 'pending')->count();

        // Calcular cambios porcentuales
        $userChange = $this->calculateChange(User::class, 'created_at');
        $petChange = $this->calculateChange(Pet::class, 'created_at');
        $requestChange = $this->calculateChange(Solicitud::class, 'created_at', ['status' => 'pending']);

        // Obtener actividad reciente
        $recentActivities = ActivityLog::with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.administrador', compact(
            'totalUsers',
            'totalPets',
            'pendingRequests',
            'userChange',
            'petChange',
            'requestChange',
            'recentActivities'
        ));
    }

    public function getStats(HttpRequest $request)
    {
        $period = $request->input('period', 'day');
        $startDate = $this->getStartDate($period);

        $stats = [
            'users' => $this->getModelStats(User::class, $startDate),
            'pets' => $this->getModelStats(Pet::class, $startDate),
            'requests' => $this->getModelStats(Solicitud::class, $startDate, ['status' => 'pending']),
            'activities' => $this->getActivityStats($startDate)
        ];

        return response()->json($stats);
    }

    private function calculateChange($model, $dateColumn, $conditions = [])
    {
        $now = Carbon::now();
        $lastMonth = Carbon::now()->subMonth();

        $currentCount = $model::where($conditions)
            ->where($dateColumn, '<=', $now)
            ->count();

        $previousCount = $model::where($conditions)
            ->where($dateColumn, '<=', $lastMonth)
            ->count();

        if ($previousCount === 0) {
            return 0;
        }

        return round((($currentCount - $previousCount) / $previousCount) * 100, 2);
    }

    private function getStartDate($period)
    {
        return match($period) {
            'day' => Carbon::now()->startOfDay(),
            'week' => Carbon::now()->startOfWeek(),
            'month' => Carbon::now()->startOfMonth(),
            default => Carbon::now()->startOfDay()
        };
    }

    private function getModelStats($model, $startDate, $conditions = [])
    {
        return $model::where($conditions)
            ->where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    private function getActivityStats($startDate)
    {
        return ActivityLog::where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }
}
