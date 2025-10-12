<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\VaccinationRecord;
use App\Models\Pet;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    /**
     * Mostrar el calendario de citas del cliente
     */
    public function index()
    {
        if (!auth()->user()->can('ver_mascotas')) {
            abort(403, 'No tienes permiso para ver esta página');
        }

        $userId = Auth::id();
        
        // Obtener todas las mascotas del usuario
        $pets = Pet::where('user_id', $userId)->get();
        
        // Obtener todas las citas del usuario (pasadas y futuras)
        $appointments = Appointment::where('client_id', $userId)
            ->with(['pet', 'veterinarian'])
            ->orderBy('requested_datetime', 'desc')
            ->get();
        
        // Separar citas pasadas y futuras
        $upcomingAppointments = $appointments->where('requested_datetime', '>=', now());
        $pastAppointments = $appointments->where('requested_datetime', '<', now());
        
        // Obtener estadísticas del calendario
        $stats = [
            'total_appointments' => $appointments->count(),
            'upcoming_count' => $upcomingAppointments->count(),
            'past_count' => $pastAppointments->count(),
            'next_appointment' => $upcomingAppointments->sortBy('requested_datetime')->first(),
            'last_appointment' => $pastAppointments->sortByDesc('requested_datetime')->first()
        ];
        
        return view('dashboard.cliente.calendario', compact('pets', 'appointments', 'upcomingAppointments', 'pastAppointments', 'stats'));
    }
    
    /**
     * Mostrar el formulario para crear una nueva cita
     */
    public function create()
    {
        if (!auth()->user()->can('ver_mascotas')) {
            abort(403, 'No tienes permiso para ver esta página');
        }

        $userId = Auth::id();
        $pets = Pet::where('user_id', $userId)->with(['veterinariosActivos'])->get();
        $appointmentTypes = VaccinationRecord::getTypeOptions();
        
        return view('dashboard.cliente.crear-cita', compact('pets', 'appointmentTypes'));
    }
    
    /**
     * Almacenar una nueva cita
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('ver_mascotas')) {
            abort(403, 'No tienes permiso para realizar esta acción');
        }

        $userId = Auth::id();
        
        // Validar que la mascota pertenece al usuario
        $pet = Pet::where('id', $request->pet_id)
                  ->where('user_id', $userId)
                  ->firstOrFail();
        
        $validated = $request->validate([
            'pet_id' => 'required|exists:pets,id',
            'record_type' => 'required|in:consulta,vacuna,operacion,emergencia,checkeo',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|date_format:H:i',
            'veterinarian_id' => 'nullable|exists:users,id',
            'location' => 'nullable|string|max:255',
            'observations' => 'nullable|string',
            'diagnosis' => 'nullable|string',
            'treatment' => 'nullable|string',
            'vaccine_name' => 'nullable|string|max:255',
            'next_date' => 'nullable|date|after:date'
        ]);
        
        // El vaccine_name será determinado por el veterinario, no por el cliente
        $validated['vaccine_name'] = null;
        
        // Si el usuario es veterinario, asignarlo automáticamente
        if (Auth::user()->hasRole('veterinario')) {
            $validated['veterinarian_id'] = Auth::id();
        } else {
            // Para clientes, usar el veterinarian_id del formulario o asignar uno por defecto
            if (empty($validated['veterinarian_id'])) {
                // Buscar el veterinario principal asignado a la mascota
                $mascota = Pet::find($validated['pet_id']);
                $veterinarioPrincipal = $mascota->veterinariosActivos()->where('tipo_asignacion', 'licenciado')->first();
                
                if ($veterinarioPrincipal) {
                    $validated['veterinarian_id'] = $veterinarioPrincipal->id;
                }
            }
        }
        
        // Crear cita usando el modelo Appointment con estado pendiente
        $appointment = Appointment::create([
            'pet_id' => $validated['pet_id'],
            'client_id' => Auth::id(),
            'veterinarian_id' => $validated['veterinarian_id'],
            'record_type' => $validated['record_type'],
            'requested_datetime' => Carbon::parse($validated['date'] . ' ' . $validated['time']),
            'location' => $validated['location'],
            'observations' => $validated['observations'],
            'status' => Appointment::STATUS_PENDING, // Estado pendiente para que aparezca en solicitudes
        ]);
        
        return redirect()->route('dashboard.cliente.calendario.index')
                        ->with('success', 'Solicitud de cita enviada exitosamente para ' . $pet->nombre . '. El veterinario la revisará pronto.');
    }
    
    /**
     * Mostrar los detalles de una cita específica
     */
    public function show($id)
    {
        if (!auth()->user()->can('ver_mascotas')) {
            abort(403, 'No tienes permiso para ver esta página');
        }

        $userId = Auth::id();
        
        $appointment = Appointment::where('client_id', $userId)
            ->with(['pet', 'veterinarian'])
            ->findOrFail($id);
        
        return view('dashboard.cliente.detalle-cita', compact('appointment'));
    }
    
    /**
     * Mostrar el formulario para editar una cita
     */
    public function edit($id)
    {
        if (!auth()->user()->can('ver_mascotas')) {
            abort(403, 'No tienes permiso para ver esta página');
        }

        $userId = Auth::id();
        
        $appointment = VaccinationRecord::whereHas('pet', function($query) use ($userId) {
            $query->where('user_id', $userId);
        })
        ->findOrFail($id);
        
        $pets = Pet::where('user_id', $userId)->get();
        $appointmentTypes = VaccinationRecord::getTypeOptions();
        
        return view('dashboard.cliente.editar-cita', compact('appointment', 'pets', 'appointmentTypes'));
    }
    
    /**
     * Solicitar cambio de fecha de una cita
     */
    public function update(Request $request, $id)
    {
        if (!auth()->user()->can('ver_mascotas')) {
            abort(403, 'No tienes permiso para realizar esta acción');
        }

        $userId = Auth::id();
        
        $appointment = VaccinationRecord::whereHas('pet', function($query) use ($userId) {
            $query->where('user_id', $userId);
        })
        ->with('veterinarian')
        ->findOrFail($id);
        
        // Verificar que no hay solicitudes pendientes
        if ($appointment->pendingChangeRequests()->exists()) {
            return redirect()->back()
                            ->with('error', 'Ya existe una solicitud de cambio pendiente para esta cita.');
        }
        
        $validated = $request->validate([
            'requested_date' => 'required|date|after_or_equal:today',
            'requested_time' => 'required|date_format:H:i',
            'reason' => 'nullable|string|max:500'
        ]);
        
        // Crear solicitud de cambio
        \App\Models\AppointmentChangeRequest::create([
            'appointment_id' => $appointment->id,
            'client_id' => $userId,
            'veterinarian_id' => $appointment->veterinarian_id,
            'requested_date' => $validated['requested_date'],
            'requested_time' => $validated['requested_time'],
            'reason' => $validated['reason'],
            'status' => 'pending'
        ]);
        
        return redirect()->route('dashboard.cliente.calendario.index')
                        ->with('success', 'Solicitud de cambio de fecha enviada. El veterinario será notificado.');
    }
    
    /**
     * Cancelar una cita (solo veterinarios pueden eliminar completamente)
     */
    public function destroy($id)
    {
        if (!auth()->user()->can('ver_mascotas')) {
            abort(403, 'No tienes permiso para realizar esta acción');
        }

        $userId = Auth::id();
        
        $appointment = VaccinationRecord::whereHas('pet', function($query) use ($userId) {
            $query->where('user_id', $userId);
        })
        ->findOrFail($id);
        
        // Los clientes no pueden eliminar citas directamente
        // Solo pueden solicitar cambios de fecha
        return redirect()->back()
                        ->with('error', 'No puedes eliminar citas directamente. Contacta a tu veterinario para cancelar la cita.');
    }
    
    /**
     * Obtener citas para un mes específico (API para calendario)
     */
    public function getAppointmentsForMonth(Request $request)
    {
        if (!auth()->user()->can('ver_mascotas')) {
            abort(403, 'No tienes permiso para ver esta página');
        }

        $userId = Auth::id();
        $month = $request->get('month', now()->format('Y-m'));
        
        $appointments = VaccinationRecord::whereHas('pet', function($query) use ($userId) {
            $query->where('user_id', $userId);
        })
        ->whereYear('date', Carbon::parse($month)->year)
        ->whereMonth('date', Carbon::parse($month)->month)
        ->with('pet')
        ->orderBy('date')
        ->orderBy('time')
        ->get();
        
        $formattedAppointments = $appointments->map(function($appointment) {
            return [
                'id' => $appointment->id,
                'title' => $appointment->pet->nombre . ' - ' . $appointment->getTypeOptions()[$appointment->record_type],
                'date' => $appointment->date->format('Y-m-d'),
                'time' => $appointment->time ? $appointment->time->format('H:i') : '09:00',
                'type' => $appointment->record_type,
                'pet_name' => $appointment->pet->nombre,
                'vet_name' => $appointment->vet_name,
                'location' => $appointment->location,
                'url' => route('dashboard.cliente.calendario.show', $appointment->id)
            ];
        });
        
        return response()->json($formattedAppointments);
    }
}
