@extends('layouts.dashboard')

@section('title', 'Dashboard - Administrador')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="lg:ml-64">
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="py-4 lg:py-6">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <h1 class="text-xl lg:text-2xl xl:text-3xl font-bold text-gray-900">Dashboard Administrativo</h1>
                            <p class="text-sm lg:text-base text-gray-600 mt-1">Panel de control y gestión del sistema</p>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-2 lg:gap-3">
                            <!-- Panel de Notificaciones -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" 
                                        class="relative inline-flex items-center justify-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
                                    <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.828 7l2.586 2.586a2 2 0 002.828 0L12 7H4.828zM4 12h16M4 16h16M4 20h16"></path>
                                    </svg>
                                    <span>Notificaciones</span>
                                    @if(count($securityAlerts) > 0)
                                        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                            {{ count($securityAlerts) }}
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
                                        <h3 class="text-sm font-medium text-gray-900">Alertas de Seguridad</h3>
                                        <p class="text-xs text-gray-500 mt-1">{{ count($securityAlerts) }} notificaciones</p>
                                    </div>
                                    <div class="max-h-96 overflow-y-auto">
                                        @if(count($securityAlerts) > 0)
                                            @foreach($securityAlerts as $alert)
                                                <div class="px-4 py-3 border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                                    <div class="flex items-start">
                                                        <div class="flex-shrink-0">
                                                            <div class="w-8 h-8 rounded-full flex items-center justify-center
                                                                @if($alert['type'] == 'error') bg-red-100
                                                                @elseif($alert['type'] == 'warning') bg-yellow-100
                                                                @elseif($alert['type'] == 'info') bg-blue-100
                                                                @else bg-gray-100 @endif">
                                                                <svg class="w-4 h-4 
                                                                    @if($alert['type'] == 'error') text-red-600
                                                                    @elseif($alert['type'] == 'warning') text-yellow-600
                                                                    @elseif($alert['type'] == 'info') text-blue-600
                                                                    @else text-gray-600 @endif" 
                                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    @if($alert['type'] == 'error')
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                                                    @elseif($alert['type'] == 'warning')
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                                                    @elseif($alert['type'] == 'info')
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                    @else
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                    @endif
                                                                </svg>
                                                            </div>
                                                        </div>
                                                        <div class="ml-3 flex-1 min-w-0">
                                                            <p class="text-sm font-medium text-gray-900 truncate">{{ $alert['title'] }}</p>
                                                            <p class="text-xs text-gray-500 mt-1">{{ $alert['message'] }}</p>
                                                            @if(isset($alert['action']) && isset($alert['url']))
                                                                <a href="{{ $alert['url'] }}" 
                                                                   class="inline-flex items-center text-xs text-blue-600 hover:text-blue-800 mt-2">
                                                                    {{ $alert['action'] }}
                                                                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                                    </svg>
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="px-4 py-8 text-center">
                                                <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <p class="text-sm text-gray-500 mt-2">No hay alertas de seguridad</p>
                                            </div>
                                        @endif
                                    </div>
                                    @if(count($securityAlerts) > 0)
                                        <div class="px-4 py-3 border-t border-gray-200">
                                            <a href="{{ route('dashboard.activity-log') }}" class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                                                Ver todas las alertas
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <a href="{{ route('qr.generator') }}" 
                               class="inline-flex items-center justify-center px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition-colors">
                                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                                </svg>
                                <span>Generar QR</span>
                            </a>
                            <a href="{{ route('dashboard.solicitudes') }}" 
                               class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span>Solicitudes</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenido principal -->
    <div class="lg:ml-64">
        <div class="px-4 sm:px-6 lg:px-8 py-6 lg:py-8">

            <!-- Acciones rápidas -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6 lg:mb-8">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Acciones Rápidas</h3>
                    <p class="text-sm text-gray-500 mt-1">Accesos directos a las funciones más utilizadas</p>
                </div>
                <div class="px-6 py-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
                    <a href="{{ route('dashboard.solicitudes') }}" 
                       class="flex items-center p-3 sm:p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors group">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center group-hover:bg-yellow-200 transition-colors">
                                <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3 min-w-0 flex-1">
                            <p class="text-sm font-medium text-gray-900 truncate">Solicitudes</p>
                            <p class="text-xs text-gray-500 truncate">Gestionar solicitudes</p>
                        </div>
                    </a>

                    <a href="{{ route('dashboard.usuarios') }}" 
                       class="flex items-center p-3 sm:p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors group">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3 min-w-0 flex-1">
                            <p class="text-sm font-medium text-gray-900 truncate">Usuarios</p>
                            <p class="text-xs text-gray-500 truncate">Gestionar usuarios</p>
                        </div>
                    </a>

                    <a href="{{ route('qr.generator') }}" 
                       class="flex items-center p-3 sm:p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors group">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center group-hover:bg-purple-200 transition-colors">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3 min-w-0 flex-1">
                            <p class="text-sm font-medium text-gray-900 truncate">Generar QR</p>
                            <p class="text-xs text-gray-500 truncate">Códigos QR masivos</p>
                        </div>
                    </a>

                    <a href="{{ route('dashboard.usuarios') }}" 
                       class="flex items-center p-3 sm:p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors group">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-200 transition-colors">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3 min-w-0 flex-1">
                            <p class="text-sm font-medium text-gray-900 truncate">Reportes</p>
                            <p class="text-xs text-gray-500 truncate">Ver estadísticas</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>

            <!-- Estadísticas principales -->
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 lg:gap-6 mb-6 lg:mb-8">
            <x-dashboard.stat-card 
                title="Total de Usuarios"
                :value="$stats['total_users']"
                icon="users"
                color="blue"
                description="Usuarios registrados"
            />
            
            <x-dashboard.stat-card 
                title="Total de Mascotas"
                :value="$stats['total_pets']"
                icon="pets"
                color="green"
                description="Mascotas registradas"
            />
            
            <x-dashboard.stat-card 
                title="Solicitudes Pendientes"
                :value="$stats['pending_solicitudes']"
                icon="alert"
                color="yellow"
                description="Esperando aprobación"
            />
            
            <x-dashboard.stat-card 
                title="Mascotas Verificadas"
                :value="$stats['verified_pets']"
                icon="check"
                color="green"
                description="Con pago verificado"
            />
        </div>

            <!-- Estadísticas secundarias -->
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 lg:gap-6 mb-6 lg:mb-8">
            <x-dashboard.stat-card 
                title="Mascotas sin QR"
                :value="$stats['pets_without_qr']"
                icon="qr-code"
                color="red"
                description="Requieren código QR"
            />
            
            <x-dashboard.stat-card 
                title="Usuarios Inactivos"
                :value="$stats['inactive_users']"
                icon="users"
                color="gray"
                description="Sin actividad reciente"
            />
            
            <x-dashboard.stat-card 
                title="Pagos Verificados"
                :value="$stats['payment_stats']['verified_payments']"
                icon="check"
                color="green"
                description="Pagos confirmados"
            />
            
            <x-dashboard.stat-card 
                title="Pagos Pendientes"
                :value="$stats['payment_stats']['pending_payments']"
                icon="alert"
                color="yellow"
                description="Esperando verificación"
            />
        </div>

            <!-- Gráficos y distribuciones -->
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 lg:gap-8 mb-6 lg:mb-8">
            <!-- Distribución por especie -->
            <x-dashboard.chart-container title="Distribución por Especie" description="Mascotas por tipo">
                <div class="w-full h-full">
                    @if($stats['species_distribution']->count() > 0)
                        <div class="space-y-4">
                            @foreach($stats['species_distribution'] as $species => $count)
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-700">{{ $species }}</span>
                                    <div class="flex items-center">
                                        <div class="w-32 bg-gray-200 rounded-full h-2 mr-3">
                                            <div class="bg-blue-600 h-2 rounded-full" 
                                                 style="width: {{ $stats['total_pets'] > 0 ? ($count / $stats['total_pets']) * 100 : 0 }}%"></div>
                                        </div>
                                        <span class="text-sm text-gray-600 w-8">{{ $count }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <p class="mt-2">No hay datos disponibles</p>
                        </div>
                    @endif
                </div>
            </x-dashboard.chart-container>

            <!-- Top razas -->
            <x-dashboard.chart-container title="Razas Más Populares" description="Top 10 razas registradas">
                <div class="w-full h-full">
                    @if($stats['breed_distribution']->count() > 0)
                        <div class="space-y-3">
                            @foreach($stats['breed_distribution'] as $breed => $count)
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-700 truncate">{{ $breed }}</span>
                                    <div class="flex items-center">
                                        <div class="w-24 bg-gray-200 rounded-full h-2 mr-3">
                                            <div class="bg-green-600 h-2 rounded-full" 
                                                 style="width: {{ $stats['total_pets'] > 0 ? ($count / $stats['total_pets']) * 100 : 0 }}%"></div>
                                        </div>
                                        <span class="text-sm text-gray-600 w-6">{{ $count }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <p class="mt-2">No hay datos disponibles</p>
                        </div>
                    @endif
                </div>
            </x-dashboard.chart-container>
        </div>

            <!-- Actividad reciente -->
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 lg:gap-8 mb-6 lg:mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Actividad Reciente (7 días)</h3>
                </div>
                <div class="px-6 py-4">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Nuevos usuarios</span>
                            <span class="text-sm font-medium text-gray-900">{{ $stats['recent_activity']['new_users'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Nuevas mascotas</span>
                            <span class="text-sm font-medium text-gray-900">{{ $stats['recent_activity']['new_pets'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Nuevas solicitudes</span>
                            <span class="text-sm font-medium text-gray-900">{{ $stats['recent_activity']['new_solicitudes'] }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Códigos QR generados</span>
                            <span class="text-sm font-medium text-gray-900">{{ $stats['recent_activity']['qr_generated'] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Usuarios por Rol</h3>
                </div>
                <div class="px-6 py-4">
                    <div class="space-y-4">
                        @foreach($stats['users_by_role'] as $role => $count)
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">{{ ucfirst($role) }}</span>
                                <span class="text-sm font-medium text-gray-900">{{ $count }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>
@endsection
