<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermisosVeterinarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear permisos para asignación de veterinarios
        $permisos = [
            'asignar-veterinarios',
            'gestionar-asignaciones',
            'desasignar-veterinarios',
            'ver-asignaciones',
            'gestionar-historial-medico',
            'ver-historial-medico',
            'crear-vacunas',
            'editar-vacunas',
            'eliminar-vacunas',
        ];

        foreach ($permisos as $permiso) {
            Permission::firstOrCreate(['name' => $permiso]);
        }

        // Asignar permisos al rol de administrador
        $adminRole = Role::where('name', 'administrador')->first();
        if ($adminRole) {
            $adminRole->givePermissionTo([
                'asignar-veterinarios',
                'gestionar-asignaciones',
                'desasignar-veterinarios',
                'ver-asignaciones',
                'gestionar-historial-medico',
                'ver-historial-medico',
                'crear-vacunas',
                'editar-vacunas',
                'eliminar-vacunas',
            ]);
        }

        // Asignar permisos al rol de super_admin
        $superAdminRole = Role::where('name', 'super_admin')->first();
        if ($superAdminRole) {
            $superAdminRole->givePermissionTo([
                'asignar-veterinarios',
                'gestionar-asignaciones',
                'desasignar-veterinarios',
                'ver-asignaciones',
                'gestionar-historial-medico',
                'ver-historial-medico',
                'crear-vacunas',
                'editar-vacunas',
                'eliminar-vacunas',
            ]);
        }

        // Asignar permisos específicos al rol de veterinario
        $veterinarioRole = Role::where('name', 'veterinario')->first();
        if ($veterinarioRole) {
            $veterinarioRole->givePermissionTo([
                'ver-asignaciones',
                'gestionar-historial-medico',
                'ver-historial-medico',
                'crear-vacunas',
                'editar-vacunas',
                'eliminar-vacunas',
            ]);
        }

        // Asignar permisos de solo lectura al rol de cliente
        $clienteRole = Role::where('name', 'cliente_qr')->first();
        if ($clienteRole) {
            $clienteRole->givePermissionTo([
                'ver-historial-medico',
            ]);
        }

        $this->command->info('Permisos de veterinarios creados y asignados exitosamente.');
    }
}