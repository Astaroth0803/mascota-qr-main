<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Vaccine;

class VaccineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vaccines = [
            // Zoetis
            ['nombre_tecnico' => 'Rabia', 'nombre_comercial' => 'Defensor 1', 'laboratorio' => 'Zoetis', 'especie' => 'perro'],
            ['nombre_tecnico' => 'Giardia Canina', 'nombre_comercial' => 'Giardia Vax', 'laboratorio' => 'Zoetis', 'especie' => 'perro'],
            ['nombre_tecnico' => 'Bordetella Canina', 'nombre_comercial' => 'Bronchicine', 'laboratorio' => 'Zoetis', 'especie' => 'perro'],
            ['nombre_tecnico' => 'Múltiple', 'nombre_comercial' => 'Vanguard Plus 5L4 CV', 'laboratorio' => 'Zoetis', 'especie' => 'perro'],
            
            // MSD
            ['nombre_tecnico' => 'Triple Felino', 'nombre_comercial' => 'Nobivac Feline I-HPC 1DS', 'laboratorio' => 'MSD', 'especie' => 'gato'],
            ['nombre_tecnico' => 'Parvovirus Canino', 'nombre_comercial' => 'Nobivac PV', 'laboratorio' => 'MSD', 'especie' => 'perro'],
            ['nombre_tecnico' => 'Bordetella Intranasal', 'nombre_comercial' => 'Nobivac KC', 'laboratorio' => 'MSD', 'especie' => 'perro'],
            ['nombre_tecnico' => 'Rabia', 'nombre_comercial' => 'Nobivac Rabia', 'laboratorio' => 'MSD', 'especie' => 'perro'],
            ['nombre_tecnico' => 'Quintuple Felina', 'nombre_comercial' => 'Nobivac Feline HCPCH + FELV', 'laboratorio' => 'MSD', 'especie' => 'gato'],
            ['nombre_tecnico' => 'Triple Felina', 'nombre_comercial' => 'Nobivac Feline Triple 1-HCP', 'laboratorio' => 'MSD', 'especie' => 'gato'],
            ['nombre_tecnico' => 'Bordetella Oral', 'nombre_comercial' => 'Nobivac Intra -Trac Oral BB', 'laboratorio' => 'MSD', 'especie' => 'perro'],
            ['nombre_tecnico' => 'Puppy Distemper con Parvovirus', 'nombre_comercial' => 'Nobivac Puppy DP Plus', 'laboratorio' => 'MSD', 'especie' => 'perro'],
            ['nombre_tecnico' => 'Múltiple + 2 Leptospira', 'nombre_comercial' => 'Quantun Multiple Nobivac Canine Dappvl2', 'laboratorio' => 'MSD', 'especie' => 'perro'],
            ['nombre_tecnico' => 'Múltiple+2Leptospira+Coronavirus', 'nombre_comercial' => 'Quantun Multiple Nobivac Canine Dappvl2+ Cv', 'laboratorio' => 'MSD', 'especie' => 'perro'],
        ];

        foreach ($vaccines as $vaccine) {
            Vaccine::create($vaccine);
        }
    }
}
