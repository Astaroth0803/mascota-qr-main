<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pet;
use App\Models\MascotaVeterinario;
use App\Models\VaccinationRecord;
use App\Models\Appointment;
use App\Models\AppointmentRequest;
use App\Policies\MedicalHistoryPolicy;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Gate;

/**
 * VeterinarioController
 * 
 * Controlador principal para la gestión de veterinarios en el sistema Mascota QR.
 * Maneja dashboard, gestión de mascotas, historial médico y estadísticas.
 * 
 * Funcionalidades principales:
 * - Dashboard con estadísticas y gráficos
 * - Gestión de mascotas asignadas
 * - Historial médico (vacunas, tratamientos, etc.)
 * - Sistema de citas y notificaciones
 * - Reportes y análisis de datos
 */
class VeterinarioController extends Controller
{
    use AuthorizesRequests;

    /**
     * Dashboard principal del veterinario
     */
    public function dashboard()
    {
        $veterinario = Auth::user();
        
        // Mascotas asignadas
        $mascotasAsignadas = $veterinario->mascotasActivas()->with('user')->get();
        
        // Estadísticas
        $stats = [
            'total_mascotas' => $mascotasAsignadas->count(),
            'citas_hoy' => $this->getCitasHoy($veterinario->id),
            'citas_pendientes' => $this->getCitasPendientes($veterinario->id),
        ];

        // Notificaciones
        $notificaciones = $this->getNotificacionesVeterinario($veterinario->id);
        
        // Notificaciones de solicitudes
        $notificacionesSolicitudes = $veterinario->notificacionesNoLeidasVeterinario()
            ->with(['cliente', 'mascota'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Obtener citas del mes actual para el calendario
        $monthlyAppointments = $this->getMonthlyAppointments($veterinario->id);
        
        // Obtener próximas citas
        $upcomingAppointments = $this->getUpcomingAppointments($veterinario->id);

        // Obtener datos para gráficos
        $dailyChartData = $this->getDailyChartData($veterinario->id);
        $weeklyChartData = $this->getWeeklyChartData($veterinario->id);
        $monthlyChartData = $this->getMonthlyChartData($veterinario->id);
        $specificDayData = $this->getSpecificDayData($veterinario->id, now()->toDateString());

        return view('veterinarian.index', compact('stats', 'notificaciones', 'notificacionesSolicitudes', 'monthlyAppointments', 'upcomingAppointments', 'dailyChartData', 'weeklyChartData', 'monthlyChartData', 'specificDayData'));
    }

    /**
     * Ver lista de mascotas asignadas
     */
    public function mascotas()
    {
        $veterinario = Auth::user();
        $mascotasAsignadas = $veterinario->mascotasActivas()
            ->with(['user', 'veterinariosActivos'])
            ->paginate(10);

        return view('veterinarian.mascotas', compact('mascotasAsignadas'));
    }

    /**
     * Ver detalles de una mascota específica
     */
    public function showMascota(Pet $pet)
    {
        // Verificar que el veterinario esté asignado a la mascota
        $veterinario = Auth::user();
        if (!$veterinario->isVeterinario()) {
            abort(403, 'Solo los veterinarios pueden acceder a esta sección.');
        }
        
        $asignacion = $pet->veterinariosActivos()->where('veterinario_id', $veterinario->id)->first();
        if (!$asignacion) {
            abort(403, 'No tienes asignada esta mascota.');
        }
        

        return view('veterinarian.mascota-show', compact('pet', 'asignacion'));
    }

    /**
     * Gestionar historial médico de una mascota
     */
    public function gestionarHistorial(Pet $pet)
    {
        // Verificar que el veterinario esté asignado a la mascota
        $veterinario = Auth::user();
        if (!$veterinario->isVeterinario()) {
            abort(403, 'Solo los veterinarios pueden acceder a esta sección.');
        }
        
        $asignacion = $pet->veterinariosActivos()->where('veterinario_id', $veterinario->id)->first();
        if (!$asignacion) {
            abort(403, 'No tienes asignada esta mascota.');
        }
        
        $vacunas = $pet->vaccinationRecords()->orderBy('date', 'desc')->get();
        $tiposRegistros = \App\Models\VaccinationRecord::getTypeOptions();
        $vacunasComunes = \App\Models\VaccinationRecord::getVaccinesBySpecies($pet->especie);
        
        return view('veterinarian.gestionar-historial', compact('pet', 'vacunas', 'tiposRegistros', 'vacunasComunes'));
    }

    /**
     * Agregar nueva vacuna al historial
     */
    public function agregarVacuna(Request $request, Pet $pet)
    {
        // Verificar que el veterinario esté asignado a la mascota
        $veterinario = Auth::user();
        if (!$veterinario->isVeterinario()) {
            abort(403, 'Solo los veterinarios pueden acceder a esta sección.');
        }
        
        $asignacion = $pet->veterinariosActivos()->where('veterinario_id', $veterinario->id)->first();
        if (!$asignacion) {
            abort(403, 'No tienes asignada esta mascota.');
        }
        
        $request->validate([
            'record_type' => 'required|string|in:vacuna,checkeo,peluqueria,operacion,emergencia,dental,dermatologia,neurologia,cardiologia',
            'vaccine_name' => 'required|string|max:255',
            'date' => 'required|date',
            'next_date' => 'nullable|date|after:date',
            'observations' => 'nullable|string|max:1000',
            'diagnosis' => 'nullable|string|max:1000',
            'treatment' => 'nullable|string|max:1000',
        ]);

        // Usar el nombre personalizado si se proporcionó, sino usar el seleccionado
        $vaccineName = $request->vaccine_name_custom ?: $request->vaccine_name;
        
        VaccinationRecord::create([
            'pet_id' => $pet->id,
            'record_type' => $request->record_type,
            'vaccine_name' => $vaccineName,
            'date' => $request->date,
            'next_date' => $request->next_date,
            'vet_name' => $veterinario->name, // Usar el nombre del veterinario logueado
            'observations' => $request->observations,
            'diagnosis' => $request->diagnosis,
            'treatment' => $request->treatment,
        ]);

        return redirect()->back()->with('success', 'Vacuna agregada exitosamente al historial médico.');
    }

    /**
     * Actualizar vacuna existente
     */
    public function actualizarVacuna(Request $request, Pet $pet, VaccinationRecord $vacuna)
    {
        // Verificar que el veterinario esté asignado a la mascota
        $veterinario = Auth::user();
        if (!$veterinario->isVeterinario()) {
            abort(403, 'Solo los veterinarios pueden acceder a esta sección.');
        }
        
        $asignacion = $pet->veterinariosActivos()->where('veterinario_id', $veterinario->id)->first();
        if (!$asignacion) {
            abort(403, 'No tienes asignada esta mascota.');
        }
        
        $request->validate([
            'record_type' => 'required|string|in:vacuna,checkeo,peluqueria,operacion,emergencia,dental,dermatologia,neurologia,cardiologia',
            'vaccine_name' => 'required|string|max:255',
            'date' => 'required|date',
            'next_date' => 'nullable|date|after:date',
            'observations' => 'nullable|string|max:1000',
            'diagnosis' => 'nullable|string|max:1000',
            'treatment' => 'nullable|string|max:1000',
        ]);

        $vacuna->update([
            'record_type' => $request->record_type,
            'vaccine_name' => $request->vaccine_name,
            'date' => $request->date,
            'next_date' => $request->next_date,
            'vet_name' => $veterinario->name, // Usar el nombre del veterinario logueado
            'observations' => $request->observations,
            'diagnosis' => $request->diagnosis,
            'treatment' => $request->treatment,
        ]);

        return redirect()->back()->with('success', 'Vacuna actualizada exitosamente.');
    }

    /**
     * Eliminar vacuna del historial
     */
    public function eliminarVacuna(Pet $pet, VaccinationRecord $vacuna)
    {
        // Verificar que el veterinario esté asignado a la mascota
        $veterinario = Auth::user();
        if (!$veterinario->isVeterinario()) {
            abort(403, 'Solo los veterinarios pueden acceder a esta sección.');
        }
        
        $asignacion = $pet->veterinariosActivos()->where('veterinario_id', $veterinario->id)->first();
        if (!$asignacion) {
            abort(403, 'No tienes asignada esta mascota.');
        }
        
        $vacuna->delete();

        return redirect()->back()->with('success', 'Vacuna eliminada del historial médico.');
    }

    /**
     * Obtener citas de hoy para un veterinario
     */
    private function getCitasHoy($veterinarioId)
    {
        $today = now()->toDateString();
        return Appointment::where('veterinarian_id', $veterinarioId)
            ->whereDate('scheduled_datetime', $today)
            ->count();
    }

    /**
     * Obtener citas del mes actual para el calendario
     */
    private function getMonthlyAppointments($veterinarioId)
    {
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();
        
        return Appointment::where('veterinarian_id', $veterinarioId)
            ->whereBetween('scheduled_datetime', [$startOfMonth, $endOfMonth])
            ->with(['pet.user'])
            ->orderBy('scheduled_datetime')
            ->get()
            ->groupBy(function($appointment) {
                return $appointment->scheduled_datetime->format('Y-m-d');
            })
            ->map(function($appointments) {
                return $appointments->map(function($appointment) {
                    return [
                        'id' => $appointment->id,
                        'pet_name' => $appointment->pet->nombre,
                        'owner_name' => $appointment->pet->user->name ?? 'N/A',
                        'time' => $appointment->scheduled_datetime ? $appointment->scheduled_datetime->format('H:i') : '09:00',
                        'type' => $appointment->record_type,
                        'type_label' => $this->getRecordTypeLabel($appointment->record_type),
                        'location' => $appointment->location,
                        'url' => route('dashboard.veterinario.calendario.show', $appointment->id),
                        'color' => $this->getAppointmentColor($appointment->record_type)
                    ];
                });
            });
    }

    /**
     * Obtener color para el tipo de cita
     */
    private function getAppointmentColor($recordType)
    {
        switch ($recordType) {
            case 'vacuna': return '#34D399'; // green-400
            case 'checkeo': return '#60A5FA'; // blue-400
            case 'peluqueria': return '#FCD34D'; // yellow-400
            case 'operacion': return '#F87171'; // red-400
            case 'emergencia': return '#EF4444'; // red-500
            case 'dental': return '#A78BFA'; // violet-400
            case 'dermatologia': return '#FB7185'; // pink-400
            case 'neurologia': return '#8B5CF6'; // violet-500
            case 'cardiologia': return '#EC4899'; // pink-500
            default: return '#6B7280'; // gray-500
        }
    }
    
    /**
     * Obtener etiqueta para el tipo de registro
     */
    private function getRecordTypeLabel($recordType)
    {
        $labels = [
            'vacuna' => 'Vacunación',
            'checkeo' => 'Chequeo',
            'peluqueria' => 'Peluquería',
            'operacion' => 'Operación',
            'emergencia' => 'Emergencia',
            'dental' => 'Dental',
            'dermatologia' => 'Dermatología',
            'neurologia' => 'Neurología',
            'cardiologia' => 'Cardiología',
        ];
        
        return $labels[$recordType] ?? ucfirst($recordType);
    }

    /**
     * Obtener citas pendientes para un veterinario
     */
    private function getCitasPendientes($veterinarioId)
    {
        return AppointmentRequest::where('veterinarian_id', $veterinarioId)
            ->where('status', 'pendiente')
            ->count();
    }
    
    /**
     * Obtener próximas citas para un veterinario (solo citas aceptadas y no finalizadas)
     */
    private function getUpcomingAppointments($veterinarioId)
    {
        // Debug: Ver todas las citas del veterinario
        $allAppointments = AppointmentRequest::where('veterinarian_id', $veterinarioId)->get();
        \Log::info('Todas las citas del veterinario:', $allAppointments->toArray());
        
        // Debug específico: Ver la cita con ID 4
        $cita4 = AppointmentRequest::find(4);
        if ($cita4) {
            \Log::info('Cita ID 4:', [
                'id' => $cita4->id,
                'status' => $cita4->status,
                'scheduled_datetime' => $cita4->scheduled_datetime,
                'veterinarian_id' => $cita4->veterinarian_id,
                'pet_id' => $cita4->pet_id
            ]);
        } else {
            \Log::info('Cita ID 4 no encontrada');
        }
        
        // Buscar en appointment_requests las solicitudes aceptadas que NO tengan citas finalizadas
        $upcomingAppointments = AppointmentRequest::where('veterinarian_id', $veterinarioId)
            ->where('status', 'aceptado')
            ->whereDate('scheduled_datetime', '>=', now()->toDateString())
            ->whereDoesntHave('appointment', function($query) {
                $query->whereIn('status', ['finalizada', 'completada', 'cita_terminada']);
            })
            ->with(['pet.user', 'appointment'])
            ->orderBy('scheduled_datetime')
            ->limit(5)
            ->get();
            
        \Log::info('Citas próximas encontradas:', $upcomingAppointments->toArray());
        
        return $upcomingAppointments;
    }

    /**
     * Obtener datos del gráfico diario (últimos 7 días)
     */
    private function getDailyChartData($veterinarioId)
    {
        // Debug: Ver todos los status únicos en la base de datos
        $allStatuses = AppointmentRequest::where('veterinarian_id', $veterinarioId)
            ->distinct()
            ->pluck('status')
            ->toArray();
        \Log::info('Status únicos encontrados:', $allStatuses);
        
        $days = [];
        $completed = [];
        $rejected = [];
        $cancelled = [];

        // Obtener datos de los últimos 7 días
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $days[] = $date->format('d/m');
            
            $startOfDay = $date->copy()->startOfDay();
            $endOfDay = $date->copy()->endOfDay();
            
            // Citas finalizadas - buscar en appointments donde la solicitud está aceptada
            $completed[] = AppointmentRequest::where('veterinarian_id', $veterinarioId)
                ->where('status', 'aceptado')
                ->whereHas('appointment', function($query) use ($startOfDay, $endOfDay) {
                    $query->whereIn('status', ['finalizada', 'completada', 'cita_terminada'])
                          ->whereBetween('scheduled_datetime', [$startOfDay, $endOfDay]);
                })
                ->count();
            
            // Citas rechazadas
            $rejected[] = AppointmentRequest::where('veterinarian_id', $veterinarioId)
                ->where('status', 'rechazado')
                ->whereBetween('created_at', [$startOfDay, $endOfDay])
                ->count();
            
            // Citas canceladas - buscar en appointments donde la solicitud está aceptada
            $cancelled[] = AppointmentRequest::where('veterinarian_id', $veterinarioId)
                ->where('status', 'aceptado')
                ->whereHas('appointment', function($query) use ($startOfDay, $endOfDay) {
                    $query->whereIn('status', ['cancelada', 'cita_cancelada'])
                          ->whereBetween('scheduled_datetime', [$startOfDay, $endOfDay]);
                })
                ->count();
        }

        return [
            'labels' => $days,
            'completed' => $completed,
            'rejected' => $rejected,
            'cancelled' => $cancelled
        ];
    }

    /**
     * Obtener datos del gráfico mensual
     */
    private function getMonthlyChartData($veterinarioId)
    {
        $months = [];
        $completed = [];
        $rejected = [];
        $cancelled = [];

        // Obtener datos de los últimos 6 meses
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');
            
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();
            
            // Citas finalizadas - buscar en appointments donde la solicitud está aceptada
            $completed[] = AppointmentRequest::where('veterinarian_id', $veterinarioId)
                ->where('status', 'aceptado')
                ->whereHas('appointment', function($query) use ($startOfMonth, $endOfMonth) {
                    $query->whereIn('status', ['finalizada', 'completada', 'cita_terminada'])
                          ->whereBetween('scheduled_datetime', [$startOfMonth, $endOfMonth]);
                })
                ->count();
            
            // Citas rechazadas
            $rejected[] = AppointmentRequest::where('veterinarian_id', $veterinarioId)
                ->where('status', 'rechazado')
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->count();
            
            // Citas canceladas - buscar en appointments donde la solicitud está aceptada
            $cancelled[] = AppointmentRequest::where('veterinarian_id', $veterinarioId)
                ->where('status', 'aceptado')
                ->whereHas('appointment', function($query) use ($startOfMonth, $endOfMonth) {
                    $query->whereIn('status', ['cancelada', 'cita_cancelada'])
                          ->whereBetween('scheduled_datetime', [$startOfMonth, $endOfMonth]);
                })
                ->count();
        }

        return [
            'labels' => $months,
            'completed' => $completed,
            'rejected' => $rejected,
            'cancelled' => $cancelled
        ];
    }

