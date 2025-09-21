<?php

namespace App\Services;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DataEncryptionService
{
    /**
     * Campos sensibles que deben ser encriptados
     */
    private const SENSITIVE_FIELDS = [
        'telefono_owner',
        'phone',
        'telefono',
        'cedula',
        'id_pago_yappy',
        'payment_id',
        'document_path',
        'file_path'
    ];

    /**
     * Encripta datos sensibles de un array
     */
    public function encryptSensitiveData(array $data): array
    {
        $encrypted = [];

        foreach ($data as $key => $value) {
            if ($this->isSensitiveField($key) && !empty($value)) {
                $encrypted[$key] = $this->encrypt($value);
            } else {
                $encrypted[$key] = $value;
            }
        }

        return $encrypted;
    }

    /**
     * Desencripta datos sensibles de un array
     */
    public function decryptSensitiveData(array $data): array
    {
        $decrypted = [];

        foreach ($data as $key => $value) {
            if ($this->isSensitiveField($key) && !empty($value)) {
                try {
                    $decrypted[$key] = $this->decrypt($value);
                } catch (\Exception $e) {
                    Log::warning('Error al desencriptar campo sensible', [
                        'field' => $key,
                        'error' => $e->getMessage()
                    ]);
                    $decrypted[$key] = $value; // Mantener valor original si falla
                }
            } else {
                $decrypted[$key] = $value;
            }
        }

        return $decrypted;
    }

    /**
     * Encripta un valor individual
     */
    public function encrypt(string $value): string
    {
        try {
            return Crypt::encryptString($value);
        } catch (\Exception $e) {
            Log::error('Error al encriptar dato', [
                'error' => $e->getMessage(),
                'value_length' => strlen($value)
            ]);
            throw $e;
        }
    }

    /**
     * Desencripta un valor individual
     */
    public function decrypt(string $encryptedValue): string
    {
        try {
            return Crypt::decryptString($encryptedValue);
        } catch (\Exception $e) {
            Log::error('Error al desencriptar dato', [
                'error' => $e->getMessage(),
                'encrypted_length' => strlen($encryptedValue)
            ]);
            throw $e;
        }
    }

    /**
     * Verifica si un campo es sensible
     */
    private function isSensitiveField(string $fieldName): bool
    {
        $fieldLower = strtolower($fieldName);
        
        foreach (self::SENSITIVE_FIELDS as $sensitiveField) {
            if (str_contains($fieldLower, $sensitiveField)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Encripta datos de una mascota
     */
    public function encryptPetData(array $petData): array
    {
        return $this->encryptSensitiveData($petData);
    }

    /**
     * Desencripta datos de una mascota
     */
    public function decryptPetData(array $petData): array
    {
        return $this->decryptSensitiveData($petData);
    }

    /**
     * Encripta datos de un usuario
     */
    public function encryptUserData(array $userData): array
    {
        return $this->encryptSensitiveData($userData);
    }

    /**
     * Desencripta datos de un usuario
     */
    public function decryptUserData(array $userData): array
    {
        return $this->decryptSensitiveData($userData);
    }

    /**
     * Encripta datos de pago
     */
    public function encryptPaymentData(array $paymentData): array
    {
        return $this->encryptSensitiveData($paymentData);
    }

    /**
     * Desencripta datos de pago
     */
    public function decryptPaymentData(array $paymentData): array
    {
        return $this->decryptSensitiveData($paymentData);
    }

    /**
     * Genera un hash seguro para datos que no necesitan ser desencriptados
     */
    public function hashSensitiveData(string $data): string
    {
        return hash('sha256', $data . config('app.key'));
    }

    /**
     * Verifica si un valor está encriptado
     */
    public function isEncrypted(string $value): bool
    {
        // Los valores encriptados de Laravel suelen tener un formato específico
        // y contienen caracteres base64
        return preg_match('/^[A-Za-z0-9+\/]+=*$/', $value) && 
               strlen($value) > 20 && 
               str_contains($value, '|');
    }

    /**
     * Encripta datos para logging (versión segura)
     */
    public function encryptForLogging(array $data): array
    {
        $encrypted = [];

        foreach ($data as $key => $value) {
            if ($this->isSensitiveField($key)) {
                // Para logging, usar hash en lugar de encriptación completa
                $encrypted[$key] = $this->hashSensitiveData($value);
            } else {
                $encrypted[$key] = $value;
            }
        }

        return $encrypted;
    }

    /**
     * Genera una clave de encriptación única para cada registro
     */
    public function generateEncryptionKey(int $recordId): string
    {
        return hash('sha256', $recordId . config('app.key') . time());
    }

    /**
     * Encripta con clave personalizada
     */
    public function encryptWithCustomKey(string $value, string $key): string
    {
        $iv = random_bytes(16);
        $encrypted = openssl_encrypt($value, 'AES-256-CBC', $key, 0, $iv);
        return base64_encode($iv . $encrypted);
    }

    /**
     * Desencripta con clave personalizada
     */
    public function decryptWithCustomKey(string $encryptedValue, string $key): string
    {
        $data = base64_decode($encryptedValue);
        $iv = substr($data, 0, 16);
        $encrypted = substr($data, 16);
        return openssl_decrypt($encrypted, 'AES-256-CBC', $key, 0, $iv);
    }
}
