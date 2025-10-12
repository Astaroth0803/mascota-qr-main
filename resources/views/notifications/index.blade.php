@extends('layouts.standard')

@section('title', 'Notificaciones')

@php
    $title = 'Notificaciones';
    $subtitle = 'Bienvenido a la sección de notificaciones de citas' . " " . auth()->user()->name;
@endphp

@section('main-content')
<div>
    <!-- Header con acciones -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center space-x-3">
            @if($unreadCount > 0)
                <button onclick="markAllAsRead()" 
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Marcar todas como leídas
                </button>
            @endif
        </div>
    </div>

    <!-- Lista de notificaciones -->
    <div class="space-y-4">
        @forelse($notifications as $notification)
            <div class="bg-white rounded-lg border border-gray-200 p-6 {{ !$notification->is_read ? 'border-l-4 border-l-blue-500' : '' }}">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3">
                            @if(!$notification->is_read)
                                <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                            @endif
                            <h3 class="text-lg font-semibold text-gray-900">{{ $notification->title }}</h3>
                        </div>
                        <p class="text-gray-600 mt-2">{{ $notification->message }}</p>
                        
                        @if($notification->data)
                            <div class="mt-3 text-sm text-gray-500">
                                @if(isset($notification->data['pet_name']))
                                    <p><strong>Mascota:</strong> {{ $notification->data['pet_name'] }}</p>
                                @endif
                                @if(isset($notification->data['appointment_type']))
                                    <p><strong>Tipo:</strong> {{ $notification->data['appointment_type'] }}</p>
                                @endif
                                @if(isset($notification->data['requested_date']))
                                    <p><strong>Fecha solicitada:</strong> {{ $notification->data['requested_date'] }}</p>
                                @endif
                                @if(isset($notification->data['scheduled_date']))
                                    <p><strong>Fecha agendada:</strong> {{ $notification->data['scheduled_date'] }}</p>
                                @endif
                                @if(isset($notification->data['rejection_reason']))
                                    <p><strong>Razón:</strong> {{ $notification->data['rejection_reason'] }}</p>
                                @endif
                            </div>
                        @endif
                        
                        <div class="flex items-center space-x-4 mt-4 text-sm text-gray-500">
                            <span>{{ $notification->created_at->diffForHumans() }}</span>
                            @if($notification->sender)
                                <span>por {{ $notification->sender->name }}</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-2 ml-4">
                        @if(!$notification->is_read)
                            <button onclick="markAsRead({{ $notification->id }})" 
                                    class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 text-sm font-medium rounded-md hover:bg-blue-200 transition-colors">
                                Marcar como leída
                            </button>
                        @endif
                        
                        @if($notification->appointmentRequest)
                            <a href="{{ route('citas.show', $notification->appointmentRequest) }}" 
                               class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-200 transition-colors">
                                Ver solicitud
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5-5-5h5v-5a7.5 7.5 0 1 1 15 0v5z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No hay notificaciones</h3>
                <p class="mt-1 text-sm text-gray-500">No tienes notificaciones en este momento.</p>
            </div>
        @endforelse
    </div>

    <!-- Paginación si es necesaria -->
    @if($notifications->hasPages())
        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    @endif
</div>

<script>
function markAsRead(notificationId) {
    const route = '{{ auth()->user()->hasRole("veterinario") ? route("notifications.mark-as-read", ":id") : route("dashboard.cliente.notifications.mark-as-read", ":id") }}';
    fetch(route.replace(':id', notificationId), {
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
    fetch(`{{ auth()->user()->hasRole('veterinario') ? route('notifications.mark-all-as-read') : route('dashboard.cliente.notifications.mark-all-as-read') }}`, {
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
