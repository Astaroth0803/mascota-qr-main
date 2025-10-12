@extends('layouts.dashboard')

@section('title', 'Detalle de Cita - Cliente')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="lg:ml-64">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="py-4 lg:py-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <!-- Título -->
                        <div class="flex-1 min-w-0">
                            <h1 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900">Detalle de Cita</h1>
                            <p class="text-sm text-gray-600 mt-1">Información completa de la cita médica</p>
                        </div>
                        
                        <!-- Botones de acción -->
                        <div class="flex items-center gap-3">
                            <a href="{{ route('dashboard.cliente.calendario.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Volver al Calendario
                            </a>
                            <a href="{{ route('dashboard.cliente.calendario.edit', $appointment->id) }}" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Editar Cita
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="lg:ml-64">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Información Principal -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Card Principal -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h2 class="text-xl font-semibold text-gray-900">{{ $appointment->pet->nombre }}</h2>
                                        <p class="text-sm text-gray-500">{{ $appointment->pet->especie }} - {{ $appointment->pet->raza }}</p>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    @if($appointment->date >= now()->toDateString()) bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ $appointment->getTypeOptions()[$appointment->record_type] ?? ucfirst($appointment->record_type) }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="px-6 py-6 space-y-6">
                            
                            <!-- Fecha y Hora -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500 mb-2">Fecha de la Cita</h3>
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <span class="text-lg font-medium text-gray-900">{{ $appointment->date->format('d/m/Y') }}</span>
                                    </div>
                                </div>
                                
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500 mb-2">Hora</h3>
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-lg font-medium text-gray-900">{{ $appointment->time ? ($appointment->time instanceof \Carbon\Carbon ? $appointment->time->format('H:i') : \Carbon\Carbon::parse($appointment->time)->format('H:i')) : '09:00' }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Información del Veterinario -->
                            @if($appointment->vet_name || $appointment->location)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @if($appointment->vet_name)
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500 mb-2">Veterinario</h3>
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        <span class="text-lg font-medium text-gray-900">{{ $appointment->vet_name }}</span>
                                    </div>
                                </div>
                                @endif
                                
                                @if($appointment->location)
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500 mb-2">Ubicación</h3>
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <span class="text-lg font-medium text-gray-900">{{ $appointment->location }}</span>
                                    </div>
                                </div>
                                @endif
                            </div>
                            @endif
                            
                            <!-- Información Médica -->
                            @if($appointment->vaccine_name || $appointment->diagnosis || $appointment->treatment)
                            <div class="space-y-4">
                                @if($appointment->vaccine_name)
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500 mb-2">Vacuna/Tratamiento</h3>
                                    <p class="text-lg font-medium text-gray-900">{{ $appointment->vaccine_name }}</p>
                                </div>
                                @endif
                                
                                @if($appointment->diagnosis)
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500 mb-2">Diagnóstico</h3>
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <p class="text-gray-900 whitespace-pre-wrap">{{ $appointment->diagnosis }}</p>
                                    </div>
                                </div>
                                @endif
                                
                                @if($appointment->treatment)
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500 mb-2">Tratamiento</h3>
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <p class="text-gray-900 whitespace-pre-wrap">{{ $appointment->treatment }}</p>
                                    </div>
                                </div>
                                @endif
                            </div>
                            @endif
                            
                            <!-- Observaciones -->
                            @if($appointment->observations)
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 mb-2">Observaciones</h3>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <p class="text-gray-900 whitespace-pre-wrap">{{ $appointment->observations }}</p>
                                </div>
                            </div>
                            @endif
                            
                        </div>
                    </div>
                    
                    <!-- Próxima Cita -->
                    @if($appointment->next_date)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Próxima Cita Programada</h3>
                        </div>
                        <div class="px-6 py-4">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-lg font-medium text-gray-900">{{ $appointment->next_date instanceof \Carbon\Carbon ? $appointment->next_date->format('d/m/Y') : \Carbon\Carbon::parse($appointment->next_date)->format('d/m/Y') }}</span>
                            </div>
                            <p class="text-sm text-gray-500 mt-1">Siguiente cita programada para {{ $appointment->pet->nombre }}</p>
                        </div>
                    </div>
                    @endif
                </div>
                
                <!-- Sidebar -->
                <div class="space-y-6">
                    
                    <!-- Información de la Mascota -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Información de la Mascota</h3>
                        </div>
                        <div class="px-6 py-4 space-y-3">
                            <div>
                                <span class="text-sm font-medium text-gray-500">Nombre:</span>
                                <p class="text-gray-900">{{ $appointment->pet->nombre }}</p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">Especie:</span>
                                <p class="text-gray-900">{{ $appointment->pet->especie }}</p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">Raza:</span>
                                <p class="text-gray-900">{{ $appointment->pet->raza }}</p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">Edad:</span>
                                <p class="text-gray-900">
                                    @if($appointment->pet->edad_anios > 0)
                                        {{ $appointment->pet->edad_anios }} año{{ $appointment->pet->edad_anios > 1 ? 's' : '' }}
                                    @endif
                                    @if($appointment->pet->edad_meses > 0)
                                        {{ $appointment->pet->edad_meses }} mes{{ $appointment->pet->edad_meses > 1 ? 'es' : '' }}
                                    @endif
                                </p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">Peso:</span>
                                <p class="text-gray-900">{{ $appointment->pet->peso ? $appointment->pet->peso . ' kg' : 'No especificado' }}</p>
                            </div>
                            <div class="pt-3">
                                <a href="{{ route('dashboard.cliente.mascotas.show', $appointment->pet->slug) }}" 
                                   class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
                                    Ver perfil completo
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Acciones Rápidas -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Acciones</h3>
                        </div>
                        <div class="px-6 py-4 space-y-3">
                            <a href="{{ route('dashboard.cliente.calendario.edit', $appointment->id) }}" 
                               class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Editar Cita
                            </a>
                            
                            <form action="{{ route('dashboard.cliente.calendario.destroy', $appointment->id) }}" method="POST" 
                                  onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta cita?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Eliminar Cita
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Fecha de Creación -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Información del Registro</h3>
                        </div>
                        <div class="px-6 py-4 space-y-3">
                            <div>
                                <span class="text-sm font-medium text-gray-500">Creado:</span>
                                <p class="text-gray-900">{{ $appointment->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">Última actualización:</span>
                                <p class="text-gray-900">{{ $appointment->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
