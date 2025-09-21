<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Crear permisos si no existen
        $permissions = [
            'ver solicitudes',
            'verificar pagos',
            'rechazar solicitudes',
            'ver perfil',
            'ver_mascotas',
            'ver_solicitudes_mascotas_adicionales',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Crear roles si no existen
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin']);
        $adminRole = Role::firstOrCreate(['name' => 'administrador']);
        $clienteQrRole = Role::firstOrCreate(['name' => 'cliente_qr']);

        
        // Asignar todos los permisos al rol de administrador
        $adminRole->syncPermissions(Permission::all());
        $superAdminRole->syncPermissions(Permission::all());

        // Asignar permisos específicos al rol 'cliente_qr'
        $clienteQrRole->syncPermissions(['ver perfil', 'ver_mascotas']);
    }
}