    /**
     * Obtener datos del gráfico semanal
     */
    private function getWeeklyChartData($veterinarioId)
    {
        $weeks = [];
        $completed = [];
        $rejected = [];
        $cancelled = [];

        // Obtener datos de las últimas 8 semanas
        for ($i = 7; $i >= 0; $i--) {
            $date = now()->subWeeks($i);
            $weeks[] = 'Sem ' . $date->format('W');
            
            $startOfWeek = $date->copy()->startOfWeek();
            $endOfWeek = $date->copy()->endOfWeek();
            
            // Citas finalizadas - buscar en appointments donde la solicitud está aceptada
            $completed[] = AppointmentRequest::where('veterinarian_id', $veterinarioId)
                ->where('status', 'aceptado')
                ->whereHas('appointment', function($query) use ($startOfWeek, $endOfWeek) {
                    $query->whereIn('status', ['finalizada', 'completada', 'cita_terminada'])
                          ->whereBetween('scheduled_datetime', [$startOfWeek, $endOfWeek]);
                })
                ->count();
            
            // Citas rechazadas
            $rejected[] = AppointmentRequest::where('veterinarian_id', $veterinarioId)
                ->where('status', 'rechazado')
                ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                ->count();
            
            // Citas canceladas - buscar en appointments donde la solicitud está aceptada
            $cancelled[] = AppointmentRequest::where('veterinarian_id', $veterinarioId)
                ->where('status', 'aceptado')
                ->whereHas('appointment', function($query) use ($startOfWeek, $endOfWeek) {
                    $query->whereIn('status', ['cancelada', 'cita_cancelada'])
                          ->whereBetween('scheduled_datetime', [$startOfWeek, $endOfWeek]);
                })
                ->count();
        }

        return [
            'labels' => $weeks,
            'completed' => $completed,
            'rejected' => $rejected,
            'cancelled' => $cancelled
        ];
    }

