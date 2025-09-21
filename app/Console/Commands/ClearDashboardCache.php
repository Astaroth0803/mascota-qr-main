<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use App\Services\DashboardService;

class ClearDashboardCache extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'dashboard:clear-cache {--user= : ID del usuario específico}';

    /**
     * The console command description.
     */
    protected $description = 'Limpia la caché de los dashboards';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->option('user');
        $dashboardService = new DashboardService();

        if ($userId) {
            $dashboardService->clearStatsCache($userId);
            $this->info("Caché del dashboard del usuario {$userId} limpiada");
        } else {
            $dashboardService->clearStatsCache();
            $this->info("Caché de todos los dashboards limpiada");
        }
    }
}
