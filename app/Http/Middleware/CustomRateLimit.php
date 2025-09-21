<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CustomRateLimit
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $type = 'general'): Response
    {
        $key = $this->generateKey($request, $type);
        $limits = $this->getLimits($type);

        if ($this->isRateLimited($key, $limits)) {
            $this->logRateLimitExceeded($request, $type);
            
            return response()->json([
                'error' => 'Demasiadas solicitudes. Intenta de nuevo más tarde.',
                'retry_after' => $limits['decay_minutes'] * 60
            ], 429);
        }

        $this->incrementAttempts($key, $limits);

        return $next($request);
    }

    /**
     * Genera la clave única para el rate limiting
     */
    private function generateKey(Request $request, string $type): string
    {
        $identifier = $request->ip();
        
        // Para usuarios autenticados, usar su ID
        if ($request->user()) {
            $identifier = 'user_' . $request->user()->id;
        }

        return "rate_limit:{$type}:{$identifier}";
    }

    /**
     * Obtiene los límites según el tipo de endpoint
     */
    private function getLimits(string $type): array
    {
        $limits = [
            'general' => [
                'max_attempts' => 100,
                'decay_minutes' => 60
            ],
            'auth' => [
                'max_attempts' => 5,
                'decay_minutes' => 15
            ],
            'pet_registration' => [
                'max_attempts' => 3,
                'decay_minutes' => 60
            ],
            'file_upload' => [
                'max_attempts' => 10,
                'decay_minutes' => 60
            ],
            'qr_generation' => [
                'max_attempts' => 20,
                'decay_minutes' => 60
            ],
            'contact_form' => [
                'max_attempts' => 3,
                'decay_minutes' => 30
            ],
            'password_reset' => [
                'max_attempts' => 3,
                'decay_minutes' => 60
            ]
        ];

        return $limits[$type] ?? $limits['general'];
    }

    /**
     * Verifica si se ha excedido el límite de rate
     */
    private function isRateLimited(string $key, array $limits): bool
    {
        $attempts = Cache::get($key, 0);
        return $attempts >= $limits['max_attempts'];
    }

    /**
     * Incrementa el contador de intentos
     */
    private function incrementAttempts(string $key, array $limits): void
    {
        $attempts = Cache::get($key, 0);
        $attempts++;
        
        Cache::put($key, $attempts, now()->addMinutes($limits['decay_minutes']));
    }

    /**
     * Registra cuando se excede el rate limit
     */
    private function logRateLimitExceeded(Request $request, string $type): void
    {
        Log::warning('Rate limit exceeded', [
            'type' => $type,
            'ip' => $request->ip(),
            'user_id' => $request->user()?->id,
            'url' => $request->fullUrl(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now()
        ]);
    }

    /**
     * Obtiene información del rate limit para el usuario
     */
    public static function getRateLimitInfo(Request $request, string $type): array
    {
        $key = (new self)->generateKey($request, $type);
        $limits = (new self)->getLimits($type);
        $attempts = Cache::get($key, 0);
        
        return [
            'attempts' => $attempts,
            'max_attempts' => $limits['max_attempts'],
            'remaining' => max(0, $limits['max_attempts'] - $attempts),
            'reset_time' => Cache::get($key . '_reset', now()->addMinutes($limits['decay_minutes']))
        ];
    }
}
