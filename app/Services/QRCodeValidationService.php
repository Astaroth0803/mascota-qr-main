<?php

namespace App\Services;

use App\Models\Pet;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class QRCodeValidationService
{
    /**
     * Valida que un código QR sea único en tiempo real
     */
    public function validateUniqueQRCode(string $qrCode, ?int $excludePetId = null): array
    {
        // Verificar en caché primero para mejor rendimiento
        $cacheKey = "qr_code_check:{$qrCode}";
        $cachedResult = Cache::get($cacheKey);
        
        if ($cachedResult !== null) {
            return $cachedResult;
        }

        // Verificar en base de datos
        $query = Pet::where('qr_code', $qrCode);
        
        if ($excludePetId) {
            $query->where('id', '!=', $excludePetId);
        }

        $exists = $query->exists();

        $result = [
            'is_unique' => !$exists,
            'message' => $exists ? 'El código QR ya está en uso' : 'Código QR disponible'
        ];

        // Cachear resultado por 5 minutos
        Cache::put($cacheKey, $result, now()->addMinutes(5));

        return $result;
    }

    /**
     * Genera un código QR único y válido
     */
    public function generateUniqueQRCode(?int $petId = null): string
    {
        $maxAttempts = 10;
        $attempts = 0;

        do {
            $qrCode = $this->generateQRCode();
            $validation = $this->validateUniqueQRCode($qrCode, $petId);
            
            if ($validation['is_unique']) {
                return $qrCode;
            }

            $attempts++;
            
            if ($attempts >= $maxAttempts) {
                Log::error('No se pudo generar un código QR único después de múltiples intentos', [
                    'pet_id' => $petId,
                    'attempts' => $attempts
                ]);
                throw new \Exception('No se pudo generar un código QR único');
            }

        } while ($attempts < $maxAttempts);

        return $qrCode; // Fallback
    }

    /**
     * Genera un código QR base
     */
    private function generateQRCode(): string
    {
        $timestamp = now()->format('YmdHis');
        $randomString = Str::random(12);
        $petId = Str::random(6);
        
        return "PET-{$petId}-{$timestamp}-{$randomString}";
    }

    /**
     * Valida el formato de un código QR
     */
    public function validateQRCodeFormat(string $qrCode): array
    {
        $errors = [];

        // Verificar longitud mínima
        if (strlen($qrCode) < 10) {
            $errors[] = 'El código QR es demasiado corto';
        }

        // Verificar longitud máxima
        if (strlen($qrCode) > 100) {
            $errors[] = 'El código QR es demasiado largo';
        }

        // Verificar caracteres permitidos (alfanumérico, guiones, puntos)
        if (!preg_match('/^[A-Za-z0-9\-\._]+$/', $qrCode)) {
            $errors[] = 'El código QR contiene caracteres no válidos';
        }

        // Verificar que no contenga patrones sospechosos
        $suspiciousPatterns = [
            '/script/i',
            '/javascript/i',
            '/vbscript/i',
            '/onload/i',
            '/onerror/i',
            '/<[^>]*>/',
            '/eval\(/i',
            '/base64_decode/i'
        ];

        foreach ($suspiciousPatterns as $pattern) {
            if (preg_match($pattern, $qrCode)) {
                $errors[] = 'El código QR contiene patrones sospechosos';
                break;
            }
        }

        return [
            'is_valid' => empty($errors),
            'errors' => $errors
        ];
    }

    /**
     * Verifica la integridad de un código QR
     */
    public function verifyQRCodeIntegrity(string $qrCode): array
    {
        // Verificar formato
        $formatValidation = $this->validateQRCodeFormat($qrCode);
        if (!$formatValidation['is_valid']) {
            return [
                'is_valid' => false,
                'errors' => $formatValidation['errors']
            ];
        }

        // Verificar unicidad
        $uniquenessValidation = $this->validateUniqueQRCode($qrCode);
        if (!$uniquenessValidation['is_unique']) {
            return [
                'is_valid' => false,
                'errors' => [$uniquenessValidation['message']]
            ];
        }

        return [
            'is_valid' => true,
            'errors' => []
        ];
    }

    /**
     * Limpia y normaliza un código QR
     */
    public function sanitizeQRCode(string $qrCode): string
    {
        // Remover espacios y caracteres de control
        $qrCode = trim($qrCode);
        $qrCode = preg_replace('/[\x00-\x1F\x7F]/', '', $qrCode);
        
        // Convertir a mayúsculas para consistencia
        $qrCode = strtoupper($qrCode);
        
        // Remover caracteres no válidos
        $qrCode = preg_replace('/[^A-Z0-9\-\._]/', '', $qrCode);
        
        return $qrCode;
    }

    /**
     * Valida un código QR en tiempo real (para AJAX)
     */
    public function validateQRCodeRealTime(string $qrCode, ?int $excludePetId = null): array
    {
        // Sanitizar entrada
        $qrCode = $this->sanitizeQRCode($qrCode);
        
        // Verificar formato
        $formatValidation = $this->validateQRCodeFormat($qrCode);
        if (!$formatValidation['is_valid']) {
            return [
                'valid' => false,
                'message' => implode(', ', $formatValidation['errors']),
                'qr_code' => $qrCode
            ];
        }

        // Verificar unicidad
        $uniquenessValidation = $this->validateUniqueQRCode($qrCode, $excludePetId);
        if (!$uniquenessValidation['is_unique']) {
            return [
                'valid' => false,
                'message' => $uniquenessValidation['message'],
                'qr_code' => $qrCode
            ];
        }

        return [
            'valid' => true,
            'message' => 'Código QR válido y disponible',
            'qr_code' => $qrCode
        ];
    }

    /**
     * Limpia caché de validación de códigos QR
     */
    public function clearQRCodeCache(string $qrCode): void
    {
        $cacheKey = "qr_code_check:{$qrCode}";
        Cache::forget($cacheKey);
    }

    /**
     * Obtiene estadísticas de códigos QR
     */
    public function getQRCodeStats(): array
    {
        $totalPets = Pet::count();
        $petsWithQR = Pet::whereNotNull('qr_code')->count();
        $petsWithoutQR = $totalPets - $petsWithQR;

        return [
            'total_pets' => $totalPets,
            'pets_with_qr' => $petsWithQR,
            'pets_without_qr' => $petsWithoutQR,
            'qr_coverage' => $totalPets > 0 ? round(($petsWithQR / $totalPets) * 100, 2) : 0
        ];
    }
}
