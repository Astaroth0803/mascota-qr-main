@extends('layouts.dashboard')

@section('title', 'Detalle de Cita - Veterinario')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Optimizado -->
    <div class="bg-white shadow-sm border-b border-gray-200 pt-16 lg:pt-0">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="py-4 lg:py-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex-1 min-w-0">
                        <h1 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900">Detalle de Cita</h1>
                        <p class="text-sm text-gray-600 mt-1">{{ $appointment->pet->nombre }} - {{ $appointment->getTypeOptions()[$appointment->record_type] ?? ucfirst($appointment->record_type) }}</p>
                    </div>
                    <!-- Acciones rápidas -->
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('dashboard.veterinario.calendario.index') }}" 
                           class="inline-flex items-center px-3 py-2 bg-gray-100 border border-gray-200 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition-colors">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            <span class="hidden sm:inline">Volver</span>
                        </a>
                        <a href="{{ route('dashboard.veterinario.calendario.edit', $appointment->id) }}" 
                           class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            <span class="hidden sm:inline">Editar</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div>
        <div class="px-4 sm:px-6 lg:px-8 py-6">
            <!-- Información principal optimizada -->
            <div class="max-w-4xl mx-auto">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-6">
                    <div class="px-6 py-5 border-b border-gray-100">
                        <div class="flex items-center space-x-4">
                            <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center">
                                <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h2 class="text-xl font-semibold text-gray-900">{{ $appointment->pet->nombre }}</h2>
                                <div class="flex items-center space-x-2 mt-2">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $appointment->getTypeOptions()[$appointment->record_type] ?? ucfirst($appointment->record_type) }}
                                    </span>
                                    @if($appointment->date->isToday())
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Hoy
                                        </span>
                                    @elseif($appointment->date->isFuture())
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Próxima
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            Pasada
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Fecha y hora -->
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900 mb-3">Fecha y Hora</h3>
                                <div class="space-y-2">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                        <span class="text-lg font-semibold text-gray-900">{{ $appointment->date->format('d/m/Y') }}</span>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <span class="text-lg font-semibold text-gray-900">{{ $appointment->time ? ($appointment->time instanceof \Carbon\Carbon ? $appointment->time->format('H:i') : \Carbon\Carbon::parse($appointment->time)->format('H:i')) : '09:00' }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Dueño -->
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900 mb-3">Dueño</h3>
                                <div class="space-y-2">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                        <span class="text-lg font-semibold text-gray-900">{{ $appointment->pet->user->name ?? 'N/A' }}</span>
                                    </div>
                                    @if($appointment->pet->user->email ?? false)
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                            <span class="text-sm text-gray-600">{{ $appointment->pet->user->email }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Veterinario -->
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900 mb-3">Veterinario</h3>
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-lg font-semibold text-gray-900">{{ $appointment->vet_name ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información de la mascota optimizada -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-6">
                    <div class="px-6 py-5 border-b border-gray-100">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900">Información de la Mascota</h3>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                            <div class="text-center p-4 bg-gray-50 rounded-xl">
                                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Especie</h4>
                                <p class="text-lg font-semibold text-gray-900">{{ $appointment->pet->especie }}</p>
                            </div>
                            <div class="text-center p-4 bg-gray-50 rounded-xl">
                                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Raza</h4>
                                <p class="text-lg font-semibold text-gray-900">{{ $appointment->pet->raza ?? 'N/A' }}</p>
                            </div>
                            <div class="text-center p-4 bg-gray-50 rounded-xl">
                                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Edad</h4>
                                <p class="text-lg font-semibold text-gray-900">{{ $appointment->pet->edad_anios ?? 'N/A' }} años</p>
                            </div>
                            <div class="text-center p-4 bg-gray-50 rounded-xl">
                                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Peso</h4>
                                <p class="text-lg font-semibold text-gray-900">{{ $appointment->pet->peso ? $appointment->pet->peso . ' kg' : 'N/A' }}</p>
                            </div>
                            <div class="text-center p-4 bg-gray-50 rounded-xl">
                                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Color</h4>
                                <p class="text-lg font-semibold text-gray-900">{{ $appointment->pet->color ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="mt-6 text-center">
                            <a href="{{ route('dashboard.veterinario.mascota.show', $appointment->pet->id) }}" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                                Ver perfil completo de la mascota
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Detalles de la cita optimizados -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Ubicación y vacuna -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
                        <div class="px-6 py-5 border-b border-gray-100">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900">Detalles de la Cita</h3>
                            </div>
                        </div>
                        <div class="p-6 space-y-4">
                            @if($appointment->location)
                                <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-xl">
                                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-semibold text-gray-900">Ubicación</h4>
                                        <p class="text-sm text-gray-600">{{ $appointment->location }}</p>
                                    </div>
                                </div>
                            @endif

                            @if($appointment->vaccine_name)
                                <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-xl">
                                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-semibold text-gray-900">Vacuna</h4>
                                        <p class="text-sm text-gray-600">{{ $appointment->vaccine_name }}</p>
                                    </div>
                                </div>
                            @endif

                            @if($appointment->next_date)
                                <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-xl">
                                    <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-semibold text-gray-900">Próxima Cita</h4>
                                        <p class="text-sm text-gray-600">{{ $appointment->next_date instanceof \Carbon\Carbon ? $appointment->next_date->format('d/m/Y') : \Carbon\Carbon::parse($appointment->next_date)->format('d/m/Y') }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Observaciones -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
                        <div class="px-6 py-5 border-b border-gray-100">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900">Observaciones</h3>
                            </div>
                        </div>
                        <div class="p-6">
                            @if($appointment->observations)
                                <div class="p-4 bg-gray-50 rounded-xl">
                                    <p class="text-gray-700 whitespace-pre-wrap">{{ $appointment->observations }}</p>
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-gray-500">No hay observaciones registradas</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Diagnóstico y tratamiento optimizados -->
                @if($appointment->diagnosis || $appointment->treatment)
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                        @if($appointment->diagnosis)
                            <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
                                <div class="px-6 py-5 border-b border-gray-100">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center">
                                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900">Diagnóstico</h3>
                                    </div>
                                </div>
                                <div class="p-6">
                                    <div class="p-4 bg-red-50 rounded-xl">
                                        <p class="text-gray-700 whitespace-pre-wrap">{{ $appointment->diagnosis }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($appointment->treatment)
                            <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
                                <div class="px-6 py-5 border-b border-gray-100">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900">Tratamiento</h3>
                                    </div>
                                </div>
                                <div class="p-6">
                                    <div class="p-4 bg-green-50 rounded-xl">
                                        <p class="text-gray-700 whitespace-pre-wrap">{{ $appointment->treatment }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Información del sistema optimizada -->
                <div class="bg-gray-50 rounded-2xl border border-gray-100 mt-6">
                    <div class="px-6 py-5">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="w-8 h-8 bg-gray-200 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-sm font-semibold text-gray-900">Información del Sistema</h3>
                        </div>
                        <div class="space-y-2 text-sm text-gray-600">
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Cita creada el {{ $appointment->created_at->format('d/m/Y \a \l\a\s H:i') }}</span>
                            </div>
                            @if($appointment->updated_at != $appointment->created_at)
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    <span>Última actualización el {{ $appointment->updated_at->format('d/m/Y \a \l\a\s H:i') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        </div>
    </div>
</div>
@endsection
