<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppointmentRequestController;

// Rutas para solicitudes de citas (appointment-requests)
Route::prefix('appointment-requests')->name('appointment-requests.')->middleware('auth')->group(function () {
    Route::get('/', [AppointmentRequestController::class, 'index'])->name('index');
    Route::get('/create', [AppointmentRequestController::class, 'create'])->name('create');
    Route::post('/', [AppointmentRequestController::class, 'store'])->name('store');
    Route::get('/{appointmentRequest}', [AppointmentRequestController::class, 'show'])->name('show');
    Route::post('/{appointmentRequest}/aceptar', [AppointmentRequestController::class, 'aceptar'])->name('aceptar');
    Route::post('/{appointmentRequest}/rechazar', [AppointmentRequestController::class, 'rechazar'])->name('rechazar');
    Route::post('/{appointmentRequest}/finalizar', [AppointmentRequestController::class, 'finalizar'])->name('finalizar');
    Route::post('/{appointmentRequest}/cancelar', [AppointmentRequestController::class, 'cancelar'])->name('cancelar');
    Route::get('/{appointmentRequest}/edit', [AppointmentRequestController::class, 'edit'])->name('edit');
    Route::put('/{appointmentRequest}', [AppointmentRequestController::class, 'update'])->name('update');
    
});


// Rutas para citas (citas) - alias para appointment-requests
Route::prefix('citas')->name('citas.')->middleware('auth')->group(function () {
    Route::get('/', [AppointmentRequestController::class, 'index'])->name('index');
    Route::get('/create', [AppointmentRequestController::class, 'create'])->name('create');
    Route::post('/', [AppointmentRequestController::class, 'store'])->name('store');
    Route::get('/{appointmentRequest}', [AppointmentRequestController::class, 'show'])->name('show');
    Route::post('/{appointmentRequest}/aceptar', [AppointmentRequestController::class, 'aceptar'])->name('aceptar');
    Route::post('/{appointmentRequest}/rechazar', [AppointmentRequestController::class, 'rechazar'])->name('rechazar');
    Route::post('/{appointmentRequest}/finalizar', [AppointmentRequestController::class, 'finalizar'])->name('finalizar');
    Route::post('/{appointmentRequest}/cancelar', [AppointmentRequestController::class, 'cancelar'])->name('cancelar');
    Route::get('/{appointmentRequest}/edit', [AppointmentRequestController::class, 'edit'])->name('edit');
    Route::put('/{appointmentRequest}', [AppointmentRequestController::class, 'update'])->name('update');
    
    // Rutas adicionales para funcionalidades específicas
    Route::get('/api/veterinarios/disponibles', [AppointmentRequestController::class, 'getVeterinariosDisponibles'])->name('veterinarios.disponibles');
    Route::get('/api/vacunas/nombres-tecnicos', [AppointmentRequestController::class, 'getNombresTecnicos'])->name('vacunas.nombres-tecnicos');
    Route::get('/api/vacunas/nombres-comerciales', [AppointmentRequestController::class, 'getNombresComerciales'])->name('vacunas.nombres-comerciales');
});



