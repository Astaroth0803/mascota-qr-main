<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class TestClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear un usuario cliente de prueba
        $cliente = User::firstOrCreate(
            ['email' => 'cliente@test.com'],
            [
                'name' => 'Cliente Test',
                'password' => bcrypt('password'),
                'slug' => 'cliente-test',
            ]
        );

        // Asignar el rol cliente_qr
        $clienteRole = Role::where('name', 'cliente_qr')->first();
        if ($clienteRole && !$cliente->hasRole('cliente_qr')) {
            $cliente->assignRole('cliente_qr');
            echo "Usuario cliente creado: {$cliente->name} ({$cliente->email})\n";
        }

        // Crear un veterinario de prueba
        $veterinario = User::firstOrCreate(
            ['email' => 'veterinario@test.com'],
            [
                'name' => 'Dr. Test Veterinario',
                'password' => bcrypt('password'),
                'tipo_veterinario' => 'licenciado',
                'slug' => 'dr-test-veterinario',
            ]
        );

        // Asignar el rol veterinario
        $veterinarioRole = Role::where('name', 'veterinario')->first();
        if ($veterinarioRole && !$veterinario->hasRole('veterinario')) {
            $veterinario->assignRole('veterinario');
            echo "Usuario veterinario creado: {$veterinario->name} ({$veterinario->email})\n";
        }
    }
}
