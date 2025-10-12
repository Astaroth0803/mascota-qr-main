@extends('layouts.dashboard')

@section('title', 'Calendario de Citas - Veterinario')

@section('content')
<div class="min-h-screen bg-gray-50" x-data="calendarioVeterinario()">
    <!-- Header Optimizado -->
    <div class="bg-white shadow-sm border-b border-gray-200 pt-16 lg:pt-0">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="py-4 lg:py-6">
                <!-- Título y navegación -->
                <div class="flex items-center justify-between mb-4">
                    <div class="flex-1 min-w-0">
                        <h1 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900">Mi Calendario</h1>
                        <p class="text-sm text-gray-600 mt-1">Gestiona las citas médicas de tus mascotas</p>
                    </div>
                    <!-- Acciones rápidas -->
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('dashboard.veterinario.calendario.today') }}" 
                           class="inline-flex items-center px-3 py-2 bg-green-50 border border-green-200 text-green-700 text-sm font-medium rounded-xl hover:bg-green-100 transition-colors">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="hidden sm:inline">Hoy</span>
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
            </div>
        </div>
    </div>

    <div>
        <div class="px-4 sm:px-6 lg:px-8 py-6">
            <!-- Estadísticas Optimizadas -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Total</p>
                            <p class="text-lg font-bold text-gray-900">{{ $stats['total_appointments'] }}</p>
                        </div>
                        <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Próximas</p>
                            <p class="text-lg font-bold text-gray-900">{{ $stats['upcoming_count'] }}</p>
                        </div>
                        <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Completadas</p>
                            <p class="text-lg font-bold text-gray-900">{{ $stats['past_count'] }}</p>
                        </div>
                        <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Mascotas</p>
                            <p class="text-lg font-bold text-gray-900">{{ $stats['assigned_pets_count'] }}</p>
                        </div>
                        <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navegación por Pestañas Optimizada -->
            <div class="mb-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-1">
                    <nav class="flex space-x-1">
                        <button @click="activeTab = 'upcoming'" 
                                :class="activeTab === 'upcoming' ? 'bg-blue-600 text-white shadow-sm' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'"
                                class="flex-1 flex items-center justify-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="hidden sm:inline">Próximas</span>
                            <span class="sm:hidden">Próx.</span>
                            <span class="ml-2 bg-white bg-opacity-20 text-white px-2 py-0.5 rounded-full text-xs font-medium">{{ $upcomingAppointments->count() }}</span>
                        </button>
                        <button @click="activeTab = 'past'" 
                                :class="activeTab === 'past' ? 'bg-blue-600 text-white shadow-sm' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'"
                                class="flex-1 flex items-center justify-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="hidden sm:inline">Historial</span>
                            <span class="sm:hidden">Hist.</span>
                            <span class="ml-2 bg-white bg-opacity-20 text-white px-2 py-0.5 rounded-full text-xs font-medium">{{ $pastAppointments->count() }}</span>
                        </button>
                    </nav>
                </div>
            </div>

            <!-- Contenido de las pestañas -->
            <div class="space-y-4">
                <!-- Pestaña: Próximas Citas -->
                <div x-show="activeTab === 'upcoming'" class="space-y-4">
                    @forelse($upcomingAppointments as $appointment)
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-4 hover:shadow-md transition-all duration-200">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-start space-x-3 flex-1 min-w-0">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center space-x-2 mb-2">
                                            <h3 class="text-base font-semibold text-gray-900 truncate">{{ $appointment->pet->nombre }}</h3>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 flex-shrink-0">
                                                {{ $appointment->record_type_label }}
                                            </span>
                                        </div>
                                        <div class="space-y-1 text-sm text-gray-600">
                                            <div class="flex items-center">
                                                <svg class="w-3.5 h-3.5 mr-1.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <span class="font-medium">{{ $appointment->scheduled_datetime->format('d/m/Y') }} a las {{ $appointment->scheduled_datetime->format('H:i') }}</span>
                                                <span class="mx-2 text-gray-300">•</span>
                                                <span class="truncate">{{ $appointment->client->name ?? 'N/A' }}</span>
                                            </div>
                                            @if($appointment->location)
                                                <div class="flex items-center">
                                                    <svg class="w-3.5 h-3.5 mr-1.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    </svg>
                                                    <span class="truncate text-xs">{{ Str::limit($appointment->location, 40) }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
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
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">No hay citas próximas</h3>
                                <p class="text-gray-500 mb-6">Programa una nueva cita para tus mascotas asignadas.</p>
                                <a href="{{ route('dashboard.veterinario.calendario.create') }}" 
                                   class="inline-flex items-center px-6 py-3 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Nueva Cita
                                </a>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Pestaña: Historial -->
                <div x-show="activeTab === 'past'" class="space-y-4">
                    @forelse($pastAppointments as $appointment)
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-4 hover:shadow-md transition-all duration-200">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-start space-x-3 flex-1 min-w-0">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center space-x-2 mb-2">
                                            <h3 class="text-base font-semibold text-gray-900 truncate">{{ $appointment->pet->nombre }}</h3>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 flex-shrink-0">
                                                {{ $appointment->record_type_label }}
                                            </span>
                                        </div>
                                        <div class="space-y-1 text-sm text-gray-600">
                                            <div class="flex items-center">
                                                <svg class="w-3.5 h-3.5 mr-1.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <span class="font-medium">{{ $appointment->scheduled_datetime->format('d/m/Y') }} a las {{ $appointment->scheduled_datetime->format('H:i') }}</span>
                                                <span class="mx-2 text-gray-300">•</span>
                                                <span class="truncate">{{ $appointment->client->name ?? 'N/A' }}</span>
                                            </div>
                                            @if($appointment->diagnosis_treatment)
                                                <div class="text-xs text-gray-600 mt-2 p-2 bg-gray-50 rounded-lg">
                                                    <strong class="text-gray-900">Diagnóstico/Tratamiento:</strong> {{ Str::limit($appointment->diagnosis_treatment, 80) }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <a href="{{ route('dashboard.veterinario.calendario.show', $appointment->id) }}" 
                                       class="inline-flex items-center px-2.5 py-1.5 border border-gray-200 text-xs font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors min-w-0">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        <span class="hidden sm:inline">Ver</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                            <div class="text-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">No hay historial de citas</h3>
                                <p class="text-gray-500">Las citas completadas aparecerán aquí.</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function calendarioVeterinario() {
    return {
        activeTab: 'upcoming'
    }
}
</script>
@endsection
