<?php

namespace App\Http\Controllers\Veterinario;

use App\Http\Controllers\Controller;
use App\Models\AppointmentChangeRequest;
use App\Models\VaccinationRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentChangeRequestController extends Controller
{
    /**
     * Mostrar solicitudes de cambio de citas pendientes
     */
    public function index()
    {
        $veterinarianId = Auth::id();
        
        $pendingRequests = AppointmentChangeRequest::where('veterinarian_id', $veterinarianId)
            ->where('status', 'pending')
            ->with(['appointment.pet', 'client'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        $allRequests = AppointmentChangeRequest::where('veterinarian_id', $veterinarianId)
            ->with(['appointment.pet', 'client'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('veterinarian.appointment-change-requests.index', compact('pendingRequests', 'allRequests'));
    }
    
    /**
     * Mostrar detalles de una solicitud de cambio
     */
    public function show(AppointmentChangeRequest $changeRequest)
    {
        // Verificar que el veterinario tiene acceso a esta solicitud
        if ($changeRequest->veterinarian_id !== Auth::id()) {
            abort(403, 'No tienes acceso a esta solicitud');
        }
        
        $changeRequest->load(['appointment.pet', 'client', 'veterinarian']);
        
        return view('veterinarian.appointment-change-requests.show', compact('changeRequest'));
    }
    
    /**
     * Aprobar una solicitud de cambio de cita
     */
    public function approve(Request $request, AppointmentChangeRequest $changeRequest)
    {
        // Verificar que el veterinario tiene acceso a esta solicitud
        if ($changeRequest->veterinarian_id !== Auth::id()) {
            abort(403, 'No tienes acceso a esta solicitud');
        }
        
        if ($changeRequest->status !== 'pending') {
            return redirect()->back()->with('error', 'Esta solicitud ya ha sido procesada.');
        }
        
        $validated = $request->validate([
            'vet_notes' => 'nullable|string|max:500'
        ]);
        
        // Actualizar la cita original
        $changeRequest->appointment->update([
            'date' => $changeRequest->requested_date,
            'time' => $changeRequest->requested_time,
        ]);
        
        // Marcar la solicitud como aprobada
        $changeRequest->approve($validated['vet_notes']);
        
        return redirect()->route('dashboard.veterinario.appointment-change-requests.index')
                        ->with('success', 'Solicitud de cambio aprobada exitosamente.');
    }
    
    /**
     * Rechazar una solicitud de cambio de cita
     */
    public function reject(Request $request, AppointmentChangeRequest $changeRequest)
    {
        // Verificar que el veterinario tiene acceso a esta solicitud
        if ($changeRequest->veterinarian_id !== Auth::id()) {
            abort(403, 'No tienes acceso a esta solicitud');
        }
        
        if ($changeRequest->status !== 'pending') {
            return redirect()->back()->with('error', 'Esta solicitud ya ha sido procesada.');
        }
        
        $validated = $request->validate([
            'vet_notes' => 'required|string|max:500'
        ]);
        
        // Marcar la solicitud como rechazada
        $changeRequest->reject($validated['vet_notes']);
        
        return redirect()->route('dashboard.veterinario.appointment-change-requests.index')
                        ->with('success', 'Solicitud de cambio rechazada.');
    }
    
    /**
     * Obtener solicitudes pendientes (API)
     */
    public function pending()
    {
        $veterinarianId = Auth::id();
        
        $pendingRequests = AppointmentChangeRequest::where('veterinarian_id', $veterinarianId)
            ->where('status', 'pending')
            ->with(['appointment.pet', 'client'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($request) {
                return [
                    'id' => $request->id,
                    'appointment_id' => $request->appointment_id,
                    'pet_name' => $request->appointment->pet->nombre,
                    'client_name' => $request->client->name,
                    'current_date' => $request->appointment->date->format('d/m/Y'),
                    'current_time' => $request->appointment->time ? $request->appointment->time->format('H:i') : 'N/A',
                    'requested_date' => $request->requested_date->format('d/m/Y'),
                    'requested_time' => $request->requested_time ? \Carbon\Carbon::parse($request->requested_time)->format('H:i') : 'N/A',
                    'reason' => $request->reason,
                    'created_at' => $request->created_at->format('d/m/Y H:i'),
                    'url' => route('dashboard.veterinario.appointment-change-requests.show', $request->id)
                ];
            });
        
        return response()->json([
            'success' => true,
            'requests' => $pendingRequests
        ]);
    }
    
    /**
     * Obtener conteo de solicitudes pendientes (API)
     */
    public function pendingCount()
    {
        $veterinarianId = Auth::id();
        
        $count = AppointmentChangeRequest::where('veterinarian_id', $veterinarianId)
            ->where('status', 'pending')
            ->count();
        
        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    }
}