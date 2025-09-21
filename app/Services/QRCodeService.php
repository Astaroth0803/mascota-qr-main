<?php

namespace App\Services;

use App\Models\Pet;
use Illuminate\Support\Str;

class QRCodeService
{
    /**
     * Genera un código QR único para una mascota
     */
    public function generateUniqueQRCode(Pet $pet): string
    {
        // Si ya tiene un código QR, lo devolvemos
        if ($pet->qr_code) {
            return $pet->qr_code;
        }

        // Generar un código único basado en el ID de la mascota y un timestamp
        $uniqueId = 'PET-' . $pet->id . '-' . time() . '-' . Str::random(8);
        
        // Verificar que no exista otro código igual (muy improbable pero por seguridad)
        while (Pet::where('qr_code', $uniqueId)->exists()) {
            $uniqueId = 'PET-' . $pet->id . '-' . time() . '-' . Str::random(8);
        }

        return $uniqueId;
    }

    /**
     * Asigna un código QR a una mascota si no lo tiene
     */
    public function assignQRCode(Pet $pet): string
    {
        if (!$pet->qr_code) {
            $qrCode = $this->generateUniqueQRCode($pet);
            $pet->update(['qr_code' => $qrCode]);
            return $qrCode;
        }

        return $pet->qr_code;
    }

    /**
     * Genera la URL pública para el perfil de la mascota
     */
    public function generatePublicUrl(Pet $pet): string
    {
        if (!$pet->qr_code) {
            $this->assignQRCode($pet);
        }

        return route('public.pet.qr', ['qrCode' => $pet->qr_code]);
    }

    /**
     * Genera el código QR para múltiples mascotas
     */
    public function generateQRForMultiplePets(array $petIds): array
    {
        $results = [];
        
        foreach ($petIds as $petId) {
            $pet = Pet::find($petId);
            if ($pet) {
                $qrCode = $this->assignQRCode($pet);
                $results[] = [
                    'pet_id' => $pet->id,
                    'pet_name' => $pet->nombre,
                    'qr_code' => $qrCode,
                    'public_url' => $this->generatePublicUrl($pet)
                ];
            }
        }

        return $results;
    }

    /**
     * Genera la URL de la API para obtener el código QR como imagen
     */
    public function generateQRImageUrl(string $text, int $size = 300): string
    {
        // Usar una API gratuita para generar códigos QR
        $encodedText = urlencode($text);
        return "https://api.qrserver.com/v1/create-qr-code/?size={$size}x{$size}&data={$encodedText}";
    }

    /**
     * Genera el código QR como SVG usando una API
     */
    public function generateQRSvg(string $text, int $size = 300): string
    {
        $encodedText = urlencode($text);
        return "https://api.qrserver.com/v1/create-qr-code/?size={$size}x{$size}&format=svg&data={$encodedText}";
    }
}

