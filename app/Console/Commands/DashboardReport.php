<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DashboardService;
use App\Models\User;

class DashboardReport extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'dashboard:report {--user= : ID del usuario específico}';

    /**
     * The console command description.
     */
    protected $description = 'Genera un reporte detallado del dashboard';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->option('user');
        $dashboardService = new DashboardService();

        $this->info("=== REPORTE DE DASHBOARD ===");
        $this->newLine();

        if ($userId) {
            $user = User::find($userId);
            if (!$user) {
                $this->error("Usuario con ID {$userId} no encontrado");
                return;
            }

            $this->info("📊 DASHBOARD DEL USUARIO: {$user->name}");
            $this->line("─────────────────────────────────────");
            
            $stats = $dashboardService->getClientStats($userId);
            $notifications = $dashboardService->getClientNotifications($userId);

            $this->line("Total de mascotas: {$stats['total_pets']}");
            $this->line("Mascotas con QR: {$stats['pets_with_qr']} ({$stats['qr_coverage']}%)");
            $this->line("Próximas vacunas: {$stats['upcoming_vaccines']->count()}");
            $this->line("Vacunas vencidas: {$stats['overdue_vaccines']}");
            $this->line("Notificaciones: {$notifications->count()}");
            
            if ($stats['species_distribution']->count() > 0) {
                $this->newLine();
                $this->info("Distribución por especie:");
                foreach ($stats['species_distribution'] as $species => $count) {
                    $this->line("  {$species}: {$count}");
                }
            }

        } else {
            $this->info("📊 DASHBOARD ADMINISTRATIVO");
            $this->line("─────────────────────────────");
            
            $stats = $dashboardService->getAdminStats();
            $alerts = $dashboardService->getSecurityAlerts();

            $this->line("Total de usuarios: {$stats['total_users']}");
            $this->line("Total de mascotas: {$stats['total_pets']}");
            $this->line("Solicitudes pendientes: {$stats['pending_solicitudes']}");
            $this->line("Mascotas verificadas: {$stats['verified_pets']}");
            $this->line("Mascotas sin QR: {$stats['pets_without_qr']}");
            $this->line("Usuarios inactivos: {$stats['inactive_users']}");
            $this->line("Alertas de seguridad: " . count($alerts));

            $this->newLine();
            $this->info("Actividad reciente (7 días):");
            $this->line("  Nuevos usuarios: {$stats['recent_activity']['new_users']}");
            $this->line("  Nuevas mascotas: {$stats['recent_activity']['new_pets']}");
            $this->line("  Nuevas solicitudes: {$stats['recent_activity']['new_solicitudes']}");
            $this->line("  Códigos QR generados: {$stats['recent_activity']['qr_generated']}");

            if ($stats['species_distribution']->count() > 0) {
                $this->newLine();
                $this->info("Distribución por especie:");
                foreach ($stats['species_distribution'] as $species => $count) {
                    $this->line("  {$species}: {$count}");
                }
            }

            if (count($alerts) > 0) {
                $this->newLine();
                $this->info("Alertas de seguridad:");
                foreach ($alerts as $alert) {
                    $this->line("  ⚠️  {$alert['title']}: {$alert['message']}");
                }
            }
        }

        $this->newLine();
        $this->info("Reporte generado exitosamente");
    }
}
