<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        // En Laravel 11 no usamos middleware en el controlador
        // sino en las rutas
        $this->dashboardService = $dashboardService;
    }

    public function index()
    {
        // Usar Spatie para verificar permisos
        if (!auth()->user()->can('ver_mascotas')) {
            abort(403, 'No tienes permiso para ver esta página');
        }

        $pets = $this->dashboardService->getUserPets(
            Auth::id(),
            Auth::user()->email
        );

        $statistics = $this->dashboardService->getUserStatistics(Auth::id());

        // Asegurarnos de que statistics siempre tenga un formato válido
        if (!is_array($statistics)) {
            $statistics = [
                'total_pets' => $pets->count(),
                'pending_vaccinations' => 0,
                'upcoming_appointments' => [],
                'recent_activities' => []
            ];
        }

        return view('dashboard.cliente', compact('pets', 'statistics'));
    }
}
