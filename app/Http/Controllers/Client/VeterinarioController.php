<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\MascotaVeterinario;
use App\Models\Pet;
use App\Models\VetRequestNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VeterinarioController extends Controller
{
    /**
     * Mostrar listado de veterinarios disponibles
     */
    public function index(Request $request)
    {
        // Verificar que el usuario esté autenticado
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para acceder a esta página.');
        }
        
        // Verificar que el usuario tenga el rol de cliente
        if (!Auth::user()->hasRole('cliente_qr')) {
            abort(403, 'No tienes permisos para acceder a esta página.');
        }
        
        $search = $request->input('search');
        $tipoVeterinario = $request->input('tipo_veterinario');
        
        // Obtener veterinarios con filtros
        $query = User::where('tipo_veterinario', '!=', null);
        
        // Aplicar filtros
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        // Solo aplicar filtro de tipo si no está vacío
        if ($tipoVeterinario && $tipoVeterinario !== '') {
            $query->where('tipo_veterinario', $tipoVeterinario);
        }
        
        $veterinarios = $query->paginate(12);
        
        
        // Si es una petición AJAX para obtener veterinarios (para el modal de cambio)
        if ($request->ajax() && $request->input('ajax') == 1) {
            return response()->json([
                'veterinarios' => $veterinarios->items()
            ]);
        }
        
        // Obtener tipos de veterinarios para el filtro
        $tiposVeterinarios = User::getTiposVeterinarios();
        
        // Obtener mascotas del cliente actual
        $mascotasCliente = Auth::user()->pets()->get();
        
        // Obtener veterinarios ya asignados a las mascotas del cliente
        $veterinariosAsignados = [];
        foreach ($mascotasCliente as $mascota) {
            $vetsActivos = $mascota->veterinariosActivos;
            foreach ($vetsActivos as $veterinario) {
                $veterinariosAsignados[] = $veterinario->id;
            }
        }
        
        return view('dashboard.cliente.veterinarios.index', compact(
            'veterinarios', 
            'tiposVeterinarios', 
            'mascotasCliente',
            'veterinariosAsignados'
        ));
    }
    
    /**
     * Solicitar veterinario para una mascota
     */
    public function solicitar(Request $request)
    {
        $request->validate([
            'veterinario_id' => 'required|exists:users,id',
            'mascota_id' => 'required|exists:pets,id',
            'tipo_asignacion' => 'required|in:auxiliar,tecnico,licenciado',
            'notas' => 'nullable|string|max:500'
        ]);
        
        // Verificar que la mascota pertenece al cliente
        $mascota = Pet::where('id', $request->mascota_id)
                     ->where('user_id', Auth::id())
                     ->firstOrFail();
        
        // Verificar que el veterinario existe y es veterinario
        $veterinario = User::whereHas('roles', function($query) {
            $query->where('name', 'veterinario');
        })->findOrFail($request->veterinario_id);
        
        // Verificar si ya existe una asignación activa
        $asignacionActiva = MascotaVeterinario::where('mascota_id', $mascota->id)
            ->where('veterinario_id', $veterinario->id)
            ->where('activo', true)
            ->first();
            
        if ($asignacionActiva) {
            $mensaje = "El Dr. {$veterinario->name} ya está asignado a {$mascota->nombre}.";
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $mensaje
                ], 422);
            }
            return redirect()->back()->withErrors(['error' => $mensaje]);
        }
        
        // Verificar si ya existe una solicitud pendiente (no procesada)
        $solicitudPendiente = MascotaVeterinario::where('mascota_id', $mascota->id)
            ->where('veterinario_id', $veterinario->id)
            ->where('activo', false)
            ->where(function($query) {
                $query->whereNull('notas')
                      ->orWhere(function($subQuery) {
                          $subQuery->whereNotNull('notas')
                                   ->where('notas', 'not like', '%[Aceptada por el veterinario%')
                                   ->where('notas', 'not like', '%[Rechazada por el veterinario%')
                                   ->where('notas', 'not like', '%[Desasignado por el cliente%');
                      });
            })
            ->first();
            
        if ($solicitudPendiente) {
            $mensaje = "Ya tienes una solicitud pendiente con el Dr. {$veterinario->name} para {$mascota->nombre}.";
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $mensaje
                ], 422);
            }
            return redirect()->back()->withErrors(['error' => $mensaje]);
        }
        
        // Crear la solicitud (asignación pendiente - NO activa hasta que sea aceptada)
        $asignacion = MascotaVeterinario::create([
            'mascota_id' => $mascota->id,
            'veterinario_id' => $veterinario->id,
            'fecha_asignacion' => now(),
            'activo' => false, // PENDIENTE hasta que el veterinario acepte
            'tipo_asignacion' => $request->tipo_asignacion,
            'notas' => $request->notas ?? 'Solicitud de cliente'
        ]);
        
        // Crear notificación para el veterinario
        VetRequestNotification::create([
            'veterinario_id' => $veterinario->id,
            'cliente_id' => Auth::id(),
            'mascota_id' => $mascota->id,
            'asignacion_id' => $asignacion->id,
            'tipo' => 'solicitud',
            'mensaje' => "El cliente {{ Auth::user()->name }} ha solicitado sus servicios para {{ $mascota->nombre }} como {{ ucfirst($request->tipo_asignacion) }}."
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Solicitud enviada exitosamente. El veterinario será notificado.'
        ]);
    }
    
    /**
     * Obtener información detallada de un veterinario
     */
    public function show(User $veterinario)
    {
        // Verificar que es veterinario
        if (!$veterinario->hasRole('veterinario')) {
            abort(404);
        }
        
        // Obtener mascotas asignadas
        $mascotasAsignadas = $veterinario->mascotasActivas()->with('user')->get();
        
        // Obtener estadísticas
        $stats = [
            'total_mascotas' => $mascotasAsignadas->count(),
            'mascotas_principales' => $mascotasAsignadas->where('pivot.tipo_asignacion', 'licenciado')->count(),
            'mascotas_tecnicas' => $mascotasAsignadas->where('pivot.tipo_asignacion', 'tecnico')->count(),
            'mascotas_auxiliares' => $mascotasAsignadas->where('pivot.tipo_asignacion', 'auxiliar')->count(),
        ];
        
        return view('dashboard.cliente.veterinarios.show', compact('veterinario', 'mascotasAsignadas', 'stats'));
    }

    /**
     * Mostrar veterinarios asignados a las mascotas del cliente
     */
    public function misVeterinarios()
    {
        $cliente = Auth::user();
        
        // Obtener todas las asignaciones activas del cliente
        $asignaciones = MascotaVeterinario::whereHas('mascota', function($query) use ($cliente) {
            $query->where('user_id', $cliente->id);
        })->where('activo', true)
          ->with(['mascota', 'veterinario'])
          ->orderBy('fecha_asignacion', 'desc')
          ->get();
        
        return view('dashboard.cliente.veterinarios.mis-veterinarios', compact('asignaciones'));
    }

    /**
     * Cambiar veterinario de una mascota
     */
    public function cambiarVeterinario(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'asignacion_id' => 'required|exists:mascota_veterinario,id',
            'nuevo_veterinario_id' => 'required|exists:users,id',
            'tipo_asignacion' => 'required|in:auxiliar,tecnico,licenciado',
            'notas' => 'nullable|string|max:500'
        ]);
        
        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        // Verificar que la asignación pertenece al cliente
        $asignacion = MascotaVeterinario::whereHas('mascota', function($query) {
            $query->where('user_id', Auth::id());
        })->findOrFail($request->asignacion_id);
        
        // Verificar que el nuevo veterinario existe y es veterinario
        $nuevoVeterinario = User::whereHas('roles', function($query) {
            $query->where('name', 'veterinario');
        })->findOrFail($request->nuevo_veterinario_id);
        
        // Verificar si ya existe una asignación activa con el nuevo veterinario
        $asignacionExistente = MascotaVeterinario::where('mascota_id', $asignacion->mascota_id)
            ->where('veterinario_id', $nuevoVeterinario->id)
            ->where('activo', true)
            ->where('id', '!=', $asignacion->id) // Excluir la asignación actual
            ->first();
            
        if ($asignacionExistente) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => "El Dr. {$nuevoVeterinario->name} ya está asignado a {$asignacion->mascota->nombre}."
                ], 422);
            }
            return redirect()->back()->withErrors(['error' => "El Dr. {$nuevoVeterinario->name} ya está asignado a {$asignacion->mascota->nombre}."]);
        }
        
        // Desactivar la asignación anterior
        $asignacion->update([
            'activo' => false,
            'notas' => ($asignacion->notas ?? '') . "\n[Cambiado por el cliente el " . now()->format('d/m/Y H:i') . "]"
        ]);
        
        // Crear nueva asignación
        $nuevaAsignacion = MascotaVeterinario::create([
            'mascota_id' => $asignacion->mascota_id,
            'veterinario_id' => $nuevoVeterinario->id,
            'fecha_asignacion' => now(),
            'activo' => true,
            'tipo_asignacion' => $request->tipo_asignacion,
            'notas' => $request->notas ?? 'Cambio de veterinario por cliente'
        ]);
        
        // Crear notificación para el nuevo veterinario
        VetRequestNotification::create([
            'veterinario_id' => $nuevoVeterinario->id,
            'cliente_id' => Auth::id(),
            'mascota_id' => $asignacion->mascota_id,
            'asignacion_id' => $nuevaAsignacion->id,
            'tipo' => 'solicitud',
            'mensaje' => "El cliente {{ Auth::user()->name }} ha cambiado la asignación de {{ $asignacion->mascota->nombre }} a usted como {{ ucfirst($request->tipo_asignacion) }}."
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Veterinario cambiado exitosamente. El nuevo veterinario ha sido notificado.'
        ]);
    }

    /**
     * Desasignar veterinario de una mascota
     */
    public function desasignar(MascotaVeterinario $asignacion)
    {
        // Verificar que la asignación pertenece al cliente
        if ($asignacion->mascota->user_id !== Auth::id()) {
            abort(403);
        }
        
        // Verificar que la asignación está activa
        if (!$asignacion->activo) {
            return response()->json([
                'success' => false,
                'message' => 'Esta asignación ya ha sido desactivada.'
            ], 422);
        }
        
        try {
            // Desactivar la asignación
            $asignacion->update([
                'activo' => false,
                'notas' => ($asignacion->notas ?? '') . "\n[Desasignado por el cliente el " . now()->format('d/m/Y H:i') . "]"
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Veterinario desasignado exitosamente.'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error al desasignar veterinario: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al desasignar el veterinario. Por favor, inténtalo de nuevo.'
            ], 500);
        }
    }
}