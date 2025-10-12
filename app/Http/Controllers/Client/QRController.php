<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use App\Services\QRCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class QRController extends Controller
{
    protected $qrCodeService;

    public function __construct(QRCodeService $qrCodeService)
    {
        $this->qrCodeService = $qrCodeService;
    }

    /**
     * Muestra la vista para gestionar códigos QR de las mascotas del cliente
     */
    public function index()
    {
        $user = Auth::user();
        
        // Obtener solo las mascotas del usuario autenticado
        $pets = Pet::where('user_id', $user->id)
            ->orWhere('correo_owner', $user->email)
            ->with('user')
            ->get();

        // Estadísticas
        $stats = [
            'total_pets' => $pets->count(),
            'pets_with_qr' => $pets->whereNotNull('qr_code')->count(),
            'pets_without_qr' => $pets->whereNull('qr_code')->count(),
        ];

        return view('client.qr.index', compact('pets', 'stats'));
    }

    /**
     * Genera código QR para una sola mascota del cliente
     * NOTA: Los clientes NO pueden generar códigos QR, solo verlos
     */
    public function generateSingle(Request $request)
    {
        return response()->json([
            'success' => false,
            'message' => 'Los clientes no pueden generar códigos QR. Contacta al administrador para generar códigos QR para tus mascotas.'
        ], 403);
    }

    /**
     * Genera códigos QR para múltiples mascotas del cliente
     * NOTA: Los clientes NO pueden generar códigos QR, solo verlos
     */
    public function generateMultiple(Request $request)
    {
        return response()->json([
            'success' => false,
            'message' => 'Los clientes no pueden generar códigos QR. Contacta al administrador para generar códigos QR para tus mascotas.'
        ], 403);
    }

    /**
     * Muestra el código QR de una mascota específica
     */
    public function show(Pet $pet)
    {
        $user = Auth::user();
        
        Log::info('QRController@show called', [
            'pet_id' => $pet->id,
            'pet_name' => $pet->nombre,
            'user_id' => $user->id,
            'pet_user_id' => $pet->user_id,
            'pet_correo_owner' => $pet->correo_owner
        ]);
        
        // Verificar que la mascota pertenece al usuario
        if ($pet->user_id !== $user->id && $pet->correo_owner !== $user->email) {
            Log::warning('Access denied to pet', [
                'pet_id' => $pet->id,
                'user_id' => $user->id,
                'pet_user_id' => $pet->user_id,
                'pet_correo_owner' => $pet->correo_owner
            ]);
            abort(403, 'No tienes permisos para ver esta mascota');
        }

        if (!$pet->qr_code) {
            Log::info('Pet has no QR code', ['pet_id' => $pet->id, 'pet_name' => $pet->nombre]);
            return redirect()->route('dashboard.cliente.qr.index')
                ->with('error', 'Esta mascota no tiene código QR. Genera uno primero.');
        }

        $publicUrl = $this->qrCodeService->generatePublicUrl($pet);
        $qrImageUrl = $this->qrCodeService->generateQRImageUrl($publicUrl);

        Log::info('QR view loaded successfully', [
            'pet_id' => $pet->id,
            'public_url' => $publicUrl,
            'qr_image_url' => $qrImageUrl
        ]);

        return view('client.qr.show', compact('pet', 'publicUrl', 'qrImageUrl'));
    }

    /**
     * Descarga el código QR como imagen
     */
    public function download(Pet $pet)
    {
        $user = Auth::user();
        
        // Verificar que la mascota pertenece al usuario
        if ($pet->user_id !== $user->id && $pet->correo_owner !== $user->email) {
            abort(403, 'No tienes permisos para descargar este código QR');
        }

        if (!$pet->qr_code) {
            abort(404, 'Esta mascota no tiene código QR');
        }

        $publicUrl = $this->qrCodeService->generatePublicUrl($pet);
        $qrImageUrl = $this->qrCodeService->generateQRImageUrl($publicUrl, 400);

        // Redirigir a la imagen para descarga
        return redirect($qrImageUrl);
    }

    /**
     * Regenera el código QR de una mascota
     * NOTA: Los clientes NO pueden regenerar códigos QR, solo verlos
     */
    public function regenerate(Request $request, Pet $pet)
    {
        return response()->json([
            'success' => false,
            'message' => 'Los clientes no pueden regenerar códigos QR. Contacta al administrador si necesitas regenerar el código QR de tu mascota.'
        ], 403);
    }
}
