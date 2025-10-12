<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pet;
use App\Models\VaccinationRecord;
use Carbon\Carbon;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener algunas mascotas
        $pets = Pet::take(3)->get();
        
        if ($pets->isEmpty()) {
            $this->command->info('No hay mascotas disponibles para crear citas de prueba.');
            return;
        }
        
        $appointments = [
            [
                'record_type' => 'checkeo',
                'date' => Carbon::now()->addDays(2),
                'time' => '10:00:00',
                'vet_name' => 'Dr. Juan Pérez',
                'location' => 'Clínica Veterinaria San Martín',
                'observations' => 'Revisión general de rutina - Control de peso y estado general'
            ],
            [
                'record_type' => 'vacuna',
                'vaccine_name' => 'Vacuna Rabia',
                'date' => Carbon::now()->addDays(5),
                'time' => '14:30:00',
                'vet_name' => 'Dra. María González',
                'location' => 'Centro Veterinario El Buen Amigo',
                'observations' => 'Aplicación de vacuna antirrábica anual',
                'next_date' => Carbon::now()->addYear()
            ],
            [
                'record_type' => 'peluqueria',
                'date' => Carbon::now()->addDays(7),
                'time' => '09:00:00',
                'vet_name' => 'Técnica Ana López',
                'location' => 'Pet Grooming Center',
                'observations' => 'Baño, corte de uñas y cepillado completo'
            ],
            [
                'record_type' => 'dental',
                'date' => Carbon::now()->addDays(10),
                'time' => '16:00:00',
                'vet_name' => 'Dr. Carlos Rodríguez',
                'location' => 'Clínica Dental Veterinaria',
                'observations' => 'Limpieza dental profesional y revisión de encías',
                'diagnosis' => 'Tartar moderado, requiere limpieza',
                'treatment' => 'Limpieza dental bajo anestesia general'
            ],
            [
                'record_type' => 'checkeo',
                'date' => Carbon::now()->subDays(5),
                'time' => '11:00:00',
                'vet_name' => 'Dr. Juan Pérez',
                'location' => 'Clínica Veterinaria San Martín',
                'observations' => 'Consulta por síntomas de resfriado',
                'diagnosis' => 'Infección respiratoria leve',
                'treatment' => 'Antibiótico por 7 días, reposo y hidratación'
            ]
        ];
        
        foreach ($pets as $index => $pet) {
            // Crear 2-3 citas por mascota
            $appointmentsForPet = array_slice($appointments, $index * 2, 2);
            
            foreach ($appointmentsForPet as $appointmentData) {
                $appointment = VaccinationRecord::create([
                    'pet_id' => $pet->id,
                    'record_type' => $appointmentData['record_type'],
                    'vaccine_name' => $appointmentData['vaccine_name'] ?? null,
                    'date' => $appointmentData['date'],
                    'time' => $appointmentData['time'],
                    'vet_name' => $appointmentData['vet_name'],
                    'location' => $appointmentData['location'],
                    'observations' => $appointmentData['observations'],
                    'diagnosis' => $appointmentData['diagnosis'] ?? null,
                    'treatment' => $appointmentData['treatment'] ?? null,
                    'next_date' => $appointmentData['next_date'] ?? null,
                ]);
                
                $this->command->info("Cita creada para {$pet->nombre}: {$appointmentData['record_type']} el {$appointmentData['date']->format('d/m/Y')}");
            }
        }
        
        $this->command->info('Citas de prueba creadas exitosamente.');
    }
}
