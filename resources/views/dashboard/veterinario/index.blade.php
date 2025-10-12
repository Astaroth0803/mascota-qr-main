@extends('layouts.dashboard')

@section('title', 'Dashboard Veterinario')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Móvil Optimizado -->
    <div class="bg-white shadow-sm border-b border-gray-200 pt-16 lg:pt-0">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="py-4 lg:py-6">
                <!-- Header Principal -->
                <div class="mb-4">
                    <h1 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900">Dashboard</h1>
                    <p class="text-sm text-gray-600 mt-1">Bienvenido, Dr. {{ auth()->user()->name }}</p>
                </div>
                
                <!-- Acciones Rápidas -->
                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('dashboard.veterinario.calendario.today') }}" 
                       class="flex items-center justify-center p-3 bg-blue-50 border border-blue-200 rounded-xl hover:bg-blue-100 transition-colors">
                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-sm font-medium text-blue-700">Citas Hoy</span>
                    </a>
                    <a href="{{ route('dashboard.veterinario.calendario.create') }}" 
                       class="flex items-center justify-center p-3 bg-green-50 border border-green-200 rounded-xl hover:bg-green-100 transition-colors">
                        <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <span class="text-sm font-medium text-green-700">Nueva Cita</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div>
        <div class="px-4 sm:px-6 lg:px-8 py-6">
            <!-- Estadísticas Compactas -->
            <div class="grid grid-cols-2 gap-3 mb-6">
                <!-- Total Mascotas -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Mascotas</p>
                            <p class="text-xl font-bold text-gray-900 mt-1">{{ $stats['total_mascotas'] }}</p>
                        </div>
                        <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Citas Hoy -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Citas Hoy</p>
                            <p class="text-xl font-bold text-gray-900 mt-1">{{ $stats['citas_hoy'] }}</p>
                        </div>
                        <div class="w-10 h-10 bg-yellow-100 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Mascotas Principales -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Principales</p>
                            <p class="text-xl font-bold text-gray-900 mt-1">{{ $stats['mascotas_principales'] }}</p>
                        </div>
                        <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Vacunas Pendientes -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Pendientes</p>
                            <p class="text-xl font-bold text-gray-900 mt-1">{{ $stats['vacunas_pendientes'] }}</p>
                        </div>
                        <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Próximas Citas -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
                <div class="px-4 py-3 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Próximas Citas
                        </h2>
                        <a href="{{ route('dashboard.veterinario.calendario.index') }}" 
                           class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                            Ver todas
                        </a>
                    </div>
                </div>
                
                <div class="p-4">
                    <!-- Lista de citas próximas -->
                    <div class="space-y-3">
                        @if(isset($upcomingAppointments) && $upcomingAppointments->count() > 0)
                            @foreach($upcomingAppointments->take(3) as $appointment)
                                <div class="flex items-center p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center mr-3">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-sm font-semibold text-gray-900 truncate">{{ $appointment->pet->nombre }}</h3>
                                        <p class="text-xs text-gray-600">{{ $appointment->date->format('d/m/Y') }} a las {{ $appointment->time ? ($appointment->time instanceof \Carbon\Carbon ? $appointment->time->format('H:i') : \Carbon\Carbon::parse($appointment->time)->format('H:i')) : '09:00' }}</p>
                                        <p class="text-xs text-gray-500">{{ $appointment->getTypeOptions()[$appointment->record_type] ?? ucfirst($appointment->record_type) }}</p>
                                    </div>
                                    <div class="ml-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-sm text-gray-500 mt-2">No hay citas próximas</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Mascotas Asignadas -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            Mis Mascotas
                        </h2>
                        <a href="{{ route('dashboard.veterinario.mascotas') }}" 
                           class="text-green-600 hover:text-green-700 text-sm font-medium">
                            Ver todas
                        </a>
                    </div>
                </div>
                
                <div class="p-4">
                    @if($mascotasAsignadas->count() > 0)
                        <div class="space-y-3">
                            @foreach($mascotasAsignadas->take(3) as $mascota)
                                <div class="flex items-center p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mr-3">
                                        @if($mascota->profile_image)
                                            <img src="{{ Storage::url($mascota->profile_image) }}" alt="{{ $mascota->nombre }}" class="w-full h-full object-cover rounded-xl">
                                        @else
                                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-sm font-semibold text-gray-900 truncate">{{ $mascota->nombre }}</h3>
                                        <p class="text-xs text-gray-600">{{ $mascota->especie }} - {{ $mascota->raza }}</p>
                                        <p class="text-xs text-gray-500">Dueño: {{ $mascota->user->name }}</p>
                                    </div>
                                    <div class="flex gap-2">
                                        <a href="{{ route('dashboard.veterinario.mascota.show', $mascota) }}" 
                                           class="p-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                        <a href="{{ route('dashboard.veterinario.historial', $mascota) }}" 
                                           class="p-2 bg-green-100 text-green-600 rounded-lg hover:bg-green-200 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            <p class="text-sm text-gray-500 mt-2">No hay mascotas asignadas</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
