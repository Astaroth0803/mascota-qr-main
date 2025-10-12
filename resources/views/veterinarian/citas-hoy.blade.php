@extends('layouts.standard')

@section('title', 'Citas de Hoy')

@php
    $title = 'Citas de Hoy';
    $subtitle = now()->format('l, d \d\e F \d\e Y');
@endphp

@section('main-content')
<div>
    <!-- Acciones rápidas -->
    <div class="flex items-center justify-end mb-6">
        <div class="flex items-center space-x-2">
            <a href="{{ route('dashboard.veterinario.calendario.index') }}" 
               class="inline-flex items-center px-3 py-2 bg-gray-100 border border-gray-200 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition-colors">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span class="hidden sm:inline">Calendario</span>
            </a>
            <a href="{{ route('dashboard.veterinario.calendario.create') }}" 
               class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span class="hidden sm:inline">Nueva</span>
            </a>
        </div>
    </div>
            <!-- Resumen del día optimizado -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-semibold text-gray-900">
                                {{ $todayAppointments->count() }} {{ $todayAppointments->count() === 1 ? 'cita' : 'citas' }} programada{{ $todayAppointments->count() === 1 ? '' : 's' }} para hoy
                            </h3>
                            @if($todayAppointments->count() > 0)
                                <p class="text-sm text-gray-600 mt-1">
                                    {{ $todayAppointments->count() }} mascota{{ $todayAppointments->count() === 1 ? '' : 's' }} esperando tu atención
                                </p>
                            @endif
                        </div>
                    </div>
                    @if($todayAppointments->count() > 0)
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('dashboard.veterinario.calendario.create') }}" 
                               class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Nueva Cita
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Lista de citas del día optimizada -->
            @forelse($todayAppointments as $appointment)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-4 hover:shadow-md transition-all duration-200">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-start space-x-3 flex-1 min-w-0">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center space-x-2 mb-2">
                                    <h3 class="text-base font-semibold text-gray-900 truncate">{{ $appointment->pet->nombre }}</h3>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 flex-shrink-0">
                                        {{ $appointment->getAppointmentTypeLabelAttribute() }}
                                    </span>
                                </div>
                                
                                <!-- Información de la cita compacta -->
                                <div class="space-y-1 text-sm text-gray-600">
                                    <div class="flex items-center">
                                        <svg class="w-3.5 h-3.5 mr-1.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="font-medium">{{ $appointment->time ? ($appointment->time instanceof \Carbon\Carbon ? $appointment->time->format('H:i') : \Carbon\Carbon::parse($appointment->time)->format('H:i')) : '09:00' }}</span>
                                        <span class="mx-2 text-gray-300">•</span>
                                        <span class="truncate">{{ $appointment->pet->user->name ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-3.5 h-3.5 mr-1.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                                        </svg>
                                        <span class="truncate">{{ $appointment->pet->especie }} - {{ $appointment->pet->raza }}</span>
                                        @if($appointment->location)
                                            <span class="mx-2 text-gray-300">•</span>
                                            <span class="truncate text-xs">{{ Str::limit($appointment->location, 30) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Acciones compactas -->
                        <div class="flex items-center space-x-1.5">
                            <a href="{{ route('dashboard.veterinario.calendario.show', $appointment->id) }}" 
                               class="inline-flex items-center px-2.5 py-1.5 border border-gray-200 text-xs font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors min-w-0">
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <span class="hidden sm:inline">Ver</span>
                            </a>
                            <a href="{{ route('dashboard.veterinario.calendario.edit', $appointment->id) }}" 
                               class="inline-flex items-center px-2.5 py-1.5 bg-blue-600 text-white text-xs font-medium rounded-lg hover:bg-blue-700 transition-colors min-w-0">
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                <span class="hidden sm:inline">Editar</span>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">¡Día libre!</h3>
                        <p class="text-gray-500 mb-6">No tienes citas programadas para hoy. Es un buen momento para revisar tu agenda o programar nuevas citas.</p>
                        <div class="flex flex-col sm:flex-row gap-3 justify-center">
                            <a href="{{ route('dashboard.veterinario.calendario.create') }}" 
                               class="inline-flex items-center px-6 py-3 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Programar Nueva Cita
                            </a>
                            <a href="{{ route('dashboard.veterinario.calendario.index') }}" 
                               class="inline-flex items-center px-6 py-3 bg-gray-100 border border-gray-200 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Ver Calendario Completo
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse

            <!-- Próximas citas (si hay espacio) -->
            @if($todayAppointments->count() > 0 && $todayAppointments->count() < 3)
                <div class="mt-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900">Próximas Citas</h3>
                                <p class="text-sm text-gray-500 mt-1">Revisa tu agenda completa</p>
                            </div>
                            <a href="{{ route('dashboard.veterinario.calendario.index') }}" 
                               class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Ver Todas
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
