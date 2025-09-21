<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Services\InputSanitizationService;
use Symfony\Component\HttpFoundation\Response;

class SecurityLogging
{
    private $sanitizationService;

    public function __construct(InputSanitizationService $sanitizationService)
    {
        $this->sanitizationService = $sanitizationService;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);
        
        // Log de la solicitud entrante
        $this->logIncomingRequest($request);
        
        $response = $next($request);
        
        // Log de la respuesta
        $this->logResponse($request, $response, $startTime);
        
        return $response;
    }

    /**
     * Registra la solicitud entrante
     */
    private function logIncomingRequest(Request $request): void
    {
        $logData = [
            'type' => 'incoming_request',
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id' => Auth::id(),
            'timestamp' => now()->toISOString(),
            'headers' => $this->sanitizeHeaders($request->headers->all()),
            'query_params' => $this->sanitizeQueryParams($request->query->all()),
            'post_data' => $this->sanitizePostData($request->all())
        ];

        // Detectar patrones sospechosos
        $suspiciousPatterns = $this->detectSuspiciousPatterns($request);
        if (!empty($suspiciousPatterns)) {
            $logData['security_warnings'] = $suspiciousPatterns;
            Log::warning('Solicitud sospechosa detectada', $logData);
        } else {
            Log::info('Solicitud entrante', $logData);
        }
    }

    /**
     * Registra la respuesta
     */
    private function logResponse(Request $request, Response $response, float $startTime): void
    {
        $duration = round((microtime(true) - $startTime) * 1000, 2);
        
        $logData = [
            'type' => 'response',
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'status_code' => $response->getStatusCode(),
            'duration_ms' => $duration,
            'ip' => $request->ip(),
            'user_id' => Auth::id(),
            'timestamp' => now()->toISOString()
        ];

        // Log de errores de seguridad
        if ($response->getStatusCode() >= 400) {
            $logData['error_type'] = $this->getErrorType($response->getStatusCode());
            Log::warning('Respuesta con error', $logData);
        } else {
            Log::info('Respuesta exitosa', $logData);
        }
    }

    /**
     * Sanitiza headers para logging
     */
    private function sanitizeHeaders(array $headers): array
    {
        $sensitiveHeaders = [
            'authorization',
            'cookie',
            'x-api-key',
            'x-auth-token'
        ];

        $sanitized = [];
        foreach ($headers as $key => $value) {
            $keyLower = strtolower($key);
            
            if (in_array($keyLower, $sensitiveHeaders)) {
                $sanitized[$key] = '[REDACTED]';
            } else {
                $sanitized[$key] = is_array($value) ? implode(', ', $value) : $value;
            }
        }

        return $sanitized;
    }

    /**
     * Sanitiza parámetros de consulta
     */
    private function sanitizeQueryParams(array $params): array
    {
        return $this->sanitizationService->cleanSensitiveData($params);
    }

    /**
     * Sanitiza datos POST
     */
    private function sanitizePostData(array $data): array
    {
        return $this->sanitizationService->cleanSensitiveData($data);
    }

    /**
     * Detecta patrones sospechosos en la solicitud
     */
    private function detectSuspiciousPatterns(Request $request): array
    {
        $warnings = [];
        
        // Verificar User-Agent sospechoso
        $userAgent = $request->userAgent();
        if ($this->isSuspiciousUserAgent($userAgent)) {
            $warnings[] = 'User-Agent sospechoso: ' . $userAgent;
        }

        // Verificar parámetros sospechosos
        $allParams = array_merge($request->query->all(), $request->all());
        foreach ($allParams as $key => $value) {
            if (is_string($value) && $this->containsSuspiciousContent($value)) {
                $warnings[] = "Parámetro sospechoso '{$key}': " . substr($value, 0, 100);
            }
        }

        // Verificar headers sospechosos
        $headers = $request->headers->all();
        foreach ($headers as $key => $value) {
            $valueStr = is_array($value) ? implode(', ', $value) : $value;
            if ($this->containsSuspiciousContent($valueStr)) {
                $warnings[] = "Header sospechoso '{$key}': " . substr($valueStr, 0, 100);
            }
        }

        return $warnings;
    }

    /**
     * Verifica si el User-Agent es sospechoso
     */
    private function isSuspiciousUserAgent(?string $userAgent): bool
    {
        if (!$userAgent) {
            return true;
        }

        $suspiciousPatterns = [
            '/bot/i',
            '/crawler/i',
            '/spider/i',
            '/scraper/i',
            '/curl/i',
            '/wget/i',
            '/python/i',
            '/php/i',
            '/java/i',
            '/perl/i'
        ];

        foreach ($suspiciousPatterns as $pattern) {
            if (preg_match($pattern, $userAgent)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Verifica si el contenido contiene patrones sospechosos
     */
    private function containsSuspiciousContent(string $content): bool
    {
        $suspiciousPatterns = [
            '/<script[^>]*>.*?<\/script>/i',
            '/javascript:/i',
            '/vbscript:/i',
            '/onload=/i',
            '/onerror=/i',
            '/eval\(/i',
            '/base64_decode\(/i',
            '/union.*select/i',
            '/drop.*table/i',
            '/insert.*into/i',
            '/delete.*from/i',
            '/update.*set/i',
            '/exec\(/i',
            '/system\(/i',
            '/shell_exec\(/i',
            '/passthru\(/i'
        ];

        foreach ($suspiciousPatterns as $pattern) {
            if (preg_match($pattern, $content)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Obtiene el tipo de error basado en el código de estado
     */
    private function getErrorType(int $statusCode): string
    {
        if ($statusCode >= 400 && $statusCode < 500) {
            return 'client_error';
        } elseif ($statusCode >= 500) {
            return 'server_error';
        } else {
            return 'unknown_error';
        }
    }
}
