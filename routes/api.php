<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Pet\PetController;
use App\Http\Controllers\AppointmentNotificationController;

// API endpoints para validación en tiempo real
Route::post('/validate-qr-code', [PetController::class, 'validateQRCodeRealTime'])->middleware('throttle:20,1')->name('api.validate-qr-code');

// API endpoints para notificaciones
Route::middleware('auth')->group(function () {
    Route::get('/notifications/unread', [AppointmentNotificationController::class, 'getUnread']);
    Route::get('/notifications/unread-count', [AppointmentNotificationController::class, 'getUnreadCount']);
    Route::post('/notifications/{notification}/mark-read', [AppointmentNotificationController::class, 'markAsRead']);
    Route::post('/notifications/mark-all-read', [AppointmentNotificationController::class, 'markAllAsRead']);
});
