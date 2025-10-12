<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AsignacionVeterinarioController;
use App\Http\Controllers\Pet\PetController;
use App\Http\Controllers\Request\PetRequestController;

// Rutas del dashboard para administradores (solo para administradores y super_admins)
Route::prefix('dashboard/administrador')->middleware('role:administrador|super_admin')->group(function () {
    Route::get('/', [PetController::class, 'adminDashboard'])->name('dashboard.administrador');

    // Rutas para solicitudes (usando PetRequestController)
    Route::get('solicitudes', [PetRequestController::class, 'adminIndex'])->name('dashboard.solicitudes');
    Route::patch('solicitudes/accept/{id}', [PetRequestController::class, 'approve'])->name('solicitudes.accept');
    Route::delete('solicitudes/reject/{id}', [PetRequestController::class, 'reject'])->name('solicitudes.reject');
    Route::get('solicitudes/{id}', [PetRequestController::class, 'show'])->name('solicitudes.show');

    // Rutas para gestionar usuarios
    Route::prefix('usuarios')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('dashboard.usuarios');
        Route::get('/table', function () {
            return view('admin.users-table');
        })->name('dashboard.usuarios.table');
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
        Route::get('/', [PetRequestController::class, 'adminIndex'])->name('index');
        Route::get('/{petRequest}', [PetRequestController::class, 'show'])->name('show');
        Route::post('/{petRequest}/approve', [PetRequestController::class, 'approve'])->name('approve');
        Route::post('/{petRequest}/reject', [PetRequestController::class, 'reject'])->name('reject');
    });

    // Rutas para asignación de veterinarios (solo para administradores con permisos)
    Route::prefix('asignar-veterinario')->middleware(['permission:asignar-veterinarios'])->group(function () {
        Route::get('/', [AsignacionVeterinarioController::class, 'index'])->middleware('permission:ver-asignaciones')->name('dashboard.administrador.asignar-veterinario');
        Route::post('/', [AsignacionVeterinarioController::class, 'store'])->middleware('permission:gestionar-asignaciones')->name('dashboard.administrador.asignar-veterinario.store');
        Route::post('/manage', [AsignacionVeterinarioController::class, 'manage'])->middleware('permission:gestionar-asignaciones')->name('dashboard.administrador.asignar-veterinario.manage');
        Route::delete('/{mascota}/{veterinario}', [AsignacionVeterinarioController::class, 'desasignar'])->middleware('permission:desasignar-veterinarios')->name('dashboard.administrador.asignar-veterinario.desasignar');
        Route::get('/{mascota}/veterinarios', [AsignacionVeterinarioController::class, 'getVeterinariosDisponibles'])->middleware('permission:ver-asignaciones')->name('dashboard.administrador.asignar-veterinario.veterinarios');
    });
});
