<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AppointmentRequest;
use App\Models\Pet;
use App\Models\User;
use App\Models\Appointment;
use App\Services\AppointmentNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AppointmentRequestController extends Controller
{
    /**
     * Mostrar lista de solicitudes para veterinarios
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        if ($user->hasRole('veterinario')) {
            $requests = AppointmentRequest::with(['pet', 'client'])
                ->where('veterinarian_id', $user->id)
                ->when($request->status, function ($query, $status) {
                    return $query->where('status', $status);
                })
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        } elseif ($user->hasRole('cliente_qr')) {
            $requests = AppointmentRequest::with(['pet', 'veterinarian'])
                ->where('client_id', $user->id)
                ->when($request->status, function ($query, $status) {
                    return $query->where('status', $status);
                })
                ->orderBy('created_at', 'desc')
                ->paginate(15);
        } else {
            abort(403, 'No tienes permisos para ver las solicitudes');
        }

        return view('appointment-requests.index', compact('requests'));
    }

    /**
     * Mostrar formulario para crear solicitud
     */
    public function create()
    {
        $user = Auth::user();
        
        if (!$user->hasRole('cliente_qr')) {
            abort(403, 'Solo los clientes pueden crear solicitudes');
        }

        $pets = $user->pets;
        $veterinarians = User::role('veterinario')->get();

        return view('appointment-requests.create', compact('pets', 'veterinarians'));
    }

    /**
     * Crear nueva solicitud
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        if (!$user->hasRole('cliente_qr')) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Solo los clientes pueden crear solicitudes'
                ], 403);
            }
            abort(403, 'Solo los clientes pueden crear solicitudes');
        }

        try {
            $request->validate([
                'pet_id' => 'required|exists:pets,id',
                'veterinarian_id' => 'required|exists:users,id',
                'appointment_type' => 'required|in:consulta,vacunacion,cirugia,emergencia,chequeo',
                'requested_datetime' => 'required|date|after:now',
                'description' => 'nullable|string|max:1000',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        }

        // Verificar que la mascota pertenece al cliente
        $pet = Pet::where('id', $request->pet_id)
            ->where('user_id', $user->id)
            ->first();

        if (!$pet) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'La mascota no existe o no te pertenece'
                ], 404);
            }
            abort(404, 'La mascota no existe o no te pertenece');
        }

        // Verificar que el veterinario existe y tiene el rol correcto
        $veterinarian = User::role('veterinario')
            ->where('id', $request->veterinarian_id)
            ->first();

        if (!$veterinarian) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'El veterinario no existe o no tiene el rol correcto'
                ], 404);
            }
            abort(404, 'El veterinario no existe o no tiene el rol correcto');
        }

        try {
            $appointmentRequest = AppointmentRequest::create([
                'pet_id' => $request->pet_id,
                'client_id' => $user->id,
                'veterinarian_id' => $request->veterinarian_id,
                'status' => AppointmentRequest::STATUS_PENDING,
                'requested_datetime' => $request->requested_datetime,
                'appointment_type' => $request->appointment_type,
                'description' => $request->description,
            ]);

            // Enviar notificación al veterinario
            AppointmentNotificationService::notifyRequestCreated($appointmentRequest);
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear la solicitud: ' . $e->getMessage()
                ], 500);
            }
            throw $e;
        }

        // Si es una petición AJAX, devolver JSON
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Solicitud de cita enviada exitosamente para ' . $pet->nombre . '. El veterinario la revisará pronto.',
                'redirect_url' => route('appointment-requests.show', $appointmentRequest)
            ]);
        }

        $user = Auth::user();
        if ($user->hasRole('veterinario')) {
            return redirect()->route('appointment-requests.show', $appointmentRequest)
                ->with('success', 'Solicitud de cita enviada exitosamente');
        } else {
            return redirect()->route('appointment-requests.show', $appointmentRequest)
                ->with('success', 'Solicitud de cita enviada exitosamente');
        }
    }

    /**
     * Mostrar detalles de una solicitud
     */
    public function show(AppointmentRequest $appointmentRequest)
    {
        $user = Auth::user();
        
        // Verificar permisos
        if ($user->hasRole('veterinario') && $appointmentRequest->veterinarian_id !== $user->id) {
            abort(403, 'No tienes permisos para ver esta solicitud');
        }
        
        if ($user->hasRole('cliente_qr') && $appointmentRequest->client_id !== $user->id) {
            abort(403, 'No tienes permisos para ver esta solicitud');
        }

        $appointmentRequest->load(['pet', 'client', 'veterinarian', 'appointment']);

        // Usar la vista de citas para mantener compatibilidad
        return view('citas.show', compact('appointmentRequest'));
    }

    /**
     * Aceptar solicitud (solo veterinarios)
     */
    public function aceptar(Request $request, AppointmentRequest $appointmentRequest)
    {
        $user = Auth::user();
        
        if (!$user->hasRole('veterinario') || $appointmentRequest->veterinarian_id !== $user->id) {
            abort(403, 'No tienes permisos para aceptar esta solicitud');
        }

        $request->validate([
            'scheduled_datetime' => 'required|date|after:now',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Convertir el datetime-local a formato correcto
        $scheduledDatetime = Carbon::parse($request->scheduled_datetime);

        try {
            DB::beginTransaction();

            // Aceptar la solicitud y crear la cita
            $appointment = $appointmentRequest->accept(
                $scheduledDatetime,
                $request->notes
            );

            // Verificar si ya existe una asignación activa
            $existingAssignment = \App\Models\MascotaVeterinario::where('mascota_id', $appointmentRequest->pet_id)
                ->where('veterinario_id', $appointmentRequest->veterinarian_id)
                ->where('activo', true)
                ->first();

            if (!$existingAssignment) {
                // Solo crear asignación si no existe una activa
                $assignment = \App\Models\MascotaVeterinario::create([
                    'mascota_id' => $appointmentRequest->pet_id,
                    'veterinario_id' => $appointmentRequest->veterinarian_id,
                    'fecha_asignacion' => now(),
                    'activo' => true,
                    'tipo_asignacion' => \App\Models\MascotaVeterinario::TIPO_LICENCIADO,
                    'notas' => 'Asignación temporal por cita - ' . $appointmentRequest->getAppointmentTypeLabelAttribute()
                ]);
            }

            // Enviar notificación al cliente
            AppointmentNotificationService::notifyRequestAccepted($appointmentRequest);

            DB::commit();

            // Si es una petición AJAX, devolver JSON
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Solicitud aceptada y cita creada exitosamente'
                ]);
            }

            return redirect()->route('appointment-requests.show', $appointmentRequest)
                ->with('success', 'Solicitud aceptada y cita creada exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Si es una petición AJAX, devolver JSON con error
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al aceptar la solicitud: ' . $e->getMessage()
                ], 422);
            }
            
            return back()->with('error', 'Error al aceptar la solicitud: ' . $e->getMessage());
        }
    }

    /**
     * Rechazar solicitud (solo veterinarios)
     */
    public function rechazar(Request $request, AppointmentRequest $appointmentRequest)
    {
        $user = Auth::user();
        
        if (!$user->hasRole('veterinario') || $appointmentRequest->veterinarian_id !== $user->id) {
            abort(403, 'No tienes permisos para rechazar esta solicitud');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        try {
            $appointmentRequest->reject($request->rejection_reason);

            // Enviar notificación al cliente
            AppointmentNotificationService::notifyRequestRejected($appointmentRequest, $request->rejection_reason);

            return redirect()->route('appointment-requests.show', $appointmentRequest)
                ->with('success', 'Solicitud rechazada exitosamente');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al rechazar la solicitud: ' . $e->getMessage());
        }
    }

    /**
     * Marcar cita como terminada (solo veterinarios)
     */
    public function complete(Request $request, AppointmentRequest $appointmentRequest)
    {
        $user = Auth::user();
        
        if (!$user->hasRole('veterinario') || $appointmentRequest->veterinarian_id !== $user->id) {
            abort(403, 'No tienes permisos para completar esta solicitud');
        }

        try {
            DB::beginTransaction();

            // Completar la solicitud
            $appointmentRequest->complete();

            // Desactivar la asignación temporal de la mascota
            $assignment = $appointmentRequest->getTemporaryAssignment();
            if ($assignment) {
                $assignment->update(['activo' => false]);
            }

            DB::commit();

            return redirect()->route('appointment-requests.show', $appointmentRequest)
                ->with('success', 'Cita marcada como terminada y mascota desasignada');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al completar la cita: ' . $e->getMessage());
        }
    }

    /**
     * Cancelar solicitud
     */
    public function cancel(Request $request, AppointmentRequest $appointmentRequest)
    {
        $user = Auth::user();
        
        // Verificar permisos
        if ($user->hasRole('veterinario') && $appointmentRequest->veterinarian_id !== $user->id) {
            abort(403, 'No tienes permisos para cancelar esta solicitud');
        }
        
        if ($user->hasRole('cliente_qr') && $appointmentRequest->client_id !== $user->id) {
            abort(403, 'No tienes permisos para cancelar esta solicitud');
        }

        $request->validate([
            'cancellation_reason' => 'required|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            // Cancelar la solicitud
            $appointmentRequest->cancel($request->cancellation_reason);

            // Desactivar la asignación temporal si existe
            $assignment = $appointmentRequest->getTemporaryAssignment();
            if ($assignment) {
                $assignment->update(['activo' => false]);
            }

            DB::commit();

            return redirect()->route('appointment-requests.show', $appointmentRequest)
                ->with('success', 'Solicitud cancelada exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al cancelar la solicitud: ' . $e->getMessage());
        }
    }

    /**
     * Reagendar cita
     */
    public function reschedule(Request $request, AppointmentRequest $appointmentRequest)
    {
        $user = Auth::user();
        
        if (!$user->hasRole('veterinario') || $appointmentRequest->veterinarian_id !== $user->id) {
            abort(403, 'No tienes permisos para reagendar esta solicitud');
        }

        $request->validate([
            'new_datetime' => 'required|date|after:now',
            'reschedule_reason' => 'required|string|max:1000',
        ]);

        try {
            $appointmentRequest->reschedule($request->new_datetime, $request->reschedule_reason);

            return redirect()->route('appointment-requests.show', $appointmentRequest)
                ->with('success', 'Cita reagendada exitosamente');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al reagendar la cita: ' . $e->getMessage());
        }
    }

    /**
     * Obtener veterinarios disponibles para una mascota
     */
    public function getVeterinariosDisponibles(Request $request)
    {
        $petId = $request->pet_id;
        
        // Obtener veterinarios que no tienen asignaciones activas con esta mascota
        $veterinarians = User::role('veterinario')
            ->whereDoesntHave('mascotaVeterinarios', function ($query) use ($petId) {
                $query->where('mascota_id', $petId)
                      ->where('activo', true);
            })
            ->get(['id', 'name', 'email']);

        return response()->json($veterinarians);
    }

    /**
     * Obtener nombres técnicos de vacunas
     */
    public function getNombresTecnicos(Request $request)
    {
        $especie = $request->especie;
        
        // Aquí deberías implementar la lógica para obtener los nombres técnicos
        // basados en la especie de la mascota
        $nombresTecnicos = [
            'canino' => ['Rabia', 'Parvovirus', 'Moquillo', 'Hepatitis'],
            'felino' => ['Rabia', 'Panleucopenia', 'Rinotraqueitis', 'Calicivirus'],
        ];

        return response()->json($nombresTecnicos[$especie] ?? []);
    }

    /**
     * Obtener nombres comerciales de vacunas
     */
    public function getNombresComerciales(Request $request)
    {
        $nombreTecnico = $request->nombre_tecnico;
        $especie = $request->especie;
        
        // Aquí deberías implementar la lógica para obtener los nombres comerciales
        // basados en el nombre técnico y la especie
        $nombresComerciales = [
            'Rabia' => ['Rabvac', 'Rabisin', 'Defensor'],
            'Parvovirus' => ['Nobivac', 'Eurican', 'Vanguard'],
            // Agregar más según sea necesario
        ];

        return response()->json($nombresComerciales[$nombreTecnico] ?? []);
    }

    /**
     * Mostrar formulario para editar una solicitud
     */
    public function edit(AppointmentRequest $appointmentRequest)
    {
        $user = Auth::user();
        
        // Verificar permisos
        if ($user->hasRole('veterinario') && $appointmentRequest->veterinarian_id !== $user->id) {
            abort(403, 'No tienes permisos para editar esta solicitud');
        }
        
        if ($user->hasRole('cliente_qr') && $appointmentRequest->client_id !== $user->id) {
            abort(403, 'No tienes permisos para editar esta solicitud');
        }

        $appointmentRequest->load(['pet', 'client', 'veterinarian', 'appointment']);
        
        // Si la solicitud tiene una cita asociada, verificar que existe y redirigir
        if ($appointmentRequest->appointment) {
            // Verificar que el Appointment existe
            $appointment = \App\Models\Appointment::find($appointmentRequest->appointment->id);
            if ($appointment) {
                \Log::info('Redirigiendo a calendario con ID: ' . $appointment->id);
                return redirect()->route('dashboard.veterinario.calendario.edit', $appointment->id);
            } else {
                \Log::info('Appointment ID ' . $appointmentRequest->appointment->id . ' no existe en la base de datos');
            }
        }
        
        \Log::info('AppointmentRequest ID: ' . $appointmentRequest->id . ' - Editando solicitud directamente');
        
        // Si no tiene cita asociada, mostrar la vista de edición de solicitud
        $appointmentTypes = Appointment::getTypeOptions();
        $assignedPets = [];
        
        if ($user->hasRole('veterinario')) {
            $assignedPets = $user->mascotasActivas()->with('user')->get();
        }
        
        // Usar appointment en lugar de appointmentRequest para compatibilidad con la vista
        $appointment = $appointmentRequest;

        return view('veterinarian.editar-cita', compact('appointment', 'assignedPets', 'appointmentTypes'));
    }

    /**
     * Actualizar una solicitud
     */
    public function update(Request $request, AppointmentRequest $appointmentRequest)
    {
        $user = Auth::user();
        
        // Verificar permisos
        if ($user->hasRole('veterinario') && $appointmentRequest->veterinarian_id !== $user->id) {
            abort(403, 'No tienes permisos para actualizar esta solicitud');
        }
        
        if ($user->hasRole('cliente_qr') && $appointmentRequest->client_id !== $user->id) {
            abort(403, 'No tienes permisos para actualizar esta solicitud');
        }

        $request->validate([
            'appointment_type' => 'required|in:consulta,vacunacion,cirugia,emergencia,chequeo',
            'requested_datetime' => 'required|date|after:now',
            'description' => 'nullable|string|max:1000',
        ]);

        try {
            $appointmentRequest->update([
                'appointment_type' => $request->appointment_type,
                'requested_datetime' => $request->requested_datetime,
                'description' => $request->description,
            ]);

            return redirect()->route('citas.show', $appointmentRequest)
                ->with('success', 'Solicitud actualizada exitosamente');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar la solicitud: ' . $e->getMessage());
        }
    }

    /**
     * Finalizar cita (alias para complete)
     */
    public function finalizar(Request $request, AppointmentRequest $appointmentRequest)
    {
        return $this->complete($request, $appointmentRequest);
    }

    /**
     * Cancelar cita (alias para cancel)
     */
    public function cancelar(Request $request, AppointmentRequest $appointmentRequest)
    {
        return $this->cancel($request, $appointmentRequest);
    }
}