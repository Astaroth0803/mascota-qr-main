<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\ContactForm;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SolicitudController;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

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

// API endpoints para validación en tiempo real
Route::post('/api/validate-qr-code', [PetController::class, 'validateQRCodeRealTime'])->middleware('throttle:20,1')->name('api.validate-qr-code');

// Authentication routes (from auth.php)
require __DIR__.'/auth.php';

// Ruta para registrar una solicitud (pública)
Route::post('/mascotaqr', [SolicitudController::class, 'store'])->middleware('throttle:3,1')->name('solicitudes.store');

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

    // Rutas del dashboard para clientes (solo para clientes_qr)
    Route::middleware(['role:cliente_qr', 'throttle:60,1'])->prefix('dashboard/cliente')->name('dashboard.cliente.')->group(function () {
        Route::get('/', [PetController::class, 'dashboardCliente'])->name('index');
        Route::get('/nuevo', [PetController::class, 'dashboardCliente'])->name('nuevo');
        Route::get('/registrar-mascota', [PetController::class, 'create'])->name('registrar.mascota');
        Route::get('/mascotas/{pet:slug}', [PetController::class, 'show'])->name('mascotas.show');
        Route::get('/mascotas/{pet:slug}/edit', [PetController::class, 'edit'])->name('mascotas.edit');
        Route::put('/mascotas/{pet:slug}', [PetController::class, 'update'])->middleware('throttle:10,1')->name('mascotas.update');
        Route::put('/mascotas/{pet:slug}/image', [PetController::class, 'updateImage'])->middleware('throttle:5,1')->name('mascotas.update-image');
        Route::get('/mascotas/{pet:slug}/vaccination-history', [PetController::class, 'showVaccinationHistory'])->name('mascotas.vaccination-history');
        Route::post('/mascotas/{pet:slug}/vaccination-records', [PetController::class, 'storeVaccinationRecord'])->middleware('throttle:20,1')->name('mascotas.vaccination-records.store');
        Route::post('/solicitudes/store-pet', [SolicitudController::class, 'storePetRequest'])->middleware('throttle:3,1')->name('solicitudes.store-pet');
        
        // Rutas para solicitudes de mascotas (para clientes)
        Route::prefix('pet-requests')->name('pet-requests.')->group(function () {
            Route::get('/', [App\Http\Controllers\PetRequestController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\PetRequestController::class, 'create'])->name('create');
            Route::post('/store', [App\Http\Controllers\PetRequestController::class, 'store'])->name('store');
            Route::get('/{petRequest}', [App\Http\Controllers\PetRequestController::class, 'show'])->name('show');
        });
        
        // Ruta para notificaciones del cliente
        Route::get('/notificaciones', [PetController::class, 'clientNotifications'])->name('notificaciones');
        Route::post('/mascotas/{pet:slug}/generate-qr', [PetController::class, 'generateQRCode'])->middleware('throttle:10,1')->name('mascotas.generate-qr');

        // Rutas para depuración
        Route::get('/mascotas/{pet:slug}/debug-records', [PetController::class, 'debugRecords'])->name('mascotas.debug-records');
        Route::get('/mascotas/{pet:slug}/debug-refresh', [PetController::class, 'debugRefreshCache'])->name('mascotas.debug-refresh');
    });

    // Rutas del dashboard para administradores (solo para administradores y super_admins)
    Route::prefix('dashboard/administrador')->middleware('role:administrador|super_admin')->group(function () {
        Route::get('/', [PetController::class, 'adminDashboard'])->name('dashboard.administrador');

        // Rutas para solicitudes
        Route::get('solicitudes', [SolicitudController::class, 'index'])->name('dashboard.solicitudes');
        Route::patch('solicitudes/accept/{id}', [SolicitudController::class, 'accept'])->name('solicitudes.accept');
        Route::delete('solicitudes/reject/{id}', [SolicitudController::class, 'reject'])->name('solicitudes.reject');
        Route::get('solicitudes/{id}', [SolicitudController::class, 'show'])->name('solicitudes.show');

        // Rutas para gestionar usuarios
        Route::prefix('usuarios')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('dashboard.usuarios');
            Route::get('create', [UserController::class, 'create'])->name('usuarios.create');
            Route::post('/', [UserController::class, 'store'])->name('usuarios.store');
            Route::get('{id}/edit', [UserController::class, 'edit'])->name('usuarios.edit');
            Route::patch('{id}', [UserController::class, 'update'])->name('usuarios.update');
            Route::delete('{id}', [UserController::class, 'destroy'])->name('usuarios.destroy');

            // Reset password routes
            Route::post('{id}/reset-password', [UserController::class, 'resetPassword'])->name('usuarios.resetPassword');
            Route::get('{id}/edit-password', [UserController::class, 'editPassword'])->name('usuarios.editPassword');
            Route::patch('{id}/edit-password', [UserController::class, 'updatePassword'])->name('usuarios.updatePassword');

            // Role and permissions routes
            Route::get('{id}/edit-roles', [UserController::class, 'editRoles'])->name('usuarios.editRoles');
            Route::patch('{id}/edit-roles', [UserController::class, 'updateRoles'])->name('usuarios.updateRoles');
            Route::get('{id}/edit-permissions', [UserController::class, 'editPermissions'])->name('usuarios.editPermissions');
            Route::patch('{id}/edit-permissions', [UserController::class, 'updatePermissions'])->name('usuarios.updatePermissions');

            // Profile edit route
            Route::get('{id}/edit-profile', [UserController::class, 'editProfile'])->name('usuarios.editProfile');

            // Mascotas del usuario
            Route::get('{id}/mascotas', [PetController::class, 'showUserPets'])->name('usuarios.mascotas');
        });

        // Rutas para códigos QR
        Route::prefix('qr')->group(function () {
            Route::get('/generator', [PetController::class, 'qrGenerator'])->name('qr.generator');
            Route::post('/generate-single', [PetController::class, 'generateSingleQRCode'])->name('qr.generate-single');
            Route::post('/generate-multiple', [PetController::class, 'generateMultipleQRCodes'])->name('qr.generate-multiple');
        });

    // Ruta para el log de actividades
    Route::get('/activity-log', [PetController::class, 'activityLog'])->name('dashboard.activity-log');
    
    // Rutas para solicitudes de mascotas (solo para administradores)
    Route::prefix('pet-requests')->name('pet-requests.')->group(function () {
        Route::get('/', [App\Http\Controllers\PetRequestController::class, 'adminIndex'])->name('index');
        Route::get('/{petRequest}', [App\Http\Controllers\PetRequestController::class, 'show'])->name('show');
        Route::post('/{petRequest}/approve', [App\Http\Controllers\PetRequestController::class, 'approve'])->name('approve');
        Route::post('/{petRequest}/reject', [App\Http\Controllers\PetRequestController::class, 'reject'])->name('reject');
    });
    });

    // Rutas del dashboard para veterinarios (solo para veterinarios con permisos)
    Route::prefix('dashboard/veterinario')->middleware(['role:veterinario', 'permission:ver-asignaciones'])->group(function () {
        Route::get('/', [App\Http\Controllers\VeterinarioController::class, 'dashboard'])->name('dashboard.veterinario');
        
        // Gestión de mascotas asignadas
        Route::get('mascotas', [App\Http\Controllers\VeterinarioController::class, 'mascotas'])->name('dashboard.veterinario.mascotas');
        Route::get('mascotas/{pet}', [App\Http\Controllers\VeterinarioController::class, 'showMascota'])->middleware('permission:ver-historial-medico')->name('dashboard.veterinario.mascota.show');
        
        // Gestión del historial médico
        Route::get('mascotas/{pet}/historial', [App\Http\Controllers\VeterinarioController::class, 'gestionarHistorial'])->middleware('permission:gestionar-historial-medico')->name('dashboard.veterinario.historial');
        Route::post('mascotas/{pet}/vacunas', [App\Http\Controllers\VeterinarioController::class, 'agregarVacuna'])->middleware('permission:crear-vacunas')->name('dashboard.veterinario.vacunas.store');
        Route::put('mascotas/{pet}/vacunas/{vacuna}', [App\Http\Controllers\VeterinarioController::class, 'actualizarVacuna'])->middleware('permission:editar-vacunas')->name('dashboard.veterinario.vacunas.update');
        Route::delete('mascotas/{pet}/vacunas/{vacuna}', [App\Http\Controllers\VeterinarioController::class, 'eliminarVacuna'])->middleware('permission:eliminar-vacunas')->name('dashboard.veterinario.vacunas.destroy');
    });

    // Rutas para asignación de veterinarios (solo para administradores con permisos)
    Route::prefix('dashboard/administrador')->middleware(['role:administrador', 'permission:asignar-veterinarios'])->group(function () {
        Route::get('asignar-veterinario', [App\Http\Controllers\AsignacionVeterinarioController::class, 'index'])->middleware('permission:ver-asignaciones')->name('dashboard.administrador.asignar-veterinario');
        Route::post('asignar-veterinario', [App\Http\Controllers\AsignacionVeterinarioController::class, 'store'])->middleware('permission:gestionar-asignaciones')->name('dashboard.administrador.asignar-veterinario.store');
        Route::post('asignar-veterinario/manage', [App\Http\Controllers\AsignacionVeterinarioController::class, 'manage'])->middleware('permission:gestionar-asignaciones')->name('dashboard.administrador.asignar-veterinario.manage');
        Route::delete('asignar-veterinario/{mascota}/{veterinario}', [App\Http\Controllers\AsignacionVeterinarioController::class, 'desasignar'])->middleware('permission:desasignar-veterinarios')->name('dashboard.administrador.asignar-veterinario.desasignar');
        Route::get('asignar-veterinario/{mascota}/veterinarios', [App\Http\Controllers\AsignacionVeterinarioController::class, 'getVeterinariosDisponibles'])->middleware('permission:ver-asignaciones')->name('dashboard.administrador.asignar-veterinario.veterinarios');
    });

    // Rutas de perfil (para todos los usuarios autenticados)
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    // Vista mejorada del dashboard del cliente
    Route::get('/dashboard/cliente/nuevo', [\App\Http\Controllers\Client\DashboardController::class, 'index'])
        ->middleware('role:cliente_qr') // Aplicamos el middleware de rol
        ->name('dashboard.cliente.nuevo');
});
