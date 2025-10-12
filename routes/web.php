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

        // Rutas para el calendario de citas
        Route::prefix('calendario')->name('calendario.')->group(function () {
            Route::get('/', [App\Http\Controllers\Client\AppointmentController::class, 'index'])->name('index');
            Route::get('/crear', [App\Http\Controllers\Client\AppointmentController::class, 'create'])->name('create');
            Route::post('/crear', [App\Http\Controllers\Client\AppointmentController::class, 'store'])->name('store');
            Route::get('/{appointment}', [App\Http\Controllers\Client\AppointmentController::class, 'show'])->name('show');
            Route::get('/{appointment}/editar', [App\Http\Controllers\Client\AppointmentController::class, 'edit'])->name('edit');
            Route::put('/{appointment}', [App\Http\Controllers\Client\AppointmentController::class, 'update'])->name('update');
            Route::delete('/{appointment}', [App\Http\Controllers\Client\AppointmentController::class, 'destroy'])->name('destroy');
            Route::get('/api/mes', [App\Http\Controllers\Client\AppointmentController::class, 'getAppointmentsForMonth'])->name('api.month');
        });

        // Rutas para gestión de códigos QR de clientes
        Route::prefix('qr')->name('qr.')->group(function () {
            Route::get('/', [App\Http\Controllers\Client\QRController::class, 'index'])->name('index');
            Route::post('/generate-single', [App\Http\Controllers\Client\QRController::class, 'generateSingle'])->name('generate-single');
            Route::post('/generate-multiple', [App\Http\Controllers\Client\QRController::class, 'generateMultiple'])->name('generate-multiple');
            Route::get('/{pet}', [App\Http\Controllers\Client\QRController::class, 'show'])->name('show');
            Route::get('/{pet}/download', [App\Http\Controllers\Client\QRController::class, 'download'])->name('download');
            Route::post('/{pet}/regenerate', [App\Http\Controllers\Client\QRController::class, 'regenerate'])->name('regenerate');
        });

        // Rutas para veterinarios disponibles
        Route::prefix('veterinarios')->name('veterinarios.')->group(function () {
            Route::get('/', [App\Http\Controllers\Client\VeterinarioController::class, 'index'])->name('index');
            Route::get('/mis-veterinarios', [App\Http\Controllers\Client\VeterinarioController::class, 'misVeterinarios'])->name('mis-veterinarios');
            Route::get('/{veterinario:slug}', [App\Http\Controllers\Client\VeterinarioController::class, 'show'])->name('show');
            Route::post('/solicitar', [App\Http\Controllers\Client\VeterinarioController::class, 'solicitar'])->name('solicitar');
            Route::post('/cambiar', [App\Http\Controllers\Client\VeterinarioController::class, 'cambiarVeterinario'])->name('cambiar');
            Route::post('/desasignar/{asignacion}', [App\Http\Controllers\Client\VeterinarioController::class, 'desasignar'])->name('desasignar');
        });

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
    Route::prefix('dashboard/veterinario')->middleware(['role:veterinario'])->group(function () {
        Route::get('/', [App\Http\Controllers\VeterinarioController::class, 'dashboard'])->name('dashboard.veterinario');
        
        // Gestión de mascotas asignadas
        Route::get('mascotas', [App\Http\Controllers\VeterinarioController::class, 'mascotas'])->name('dashboard.veterinario.mascotas');
        Route::get('mascotas/{pet}', [App\Http\Controllers\VeterinarioController::class, 'showMascota'])->name('dashboard.veterinario.mascota.show');
        
        // Gestión del historial médico
        Route::get('mascotas/{pet}/historial', [App\Http\Controllers\VeterinarioController::class, 'gestionarHistorial'])->name('dashboard.veterinario.historial');
        Route::post('mascotas/{pet}/vacunas', [App\Http\Controllers\VeterinarioController::class, 'agregarVacuna'])->name('dashboard.veterinario.vacunas.store');
        Route::put('mascotas/{pet}/vacunas/{vacuna}', [App\Http\Controllers\VeterinarioController::class, 'actualizarVacuna'])->name('dashboard.veterinario.vacunas.update');
        Route::delete('mascotas/{pet}/vacunas/{vacuna}', [App\Http\Controllers\VeterinarioController::class, 'eliminarVacuna'])->name('dashboard.veterinario.vacunas.destroy');
    });

    // Rutas para el calendario de citas de veterinarios (separadas para evitar problemas de middleware)
    Route::prefix('dashboard/veterinario/calendario')->middleware(['role:veterinario'])->name('dashboard.veterinario.calendario.')->group(function () {
        Route::get('/', [App\Http\Controllers\Veterinario\AppointmentController::class, 'index'])->name('index');
        Route::get('/hoy', [App\Http\Controllers\Veterinario\AppointmentController::class, 'today'])->name('today');
        Route::get('/crear', [App\Http\Controllers\Veterinario\AppointmentController::class, 'create'])->name('create');
        Route::post('/crear', [App\Http\Controllers\Veterinario\AppointmentController::class, 'store'])->name('store');
        Route::get('/{appointment}', [App\Http\Controllers\Veterinario\AppointmentController::class, 'show'])->name('show');
        Route::get('/{appointment}/editar', [App\Http\Controllers\Veterinario\AppointmentController::class, 'edit'])->name('edit');
        Route::put('/{appointment}', [App\Http\Controllers\Veterinario\AppointmentController::class, 'update'])->name('update');
        Route::delete('/{appointment}', [App\Http\Controllers\Veterinario\AppointmentController::class, 'destroy'])->name('destroy');
        Route::get('/api/mes', [App\Http\Controllers\Veterinario\AppointmentController::class, 'getAppointmentsForMonth'])->name('api.month');
    });

    // Rutas para solicitudes de veterinarios
    Route::prefix('dashboard/veterinario/solicitudes')->middleware(['role:veterinario'])->name('dashboard.veterinario.solicitudes.')->group(function () {
        Route::get('/', [App\Http\Controllers\Veterinario\VetRequestController::class, 'index'])->name('index');
        Route::get('/{solicitud}', [App\Http\Controllers\Veterinario\VetRequestController::class, 'show'])->name('show');
        Route::post('/{solicitud}/aceptar', [App\Http\Controllers\Veterinario\VetRequestController::class, 'aceptar'])->name('aceptar');
        Route::post('/{solicitud}/rechazar', [App\Http\Controllers\Veterinario\VetRequestController::class, 'rechazar'])->name('rechazar');
        Route::get('/api/pendientes', [App\Http\Controllers\Veterinario\VetRequestController::class, 'pendientes'])->name('api.pendientes');
        
        // Rutas para citas pendientes
        Route::post('/citas/{cita}/aceptar', [App\Http\Controllers\Veterinario\VetRequestController::class, 'aceptarCita'])->name('citas.aceptar');
        Route::post('/citas/{cita}/rechazar', [App\Http\Controllers\Veterinario\VetRequestController::class, 'rechazarCita'])->name('citas.rechazar');
    });

    // Rutas para notificaciones de veterinarios
    Route::prefix('dashboard/veterinario/notificaciones')->middleware(['role:veterinario'])->name('dashboard.veterinario.notificaciones.')->group(function () {
        Route::get('/', [App\Http\Controllers\Veterinario\VetNotificationController::class, 'index'])->name('index');
        Route::post('/{notificacion}/marcar-leida', [App\Http\Controllers\Veterinario\VetNotificationController::class, 'marcarLeida'])->name('marcar-leida');
        Route::post('/marcar-todas-leidas', [App\Http\Controllers\Veterinario\VetNotificationController::class, 'marcarTodasLeidas'])->name('marcar-todas-leidas');
        Route::get('/api/no-leidas', [App\Http\Controllers\Veterinario\VetNotificationController::class, 'noLeidas'])->name('api.no-leidas');
        Route::get('/api/conteo-no-leidas', [App\Http\Controllers\Veterinario\VetNotificationController::class, 'conteoNoLeidas'])->name('api.conteo-no-leidas');
    });

    // Rutas para solicitudes de cambio de citas
    Route::prefix('dashboard/veterinario/solicitudes-cambio-citas')->middleware(['role:veterinario'])->name('dashboard.veterinario.appointment-change-requests.')->group(function () {
        Route::get('/', [App\Http\Controllers\Veterinario\AppointmentChangeRequestController::class, 'index'])->name('index');
        Route::get('/{changeRequest}', [App\Http\Controllers\Veterinario\AppointmentChangeRequestController::class, 'show'])->name('show');
        Route::post('/{changeRequest}/aprobar', [App\Http\Controllers\Veterinario\AppointmentChangeRequestController::class, 'approve'])->name('approve');
        Route::post('/{changeRequest}/rechazar', [App\Http\Controllers\Veterinario\AppointmentChangeRequestController::class, 'reject'])->name('reject');
        Route::get('/api/pendientes', [App\Http\Controllers\Veterinario\AppointmentChangeRequestController::class, 'pending'])->name('api.pending');
        Route::get('/api/conteo-pendientes', [App\Http\Controllers\Veterinario\AppointmentChangeRequestController::class, 'pendingCount'])->name('api.pending-count');
    });
    
    // Rutas para el nuevo sistema de citas con estados
    Route::resource('appointments', App\Http\Controllers\AppointmentController::class)->middleware(['auth']);
    
    // Rutas específicas para veterinarios
    Route::prefix('dashboard/veterinario/appointments')->middleware(['role:veterinario'])->name('vet.appointments.')->group(function () {
        Route::get('/', [App\Http\Controllers\AppointmentController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\AppointmentController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\AppointmentController::class, 'store'])->name('store');
        Route::get('/{appointment}', [App\Http\Controllers\AppointmentController::class, 'show'])->name('show');
        Route::get('/{appointment}/edit', [App\Http\Controllers\AppointmentController::class, 'edit'])->name('edit');
        Route::patch('/{appointment}', [App\Http\Controllers\AppointmentController::class, 'update'])->name('update');
        Route::delete('/{appointment}', [App\Http\Controllers\AppointmentController::class, 'destroy'])->name('destroy');
    });
    
    // Rutas específicas para clientes
    Route::prefix('dashboard/cliente/appointments')->middleware(['role:cliente_qr'])->name('client.appointments.')->group(function () {
        Route::get('/', [App\Http\Controllers\AppointmentController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\AppointmentController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\AppointmentController::class, 'store'])->name('store');
        Route::get('/{appointment}', [App\Http\Controllers\AppointmentController::class, 'show'])->name('show');
        Route::patch('/{appointment}/cancel', [App\Http\Controllers\AppointmentController::class, 'update'])->name('cancel');
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
