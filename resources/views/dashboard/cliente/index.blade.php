@extends('layouts.dashboard')

@section('title', 'Dashboard - Cliente')

@section('content')
<div class="min-h-screen bg-gray-50" x-data="dashboardClient()">
    <!-- Header Móvil Optimizado -->
    <div class="bg-white shadow-sm border-b border-gray-200 pt-16 lg:pt-0">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="py-4 lg:py-6">
                <!-- Header Principal -->
                <div class="mb-4">
                    <h1 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900">Dashboard</h1>
                    <p class="text-sm text-gray-600 mt-1">Bienvenido, {{ auth()->user()->name }}</p>
                </div>
                
                <!-- Acciones Rápidas -->
                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('dashboard.cliente.pet-requests.create') }}" 
                       class="flex items-center justify-center p-3 bg-green-50 border border-green-200 rounded-xl hover:bg-green-100 transition-colors">
                        <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <span class="text-sm font-medium text-green-700">Nueva Mascota</span>
                    </a>
                    <a href="{{ route('dashboard.cliente.qr.index') }}" 
                       class="flex items-center justify-center p-3 bg-purple-50 border border-purple-200 rounded-xl hover:bg-purple-100 transition-colors">
                        <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                        </svg>
                        <span class="text-sm font-medium text-purple-700">Mis QR</span>
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
                            <p class="text-xl font-bold text-gray-900 mt-1">{{ $stats['total_pets'] }}</p>
                        </div>
                        <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Con Código QR -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Con QR</p>
                            <p class="text-xl font-bold text-gray-900 mt-1">{{ $stats['pets_with_qr'] }}</p>
                        </div>
                        <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Próximas Citas -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Próximas Citas</p>
                            <p class="text-xl font-bold text-gray-900 mt-1">{{ $stats['upcoming_appointments'] ?? 0 }}</p>
                        </div>
                        <div class="w-10 h-10 bg-yellow-100 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Sin QR -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Sin QR</p>
                            <p class="text-xl font-bold text-gray-900 mt-1">{{ $stats['pets_without_qr'] ?? 0 }}</p>
                        </div>
                        <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Widgets Compactos -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
                <!-- Calendario de Citas -->
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Próximas Citas</h3>
                    <a href="{{ route('dashboard.cliente.calendario.index') }}" class="text-sm text-blue-600 hover:text-blue-500 font-medium">
                        Ver calendario completo →
                    </a>
                </div>
                <div class="space-y-3">
                    @forelse($upcomingAppointments ?? [] as $appointment)
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm font-medium text-gray-900">{{ $appointment['pet_name'] ?? 'Mascota' }}</p>
                                <p class="text-sm text-gray-500">{{ $appointment['date'] ?? 'Fecha no disponible' }} - {{ $appointment['time'] ?? 'Hora no disponible' }}</p>
                            </div>
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $appointment['type'] ?? 'Cita' }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No hay citas próximas</h3>
                            <p class="mt-1 text-sm text-gray-500">Programa una cita para tu mascota.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Recordatorios Rápidos -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Recordatorios</h3>
                <div class="space-y-3">
                    @if(count($stats['upcoming_vaccines']) > 0)
                        <div class="flex items-center p-3 bg-yellow-50 rounded-lg">
                            <div class="flex-shrink-0">
                                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-yellow-800">{{ count($stats['upcoming_vaccines']) }} vacunas próximas</p>
                                <p class="text-xs text-yellow-600">Revisa el calendario</p>
                            </div>
                        </div>
                    @endif
                    
                    @if($stats['overdue_vaccines'] > 0)
                        <div class="flex items-center p-3 bg-red-50 rounded-lg">
                            <div class="flex-shrink-0">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-red-800">{{ $stats['overdue_vaccines'] }} vacunas vencidas</p>
                                <p class="text-xs text-red-600">Requieren atención urgente</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

            <!-- Lista de Mascotas Compacta -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Mis Mascotas</h3>
                        <p class="text-sm text-gray-500" x-text="`${filteredPets.length} de ${pets.length} mascotas`"></p>
                    </div>
                    <div class="flex items-center justify-between sm:justify-end gap-3">
                            <!-- Botón Nueva Mascota -->
                            <a href="{{ route('dashboard.cliente.pet-requests.create') }}" 
                               class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Nueva Mascota
                            </a>
                            
                            <!-- Botón Códigos QR -->
                            <a href="{{ route('dashboard.cliente.qr.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                                </svg>
                                Mis QR
                            </a>
                        
                        <!-- Vista Toggle Optimizado para Móvil -->
                        <div class="flex rounded-lg border border-gray-300">
                            <button @click="viewMode = 'grid'" 
                                    :class="viewMode === 'grid' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700'"
                                    class="px-2 sm:px-3 py-1.5 sm:py-1 text-sm font-medium rounded-l-lg border-r border-gray-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                                </svg>
                            </button>
                            <button @click="viewMode = 'list'" 
                                    :class="viewMode === 'list' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700'"
                                    class="px-2 sm:px-3 py-1.5 sm:py-1 text-sm font-medium rounded-r-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Vista de Lista Optimizada para Móviles -->
            <div x-show="viewMode === 'list'" class="divide-y divide-gray-200">
                <template x-for="pet in filteredPets" :key="pet.id">
                    <div class="px-4 sm:px-6 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <img x-show="pet.profile_image" 
                                     :src="pet.profile_image" 
                                     :alt="pet.nombre"
                                     class="w-12 h-12 rounded-lg object-cover">
                                <div x-show="!pet.profile_image" 
                                     class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900" x-text="pet.nombre"></p>
                                <p class="text-sm text-gray-500" x-text="`${pet.especie} - ${pet.raza}`"></p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span x-show="pet.qr_code" 
                                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                QR
                            </span>
                            <span x-show="!pet.qr_code" 
                                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                Sin QR
                            </span>
                            <div class="flex space-x-1">
                                <a :href="`/dashboard/cliente/mascotas/${pet.slug}`" 
                                   class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <span class="hidden sm:inline">Ver</span>
                                </a>
                                <a :href="`/dashboard/cliente/mascotas/${pet.slug}/edit`" 
                                   class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium text-gray-700 bg-gray-50 hover:bg-gray-100 rounded-lg transition-colors">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    <span class="hidden sm:inline">Editar</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Vista de Grid -->
            <div x-show="viewMode === 'grid'" class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <template x-for="pet in filteredPets" :key="pet.id">
                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                            <div class="p-4">
                                <div class="flex items-center mb-3">
                                    <img x-show="pet.profile_image" 
                                         :src="pet.profile_image" 
                                         :alt="pet.nombre"
                                         class="w-12 h-12 rounded-lg object-cover">
                                    <div x-show="!pet.profile_image" 
                                         class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <h4 class="text-sm font-medium text-gray-900" x-text="pet.nombre"></h4>
                                        <p class="text-xs text-gray-500" x-text="`${pet.especie} - ${pet.raza}`"></p>
                                    </div>
                                    <span x-show="pet.qr_code" 
                                          class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        QR
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <div class="flex space-x-2">
                                        <a :href="`/dashboard/cliente/mascotas/${pet.slug}`" 
                                           class="text-blue-600 hover:text-blue-500 text-sm font-medium">
                                            Ver
                                        </a>
                                        <a :href="`/dashboard/cliente/mascotas/${pet.slug}/edit`" 
                                           class="text-gray-600 hover:text-gray-500 text-sm font-medium">
                                            Editar
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

                <!-- Estado Vacío -->
                <div x-show="filteredPets.length === 0" class="px-6 py-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No hay mascotas</h3>
                    <p class="mt-1 text-sm text-gray-500">Comienza registrando tu primera mascota.</p>
                    <div class="mt-6">
                        <a href="{{ route('dashboard.cliente.pet-requests.create') }}" 
                           class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Registrar Primera Mascota
                        </a>
                    </div>
                </div>
        </div>
    </div>
    </div>
</div>

<script>
function dashboardClient() {
    return {
        viewMode: 'grid',
        showAllNotifications: false,
        pets: {!! json_encode($pets->map(function($pet) {
            return [
                'id' => $pet->id,
                'nombre' => $pet->nombre,
                'especie' => $pet->especie,
                'raza' => $pet->raza,
                'slug' => $pet->slug,
                'qr_code' => $pet->qr_code,
                'profile_image' => $pet->profile_image ? asset('storage/' . $pet->profile_image) : null,
            ];
        })) !!},
        filteredPets: [],
        
        init() {
            this.filteredPets = [...this.pets];
        }
    }
}
</script>
@endsection