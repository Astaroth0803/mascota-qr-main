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
        
        // Obtener todas las citas del veterinario
        $appointments = Appointment::where('veterinarian_id', $veterinarianId)
            ->with(['pet.user', 'client'])
            ->orderBy('scheduled_datetime', 'desc')
            ->get();
        
        // Separar citas pasadas y futuras
        $upcomingAppointments = $appointments->filter(function($appointment) {
            return $appointment->scheduled_datetime >= now() && 
                   !in_array($appointment->status, ['finalizada', 'completada', 'cita_terminada']);
        });
        
        // Historial: citas pasadas Y citas finalizadas
        $pastAppointments = $appointments->filter(function($appointment) {
            return $appointment->scheduled_datetime < now() || 
                   in_array($appointment->status, ['finalizada', 'completada', 'cita_terminada']);
        });
        
        // Obtener estadísticas del calendario
        $stats = [
            'total_appointments' => $appointments->count(),
            'upcoming_count' => $upcomingAppointments->count(),
            'past_count' => $pastAppointments->count(),
            'next_appointment' => $upcomingAppointments->sortBy('scheduled_datetime')->first(),
            'last_appointment' => $pastAppointments->sortByDesc('scheduled_datetime')->first(),
            'assigned_pets_count' => $assignedPets->count()
        ];
        
        return view('veterinarian.calendario', compact('assignedPets', 'appointments', 'upcomingAppointments', 'pastAppointments', 'stats'));
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
        
        return view('veterinarian.crear-cita', compact('assignedPets', 'appointmentTypes'));
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
        
        $appointment = Appointment::create($validated);
        
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
        
        // Debug: Log the appointment data
        \Log::info('Appointment Debug:', [
            'id' => $appointment->id,
            'status' => $appointment->status,
            'scheduled_datetime' => $appointment->scheduled_datetime,
            'veterinarian_id' => $appointment->veterinarian_id,
            'pet_id' => $appointment->pet_id
        ]);
        
        return view('veterinarian.detalle-cita', compact('appointment'));
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
        
        // No permitir editar citas finalizadas
        if ($appointment->status === 'finalizada') {
            return redirect()->route('dashboard.veterinario.calendario.show', $appointment->id)
                ->with('error', 'No se puede editar una cita que ya ha sido finalizada.');
        }
        
        $assignedPets = Auth::user()->mascotasActivas()->with('user')->get();
        $appointmentTypes = Appointment::getTypeOptions();
        
        return view('veterinarian.editar-cita', compact('appointment', 'assignedPets', 'appointmentTypes'));
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
        
        $appointment = Appointment::where('veterinarian_id', $veterinarianId)
            ->findOrFail($id);
        
        // No permitir editar citas finalizadas
        if ($appointment->status === 'finalizada') {
            return redirect()->route('dashboard.veterinario.calendario.show', $appointment->id)
                ->with('error', 'No se puede modificar una cita que ya ha sido finalizada.');
        }
        
        $validated = $request->validate([
            'status' => 'required|string|in:pendiente,agendada,en_progreso,finalizada,cancelada'
        ]);
        
        // Log para debug
        \Log::info('Updating appointment', [
            'appointment_id' => $id,
            'current_status' => $appointment->status,
            'new_status' => $validated['status'],
            'validated_data' => $validated
        ]);
        
        $appointment->update($validated);
        
        // Verificar que se actualizó
        $appointment->refresh();
        \Log::info('Appointment updated', [
            'appointment_id' => $id,
            'new_status' => $appointment->status
        ]);
        
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
        
        $appointment = Appointment::where('veterinarian_id', $veterinarianId)
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
        
        $appointments = \App\Models\AppointmentRequest::where('veterinarian_id', $veterinarianId)
            ->whereIn('status', ['aceptado', 'rechazado'])
            ->whereNotNull('scheduled_datetime')
            ->whereYear('scheduled_datetime', Carbon::parse($month)->year)
            ->whereMonth('scheduled_datetime', Carbon::parse($month)->month)
            ->with(['pet.user', 'client', 'appointment'])
            ->orderBy('scheduled_datetime')
            ->get();
        
        $formattedAppointments = $appointments->map(function($appointmentRequest) {
            return [
                'id' => $appointmentRequest->id,
                'title' => $appointmentRequest->pet->nombre . ' - ' . $appointmentRequest->getAppointmentTypeLabelAttribute(),
                'date' => $appointmentRequest->scheduled_datetime ? $appointmentRequest->scheduled_datetime->format('Y-m-d') : null,
                'time' => $appointmentRequest->scheduled_datetime ? $appointmentRequest->scheduled_datetime->format('H:i') : null,
                'type' => $appointmentRequest->appointment_type,
                'pet_name' => $appointmentRequest->pet->nombre,
                'owner_name' => $appointmentRequest->client->name ?? 'N/A',
                'vet_name' => $appointmentRequest->veterinarian->name ?? 'N/A',
                'status' => $appointmentRequest->status,
                'url' => route('appointment-requests.show', $appointmentRequest->id)
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
        
        $todayAppointments = \App\Models\AppointmentRequest::where('veterinarian_id', $veterinarianId)
            ->whereDate('scheduled_datetime', $today)
            ->whereIn('status', ['aceptado', 'rechazado'])
            ->with(['pet.user', 'client', 'appointment'])
            ->orderBy('scheduled_datetime')
            ->get();
        
        return view('veterinarian.citas-hoy', compact('todayAppointments'));
    }
}