<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use App\Services\QRCodeValidationService;

class ClearSecurityCache extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'security:clear-cache {--type=all : Tipo de caché a limpiar (all, qr, rate-limit, files)}';

    /**
     * The console command description.
     */
    protected $description = 'Limpia la caché de seguridad del sistema';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = $this->option('type');

        switch ($type) {
            case 'qr':
                $this->clearQRCache();
                break;
            case 'rate-limit':
                $this->clearRateLimitCache();
                break;
            case 'files':
                $this->clearFileCache();
                break;
            case 'all':
            default:
                $this->clearAllSecurityCache();
                break;
        }

        $this->info("Caché de seguridad limpiada: {$type}");
    }

    /**
     * Limpia caché de códigos QR
     */
    private function clearQRCache()
    {
        $keys = Cache::getRedis()->keys('qr_code_check:*');
        foreach ($keys as $key) {
            Cache::forget(str_replace(config('cache.prefix') . ':', '', $key));
        }
        $this->info('Caché de códigos QR limpiada');
    }

    /**
     * Limpia caché de rate limiting
     */
    private function clearRateLimitCache()
    {
        $keys = Cache::getRedis()->keys('rate_limit:*');
        foreach ($keys as $key) {
            Cache::forget(str_replace(config('cache.prefix') . ':', '', $key));
        }
        $this->info('Caché de rate limiting limpiada');
    }

    /**
     * Limpia caché de archivos
     */
    private function clearFileCache()
    {
        $keys = Cache::getRedis()->keys('file_validation:*');
        foreach ($keys as $key) {
            Cache::forget(str_replace(config('cache.prefix') . ':', '', $key));
        }
        $this->info('Caché de archivos limpiada');
    }

    /**
     * Limpia toda la caché de seguridad
     */
    private function clearAllSecurityCache()
    {
        $this->clearQRCache();
        $this->clearRateLimitCache();
        $this->clearFileCache();
        
        // Limpiar caché general
        Cache::flush();
        $this->info('Toda la caché de seguridad limpiada');
    }
}
