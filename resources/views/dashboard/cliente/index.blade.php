@extends('layouts.dashboard')

@section('title', 'Dashboard - Cliente')

@section('content')
<div class="min-h-screen bg-gray-50" x-data="dashboardClient()">
    <!-- Header Optimizado para Móviles -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="lg:ml-64">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-4 lg:py-6">
                <!-- Header Responsive -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
                    <!-- Título -->
                    <div class="flex-1 min-w-0 hidden lg:block">
                        <h1 class="text-lg sm:text-xl lg:text-2xl xl:text-3xl font-bold text-gray-900">Mi Dashboard</h1>
                        <p class="text-xs sm:text-sm lg:text-base text-gray-600 mt-1">Gestiona tus mascotas y mantén su información actualizada</p>
                    </div>
                    
                    <!-- Botones -->
                    <div class="flex items-center gap-2 sm:gap-3">
                        <!-- Panel de Notificaciones -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" 
                                    class="relative inline-flex items-center justify-center px-3 sm:px-4 py-2.5 sm:py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors w-full sm:w-auto">
                                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.828 7l2.586 2.586a2 2 0 002.828 0L12 7H4.828zM4 12h16M4 16h16M4 20h16"></path>
                                </svg>
                                <span class="hidden sm:inline">Notificaciones</span>
                                <span class="sm:hidden">Notif.</span>
                                @if($notifications->count() > 0)
                                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-4 w-4 sm:h-5 sm:w-5 flex items-center justify-center text-xs">
                                        {{ $notifications->count() }}
                                    </span>
                                @endif
                            </button>
                            
                            <!-- Dropdown de Notificaciones -->
                            <div x-show="open" 
                                 @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 transform scale-95"
                                 x-transition:enter-end="opacity-100 transform scale-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 transform scale-100"
                                 x-transition:leave-end="opacity-0 transform scale-95"
                                 class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                                <div class="px-4 py-3 border-b border-gray-200">
                                    <h3 class="text-sm font-medium text-gray-900">Mis Notificaciones</h3>
                                    <p class="text-xs text-gray-500 mt-1">{{ $notifications->count() }} notificaciones</p>
                                </div>
                                <div class="max-h-96 overflow-y-auto">
                                    @if($notifications->count() > 0)
                                        @foreach($notifications as $notification)
                                            <div class="px-4 py-3 border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                                <div class="flex items-start">
                                                    <div class="flex-shrink-0">
                                                        <div class="w-8 h-8 rounded-full flex items-center justify-center
                                                            @if($notification['type'] == 'error') bg-red-100 text-red-600
                                                            @elseif($notification['type'] == 'warning') bg-yellow-100 text-yellow-600
                                                            @elseif($notification['type'] == 'success') bg-green-100 text-green-600
                                                            @else bg-blue-100 text-blue-600 @endif">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                @if($notification['type'] == 'error')
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                @elseif($notification['type'] == 'warning')
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                                                @elseif($notification['type'] == 'success')
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                @else
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                @endif
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <div class="ml-3 flex-1 min-w-0">
                                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $notification['title'] }}</p>
                                                        <p class="text-xs text-gray-500 mt-1">{{ $notification['message'] }}</p>
                                                        @if(isset($notification['action']) && isset($notification['url']))
                                                            <a href="{{ $notification['url'] }}" 
                                                               class="inline-flex items-center text-xs text-blue-600 hover:text-blue-800 mt-2">
                                                                {{ $notification['action'] }}
                                                                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                                </svg>
                                                            </a>
                                                        @endif
                                                        @if(isset($notification['date']))
                                                            <p class="text-xs text-gray-400 mt-1">{{ $notification['date']->format('d/m/Y H:i') }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="px-4 py-8 text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <h3 class="mt-2 text-sm font-medium text-gray-900">No hay notificaciones</h3>
                                            <p class="mt-1 text-sm text-gray-500">Todo está al día</p>
                                        </div>
                                    @endif
                                </div>
                                @if($notifications->count() > 0)
                                    <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
                                        <a href="{{ route('dashboard.cliente.notificaciones') }}" 
                                           class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                            Ver todas las notificaciones
                                        </a>
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

    <div class="lg:ml-64">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Estadísticas Principales Optimizadas para Móviles -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6 mb-6 lg:mb-8">
            <x-dashboard.stat-card 
                title="Total de Mascotas"
                :value="$stats['total_pets']"
                icon="pets"
                color="blue"
                description="Mascotas registradas"
                :trend="$stats['pets_trend'] ?? null"
            />
            
            <x-dashboard.stat-card 
                title="Con Código QR"
                :value="$stats['pets_with_qr']"
                icon="qr-code"
                color="green"
                :change="$stats['qr_coverage']"
                change-type="positive"
                description="Cobertura: {{ $stats['qr_coverage'] }}%"
            />
            
                <x-dashboard.stat-card
                title="Próximas Vacunas"
                :value="count($stats['upcoming_vaccines'])"
                icon="alert"
                color="yellow"
                description="En los próximos 30 días"
                :trend="$stats['vaccines_trend'] ?? null"
            />
            
            <x-dashboard.stat-card 
                title="Vacunas Vencidas"
                :value="$stats['overdue_vaccines']"
                icon="alert"
                color="red"
                description="Requieren atención"
                :trend="$stats['overdue_trend'] ?? null"
            />
        </div>

        <!-- Widgets Optimizados para Móviles -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 mb-6 lg:mb-8">
            <!-- Calendario de Citas -->
            <div class="lg:col-span-2 bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Próximas Citas</h3>
                    <a href="#" class="text-sm text-blue-600 hover:text-blue-500 font-medium">
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
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 sm:p-6">
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

        <!-- Lista de Mascotas Optimizada para Móviles -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
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
                                   class="text-blue-600 hover:text-blue-500 text-sm font-medium px-2 py-1 rounded hover:bg-blue-50">
                                    Ver
                                </a>
                                <a :href="`/dashboard/cliente/mascotas/${pet.slug}/edit`" 
                                   class="text-gray-600 hover:text-gray-500 text-sm font-medium px-2 py-1 rounded hover:bg-gray-50">
                                    Editar
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
                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">
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