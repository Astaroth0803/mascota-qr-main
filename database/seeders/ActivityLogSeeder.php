<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ActivityLog;
use App\Models\User;
use App\Models\Pet;
use App\Models\Solicitud;
use Carbon\Carbon;

class ActivityLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener usuarios existentes
        $users = User::all();
        
        if ($users->isEmpty()) {
            $this->command->warn('No hay usuarios en la base de datos. Creando usuario de ejemplo...');
            $user = User::create([
                'name' => 'Administrador',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
            ]);
            $users = collect([$user]);
        }

        $admin = $users->first();

        // Crear logs de ejemplo para los últimos 30 días
        $activities = [
            // Actividades de usuarios
            [
                'action' => 'created',
                'description' => 'Usuario creado: Juan Pérez',
                'model_type' => 'App\Models\User',
                'model_id' => 1,
                'old_values' => null,
                'new_values' => ['name' => 'Juan Pérez', 'email' => 'juan@example.com'],
                'created_at' => Carbon::now()->subDays(25)->subHours(2),
            ],
            [
                'action' => 'updated',
                'description' => 'Perfil de usuario actualizado',
                'model_type' => 'App\Models\User',
                'model_id' => 1,
                'old_values' => ['name' => 'Juan Pérez'],
                'new_values' => ['name' => 'Juan Carlos Pérez'],
                'created_at' => Carbon::now()->subDays(20)->subHours(5),
            ],
            [
                'action' => 'login',
                'description' => 'Inicio de sesión exitoso',
                'model_type' => null,
                'model_id' => null,
                'old_values' => null,
                'new_values' => ['ip' => '192.168.1.100'],
                'created_at' => Carbon::now()->subDays(15)->subHours(3),
            ],
            [
                'action' => 'logout',
                'description' => 'Cierre de sesión',
                'model_type' => null,
                'model_id' => null,
                'old_values' => null,
                'new_values' => null,
                'created_at' => Carbon::now()->subDays(15)->subHours(1),
            ],

            // Actividades de mascotas
            [
                'action' => 'created',
                'description' => 'Mascota registrada: Max (Perro)',
                'model_type' => 'App\Models\Pet',
                'model_id' => 1,
                'old_values' => null,
                'new_values' => ['nombre' => 'Max', 'especie' => 'Perro', 'raza' => 'Labrador'],
                'created_at' => Carbon::now()->subDays(18)->subHours(4),
            ],
            [
                'action' => 'updated',
                'description' => 'Información de mascota actualizada',
                'model_type' => 'App\Models\Pet',
                'model_id' => 1,
                'old_values' => ['edad_anios' => 2],
                'new_values' => ['edad_anios' => 3],
                'created_at' => Carbon::now()->subDays(12)->subHours(6),
            ],
            [
                'action' => 'created',
                'description' => 'Mascota registrada: Luna (Gato)',
                'model_type' => 'App\Models\Pet',
                'model_id' => 2,
                'old_values' => null,
                'new_values' => ['nombre' => 'Luna', 'especie' => 'Gato', 'raza' => 'Persa'],
                'created_at' => Carbon::now()->subDays(10)->subHours(2),
            ],

            // Actividades de solicitudes
            [
                'action' => 'created',
                'description' => 'Solicitud de registro recibida',
                'model_type' => 'App\Models\Solicitud',
                'model_id' => 1,
                'old_values' => null,
                'new_values' => ['nombre' => 'Bella', 'especie' => 'Perro', 'raza' => 'Golden Retriever'],
                'created_at' => Carbon::now()->subDays(8)->subHours(3),
            ],
            [
                'action' => 'updated',
                'description' => 'Solicitud aprobada',
                'model_type' => 'App\Models\Solicitud',
                'model_id' => 1,
                'old_values' => ['status' => 'pending'],
                'new_values' => ['status' => 'approved'],
                'created_at' => Carbon::now()->subDays(7)->subHours(1),
            ],
            [
                'action' => 'deleted',
                'description' => 'Solicitud rechazada y eliminada',
                'model_type' => 'App\Models\Solicitud',
                'model_id' => 2,
                'old_values' => ['status' => 'pending'],
                'new_values' => null,
                'created_at' => Carbon::now()->subDays(5)->subHours(2),
            ],

            // Actividades del sistema
            [
                'action' => 'system',
                'description' => 'Backup de base de datos completado',
                'model_type' => null,
                'model_id' => null,
                'old_values' => null,
                'new_values' => ['backup_size' => '2.5MB'],
                'created_at' => Carbon::now()->subDays(3)->subHours(1),
            ],
            [
                'action' => 'security',
                'description' => 'Intento de acceso no autorizado detectado',
                'model_type' => null,
                'model_id' => null,
                'old_values' => null,
                'new_values' => ['ip' => '192.168.1.200', 'attempts' => 3],
                'created_at' => Carbon::now()->subDays(2)->subHours(4),
            ],
            [
                'action' => 'maintenance',
                'description' => 'Mantenimiento programado ejecutado',
                'model_type' => null,
                'model_id' => null,
                'old_values' => null,
                'new_values' => ['duration' => '15 minutes'],
                'created_at' => Carbon::now()->subDays(1)->subHours(2),
            ],

            // Actividades recientes
            [
                'action' => 'login',
                'description' => 'Inicio de sesión exitoso',
                'model_type' => null,
                'model_id' => null,
                'old_values' => null,
                'new_values' => ['ip' => '192.168.1.150'],
                'created_at' => Carbon::now()->subHours(6),
            ],
            [
                'action' => 'created',
                'description' => 'Nueva mascota registrada: Rocky (Perro)',
                'model_type' => 'App\Models\Pet',
                'model_id' => 3,
                'old_values' => null,
                'new_values' => ['nombre' => 'Rocky', 'especie' => 'Perro', 'raza' => 'Bulldog'],
                'created_at' => Carbon::now()->subHours(3),
            ],
            [
                'action' => 'updated',
                'description' => 'Configuración del sistema actualizada',
                'model_type' => null,
                'model_id' => null,
                'old_values' => ['max_file_size' => '5MB'],
                'new_values' => ['max_file_size' => '10MB'],
                'created_at' => Carbon::now()->subHours(1),
            ],
        ];

        foreach ($activities as $activity) {
            ActivityLog::create([
                'user_id' => $admin->id,
                'action' => $activity['action'],
                'description' => $activity['description'],
                'model_type' => $activity['model_type'],
                'model_id' => $activity['model_id'],
                'old_values' => $activity['old_values'],
                'new_values' => $activity['new_values'],
                'created_at' => $activity['created_at'],
                'updated_at' => $activity['created_at'],
            ]);
        }

        $this->command->info('Se han creado ' . count($activities) . ' registros de actividad de ejemplo.');
    }
}