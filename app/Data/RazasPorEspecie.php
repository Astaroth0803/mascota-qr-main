<?php

namespace App\Data;

class RazasPorEspecie
{
    public static function getRazas(string $especie): array
    {
        return match ($especie) {
            'Perro' => [
                'Labrador Retriever',
                'Pastor Alemán',
                'Golden Retriever',
                'Bulldog',
                'Beagle',
                'Poodle',
                'Chihuahua',
                'Husky Siberiano',
                'Boxer',
                'Dachshund',
                'Otra'
            ],
            'Gato' => [
                'Siamés',
                'Persa',
                'Maine Coon',
                'Bengalí',
                'Ragdoll',
                'Esfinge',
                'British Shorthair',
                'Abisinio',
                'Birmano',
                'Otra'
            ],
            'Conejo' => [
                'Holandés',
                'Mini Lop',
                'Angora',
                'Rex',
                'Cabeza de León',
                'Otra'
            ],
            'Ave' => [
                'Canario',
                'Periquito',
                'Cacatúa',
                'Loro',
                'Agapornis',
                'Otra'
            ],
            'Hamster' => [
                'Sirio',
                'Roborovski',
                'Ruso',
                'Chino',
                'Otra'
            ],
            default => []
        };
    }
} 