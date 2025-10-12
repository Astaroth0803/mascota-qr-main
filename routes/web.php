<?php

use  Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Pet\PetController;
use App\Http\Controllers\ContactForm;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AppointmentRequestController;
use App\Http\Controllers\BroadcastingController;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

// Broadcasting authentication
Route::post('/broadcasting/auth', [BroadcastingController::class, 'auth'])->middleware('auth');

// Rutas para notificaciones (alternativa a API)
Route::middleware('auth')->group(function () {
    Route::get('/notifications/unread', [App\Http\Controllers\AppointmentNotificationController::class, 'getUnread']);
    Route::get('/notifications/unread-count', [App\Http\Controllers\AppointmentNotificationController::class, 'getUnreadCount']);
    Route::post('/notifications/{notification}/mark-read', [App\Http\Controllers\AppointmentNotificationController::class, 'markAsRead']);
    Route::post('/notifications/mark-all-read', [App\Http\Controllers\AppointmentNotificationController::class, 'markAllAsRead']);
});

// Static pages (public access)
Route::view('/', 'home')->name('home');
Route::view('/comunidad', 'comunidad')->name('comunidad');
Route::view('/about', 'about')->name('about');
Route::view('/mascotaqr', 'mascotaqr')->name('mascotaqr');
Route::view('/comprarealizada', 'comprarealizada')->name('comprarealizada');
Route::view('/tienda', 'tienda')->name('tienda');

// Rutas públicas para perfiles de mascotas
Route::get('/pet/qr/{qrCode}', [App\Http\Controllers\PublicPetController::class, 'showByQrCode'])->name('public.pet.qr');
Route::get('/pet/{id}', [App\Http\Controllers\PublicPetController::class, 'show'])->name('public.pet.show');


// Contact form routes (public access)
Route::get('/contactanos', function () {
    return view('contactanos');
})->name('contactanos');
Route::post('/contactanos', [ContactForm::class, 'store'])->middleware('throttle:3,1')->name('contactanos.store');


// Authentication routes (from auth.php)
require __DIR__.'/auth.php';

// Ruta para registrar una solicitud (pública) - Redirigir al formulario de mascota
Route::post('/mascotaqr', function() {
    return redirect()->route('dashboard.cliente.registrar.mascota');
})->middleware('throttle:3,1')->name('solicitudes.store');

// Authenticated routes (require login)
Route::middleware('auth')->group(function () {
    // Ruta base para el dashboard (redirige según el rol)
    Route::get('/dashboard', function () {
        /** @var \App\Models\User */
        $user = Auth::user();

        if ($user->hasRole('cliente_qr')) {
            return redirect()->route('dashboard.cliente.index');
        } elseif ($user->hasRole('veterinario')) {
            return redirect()->route('dashboard.veterinario');
        } elseif ($user->hasAnyRole(['administrador', 'super_admin'])) {
            return redirect()->route('dashboard.administrador');
        }
        return redirect('/');
    })->name('dashboard');

    // Rutas de perfil (para todos los usuarios autenticados)
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    // Rutas de notificaciones - Usar AppointmentNotificationController
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [App\Http\Controllers\AppointmentNotificationController::class, 'index'])->name('index');
        Route::post('/{id}/mark-read', [App\Http\Controllers\AppointmentNotificationController::class, 'markAsRead'])->name('mark-read');
        Route::post('/mark-all-read', [App\Http\Controllers\AppointmentNotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::get('/unread-count', [App\Http\Controllers\AppointmentNotificationController::class, 'getUnreadCount'])->name('unread-count');
    });

    // Incluir rutas organizadas por funcionalidad
    require __DIR__.'/client.php';
    require __DIR__.'/admin.php';
    require __DIR__.'/veterinarian.php';
    require __DIR__.'/citas.php';
    require __DIR__.'/api.php';
});
