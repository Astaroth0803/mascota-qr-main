<?php

namespace App\Http\Controllers;

use App\Models\PetRequest;
use App\Models\Pet;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PetRequestController extends Controller
{
    /**
     * Muestra el formulario para crear una solicitud de mascota
     */
    public function create()
    {
        // Solo usuarios no administradores pueden crear solicitudes
        if (Auth::user()->hasAnyRole(['administrador', 'super_admin'])) {
            return redirect()->route('pet-requests.index')
                ->with('info', 'Los administradores no pueden crear solicitudes de mascotas. Usa esta sección para gestionar las solicitudes existentes.');
        }

        // Verificar que el usuario tenga al menos una mascota registrada
        $userPetsCount = Pet::where('user_id', Auth::id())->count();
        
        if ($userPetsCount === 0) {
            return redirect()->route('mascotaqr')
                ->with('info', 'Para solicitar una nueva mascota, primero debes registrar tu primera mascota.');
        }

        return view('mascotas.pet-request-create');
    }

    /**
     * Almacena una nueva solicitud de mascota
     */
    public function store(Request $request)
    {
        // Solo usuarios no administradores pueden crear solicitudes
        if (Auth::user()->hasAnyRole(['administrador', 'super_admin'])) {
            return redirect()->route('pet-requests.index')
                ->with('error', 'Los administradores no pueden crear solicitudes de mascotas.');
        }

        // Verificar que el usuario tenga al menos una mascota registrada
        $userPetsCount = Pet::where('user_id', Auth::id())->count();
        
        if ($userPetsCount === 0) {
            return redirect()->route('mascotaqr')
                ->with('error', 'Para solicitar una nueva mascota, primero debes registrar tu primera mascota.');
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'especie' => 'required|string|max:255',
            'raza' => 'required|string|max:255',
            'edad_anios' => 'required|integer|min:0|max:30',
            'edad_meses' => 'required|integer|min:0|max:11',
            'sexo' => 'required|string|in:Macho,Hembra',
            'payment_id' => 'nullable|string|max:255'
        ]);

        // Crear la solicitud
        $petRequest = PetRequest::create([
            'user_id' => Auth::id(),
            'nombre' => $validated['nombre'],
            'especie' => $validated['especie'],
            'raza' => $validated['raza'],
            'edad_anios' => $validated['edad_anios'],
            'edad_meses' => $validated['edad_meses'],
            'sexo' => $validated['sexo'],
            'payment_id' => $validated['payment_id'],
            'status' => 'pending'
        ]);

        // Log de la solicitud
        Log::info('Solicitud de mascota creada', [
            'request_id' => $petRequest->id,
            'user_id' => Auth::id(),
            'pet_name' => $petRequest->nombre,
            'payment_id' => $petRequest->payment_id
        ]);

        return redirect()->route('dashboard.cliente.index')
            ->with('success', 'Solicitud de mascota enviada correctamente. Será revisada por un administrador.');
    }

    /**
     * Muestra las solicitudes del usuario actual
     */
    public function index()
    {
        $petRequests = PetRequest::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('mascotas.pet-requests', compact('petRequests'));
    }

    /**
     * Muestra una solicitud específica
     */
    public function show(PetRequest $petRequest)
    {
        // Verificar que el usuario tenga acceso a esta solicitud
        if ($petRequest->user_id !== Auth::id() && !Auth::user()->hasAnyRole(['administrador', 'super_admin'])) {
            return redirect()->route('dashboard.cliente.index')
                ->with('error', 'No tienes permiso para ver esta solicitud.');
        }

        return view('mascotas.pet-request-show', compact('petRequest'));
    }

    /**
     * Muestra todas las solicitudes para administradores
     */
    public function adminIndex()
    {
        // Solo administradores pueden acceder
        if (!Auth::user()->hasAnyRole(['administrador', 'super_admin'])) {
            return redirect()->route('dashboard.cliente.index')
                ->with('error', 'No tienes permisos para acceder a esta función.');
        }

        $petRequests = PetRequest::with(['user', 'reviewer'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'total' => PetRequest::count(),
            'pending' => PetRequest::pending()->count(),
            'approved' => PetRequest::approved()->count(),
            'rejected' => PetRequest::rejected()->count()
        ];

        return view('mascotas.pet-requests-admin', compact('petRequests', 'stats'));
    }

    /**
     * Aprueba una solicitud de mascota
     */
    public function approve(Request $request, PetRequest $petRequest)
    {
        // Solo administradores pueden aprobar
        if (!Auth::user()->hasAnyRole(['administrador', 'super_admin'])) {
            return redirect()->route('dashboard.cliente.index')
                ->with('error', 'No tienes permisos para realizar esta acción.');
        }

        // Verificar que la solicitud esté pendiente
        if (!$petRequest->isPending()) {
            return redirect()->back()
                ->with('error', 'Esta solicitud ya ha sido procesada.');
        }

        // Verificar el pago si se proporcionó un ID
        if ($petRequest->payment_id) {
            // Aquí podrías integrar con la API del banco para verificar el pago
            // Por ahora, asumimos que el pago es válido si se proporciona un ID
            $paymentValid = $this->verifyPaymentWithBank($petRequest->payment_id);
            
            if (!$paymentValid) {
                return redirect()->back()
                    ->with('error', 'El ID de pago no es válido o no se encontró en el sistema bancario.');
            }
        }

        // Crear la mascota
        $pet = Pet::create([
            'nombre' => $petRequest->nombre,
            'especie' => $petRequest->especie,
            'raza' => $petRequest->raza,
            'edad_anios' => $petRequest->edad_anios,
            'edad_meses' => $petRequest->edad_meses,
            'sexo' => $petRequest->sexo,
            'user_id' => $petRequest->user_id,
            'nombre_owner' => $petRequest->user->name,
            'apellido_owner' => $petRequest->user->lastname ?? '',
            'correo_owner' => $petRequest->user->email,
            'telefono_owner' => $petRequest->user->phone ?? '',
            'slug' => Str::slug($petRequest->nombre)
        ]);

        // Crear el registro de pago si existe
        if ($petRequest->payment_id) {
            Payment::create([
                'pet_id' => $pet->id,
                'payment_method' => 'bank_transfer',
                'payment_id' => $petRequest->payment_id,
                'status' => 'verified'
            ]);
        }

        // Actualizar la solicitud
        $petRequest->update([
            'status' => 'approved',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now()
        ]);

        // Log de la aprobación
        Log::info('Solicitud de mascota aprobada', [
            'request_id' => $petRequest->id,
            'pet_id' => $pet->id,
            'user_id' => $petRequest->user_id,
            'reviewed_by' => Auth::id()
        ]);

        return redirect()->back()
            ->with('success', 'Solicitud aprobada y mascota creada correctamente.');
    }

    /**
     * Rechaza una solicitud de mascota
     */
    public function reject(Request $request, PetRequest $petRequest)
    {
        // Solo administradores pueden rechazar
        if (!Auth::user()->hasAnyRole(['administrador', 'super_admin'])) {
            return redirect()->route('dashboard.cliente.index')
                ->with('error', 'No tienes permisos para realizar esta acción.');
        }

        // Verificar que la solicitud esté pendiente
        if (!$petRequest->isPending()) {
            return redirect()->back()
                ->with('error', 'Esta solicitud ya ha sido procesada.');
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:1000'
        ]);

        // Actualizar la solicitud
        $petRequest->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now()
        ]);

        // Log del rechazo
        Log::info('Solicitud de mascota rechazada', [
            'request_id' => $petRequest->id,
            'user_id' => $petRequest->user_id,
            'reviewed_by' => Auth::id(),
            'reason' => $validated['rejection_reason']
        ]);

        return redirect()->back()
            ->with('success', 'Solicitud rechazada correctamente.');
    }

    /**
     * Verifica el pago con el banco (simulado)
     */
    private function verifyPaymentWithBank($paymentId)
    {
        // Aquí implementarías la integración real con la API del banco
        // Por ahora, simulamos que el pago es válido si tiene un formato específico
        return !empty($paymentId) && strlen($paymentId) >= 5;
    }
}