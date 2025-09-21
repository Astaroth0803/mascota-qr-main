<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pet;
use App\Models\User;
use App\Models\MascotaVeterinario;
use Illuminate\Support\Facades\Auth;

class AsignacionVeterinarioController extends Controller
{

    /**
     * Mostrar vista de asignación de veterinarios
     */
    public function index()
    {
        $mascotas = Pet::with(['user', 'veterinariosActivos'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        $veterinarios = User::role('veterinario')
            ->where('id', '!=', Auth::id())
            ->get();

        return view('dashboard.administrador.asignar-veterinario', compact('mascotas', 'veterinarios'));
    }

    /**
     * Asignar veterinario a una mascota
     */
    public function store(Request $request)
    {
        $request->validate([
            'mascota_id' => 'required|exists:pets,id',
            'veterinario_id' => 'required|exists:users,id',
            'tipo_asignacion' => 'required|in:principal,especialista,emergencia',
            'notas' => 'nullable|string|max:1000',
        ]);

        // Verificar que el usuario sea veterinario
        $veterinario = User::findOrFail($request->veterinario_id);
        if (!$veterinario->hasRole('veterinario')) {
            return redirect()->back()->with('error', 'El usuario seleccionado no es un veterinario.');
        }

        // Verificar que no exista ya la asignación
        $asignacionExistente = MascotaVeterinario::where('mascota_id', $request->mascota_id)
            ->where('veterinario_id', $request->veterinario_id)
            ->where('activo', true)
            ->first();

        if ($asignacionExistente) {
            return redirect()->back()->with('error', 'Este veterinario ya está asignado a esta mascota.');
        }

        // Crear la asignación
        MascotaVeterinario::create([
            'mascota_id' => $request->mascota_id,
            'veterinario_id' => $request->veterinario_id,
            'fecha_asignacion' => now(),
            'tipo_asignacion' => $request->tipo_asignacion,
            'notas' => $request->notas,
            'activo' => true,
        ]);

        return redirect()->back()->with('success', 'Veterinario asignado exitosamente a la mascota.');
    }

    /**
     * Gestionar veterinarios de una mascota
     */
    public function manage(Request $request)
    {
        $request->validate([
            'mascota_id' => 'required|exists:pets,id',
            'veterinario_id' => 'required|exists:users,id',
            'tipo_asignacion' => 'required|in:principal,especialista,emergencia',
            'notas' => 'nullable|string|max:1000',
        ]);

        // Verificar que el usuario sea veterinario
        $veterinario = User::findOrFail($request->veterinario_id);
        if (!$veterinario->hasRole('veterinario')) {
            return redirect()->back()->with('error', 'El usuario seleccionado no es un veterinario.');
        }

        // Buscar asignación existente
        $asignacion = MascotaVeterinario::where('mascota_id', $request->mascota_id)
            ->where('veterinario_id', $request->veterinario_id)
            ->first();

        if ($asignacion) {
            // Actualizar asignación existente
            $asignacion->update([
                'tipo_asignacion' => $request->tipo_asignacion,
                'notas' => $request->notas,
                'activo' => true,
            ]);
        } else {
            // Crear nueva asignación
            MascotaVeterinario::create([
                'mascota_id' => $request->mascota_id,
                'veterinario_id' => $request->veterinario_id,
                'fecha_asignacion' => now(),
                'tipo_asignacion' => $request->tipo_asignacion,
                'notas' => $request->notas,
                'activo' => true,
            ]);
        }

        return redirect()->back()->with('success', 'Asignación de veterinario actualizada exitosamente.');
    }

    /**
     * Desasignar veterinario de una mascota
     */
    public function desasignar(Request $request, Pet $mascota, User $veterinario)
    {
        $asignacion = MascotaVeterinario::where('mascota_id', $mascota->id)
            ->where('veterinario_id', $veterinario->id)
            ->where('activo', true)
            ->first();

        if (!$asignacion) {
            return redirect()->back()->with('error', 'No se encontró la asignación.');
        }

        $asignacion->update(['activo' => false]);

        return redirect()->back()->with('success', 'Veterinario desasignado exitosamente.');
    }

    /**
     * Obtener veterinarios disponibles para una mascota
     */
    public function getVeterinariosDisponibles(Pet $mascota)
    {
        $veterinariosAsignados = $mascota->veterinariosActivos()->pluck('veterinario_id');
        
        $veterinariosDisponibles = User::role('veterinario')
            ->whereNotIn('id', $veterinariosAsignados)
            ->get(['id', 'name']);

        return response()->json($veterinariosDisponibles);
    }
}
