<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        
        // Determinar qué vista usar según el rol
        if ($user->hasRole('veterinario')) {
            return view('profile.veterinario.edit', [
                'user' => $user,
                'tiposVeterinarios' => \App\Models\User::getTiposVeterinarios(),
            ]);
        } elseif ($user->hasRole('cliente_qr')) {
            return view('profile.cliente.edit', [
                'user' => $user,
            ]);
        } else {
            // Administradores y otros roles
            return view('profile.admin.edit', [
                'user' => $user,
            ]);
        }
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        
        // Actualizar campos básicos
        $user->fill($request->validated());

        // Actualizar campos específicos según el rol
        if ($user->hasRole('veterinario')) {
            $user->tipo_veterinario = $request->input('tipo_veterinario');
            $user->ubicacion = $request->input('ubicacion');
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
