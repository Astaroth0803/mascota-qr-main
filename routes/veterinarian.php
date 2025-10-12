<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VeterinarioController;
use App\Http\Controllers\Veterinario\AppointmentController;
use App\Http\Controllers\Veterinario\VetRequestController;
use App\Http\Controllers\Veterinario\VetNotificationController;
use App\Http\Controllers\Veterinario\AppointmentChangeRequestController;
use App\Http\Controllers\AppointmentNotificationController;

// Rutas del dashboard para veterinarios (solo para veterinarios con permisos)
Route::prefix('dashboard/veterinario')->middleware(['role:veterinario'])->group(function () {
    Route::get('/', [VeterinarioController::class, 'dashboard'])->name('dashboard.veterinario');
    
    // Gestión de mascotas asignadas
    Route::get('mascotas', [VeterinarioController::class, 'mascotas'])->name('dashboard.veterinario.mascotas');
    Route::get('mascotas/{pet}', [VeterinarioController::class, 'showMascota'])->name('dashboard.veterinario.mascota.show');
    
    // Gestión del historial médico
    Route::get('mascotas/{pet}/historial', [VeterinarioController::class, 'gestionarHistorial'])->name('dashboard.veterinario.historial');
    Route::post('mascotas/{pet}/vacunas', [VeterinarioController::class, 'agregarVacuna'])->name('dashboard.veterinario.vacunas.store');
    Route::put('mascotas/{pet}/vacunas/{vacuna}', [VeterinarioController::class, 'actualizarVacuna'])->name('dashboard.veterinario.vacunas.update');
    Route::delete('mascotas/{pet}/vacunas/{vacuna}', [VeterinarioController::class, 'eliminarVacuna'])->name('dashboard.veterinario.vacunas.destroy');
    
    // Las rutas de citas ahora están en routes/citas.php

    // Rutas para notificaciones
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [AppointmentNotificationController::class, 'index'])->name('index');
        Route::post('/{notification}/mark-as-read', [AppointmentNotificationController::class, 'markAsRead'])->name('mark-as-read');
        Route::post('/mark-all-as-read', [AppointmentNotificationController::class, 'markAllAsRead'])->name('mark-all-as-read');
        Route::get('/unread', [AppointmentNotificationController::class, 'getUnread'])->name('unread');
        Route::get('/unread-count', [AppointmentNotificationController::class, 'getUnreadCount'])->name('unread-count');
    });

    // Ruta para datos del gráfico por día específico
    Route::get('/chart-data/{date}', [VeterinarioController::class, 'getChartDataForDate'])->name('chart-data');
});

// Rutas para el calendario de citas de veterinarios
Route::prefix('dashboard/veterinario/calendario')->middleware(['role:veterinario'])->name('dashboard.veterinario.calendario.')->group(function () {
    Route::get('/', [AppointmentController::class, 'index'])->name('index');
    Route::get('/hoy', [AppointmentController::class, 'today'])->name('today');
    Route::get('/crear', [AppointmentController::class, 'create'])->name('create');
    Route::post('/crear', [AppointmentController::class, 'store'])->name('store');
    Route::get('/{appointment}', [AppointmentController::class, 'show'])->name('show');
    Route::get('/{appointment}/editar', [AppointmentController::class, 'edit'])->name('edit');
    Route::put('/{appointment}', [AppointmentController::class, 'update'])->name('update');
    Route::delete('/{appointment}', [AppointmentController::class, 'destroy'])->name('destroy');
    Route::get('/api/mes', [AppointmentController::class, 'getAppointmentsForMonth'])->name('api.month');
});

// Rutas para solicitudes de veterinarios
Route::prefix('dashboard/veterinario/solicitudes')->middleware(['role:veterinario'])->name('dashboard.veterinario.solicitudes.')->group(function () {
    Route::get('/', [VetRequestController::class, 'index'])->name('index');
    Route::get('/{solicitud}', [VetRequestController::class, 'show'])->name('show');
    Route::post('/{solicitud}/aceptar', [VetRequestController::class, 'aceptar'])->name('aceptar');
    Route::post('/{solicitud}/rechazar', [VetRequestController::class, 'rechazar'])->name('rechazar');
    Route::get('/api/pendientes', [VetRequestController::class, 'pendientes'])->name('api.pendientes');
    
    // Rutas para citas pendientes
    Route::post('/citas/{cita}/aceptar', [VetRequestController::class, 'aceptarCita'])->name('citas.aceptar');
    Route::post('/citas/{cita}/rechazar', [VetRequestController::class, 'rechazarCita'])->name('citas.rechazar');
});

// Rutas para notificaciones de veterinarios
Route::prefix('dashboard/veterinario/notificaciones')->middleware(['role:veterinario'])->name('dashboard.veterinario.notificaciones.')->group(function () {
    Route::get('/', [VetNotificationController::class, 'index'])->name('index');
    Route::post('/{notificacion}/marcar-leida', [VetNotificationController::class, 'marcarLeida'])->name('marcar-leida');
    Route::post('/marcar-todas-leidas', [VetNotificationController::class, 'marcarTodasLeidas'])->name('marcar-todas-leidas');
    Route::get('/api/no-leidas', [VetNotificationController::class, 'noLeidas'])->name('api.no-leidas');
    Route::get('/api/conteo-no-leidas', [VetNotificationController::class, 'conteoNoLeidas'])->name('api.conteo-no-leidas');
});

// Rutas para solicitudes de cambio de citas
Route::prefix('dashboard/veterinario/solicitudes-cambio-citas')->middleware(['role:veterinario'])->name('dashboard.veterinario.appointment-change-requests.')->group(function () {
    Route::get('/', [AppointmentChangeRequestController::class, 'index'])->name('index');
    Route::get('/{changeRequest}', [AppointmentChangeRequestController::class, 'show'])->name('show');
    Route::post('/{changeRequest}/aprobar', [AppointmentChangeRequestController::class, 'approve'])->name('approve');
    Route::post('/{changeRequest}/rechazar', [AppointmentChangeRequestController::class, 'reject'])->name('reject');
    Route::get('/api/pendientes', [AppointmentChangeRequestController::class, 'pending'])->name('api.pending');
    Route::get('/api/conteo-pendientes', [AppointmentChangeRequestController::class, 'pendingCount'])->name('api.pending-count');
});

