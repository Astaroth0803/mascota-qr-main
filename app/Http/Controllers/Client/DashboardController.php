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

        $this->dashboardService = $dashboardService;
    }

    public function index()
    {
        // Usar Spatie para verificar permisos
        if (!auth()->user()->can('ver_mascotas')) {
            abort(403, 'No tienes permiso para ver esta página');
        }

        // Obtener mascotas del usuario directamente
        $pets = \App\Models\Pet::where('user_id', Auth::id())->get();

        $stats = $this->dashboardService->getClientStats(Auth::id());

        // Asegurarnos de que stats siempre tenga un formato válido
        if (!is_array($stats)) {
            $stats = [
                'total_pets' => $pets->count(),
                'pets_with_qr' => 0,
                'pets_without_qr' => $pets->count(),
                'upcoming_appointments' => 0,
                'upcoming_vaccines' => collect(),
                'overdue_vaccines' => 0,
                'recent_activity' => collect(),
                'species_distribution' => collect(),
                'age_distribution' => collect(),
                'pets_trend' => null,
                'qr_trend' => null,
                'appointments_trend' => null,
                'qr_pending_trend' => null,
                'vaccines_trend' => null,
                'overdue_trend' => null,
                'last_updated' => now()
            ];
        }

        // Obtener citas próximas reales
        $upcomingAppointments = $this->getUpcomingAppointments();

        return view('client.index', compact('pets', 'stats', 'upcomingAppointments'));
    }

    /**
     * Obtener citas próximas del cliente
     */
    private function getUpcomingAppointments()
    {
        $userId = Auth::id();

        // Obtener solicitudes de citas aceptadas
        $appointmentRequests = \App\Models\AppointmentRequest::where('client_id', $userId)
            ->where('status', 'aceptado')
            ->where('scheduled_datetime', '>=', now())
            ->with(['pet', 'veterinarian'])
            ->orderBy('scheduled_datetime', 'asc')
            ->limit(5)
            ->get();

        return $appointmentRequests->map(function ($request) {
            return [
                'id' => $request->id,
                'pet_name' => $request->pet->nombre ?? 'Mascota',
                'date' => $request->scheduled_datetime->format('d/m/Y'),
                'time' => $request->scheduled_datetime->format('H:i'),
                'type' => $request->getAppointmentTypeLabelAttribute(),
                'veterinarian' => $request->veterinarian->name ?? 'Veterinario',
                'description' => $request->description,
            ];
        });
    }
}
