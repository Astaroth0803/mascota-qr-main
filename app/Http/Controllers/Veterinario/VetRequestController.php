<?php

namespace App\Http\Controllers\Veterinario;

use App\Http\Controllers\Controller;
use App\Models\MascotaVeterinario;
use App\Models\Appointment;
use App\Models\AppointmentRequest;
use App\Http\Requests\VetRequestRejectRequest;
use App\Http\Requests\AppointmentAcceptRequest;
use App\Http\Requests\AppointmentRejectRequest;
use App\Events\VetRequestAccepted;
use App\Events\VetRequestRejected;
use App\Events\AppointmentAccepted;
use App\Events\AppointmentRejected;
use App\Events\VetRequestReceived;
use App\Events\AppointmentRequestReceived;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class VetRequestController extends Controller
{
    use AuthorizesRequests;
    /**
     * Mostrar solicitudes pendientes del veterinario
     */
    public function index()
    {
        $veterinario = Auth::user();
        
        // Obtener solicitudes de citas pendientes
        $solicitudesCitas = AppointmentRequest::where('veterinarian_id', $veterinario->id)
            ->where('status', 'pendiente')
            ->with(['pet.user', 'client'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Obtener solicitudes de asignación de mascotas pendientes
        $solicitudesMascotas = MascotaVeterinario::where('veterinario_id', $veterinario->id)
            ->where('activo', false) // Solicitudes pendientes
            ->where(function($query) {
                // Solo mostrar solicitudes que NO han sido procesadas
                $query->whereNull('notas')
                      ->orWhere(function($subQuery) {
                          $subQuery->whereNotNull('notas')
                                   ->where('notas', 'not like', '%[Aceptada por el veterinario%')
                                   ->where('notas', 'not like', '%[Rechazada por el veterinario%')
                                   ->where('notas', 'not like', '%[Desasignado por el cliente%');
                      });
            })
            ->with(['mascota.user', 'veterinario'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('veterinarian.solicitudes.index', compact('solicitudesCitas', 'solicitudesMascotas'));
    }
    
    /**
     * Mostrar detalles de una solicitud específica
     */
    public function show(MascotaVeterinario $solicitud)
    {
        $this->authorize('access', $solicitud);
        
        $solicitud->load(['mascota.user', 'veterinario']);
        
        return view('veterinarian.solicitudes.show', compact('solicitud'));
    }
    
    /**
     * Aceptar una solicitud
     */
    public function aceptar(Request $request, MascotaVeterinario $solicitud)
    {
        $this->authorize('accept', $solicitud);
        
        // Verificar que la solicitud no ha sido procesada
        if ($solicitud->activo) {
            return $request->ajax() || $request->wantsJson()
                ? response()->json(['success' => false, 'message' => 'Esta solicitud ya ha sido aceptada.'], 422)
                : redirect()->back()->with('error', 'Esta solicitud ya ha sido aceptada.');
        }
        
        // Actualizar la solicitud
        $solicitud->update([
            'activo' => true,
            'notas' => trim(($solicitud->notas ?? '') . "\n[Aceptada por el veterinario el " . now()->format('d/m/Y H:i') . "]")
        ]);
        
        // Disparar evento
        event(new VetRequestAccepted($solicitud));
        
        // Respuesta adaptable
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Solicitud aceptada exitosamente. El cliente ha sido notificado.'
            ]);
        }
        
        return redirect()->back()->with('success', 'Solicitud aceptada exitosamente. El cliente ha sido notificado.');
    }
    
    /**
     * Rechazar una solicitud
     */
    public function rechazar(VetRequestRejectRequest $request, MascotaVeterinario $solicitud)
    {
        $this->authorize('reject', $solicitud);
        
        // Verificar que la solicitud no ha sido procesada
        if ($solicitud->activo) {
            return $request->ajax() || $request->wantsJson()
                ? response()->json(['success' => false, 'message' => 'Esta solicitud ya ha sido aceptada y no puede ser rechazada.'], 422)
                : redirect()->back()->with('error', 'Esta solicitud ya ha sido aceptada y no puede ser rechazada.');
        }
        
        // Verificar si ya fue rechazada
        if (strpos($solicitud->notas ?? '', '[Rechazada por el veterinario') !== false) {
            return $request->ajax() || $request->wantsJson()
                ? response()->json(['success' => false, 'message' => 'Esta solicitud ya ha sido rechazada.'], 422)
                : redirect()->back()->with('error', 'Esta solicitud ya ha sido rechazada.');
        }
        
        // Actualizar la solicitud
        $solicitud->update([
            'activo' => false,
            'notas' => trim(($solicitud->notas ?? '') . "\n[Rechazada por el veterinario el " . now()->format('d/m/Y H:i') . ($request->motivo ? " - Motivo: {$request->motivo}" : '') . "]")
        ]);
        
        // Disparar evento
        event(new VetRequestRejected($solicitud, $request->motivo));
        
        // Respuesta adaptable
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Solicitud rechazada exitosamente. El cliente ha sido notificado.'
            ]);
        }
        
        return redirect()->back()->with('success', 'Solicitud rechazada exitosamente. El cliente ha sido notificado.');
    }
    
    /**
     * Obtener solicitudes pendientes (para AJAX)
     */
    public function pendientes()
    {
        $veterinario = Auth::user();
        
        $solicitudes = MascotaVeterinario::where('veterinario_id', $veterinario->id)
            ->where('activo', true)
            ->with(['mascota.user'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        return response()->json($solicitudes);
    }
    
    /**
     * Aceptar una cita pendiente
     */
    public function aceptarCita(AppointmentAcceptRequest $request, Appointment $cita)
    {
        $this->authorize('accept', $cita);
        
        // Verificar que la cita está en estado pendiente
        if (!$cita->canBeScheduled()) {
            return $request->ajax() || $request->wantsJson()
                ? response()->json(['success' => false, 'message' => 'Esta cita no puede ser agendada.'], 422)
                : redirect()->back()->withErrors(['error' => 'Esta cita no puede ser agendada.']);
        }
        
        // Verificar disponibilidad del veterinario
        if ($this->isVeterinarianBusy(Auth::id(), $request->scheduled_datetime, $cita->id)) {
            return $request->ajax() || $request->wantsJson()
                ? response()->json(['success' => false, 'message' => 'No estás disponible en esa fecha y hora.'], 422)
                : redirect()->back()->withErrors(['scheduled_datetime' => 'No estás disponible en esa fecha y hora.']);
        }
        
        // Actualizar la cita
        $cita->update([
            'status' => Appointment::STATUS_SCHEDULED,
            'scheduled_datetime' => $request->scheduled_datetime,
            'location' => $request->location ?? Auth::user()->ubicacion,
        ]);
        
        // Disparar evento
        event(new AppointmentAccepted($cita, $request->scheduled_datetime, $request->location));
        
        // Respuesta adaptable
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Cita agendada exitosamente.'
            ]);
        }
        
        return redirect()->route('dashboard.veterinario.solicitudes.index')->with('success', 'Cita agendada exitosamente.');
    }
    
    /**
     * Rechazar una cita pendiente
     */
    public function rechazarCita(AppointmentRejectRequest $request, Appointment $cita)
    {
        $this->authorize('reject', $cita);
        
        // Verificar que la cita puede ser cancelada
        if (!$cita->canBeCancelled()) {
            return $request->ajax() || $request->wantsJson()
                ? response()->json(['success' => false, 'message' => 'Esta cita no puede ser cancelada.'], 422)
                : redirect()->back()->withErrors(['error' => 'Esta cita no puede ser cancelada.']);
        }
        
        // Actualizar la cita
        $cita->update([
            'status' => Appointment::STATUS_CANCELLED,
            'cancellation_reason' => $request->cancellation_reason,
            'cancelled_at' => now(),
        ]);
        
        // Disparar evento
        event(new AppointmentRejected($cita, $request->cancellation_reason));
        
        // Respuesta adaptable
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Cita rechazada.'
            ]);
        }
        
        return redirect()->route('dashboard.veterinario.solicitudes.index')->with('success', 'Cita rechazada.');
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
}