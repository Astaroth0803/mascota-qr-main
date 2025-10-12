<?php

namespace App\Http\Controllers\Veterinario;

use App\Http\Controllers\Controller;
use App\Models\VaccinationRecord;
use App\Models\Pet;
use App\Models\User;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    /**
     * Mostrar el calendario de citas del veterinario
     */
    public function index()
    {
        if (!auth()->user()->hasRole('veterinario')) {
            abort(403, 'Solo los veterinarios pueden acceder a esta página');
        }

        $veterinarianId = Auth::id();
        
        // Obtener todas las mascotas asignadas al veterinario
        $assignedPets = Auth::user()->mascotasActivas()->get();
        
        // Obtener todas las citas del veterinario (pasadas y futuras)
        $appointments = Appointment::where('veterinarian_id', $veterinarianId)
            ->whereIn('status', [Appointment::STATUS_SCHEDULED, Appointment::STATUS_IN_PROGRESS, Appointment::STATUS_COMPLETED])
            ->whereNotNull('scheduled_datetime')
            ->with(['pet.user', 'client'])
            ->orderBy('scheduled_datetime', 'desc')
            ->get();
        
        // Separar citas pasadas y futuras
        $upcomingAppointments = $appointments->where('scheduled_datetime', '>=', now());
        $pastAppointments = $appointments->where('scheduled_datetime', '<', now());
        
        // Obtener estadísticas del calendario
        $stats = [
            'total_appointments' => $appointments->count(),
            'upcoming_count' => $upcomingAppointments->count(),
            'past_count' => $pastAppointments->count(),
            'next_appointment' => $upcomingAppointments->sortBy('scheduled_datetime')->first(),
            'last_appointment' => $pastAppointments->sortByDesc('scheduled_datetime')->first(),
            'assigned_pets_count' => $assignedPets->count()
        ];
        
        return view('dashboard.veterinario.calendario', compact('assignedPets', 'appointments', 'upcomingAppointments', 'pastAppointments', 'stats'));
    }
    
    /**
     * Mostrar el formulario para crear una nueva cita
     */
    public function create()
    {
        if (!auth()->user()->hasRole('veterinario')) {
            abort(403, 'Solo los veterinarios pueden acceder a esta página');
        }

        $veterinarianId = Auth::id();
        $assignedPets = Auth::user()->mascotasActivas()->with('user')->get();
        $appointmentTypes = Appointment::getTypeOptions();
        
        return view('dashboard.veterinario.crear-cita', compact('assignedPets', 'appointmentTypes'));
    }
    
    /**
     * Almacenar una nueva cita
     */
    public function store(Request $request)
    {
        if (!auth()->user()->hasRole('veterinario')) {
            abort(403, 'Solo los veterinarios pueden realizar esta acción');
        }

        $veterinarianId = Auth::id();
        
        // Validar que la mascota esté asignada al veterinario
        $pet = Auth::user()->mascotasActivas()->where('pets.id', $request->pet_id)->first();
        if (!$pet) {
            return redirect()->back()->withErrors(['pet_id' => 'No tienes asignada esta mascota.']);
        }
        
        $validated = $request->validate([
            'pet_id' => 'required|exists:pets,id',
            'record_type' => 'required|string',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|date_format:H:i',
            'vet_name' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'observations' => 'nullable|string',
            'diagnosis' => 'nullable|string',
            'treatment' => 'nullable|string',
            'vaccine_name' => 'nullable|string|max:255',
            'next_date' => 'nullable|date|after:date',
            'pet_weight' => 'nullable|numeric|min:0|max:999.99'
        ]);
        
        // Agregar el ID del veterinario
        $validated['veterinarian_id'] = $veterinarianId;
        $validated['vet_name'] = $validated['vet_name'] ?: Auth::user()->name;
        
        $appointment = VaccinationRecord::create($validated);
        
        // Actualizar el peso de la mascota si se proporciona (solo veterinarios)
        if (isset($validated['pet_weight']) && $validated['pet_weight'] !== null) {
            $pet->update(['peso' => $validated['pet_weight']]);
        }
        
        return redirect()->route('dashboard.veterinario.calendario.index')
                        ->with('success', 'Cita programada exitosamente para ' . $pet->nombre);
    }
    
    /**
     * Mostrar los detalles de una cita específica
     */
    public function show($id)
    {
        if (!auth()->user()->hasRole('veterinario')) {
            abort(403, 'Solo los veterinarios pueden acceder a esta página');
        }

        $veterinarianId = Auth::id();
        
        $appointment = Appointment::where('veterinarian_id', $veterinarianId)
            ->with(['pet.user', 'client'])
            ->findOrFail($id);
        
        return view('dashboard.veterinario.detalle-cita', compact('appointment'));
    }
    
    /**
     * Mostrar el formulario para editar una cita
     */
    public function edit($id)
    {
        if (!auth()->user()->hasRole('veterinario')) {
            abort(403, 'Solo los veterinarios pueden acceder a esta página');
        }

        $veterinarianId = Auth::id();
        
        $appointment = Appointment::where('veterinarian_id', $veterinarianId)
            ->findOrFail($id);
        
        $assignedPets = Auth::user()->mascotasActivas()->with('user')->get();
        $appointmentTypes = Appointment::getTypeOptions();
        
        return view('dashboard.veterinario.editar-cita', compact('appointment', 'assignedPets', 'appointmentTypes'));
    }
    
    /**
     * Actualizar una cita existente
     */
    public function update(Request $request, $id)
    {
        if (!auth()->user()->hasRole('veterinario')) {
            abort(403, 'Solo los veterinarios pueden realizar esta acción');
        }

        $veterinarianId = Auth::id();
        
        $appointment = VaccinationRecord::where('veterinarian_id', $veterinarianId)
            ->findOrFail($id);
        
        $validated = $request->validate([
            'record_type' => 'required|string',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'vet_name' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'observations' => 'nullable|string',
            'diagnosis' => 'nullable|string',
            'treatment' => 'nullable|string',
            'vaccine_name' => 'nullable|string|max:255',
            'next_date' => 'nullable|date|after:date',
            'pet_weight' => 'nullable|numeric|min:0|max:999.99'
        ]);
        
        $validated['vet_name'] = $validated['vet_name'] ?: Auth::user()->name;
        
        $appointment->update($validated);
        
        // Actualizar el peso de la mascota si se proporciona (solo veterinarios)
        if (isset($validated['pet_weight']) && $validated['pet_weight'] !== null) {
            $pet = $appointment->pet;
            $pet->update(['peso' => $validated['pet_weight']]);
        }
        
        return redirect()->route('dashboard.veterinario.calendario.index')
                        ->with('success', 'Cita actualizada exitosamente');
    }
    
    /**
     * Eliminar una cita
     */
    public function destroy($id)
    {
        if (!auth()->user()->hasRole('veterinario')) {
            abort(403, 'Solo los veterinarios pueden realizar esta acción');
        }

        $veterinarianId = Auth::id();
        
        $appointment = VaccinationRecord::where('veterinarian_id', $veterinarianId)
            ->findOrFail($id);
        
        $petName = $appointment->pet->nombre;
        $appointment->delete();
        
        return redirect()->route('dashboard.veterinario.calendario.index')
                        ->with('success', "Cita eliminada exitosamente para {$petName}");
    }
    
    /**
     * Obtener citas para un mes específico (API para calendario)
     */
    public function getAppointmentsForMonth(Request $request)
    {
        if (!auth()->user()->hasRole('veterinario')) {
            abort(403, 'Solo los veterinarios pueden acceder a esta página');
        }

        $veterinarianId = Auth::id();
        $month = $request->get('month', now()->format('Y-m'));
        
        $appointments = Appointment::where('veterinarian_id', $veterinarianId)
            ->whereIn('status', [Appointment::STATUS_SCHEDULED, Appointment::STATUS_IN_PROGRESS, Appointment::STATUS_COMPLETED])
            ->whereNotNull('scheduled_datetime')
            ->whereYear('scheduled_datetime', Carbon::parse($month)->year)
            ->whereMonth('scheduled_datetime', Carbon::parse($month)->month)
            ->with(['pet.user', 'client'])
            ->orderBy('scheduled_datetime')
            ->get();
        
        $formattedAppointments = $appointments->map(function($appointment) {
            return [
                'id' => $appointment->id,
                'title' => $appointment->pet->nombre . ' - ' . $appointment->record_type_label,
                'date' => $appointment->scheduled_datetime ? $appointment->scheduled_datetime->format('Y-m-d') : null,
                'time' => $appointment->scheduled_datetime ? $appointment->scheduled_datetime->format('H:i') : null,
                'type' => $appointment->record_type,
                'pet_name' => $appointment->pet->nombre,
                'owner_name' => $appointment->client->name ?? 'N/A',
                'vet_name' => $appointment->veterinarian->name ?? 'N/A',
                'location' => $appointment->location,
                'url' => route('dashboard.veterinario.calendario.show', $appointment->id)
            ];
        });
        
        return response()->json($formattedAppointments);
    }
    
    /**
     * Vista de citas del día
     */
    public function today()
    {
        if (!auth()->user()->hasRole('veterinario')) {
            abort(403, 'Solo los veterinarios pueden acceder a esta página');
        }

        $veterinarianId = Auth::id();
        $today = now()->toDateString();
        
        $todayAppointments = VaccinationRecord::where('veterinarian_id', $veterinarianId)
            ->where('date', $today)
            ->with(['pet.user', 'veterinarian'])
            ->orderBy('time')
            ->get();
        
        return view('dashboard.veterinario.citas-hoy', compact('todayAppointments'));
    }
}