    /**
     * Obtener datos de un día específico
     */
    private function getSpecificDayData($veterinarioId, $date)
    {
        $startOfDay = \Carbon\Carbon::parse($date)->startOfDay();
        $endOfDay = \Carbon\Carbon::parse($date)->endOfDay();
        
        return [
            'labels' => [\Carbon\Carbon::parse($date)->format('d/m')],
            'completed' => [
                AppointmentRequest::where('veterinarian_id', $veterinarioId)
                    ->where('status', 'aceptado')
                    ->whereHas('appointment', function($query) use ($startOfDay, $endOfDay) {
                        $query->whereIn('status', ['finalizada', 'completada', 'cita_terminada'])
                              ->whereBetween('scheduled_datetime', [$startOfDay, $endOfDay]);
                    })
                    ->count()
            ],
            'rejected' => [
                AppointmentRequest::where('veterinarian_id', $veterinarioId)
                    ->where('status', 'rechazado')
                    ->whereBetween('created_at', [$startOfDay, $endOfDay])
                    ->count()
            ],
            'cancelled' => [
                AppointmentRequest::where('veterinarian_id', $veterinarioId)
                    ->where('status', 'aceptado')
                    ->whereHas('appointment', function($query) use ($startOfDay, $endOfDay) {
                        $query->whereIn('status', ['cancelada', 'cita_cancelada'])
                              ->whereBetween('scheduled_datetime', [$startOfDay, $endOfDay]);
                    })
                    ->count()
            ]
        ];
    }

    /**
     * Obtener datos del gráfico para un día específico (AJAX)
     */
    public function getChartDataForDate($date)
    {
        $veterinarioId = Auth::id();
        $data = $this->getSpecificDayData($veterinarioId, $date);
        
        return response()->json($data);
    }

    /**
     * Obtener notificaciones del veterinario
     */
    private function getNotificacionesVeterinario($veterinarioId)
    {
        // TODO: Implementar sistema de notificaciones para veterinarios
        return collect([]);
    }
}