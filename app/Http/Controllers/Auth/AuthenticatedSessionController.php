<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Autenticación
        $request->authenticate();

        // Regenerar la sesión
        $request->session()->regenerate();

        // ✅ Actualizar la columna 'verificado' si es necesario
        $user = Auth::user(); // Assuming your User model is App\Models\User
        $user = \App\Models\User::find($user->id);
        if ($user->verificado == 0) {
            $user->verificado = 1;
            $user->save();
        }

        // Redirigir a la ruta deseada (dashboard)
        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
