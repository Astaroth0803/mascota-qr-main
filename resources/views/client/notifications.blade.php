@extends('layouts.standard')

@section('title', 'Notificaciones')

@php
    $title = 'Notificaciones';
    $subtitle = 'Bienvenido a la sección de notificaciones de citas ' . auth()->user()->name;
@endphp

@section('main-content')
<div class="space-y-6">
    <!-- Header limpio -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Notificaciones</h1>
            <p class="text-gray-600 mt-1">Tienes {{ $unreadCount ?? 0 }} notificaciones sin leer</p>
        </div>
        @if(isset($unreadCount) && $unreadCount > 0)
            <button onclick="markAllAsRead()" 
                    class="inline-flex items-center px-4 py-2 bg-purple-100 text-purple-700 text-sm font-medium rounded-lg hover:bg-purple-200 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Marcar todas como leídas
            </button>
        @endif
    </div>

    <!-- Lista de notificaciones organizadas por fecha -->
    <div class="space-y-6">
        @php
            $groupedNotifications = collect($notifications ?? [])->groupBy(function($notification) {
                $date = $notification->created_at ?? now();
                if ($date->isToday()) {
                    return 'Hoy';
                } elseif ($date->isYesterday()) {
                    return 'Ayer';
                } else {
                    return $date->format('d/m/Y');
                }
            });
        @endphp

        @forelse($groupedNotifications as $dateGroup => $notificationsGroup)
            <div>
                <h3 class="text-sm font-medium text-gray-500 mb-3">{{ $dateGroup }}</h3>
                <div class="space-y-3">
                    @foreach($notificationsGroup as $notification)
                        <div class="bg-white rounded-lg border border-gray-200 p-4 hover:shadow-sm transition-shadow {{ !$notification->is_read ? 'border-l-4 border-l-purple-500' : '' }}">
                            <div class="flex items-start space-x-4">
                                <!-- Icono de notificación -->
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                        @if($notification->type === 'appointment_accepted')
                                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        @elseif($notification->type === 'appointment_rejected')
                                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5-5-5h5v-5a7.5 7.5 0 1 1 15 0v5z"></path>
                                            </svg>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Contenido de la notificación -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h4 class="text-sm font-semibold text-gray-900">{{ $notification->title ?? 'Notificación' }}</h4>
                                            <p class="text-sm text-gray-600 mt-1">{{ $notification->message ?? 'Sin mensaje' }}</p>
                                            
                                            @if(isset($notification->data) && $notification->data)
                                                <div class="mt-2 text-xs text-gray-500">
                                                    @if(isset($notification->data['pet_name']))
                                                        <span class="inline-block bg-gray-100 px-2 py-1 rounded mr-2">{{ $notification->data['pet_name'] }}</span>
                                                    @endif
                                                    @if(isset($notification->data['appointment_type']))
                                                        <span class="inline-block bg-gray-100 px-2 py-1 rounded mr-2">{{ $notification->data['appointment_type'] }}</span>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <!-- Acciones -->
                                        <div class="flex items-center space-x-2 ml-4">
                                            @if(!$notification->is_read)
                                                <button onclick="markAsRead({{ $notification->id }})" 
                                                        class="text-gray-400 hover:text-gray-600 transition-colors"
                                                        title="Marcar como leída">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </button>
                                            @endif
                                            
                                            @if(isset($notification->appointmentRequest))
                                                <a href="{{ route('citas.show', $notification->appointmentRequest) }}" 
                                                   class="text-gray-400 hover:text-gray-600 transition-colors"
                                                   title="Ver solicitud">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5-5-5h5v-5a7.5 7.5 0 1 1 15 0v5z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No hay notificaciones</h3>
                <p class="text-gray-500">No tienes notificaciones en este momento.</p>
            </div>
        @endforelse
    </div>

    <!-- Paginación si es necesaria -->
    @if(isset($notifications) && $notifications instanceof \Illuminate\Pagination\LengthAwarePaginator && $notifications->hasPages())
        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    @endif
</div>

<script>
function markAsRead(notificationId) {
    fetch(`/dashboard/cliente/notifications/${notificationId}/mark-as-read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function markAllAsRead() {
    fetch('/dashboard/cliente/notifications/mark-all-as-read', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
</script>
@endsection

