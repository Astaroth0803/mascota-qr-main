<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Pet\PetController;
use App\Http\Controllers\Request\PetRequestController;
use App\Http\Controllers\Client\AppointmentController;
use App\Http\Controllers\Client\QRController;
use App\Http\Controllers\Client\VeterinarioController;
use App\Http\Controllers\Client\DashboardController;
use App\Http\Controllers\AppointmentNotificationController;

// Rutas del dashboard para clientes (solo para clientes_qr)
Route::middleware(['role:cliente_qr', 'throttle:60,1'])->prefix('dashboard/cliente')->name('dashboard.cliente.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');
    Route::get('/nuevo', [DashboardController::class, 'index'])->name('nuevo');
    Route::get('/registrar-mascota', [PetController::class, 'create'])->name('registrar.mascota');
    Route::get('/mascotas/{pet:slug}', [PetController::class, 'show'])->name('mascotas.show');
    Route::get('/mascotas/{pet:slug}/edit', [PetController::class, 'edit'])->name('mascotas.edit');
    Route::put('/mascotas/{pet:slug}', [PetController::class, 'update'])->middleware('throttle:10,1')->name('mascotas.update');
    Route::put('/mascotas/{pet:slug}/image', [PetController::class, 'updateImage'])->middleware('throttle:5,1')->name('mascotas.update-image');
    Route::get('/mascotas/{pet:slug}/vaccination-history', [PetController::class, 'showVaccinationHistory'])->name('mascotas.vaccination-history');
    Route::post('/mascotas/{pet:slug}/vaccination-records', [PetController::class, 'storeVaccinationRecord'])->middleware('throttle:20,1')->name('mascotas.vaccination-records.store');
    // Ruta para solicitudes de mascotas - usar PetRequestController
    Route::post('/solicitudes/store-pet', [PetRequestController::class, 'store'])->middleware('throttle:3,1')->name('solicitudes.store-pet');
    
    // Rutas para solicitudes de mascotas (para clientes)
    Route::prefix('pet-requests')->name('pet-requests.')->group(function () {
        Route::get('/', [PetRequestController::class, 'index'])->name('index');
        Route::get('/create', [PetRequestController::class, 'create'])->name('create');
        Route::post('/store', [PetRequestController::class, 'store'])->name('store');
        Route::get('/{petRequest}', [PetRequestController::class, 'show'])->name('show');
    });
    
    // Las rutas de citas están en el sistema unificado de appointments

    // Rutas para notificaciones
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [AppointmentNotificationController::class, 'index'])->name('index');
        Route::post('/{notification}/mark-as-read', [AppointmentNotificationController::class, 'markAsRead'])->name('mark-as-read');
        Route::post('/mark-all-as-read', [AppointmentNotificationController::class, 'markAllAsRead'])->name('mark-all-as-read');
        Route::get('/unread', [AppointmentNotificationController::class, 'getUnread'])->name('unread');
        Route::get('/unread-count', [AppointmentNotificationController::class, 'getUnreadCount'])->name('unread-count');
    });
    
    // Ruta para notificaciones del cliente (redirige al nuevo sistema)
    Route::get('/notificaciones', [PetController::class, 'clientNotifications'])->name('notificaciones');
    
    // Ruta específica para notificaciones del nuevo sistema
    Route::get('/notifications', [AppointmentNotificationController::class, 'index'])->name('notifications.index');
    
    Route::post('/mascotas/{pet:slug}/generate-qr', [PetController::class, 'generateQRCode'])->middleware('throttle:10,1')->name('mascotas.generate-qr');

    // Rutas para el calendario de citas
    Route::prefix('calendario')->name('calendario.')->group(function () {
        Route::get('/', [AppointmentController::class, 'index'])->name('index');
        Route::get('/crear', [AppointmentController::class, 'create'])->name('create');
        Route::post('/crear', [AppointmentController::class, 'store'])->name('store');
        Route::get('/{appointment}', [AppointmentController::class, 'show'])->name('show');
        Route::get('/{appointment}/editar', [AppointmentController::class, 'edit'])->name('edit');
        Route::put('/{appointment}', [AppointmentController::class, 'update'])->name('update');
        Route::delete('/{appointment}', [AppointmentController::class, 'destroy'])->name('destroy');
        Route::get('/api/mes', [AppointmentController::class, 'getAppointmentsForMonth'])->name('api.month');
    });

    // Rutas para gestión de códigos QR de clientes
    Route::prefix('qr')->name('qr.')->group(function () {
        Route::get('/', [QRController::class, 'index'])->name('index');
        Route::post('/generate-single', [QRController::class, 'generateSingle'])->name('generate-single');
        Route::post('/generate-multiple', [QRController::class, 'generateMultiple'])->name('generate-multiple');
        Route::get('/{pet}', [QRController::class, 'show'])->name('show');
        Route::get('/{pet}/download', [QRController::class, 'download'])->name('download');
        Route::post('/{pet}/regenerate', [QRController::class, 'regenerate'])->name('regenerate');
    });

    // Rutas para veterinarios disponibles
    Route::prefix('veterinarios')->name('veterinarios.')->group(function () {
        Route::get('/', [VeterinarioController::class, 'index'])->name('index');
        Route::get('/mis-veterinarios', [VeterinarioController::class, 'misVeterinarios'])->name('mis-veterinarios');
        Route::get('/{veterinario:slug}', [VeterinarioController::class, 'show'])->name('show');
        Route::post('/solicitar', [VeterinarioController::class, 'solicitar'])->name('solicitar');
        Route::post('/cambiar', [VeterinarioController::class, 'cambiarVeterinario'])->name('cambiar');
        Route::post('/desasignar/{asignacion}', [VeterinarioController::class, 'desasignar'])->name('desasignar');
    });

    // Rutas para depuración
    Route::get('/mascotas/{pet:slug}/debug-records', [PetController::class, 'debugRecords'])->name('mascotas.debug-records');
    Route::get('/mascotas/{pet:slug}/debug-refresh', [PetController::class, 'debugRefreshCache'])->name('mascotas.debug-refresh');

});
