<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class VeterinarioUbicacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ubicaciones de ejemplo para veterinarios
        $ubicaciones = [
            'Panamá, Panamá, Clínica Veterinaria Central',
            'Ciudad de Panamá, Panamá, Hospital Veterinario San José',
            'Panamá, Panamá, Clínica Veterinaria del Mar',
            'Ciudad de Panamá, Panamá, Centro Veterinario Los Pinos',
            'Panamá, Panamá, Clínica Veterinaria El Dorado',
            'Ciudad de Panamá, Panamá, Hospital Veterinario Bella Vista',
            'Panamá, Panamá, Clínica Veterinaria Obarrio',
            'Ciudad de Panamá, Panamá, Centro Veterinario Clayton',
            'Panamá, Panamá, Clínica Veterinaria Costa del Este',
            'Ciudad de Panamá, Panamá, Hospital Veterinario Albrook'
        ];

        // Obtener todos los veterinarios que no tienen ubicación
        $veterinarios = User::whereHas('roles', function($query) {
            $query->where('name', 'veterinario');
        })->whereNull('ubicacion')->get();

        $count = 0;
        foreach ($veterinarios as $veterinario) {
            // Asignar una ubicación aleatoria
            $ubicacion = $ubicaciones[array_rand($ubicaciones)];
            $veterinario->ubicacion = $ubicacion;
            $veterinario->save();
            $count++;
            
            echo "Ubicación asignada a {$veterinario->name}: {$ubicacion}\n";
        }

        echo "Se asignaron ubicaciones a {$count} veterinarios.\n";
    }
}