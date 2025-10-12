<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pet;
use App\Models\MascotaVeterinario;
use App\Models\VaccinationRecord;
use App\Policies\MedicalHistoryPolicy;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Gate;

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
            'mascotas_principales' => $veterinario->mascotasPrincipales()->count(),
            'citas_hoy' => $this->getCitasHoy($veterinario->id),
            'vacunas_pendientes' => $this->getVacunasPendientes($veterinario->id),
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

        return view('dashboard.veterinario.index', compact('mascotasAsignadas', 'stats', 'notificaciones', 'notificacionesSolicitudes', 'monthlyAppointments'));
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

        return view('dashboard.veterinario.mascotas', compact('mascotasAsignadas'));
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
        

        return view('dashboard.veterinario.mascota-show', compact('pet', 'asignacion'));
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
        
        return view('dashboard.veterinario.gestionar-historial', compact('pet', 'vacunas', 'tiposRegistros', 'vacunasComunes'));
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
        return VaccinationRecord::where('veterinarian_id', $veterinarioId)
            ->where('date', $today)
            ->count();
    }

    /**
     * Obtener citas del mes actual para el calendario
     */
    private function getMonthlyAppointments($veterinarioId)
    {
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();
        
        return VaccinationRecord::where('veterinarian_id', $veterinarioId)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->with(['pet.user'])
            ->orderBy('date')
            ->orderBy('time')
            ->get()
            ->groupBy(function($appointment) {
                return $appointment->date->format('Y-m-d');
            })
            ->map(function($appointments) {
                return $appointments->map(function($appointment) {
                    return [
                        'id' => $appointment->id,
                        'pet_name' => $appointment->pet->nombre,
                        'owner_name' => $appointment->pet->user->name ?? 'N/A',
                        'time' => $appointment->time ? ($appointment->time instanceof \Carbon\Carbon ? $appointment->time->format('H:i') : \Carbon\Carbon::parse($appointment->time)->format('H:i')) : '09:00',
                        'type' => $appointment->record_type,
                        'type_label' => $appointment->getTypeOptions()[$appointment->record_type] ?? ucfirst($appointment->record_type),
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
     * Obtener vacunas pendientes para un veterinario
     */
    private function getVacunasPendientes($veterinarioId)
    {
        // TODO: Implementar lógica para vacunas pendientes
        return 0;
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