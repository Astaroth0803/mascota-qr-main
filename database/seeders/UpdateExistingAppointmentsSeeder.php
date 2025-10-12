<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VaccinationRecord;
use App\Models\User;

class UpdateExistingAppointmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener el primer veterinario disponible
        $veterinarian = User::whereHas('roles', function($query) {
            $query->where('name', 'veterinario');
        })->first();
        
        if (!$veterinarian) {
            $this->command->info('No se encontró ningún veterinario en la base de datos.');
            return;
        }
        
        // Actualizar todas las citas existentes para asignarlas al veterinario
        $updatedCount = VaccinationRecord::whereNull('veterinarian_id')
            ->update(['veterinarian_id' => $veterinarian->id]);
        
        $this->command->info("Se actualizaron {$updatedCount} citas existentes asignándolas al veterinario: {$veterinarian->name}");
    }
}
