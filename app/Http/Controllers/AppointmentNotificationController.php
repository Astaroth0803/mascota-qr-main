<?php

namespace App\Http\Controllers;

use App\Models\AppointmentNotification;
use App\Services\AppointmentNotificationService;
use App\Http\Requests\MarkNotificationReadRequest;
use App\Http\Requests\GetNotificationsRequest;
use App\Events\NotificationCountUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AppointmentNotificationController extends Controller
{
    use AuthorizesRequests;
    /**
     * Mostrar notificaciones del usuario
     */
    public function index()
    {
        $user = Auth::user();
        $this->authorize('access', AppointmentNotification::class);
        
        // Optimización: obtener notificaciones y conteo en una sola consulta
        $notifications = AppointmentNotificationService::getNotificationsForUser($user->id, 20);
        $unreadCount = AppointmentNotificationService::getUnreadCountForUser($user->id);

        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    /**
     * Marcar notificación como leída
     */
    public function markAsRead(MarkNotificationReadRequest $request, AppointmentNotification $notification)
    {
        $this->authorize('markAsRead', $notification);

        $user = Auth::user();
        $notification->markAsRead();

        // Emitir evento para actualizar contador en tiempo real
        $unreadCount = AppointmentNotificationService::getUnreadCountForUser($user->id);
        event(new NotificationCountUpdated($user, $unreadCount));

        return response()->json([
            'success' => true,
            'message' => 'Notificación marcada como leída.',
            'notification' => $notification->fresh(),
            'unread_count' => $unreadCount
        ]);
    }

    /**
     * Marcar todas las notificaciones como leídas
     */
    public function markAllAsRead()
    {
        $user = Auth::user();
        $this->authorize('access', AppointmentNotification::class);
        
        $markedCount = AppointmentNotificationService::markAllAsReadForUser($user->id);

        // Emitir evento para actualizar contador en tiempo real
        event(new NotificationCountUpdated($user, 0));

        return response()->json([
            'success' => true,
            'message' => "Se marcaron {$markedCount} notificaciones como leídas.",
            'marked_count' => $markedCount,
            'unread_count' => 0
        ]);
    }

    /**
     * Obtener notificaciones no leídas (API)
     */
    public function getUnread(GetNotificationsRequest $request)
    {
        // Debug: Verificar autenticación
        \Log::info('API getUnread called', [
            'user_authenticated' => Auth::check(),
            'user_id' => Auth::id(),
            'user' => Auth::user() ? Auth::user()->toArray() : null
        ]);
        
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado',
                'debug' => 'Auth::check() returned false'
            ], 401);
        }
        
        $user = Auth::user();
        $this->authorize('access', AppointmentNotification::class);
        
        $limit = $request->get('limit', 20);
        $notifications = AppointmentNotificationService::getUnreadNotificationsForUser($user->id, $limit);

        return response()->json([
            'success' => true,
            'notifications' => $notifications,
            'count' => $notifications->count(),
            'message' => 'Notificaciones no leídas obtenidas exitosamente.'
        ]);
    }

    /**
     * Obtener contador de notificaciones no leídas (API)
     */
    public function getUnreadCount()
    {
        // Debug: Verificar autenticación
        \Log::info('API getUnreadCount called', [
            'user_authenticated' => Auth::check(),
            'user_id' => Auth::id(),
            'user' => Auth::user() ? Auth::user()->toArray() : null
        ]);
        
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado',
                'debug' => 'Auth::check() returned false'
            ], 401);
        }
        
        $user = Auth::user();
        $this->authorize('access', AppointmentNotification::class);
        
        $count = AppointmentNotificationService::getUnreadCountForUser($user->id);

        return response()->json([
            'success' => true,
            'count' => $count,
            'message' => 'Contador de notificaciones obtenido exitosamente.'
        ]);
    }
}
