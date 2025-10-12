<?php

namespace App\Http\Controllers\Veterinario;

use App\Http\Controllers\Controller;
use App\Models\VetRequestNotification;
use App\Models\MascotaVeterinario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VetNotificationController extends Controller
{
    /**
     * Mostrar notificaciones del veterinario
     */
    public function index()
    {
        $veterinario = Auth::user();
        
        // Obtener notificaciones no leídas
        $notificaciones = $veterinario->notificacionesNoLeidasVeterinario()
            ->with(['cliente', 'mascota', 'asignacion'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        $notificacionesNoLeidas = $notificaciones->count();
        
        return view('veterinarian.notificaciones.index', compact('notificaciones', 'notificacionesNoLeidas'));
    }
    
    /**
     * Marcar notificación como leída
     */
    public function marcarLeida(VetRequestNotification $notificacion)
    {
        // Verificar que la notificación pertenece al veterinario autenticado
        if ($notificacion->veterinario_id !== Auth::id()) {
            abort(403);
        }
        
        $notificacion->marcarComoLeida();
        
        return redirect()->back()->with('success', 'Notificación marcada como leída.');
    }
    
    /**
     * Marcar todas las notificaciones como leídas
     */
    public function marcarTodasLeidas()
    {
        Auth::user()->notificacionesNoLeidasVeterinario()->update([
            'leida' => true,
            'leida_at' => now()
        ]);
        
        return redirect()->back()->with('success', 'Todas las notificaciones han sido marcadas como leídas.');
    }
    
    /**
     * Obtener notificaciones no leídas (para AJAX)
     */
    public function noLeidas()
    {
        $notificaciones = Auth::user()->notificacionesNoLeidasVeterinario()
            ->with(['cliente', 'mascota'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        return response()->json($notificaciones);
    }
    
    /**
     * Obtener conteo de notificaciones no leídas
     */
    public function conteoNoLeidas()
    {
        $conteo = Auth::user()->notificacionesNoLeidasVeterinario()->count();
        
        return response()->json(['conteo' => $conteo]);
    }
}