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
        
        return view('client.calendario', compact('pets', 'appointments', 'upcomingAppointments', 'pastAppointments', 'stats'));
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
        
        return view('client.crear-cita', compact('pets', 'appointmentTypes'));
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
            'veterinarian_id' => 'required|exists:users,id',
            'appointment_type' => 'required|in:consulta,vacunacion,cirugia,emergencia,chequeo',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|date_format:H:i',
            'description' => 'nullable|string',
        ]);
        
        // Verificar que el veterinario existe y tiene el rol correcto
        $veterinarian = \App\Models\User::role('veterinario')
            ->where('id', $validated['veterinarian_id'])
            ->firstOrFail();
        
        try {
            // Crear solicitud de cita usando el modelo AppointmentRequest (mismo método que appointment-requests/create)
            $appointmentRequest = \App\Models\AppointmentRequest::create([
                'pet_id' => $validated['pet_id'],
                'client_id' => Auth::id(),
                'veterinarian_id' => $validated['veterinarian_id'],
                'status' => \App\Models\AppointmentRequest::STATUS_PENDING,
                'requested_datetime' => Carbon::parse($validated['date'] . ' ' . $validated['time']),
                'appointment_type' => $validated['appointment_type'],
                'description' => $validated['description'] ?? null,
            ]);
            
            // Si es una petición AJAX, devolver JSON
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Solicitud de cita enviada exitosamente para ' . $pet->nombre . '. El veterinario la revisará pronto.'
                ]);
            }
            
            return redirect()->route('dashboard.cliente.calendario.index')
                            ->with('success', 'Solicitud de cita enviada exitosamente para ' . $pet->nombre . '. El veterinario la revisará pronto.');
                            
        } catch (\Exception $e) {
            // Si es una petición AJAX, devolver JSON con error
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear la solicitud de cita: ' . $e->getMessage()
                ], 422);
            }
            
            return redirect()->back()
                            ->with('error', 'Error al crear la solicitud de cita: ' . $e->getMessage())
                            ->withInput();
        }
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
        
        return view('client.detalle-cita', compact('appointment'));
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
        
        return view('client.editar-cita', compact('appointment', 'pets', 'appointmentTypes'));
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
