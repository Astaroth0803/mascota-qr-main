@extends('layouts.dashboard')

@section('title', 'Notificaciones - Veterinario')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Optimizado -->
    <div class="bg-white shadow-sm border-b border-gray-200 pt-16 lg:pt-0">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="py-4 lg:py-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex-1 min-w-0">
                        <h1 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900">Notificaciones</h1>
                        <p class="text-sm text-gray-600 mt-1">Gestiona las notificaciones y solicitudes de tus clientes</p>
                    </div>
                    <!-- Acciones rápidas -->
                    <div class="flex items-center space-x-2">
                        @if($notificaciones->count() > 0)
                            <form action="{{ route('dashboard.veterinario.notificaciones.marcar-todas-leidas') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" 
                                        class="inline-flex items-center px-3 py-2 bg-gray-100 border border-gray-200 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition-colors">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="hidden sm:inline">Marcar todas</span>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div>
        <div class="px-4 sm:px-6 lg:px-8 py-6">
            <!-- Notificaciones Optimizadas -->
            @if($notificaciones->count() > 0)
                <div class="space-y-4">
                    @foreach($notificaciones as $notificacion)
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-all duration-200">
                            <div class="flex items-start justify-between">
                                <div class="flex items-start space-x-4">
                                    <!-- Icono de notificación -->
                                    <div class="w-12 h-12 bg-orange-100 rounded-2xl flex items-center justify-center flex-shrink-0">
                                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.828 7l2.586 2.586a2 2 0 002.828 0L12.828 7H4.828z" />
                                        </svg>
                                    </div>
                                    
                                    <!-- Información -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center space-x-3 mb-2">
                                            <h3 class="text-lg font-semibold text-gray-900">
                                                {{ $notificacion->cliente->name }} - {{ $notificacion->mascota->nombre }}
                                            </h3>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                {{ ucfirst($notificacion->tipo) }}
                                            </span>
                                        </div>
                                        
                                        <p class="text-sm text-gray-700 mb-3">{{ $notificacion->mensaje }}</p>
                                        
                                        <div class="flex items-center space-x-4 text-sm text-gray-500">
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                {{ $notificacion->created_at->format('d/m/Y H:i') }}
                                            </div>
                                            @if($notificacion->mascota)
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                    </svg>
                                                    {{ $notificacion->mascota->especie }} - {{ $notificacion->mascota->raza }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Botones de acción -->
                                <div class="flex items-center space-x-2">
                                    @if($notificacion->tipo === 'solicitud')
                                        <a href="{{ route('dashboard.veterinario.solicitudes.show', $notificacion->asignacion_id) }}" 
                                           class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            Ver Solicitud
                                        </a>
                                    @endif
                                    
                                    <form action="{{ route('dashboard.veterinario.notificaciones.marcar-leida', $notificacion->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="inline-flex items-center px-3 py-2 border border-gray-200 text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Marcar Leída
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No hay notificaciones pendientes</h3>
                        <p class="text-gray-500">Todas las notificaciones han sido leídas.</p>
                    </div>
                </div>
            @endif

            <!-- Enlaces rápidos optimizados -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('dashboard.veterinario.solicitudes.index') }}" 
                   class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-all duration-200">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-green-100 rounded-2xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-semibold text-gray-900">Solicitudes</h3>
                            <p class="text-sm text-gray-500">Gestiona las solicitudes de clientes</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('dashboard.veterinario.appointment-change-requests.index') }}" 
                   class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-all duration-200">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-semibold text-gray-900">Cambios de Citas</h3>
                            <p class="text-sm text-gray-500">Aprobar cambios de fecha</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('dashboard.veterinario.calendario.index') }}" 
                   class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-all duration-200">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-purple-100 rounded-2xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-semibold text-gray-900">Mi Calendario</h3>
                            <p class="text-sm text-gray-500">Ver y gestionar citas</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
