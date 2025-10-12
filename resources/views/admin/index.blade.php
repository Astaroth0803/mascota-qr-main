@extends('layouts.standard')

@section('title', 'Dashboard Administrativo')

@php
    $title = 'Dashboard Administrativo';
    $subtitle = 'Panel de control y gestión del sistema';
@endphp

@section('main-content')
<div class="space-y-6">
    <!-- Acciones rápidas -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Acciones Rápidas</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Accesos directos a las funciones más utilizadas</p>
        </div>
        <div class="px-6 py-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
                <a href="{{ route('dashboard.solicitudes') }}" 
                   class="flex items-center p-3 sm:p-4 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors group">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center group-hover:bg-yellow-200 dark:group-hover:bg-yellow-900/50 transition-colors">
                            <svg class="w-4 h-4 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3 min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">Solicitudes</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">Gestionar solicitudes</p>
                    </div>
                </a>

                <a href="{{ route('dashboard.usuarios') }}" 
                   class="flex items-center p-3 sm:p-4 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors group">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center group-hover:bg-blue-200 dark:group-hover:bg-blue-900/50 transition-colors">
                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3 min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">Usuarios</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">Gestionar usuarios</p>
                    </div>
                </a>

                <a href="{{ route('qr.generator') }}" 
                   class="flex items-center p-3 sm:p-4 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors group">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center group-hover:bg-purple-200 dark:group-hover:bg-purple-900/50 transition-colors">
                            <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3 min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">Generar QR</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">Códigos QR masivos</p>
                    </div>
                </a>

                <a href="{{ route('dashboard.usuarios') }}" 
                   class="flex items-center p-3 sm:p-4 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors group">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center group-hover:bg-green-200 dark:group-hover:bg-green-900/50 transition-colors">
                            <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-3 min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-900 dark:text-white truncate">Reportes</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">Ver estadísticas</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <!-- Estadísticas principales -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 lg:gap-6">
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
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 lg:gap-6">
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
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 lg:gap-8">
        <!-- Distribución por especie -->
        <x-dashboard.chart-container title="Distribución por Especie" description="Mascotas por tipo">
            <div class="w-full h-full">
                @if($stats['species_distribution']->count() > 0)
                    <div class="space-y-4">
                        @foreach($stats['species_distribution'] as $species => $count)
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $species }}</span>
                                <div class="flex items-center">
                                    <div class="w-32 bg-gray-200 dark:bg-gray-600 rounded-full h-2 mr-3">
                                        <div class="bg-blue-600 h-2 rounded-full" 
                                             style="width: {{ $stats['total_pets'] > 0 ? ($count / $stats['total_pets']) * 100 : 0 }}%"></div>
                                    </div>
                                    <span class="text-sm text-gray-600 dark:text-gray-400 w-8">{{ $count }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-gray-500 dark:text-gray-400">
                        <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300 truncate">{{ $breed }}</span>
                                <div class="flex items-center">
                                    <div class="w-24 bg-gray-200 dark:bg-gray-600 rounded-full h-2 mr-3">
                                        <div class="bg-green-600 h-2 rounded-full" 
                                             style="width: {{ $stats['total_pets'] > 0 ? ($count / $stats['total_pets']) * 100 : 0 }}%"></div>
                                    </div>
                                    <span class="text-sm text-gray-600 dark:text-gray-400 w-6">{{ $count }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-gray-500 dark:text-gray-400">
                        <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <p class="mt-2">No hay datos disponibles</p>
                    </div>
                @endif
            </div>
        </x-dashboard.chart-container>
    </div>

    <!-- Actividad reciente -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 lg:gap-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Actividad Reciente (7 días)</h3>
            </div>
            <div class="px-6 py-4">
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Nuevos usuarios</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $stats['recent_activity']['new_users'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Nuevas mascotas</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $stats['recent_activity']['new_pets'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Nuevas solicitudes</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $stats['recent_activity']['new_solicitudes'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Códigos QR generados</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $stats['recent_activity']['qr_generated'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Usuarios por Rol</h3>
            </div>
            <div class="px-6 py-4">
                <div class="space-y-4">
                    @foreach($stats['users_by_role'] as $role => $count)
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ ucfirst($role) }}</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $count }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection