<?php

namespace App\Http\Controllers\Appointment;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    /**
     * Display a listing of appointments.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        if ($user->hasRole('veterinario')) {
            $appointments = Appointment::byVeterinarian($user->id)
                ->with(['pet', 'client'])
                ->orderBy('scheduled_datetime', 'desc')
                ->paginate(15);
                
            return view('veterinarian.appointments.index', compact('appointments'));
        } else {
            $appointments = Appointment::where('client_id', $user->id)
                ->with(['pet', 'veterinarian'])
                ->orderBy('scheduled_datetime', 'desc')
                ->paginate(15);
                
            return view('client.appointments.index', compact('appointments'));
        }
    }

    /**
     * Show the form for creating a new appointment.
     */
    public function create()
    {
        $user = Auth::user();
        
        if ($user->hasRole('veterinario')) {
            // Veterinario puede crear citas para mascotas asignadas
            $assignedPets = $user->mascotasAsignadas()->get();
            $recordTypes = $this->getRecordTypes();
            
            return view('veterinarian.appointments.create', compact('assignedPets', 'recordTypes'));
        } else {
            // Cliente puede crear citas para sus mascotas
            $pets = $user->pets()->get();
            $veterinarians = User::whereHas('roles', function($query) {
                $query->where('name', 'veterinario');
            })->get();
            $recordTypes = $this->getRecordTypes();
            
            return view('client.appointments.create', compact('pets', 'veterinarians', 'recordTypes'));
        }
    }

    /**
     * Store a newly created appointment.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        $rules = [
            'pet_id' => 'required|exists:pets,id',
            'record_type' => 'required|in:consulta,vacuna,operacion,emergencia,checkeo',
            'requested_datetime' => 'required|date|after:now',
        ];
        
        if ($user->hasRole('veterinario')) {
            // Veterinario puede agendar directamente
            $rules['scheduled_datetime'] = 'required|date|after:now';
        } else {
            // Cliente necesita especificar veterinario
            $rules['veterinarian_id'] = 'required|exists:users,id';
        }
        
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Verificar disponibilidad del veterinario
        if ($request->has('scheduled_datetime')) {
            $veterinarianId = $user->hasRole('veterinario') ? $user->id : $request->veterinarian_id;
            if ($this->isVeterinarianBusy($veterinarianId, $request->scheduled_datetime)) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'El veterinario no está disponible en esa fecha y hora.'
                    ], 422);
                }
                return redirect()->back()
                    ->withErrors(['scheduled_datetime' => 'El veterinario no está disponible en esa fecha y hora.'])
                    ->withInput();
            }
        }
        
        $appointmentData = [
            'pet_id' => $request->pet_id,
            'record_type' => $request->record_type,
            'requested_datetime' => $request->requested_datetime,
        ];
        
        if ($user->hasRole('veterinario')) {
            // Veterinario crea cita directamente agendada
            $appointmentData['veterinarian_id'] = $user->id;
            $appointmentData['client_id'] = Pet::find($request->pet_id)->user_id;
            $appointmentData['scheduled_datetime'] = $request->scheduled_datetime;
            $appointmentData['location'] = $request->location ?? $user->ubicacion;
            $appointmentData['status'] = Appointment::STATUS_SCHEDULED;
        } else {
            // Cliente solicita cita (estado pendiente)
            $appointmentData['veterinarian_id'] = $request->veterinarian_id;
            $appointmentData['client_id'] = $user->id;
            $appointmentData['status'] = Appointment::STATUS_PENDING;
        }
        
        $appointment = Appointment::create($appointmentData);
        
        $message = $user->hasRole('veterinario') 
            ? 'Cita agendada exitosamente.' 
            : 'Solicitud de cita enviada. El veterinario la revisará pronto.';
        
        // Si es una petición AJAX, devolver JSON
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        }
            
        return redirect()->route('appointments.index')->with('success', $message);
    }

    /**
     * Display the specified appointment.
     */
    public function show(Appointment $appointment)
    {
        $this->authorize('view', $appointment);
        
        return view('dashboard.appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified appointment.
     */
    public function edit(Appointment $appointment)
    {
        $this->authorize('update', $appointment);
        
        $recordTypes = $this->getRecordTypes();
        $vaccineTypes = $this->getVaccineTypes();
        
        return view('dashboard.appointments.edit', compact('appointment', 'recordTypes', 'vaccineTypes'));
    }

    /**
     * Update the specified appointment.
     */
    public function update(Request $request, Appointment $appointment)
    {
        $this->authorize('update', $appointment);
        
        $rules = [
            'status' => 'required|in:pendiente,agendada,en_progreso,finalizada,cancelada',
        ];
        
        // Validaciones específicas por estado
        if ($request->status === Appointment::STATUS_SCHEDULED) {
            $rules['scheduled_datetime'] = 'required|date|after:now';
            $rules['location'] = 'required|string|max:255';
        }
        
        if ($request->status === Appointment::STATUS_IN_PROGRESS) {
            $rules['diagnosis_treatment'] = 'nullable|string';
            $rules['observations'] = 'nullable|string';
            
            // Si es vacuna, validar campos específicos
            if ($appointment->record_type === Appointment::TYPE_VACCINE) {
                $rules['vaccine_type'] = 'nullable|string';
                $rules['vaccine_name'] = 'nullable|string';
                $rules['technical_name'] = 'nullable|string';
                $rules['laboratory'] = 'nullable|string';
                $rules['lot_number'] = 'nullable|string';
                $rules['creation_date'] = 'nullable|date';
                $rules['expiry_date'] = 'nullable|date|after:creation_date';
            }
        }
        
        if ($request->status === Appointment::STATUS_CANCELLED) {
            $rules['cancellation_reason'] = 'required|string|max:500';
        }
        
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Validar flujo de estados
        if (!$this->canTransitionTo($appointment, $request->status)) {
            return redirect()->back()
                ->withErrors(['status' => 'Transición de estado no válida.'])
                ->withInput();
        }
        
        // Verificar disponibilidad si se está agendando
        if ($request->status === Appointment::STATUS_SCHEDULED && $request->scheduled_datetime) {
            if ($this->isVeterinarianBusy($appointment->veterinarian_id, $request->scheduled_datetime, $appointment->id)) {
                return redirect()->back()
                    ->withErrors(['scheduled_datetime' => 'El veterinario no está disponible en esa fecha y hora.'])
                    ->withInput();
            }
        }
        
        $updateData = ['status' => $request->status];
        
        // Actualizar campos específicos según el estado
        if ($request->status === Appointment::STATUS_SCHEDULED) {
            $updateData['scheduled_datetime'] = $request->scheduled_datetime;
            $updateData['location'] = $request->location;
        }
        
        if ($request->status === Appointment::STATUS_IN_PROGRESS) {
            $updateData['diagnosis_treatment'] = $request->diagnosis_treatment;
            $updateData['observations'] = $request->observations;
            
            if ($appointment->record_type === Appointment::TYPE_VACCINE) {
                $updateData['vaccine_type'] = $request->vaccine_type;
                $updateData['vaccine_name'] = $request->vaccine_name;
                $updateData['technical_name'] = $request->technical_name;
                $updateData['laboratory'] = $request->laboratory;
                $updateData['lot_number'] = $request->lot_number;
                $updateData['creation_date'] = $request->creation_date;
                $updateData['expiry_date'] = $request->expiry_date;
            }
        }
        
        if ($request->status === Appointment::STATUS_CANCELLED) {
            $updateData['cancellation_reason'] = $request->cancellation_reason;
            $updateData['cancelled_at'] = now();
        }
        
        $appointment->update($updateData);
        
        $message = match($request->status) {
            Appointment::STATUS_SCHEDULED => 'Cita agendada exitosamente.',
            Appointment::STATUS_IN_PROGRESS => 'Cita iniciada. Puede completar los campos médicos.',
            Appointment::STATUS_COMPLETED => 'Cita finalizada exitosamente.',
            Appointment::STATUS_CANCELLED => 'Cita cancelada.',
            default => 'Cita actualizada.'
        };
        
        return redirect()->route('appointments.show', $appointment)->with('success', $message);
    }

    /**
     * Remove the specified appointment.
     */
    public function destroy(Appointment $appointment)
    {
        $this->authorize('delete', $appointment);
        
        $appointment->delete();
        
        return redirect()->route('appointments.index')->with('success', 'Cita eliminada exitosamente.');
    }

    /**
     * Verificar si el veterinario está ocupado en una fecha/hora específica
     */
    private function isVeterinarianBusy($veterinarianId, $datetime, $excludeAppointmentId = null)
    {
        $query = Appointment::where('veterinarian_id', $veterinarianId)
            ->where('scheduled_datetime', $datetime)
            ->whereIn('status', [Appointment::STATUS_SCHEDULED, Appointment::STATUS_IN_PROGRESS]);
            
        if ($excludeAppointmentId) {
            $query->where('id', '!=', $excludeAppointmentId);
        }
        
        return $query->exists();
    }

    /**
     * Validar si se puede hacer transición a un estado
     */
    private function canTransitionTo(Appointment $appointment, $newStatus)
    {
        return match($newStatus) {
            Appointment::STATUS_SCHEDULED => $appointment->canBeScheduled(),
            Appointment::STATUS_IN_PROGRESS => $appointment->canBeInProgress(),
            Appointment::STATUS_COMPLETED => $appointment->canBeCompleted(),
            Appointment::STATUS_CANCELLED => $appointment->canBeCancelled(),
            default => false
        };
    }

    /**
     * Obtener tipos de registro disponibles
     */
    private function getRecordTypes()
    {
        return [
            Appointment::TYPE_VACCINE => 'Vacunación',
            Appointment::TYPE_OPERATION => 'Operación',
            Appointment::TYPE_EMERGENCY => 'Emergencia',
            Appointment::TYPE_CHECKUP => 'Chequeo General',
        ];
    }

    /**
     * Obtener tipos de vacunas estándar
     */
    private function getVaccineTypes()
    {
        return [
            'Vacuna Múltiple (DHPP)' => [
                'technical_name' => 'Vacuna Múltiple Canina DHPP',
                'laboratory' => 'Zoetis'
            ],
            'Vacuna Antirrábica' => [
                'technical_name' => 'Vacuna Antirrábica Inactivada',
                'laboratory' => 'Merck Animal Health'
            ],
            'Vacuna Triple Felina' => [
                'technical_name' => 'Vacuna Felina FVRCP',
                'laboratory' => 'Boehringer Ingelheim'
            ]
        ];
    }
}