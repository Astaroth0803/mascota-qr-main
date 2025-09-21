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
            'citas_hoy' => 0, // TODO: Implementar sistema de citas
            'vacunas_pendientes' => $this->getVacunasPendientes($veterinario->id),
        ];

        // Notificaciones
        $notificaciones = $this->getNotificacionesVeterinario($veterinario->id);

        return view('dashboard.veterinario.index', compact('mascotasAsignadas', 'stats', 'notificaciones'));
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