<?php

namespace App\Services;

use Illuminate\Support\Str;
use HTMLPurifier;
use HTMLPurifier_Config;

class InputSanitizationService
{
    private $purifier;

    public function __construct()
    {
        $config = HTMLPurifier_Config::createDefault();
        $config->set('HTML.Allowed', '');
        $config->set('AutoFormat.RemoveEmpty', true);
        $this->purifier = new HTMLPurifier($config);
    }

    /**
     * Sanitiza texto general (nombres, descripciones, etc.)
     */
    public function sanitizeText(string $input): string
    {
        // Remover caracteres de control y espacios extra
        $input = trim($input);
        $input = preg_replace('/[\x00-\x1F\x7F]/', '', $input);
        
        // Limpiar HTML/JavaScript
        $input = $this->purifier->purify($input);
        
        // Escapar caracteres especiales para base de datos
        $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
        
        return $input;
    }

    /**
     * Sanitiza números enteros
     */
    public function sanitizeInteger($input): ?int
    {
        if (is_null($input) || $input === '') {
            return null;
        }

        $input = filter_var($input, FILTER_SANITIZE_NUMBER_INT);
        return is_numeric($input) ? (int) $input : null;
    }

    /**
     * Sanitiza números decimales
     */
    public function sanitizeFloat($input): ?float
    {
        if (is_null($input) || $input === '') {
            return null;
        }

        $input = filter_var($input, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        return is_numeric($input) ? (float) $input : null;
    }

    /**
     * Sanitiza emails
     */
    public function sanitizeEmail(string $input): ?string
    {
        $input = trim($input);
        $input = strtolower($input);
        
        // Validar formato de email
        if (!filter_var($input, FILTER_VALIDATE_EMAIL)) {
            return null;
        }

        return $input;
    }

    /**
     * Sanitiza teléfonos
     */
    public function sanitizePhone(string $input): string
    {
        // Remover todo excepto números, +, -, (, ), espacios
        $input = preg_replace('/[^0-9+\-\(\)\s]/', '', $input);
        $input = trim($input);
        
        // Limitar longitud
        if (strlen($input) > 20) {
            $input = substr($input, 0, 20);
        }

        return $input;
    }

    /**
     * Sanitiza URLs
     */
    public function sanitizeUrl(string $input): ?string
    {
        $input = trim($input);
        
        // Agregar protocolo si no existe
        if (!preg_match('/^https?:\/\//', $input)) {
            $input = 'https://' . $input;
        }

        // Validar URL
        if (!filter_var($input, FILTER_VALIDATE_URL)) {
            return null;
        }

        return $input;
    }

    /**
     * Sanitiza fechas
     */
    public function sanitizeDate(string $input): ?string
    {
        $input = trim($input);
        
        // Verificar formato de fecha
        $date = \DateTime::createFromFormat('Y-m-d', $input);
        if ($date && $date->format('Y-m-d') === $input) {
            return $input;
        }

        return null;
    }

    /**
     * Sanitiza arrays de strings
     */
    public function sanitizeStringArray(array $input): array
    {
        return array_map([$this, 'sanitizeText'], $input);
    }

    /**
     * Sanitiza datos de formulario completo
     */
    public function sanitizeFormData(array $data): array
    {
        $sanitized = [];

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $sanitized[$key] = $this->sanitizeStringArray($value);
            } elseif (is_string($value)) {
                // Aplicar sanitización según el tipo de campo
                $sanitized[$key] = $this->getSanitizedValue($key, $value);
            } else {
                $sanitized[$key] = $value;
            }
        }

        return $sanitized;
    }

    /**
     * Determina el tipo de sanitización según el nombre del campo
     */
    private function getSanitizedValue(string $key, string $value): string
    {
        $key = strtolower($key);

        // Campos de email
        if (str_contains($key, 'email') || str_contains($key, 'correo')) {
            return $this->sanitizeEmail($value) ?? '';
        }

        // Campos de teléfono
        if (str_contains($key, 'phone') || str_contains($key, 'telefono')) {
            return $this->sanitizePhone($value);
        }

        // Campos de URL
        if (str_contains($key, 'url') || str_contains($key, 'website')) {
            return $this->sanitizeUrl($value) ?? '';
        }

        // Campos de fecha
        if (str_contains($key, 'date') || str_contains($key, 'fecha')) {
            return $this->sanitizeDate($value) ?? '';
        }

        // Campos numéricos
        if (str_contains($key, 'edad') || str_contains($key, 'age')) {
            return (string) $this->sanitizeInteger($value);
        }

        // Por defecto, sanitizar como texto
        return $this->sanitizeText($value);
    }

    /**
     * Valida y sanitiza datos de entrada con reglas específicas
     */
    public function validateAndSanitize(array $data, array $rules): array
    {
        $sanitized = $this->sanitizeFormData($data);
        $errors = [];

        foreach ($rules as $field => $rule) {
            $value = $sanitized[$field] ?? null;

            // Aplicar validaciones específicas
            if (str_contains($rule, 'required') && empty($value)) {
                $errors[$field] = "El campo {$field} es obligatorio.";
            }

            if (str_contains($rule, 'email') && $value && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $errors[$field] = "El campo {$field} debe ser un email válido.";
            }

            if (str_contains($rule, 'min:') && $value) {
                $min = (int) substr($rule, strpos($rule, 'min:') + 4);
                if (strlen($value) < $min) {
                    $errors[$field] = "El campo {$field} debe tener al menos {$min} caracteres.";
                }
            }

            if (str_contains($rule, 'max:') && $value) {
                $max = (int) substr($rule, strpos($rule, 'max:') + 4);
                if (strlen($value) > $max) {
                    $errors[$field] = "El campo {$field} no puede tener más de {$max} caracteres.";
                }
            }
        }

        return [
            'data' => $sanitized,
            'errors' => $errors
        ];
    }

    /**
     * Limpia datos sensibles para logging
     */
    public function cleanSensitiveData(array $data): array
    {
        $sensitiveFields = [
            'password', 'contraseña', 'token', 'secret', 'key',
            'credit_card', 'tarjeta', 'cvv', 'ssn', 'cedula'
        ];

        foreach ($data as $key => $value) {
            $keyLower = strtolower($key);
            
            foreach ($sensitiveFields as $sensitive) {
                if (str_contains($keyLower, $sensitive)) {
                    $data[$key] = '[REDACTED]';
                    break;
                }
            }
        }

        return $data;
    }
}
