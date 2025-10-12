<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class VeterinarioTipoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Actualizar veterinarios existentes sin tipo asignado
        $veterinarios = User::whereHas('roles', function($query) {
            $query->where('name', 'veterinario');
        })->whereNull('tipo_veterinario')->get();

        foreach ($veterinarios as $veterinario) {
            // Asignar tipo por defecto como "licenciado"
            $veterinario->update(['tipo_veterinario' => User::TIPO_LICENCIADO]);
            $this->command->info("Actualizado veterinario: {$veterinario->name} -> Lic. Veterinario");
        }

        $this->command->info("Se actualizaron {$veterinarios->count()} veterinarios con tipo por defecto.");
    }
}