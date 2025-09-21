<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use Illuminate\Http\Request;

class PublicPetController extends Controller
{
    /**
     * Muestra el perfil público de una mascota por su código QR
     */
    public function showByQrCode($qrCode)
    {
        $pet = Pet::where('qr_code', $qrCode)
            ->with(['user', 'vaccinationRecords' => function($query) {
                $query->orderBy('created_at', 'desc')->limit(5);
            }])
            ->firstOrFail();

        return view('public.pet-profile', compact('pet'));
    }

    /**
     * Muestra el perfil público de una mascota por su ID (para administradores)
     */
    public function show($id)
    {
        $pet = Pet::with(['user', 'vaccinationRecords' => function($query) {
            $query->orderBy('created_at', 'desc')->limit(5);
        }])
        ->findOrFail($id);

        return view('public.pet-profile', compact('pet'));
    }
}

