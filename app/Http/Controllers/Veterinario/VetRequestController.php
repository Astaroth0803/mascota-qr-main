<?php

namespace App\Http\Controllers\Veterinario;

use App\Http\Controllers\Controller;
use App\Models\MascotaVeterinario;
use App\Models\VetRequestNotification;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VetRequestController extends Controller
{
    /**
     * Mostrar solicitudes pendientes del veterinario
     */
    public function index()
    {
        $veterinario = Auth::user();
        
        // Obtener solicitudes pendientes (no activas y no procesadas)
        $solicitudes = MascotaVeterinario::where('veterinario_id', $veterinario->id)
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
        
        // Obtener citas pendientes (nuevas solicitudes de citas)
        $citasPendientes = Appointment::where('veterinarian_id', $veterinario->id)
            ->where('status', Appointment::STATUS_PENDING)
            ->with(['pet.user', 'client'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('dashboard.veterinario.solicitudes.index', compact('solicitudes', 'citasPendientes'));
    }
    
    /**
     * Mostrar detalles de una solicitud específica
     */
    public function show(MascotaVeterinario $solicitud)
    {
        // Verificar que la solicitud pertenece al veterinario autenticado
        if ($solicitud->veterinario_id !== Auth::id()) {
            abort(403);
        }
        
        $solicitud->load(['mascota.user', 'veterinario']);
        
        return view('dashboard.veterinario.solicitudes.show', compact('solicitud'));
    }
    
    /**
     * Aceptar una solicitud
     */
    public function aceptar(Request $request, MascotaVeterinario $solicitud)
    {
        // Verificar que la solicitud pertenece al veterinario autenticado
        if ($solicitud->veterinario_id !== Auth::id()) {
            abort(403);
        }
        
        // Verificar que la solicitud no ha sido procesada (si ya está activa, significa que ya fue aceptada)
        if ($solicitud->activo) {
            return redirect()->back()->with('error', 'Esta solicitud ya ha sido aceptada.');
        }
        
        // Actualizar la solicitud (ACTIVAR al aceptarla)
        $solicitud->update([
            'activo' => true, // ACTIVAR la asignación al aceptarla
            'notas' => ($solicitud->notas ?? '') . "\n[Aceptada por el veterinario el " . now()->format('d/m/Y H:i') . "]"
        ]);
        
        // Crear notificación para el cliente
        VetRequestNotification::create([
            'veterinario_id' => $solicitud->veterinario_id,
            'cliente_id' => $solicitud->mascota->user_id,
            'mascota_id' => $solicitud->mascota_id,
            'asignacion_id' => $solicitud->id,
            'tipo' => 'aceptada',
            'mensaje' => "El veterinario {$solicitud->veterinario->name} ha aceptado atender a {$solicitud->mascota->nombre} como " . ucfirst($solicitud->tipo_asignacion) . "."
        ]);
        
        // Si es una petición AJAX, devolver JSON
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
    public function rechazar(Request $request, MascotaVeterinario $solicitud)
    {
        $request->validate([
            'motivo' => 'nullable|string|max:500'
        ]);
        
        // Verificar que la solicitud pertenece al veterinario autenticado
        if ($solicitud->veterinario_id !== Auth::id()) {
            abort(403);
        }
        
        // Verificar que la solicitud no ha sido procesada (si ya está activa, fue aceptada; si fue rechazada, tendría notas de rechazo)
        if ($solicitud->activo) {
            return redirect()->back()->with('error', 'Esta solicitud ya ha sido aceptada y no puede ser rechazada.');
        }
        
        // Verificar si ya fue rechazada
        if (strpos($solicitud->notas ?? '', '[Rechazada por el veterinario') !== false) {
            return redirect()->back()->with('error', 'Esta solicitud ya ha sido rechazada.');
        }
        
        // Marcar como rechazada (mantener activo = false y agregar nota de rechazo)
        $solicitud->update([
            'activo' => false, // Mantener como false (rechazada)
            'notas' => ($solicitud->notas ?? '') . "\n[Rechazada por el veterinario el " . now()->format('d/m/Y H:i') . ($request->motivo ? " - Motivo: {$request->motivo}" : '') . "]"
        ]);
        
        // Crear notificación para el cliente
        VetRequestNotification::create([
            'veterinario_id' => $solicitud->veterinario_id,
            'cliente_id' => $solicitud->mascota->user_id,
            'mascota_id' => $solicitud->mascota_id,
            'asignacion_id' => $solicitud->id,
            'tipo' => 'rechazada',
            'mensaje' => "El veterinario {$solicitud->veterinario->name} ha rechazado atender a {$solicitud->mascota->nombre} como " . ucfirst($solicitud->tipo_asignacion) . "." . ($request->motivo ? " Motivo: {$request->motivo}" : '')
        ]);
        
        // Si es una petición AJAX, devolver JSON
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
    public function aceptarCita(Request $request, Appointment $cita)
    {
        // Verificar que la cita pertenece al veterinario autenticado
        if ($cita->veterinarian_id !== Auth::id()) {
            abort(403);
        }
        
        // Verificar que la cita está en estado pendiente
        if (!$cita->canBeScheduled()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Esta cita no puede ser agendada.'
                ], 422);
            }
            return redirect()->back()->withErrors(['error' => 'Esta cita no puede ser agendada.']);
        }
        
        $request->validate([
            'scheduled_datetime' => 'required|date|after:now',
            'location' => 'nullable|string|max:255',
        ]);
        
        // Verificar disponibilidad del veterinario
        if ($this->isVeterinarianBusy(Auth::id(), $request->scheduled_datetime, $cita->id)) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No estás disponible en esa fecha y hora.'
                ], 422);
            }
            return redirect()->back()->withErrors(['scheduled_datetime' => 'No estás disponible en esa fecha y hora.']);
        }
        
        $cita->update([
            'status' => Appointment::STATUS_SCHEDULED,
            'scheduled_datetime' => $request->scheduled_datetime,
            'location' => $request->location ?? Auth::user()->ubicacion,
        ]);
        
        // Si es una petición AJAX, devolver JSON
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
    public function rechazarCita(Request $request, Appointment $cita)
    {
        // Verificar que la cita pertenece al veterinario autenticado
        if ($cita->veterinarian_id !== Auth::id()) {
            abort(403);
        }
        
        // Verificar que la cita puede ser cancelada
        if (!$cita->canBeCancelled()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Esta cita no puede ser cancelada.'
                ], 422);
            }
            return redirect()->back()->withErrors(['error' => 'Esta cita no puede ser cancelada.']);
        }
        
        $request->validate([
            'cancellation_reason' => 'required|string|max:500',
        ]);
        
        $cita->update([
            'status' => Appointment::STATUS_CANCELLED,
            'cancellation_reason' => $request->cancellation_reason,
            'cancelled_at' => now(),
        ]);
        
        // Si es una petición AJAX, devolver JSON
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