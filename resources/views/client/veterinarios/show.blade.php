@extends('layouts.standard')

@section('title', 'Perfil del Veterinario')

@php
    $title = 'Perfil del Veterinario';
    $subtitle = 'Información detallada del profesional veterinario';
@endphp

@section('main-content')
<div class="w-full">
    <!-- Header Principal -->
    <div class="mb-6">
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Perfil del Veterinario</h1>
        <p class="text-gray-600 mt-1">Información detallada del profesional veterinario</p>
    </div>
    
    <!-- Acciones Rápidas -->
    <div class="flex flex-col sm:flex-row gap-3 mb-6">
        <a href="{{ route('dashboard.cliente.veterinarios.index') }}"
           class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Volver a Veterinarios
        </a>
    </div>

    <!-- Contenido principal -->
    <div class="w-full">
        <div class="px-2 sm:px-4 lg:px-6 xl:px-8 py-4 lg:py-6">
            <!-- Banner del Veterinario -->
            <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-xl shadow-lg p-8 mb-8 relative overflow-hidden border border-gray-200">
                <div class="flex flex-col lg:flex-row items-center space-y-6 lg:space-y-0 lg:space-x-8">
                    <!-- Avatar del Veterinario -->
                    <div class="relative">
                        <div class="w-24 h-24 lg:w-32 lg:h-32 bg-white rounded-full shadow-xl flex items-center justify-center overflow-hidden ring-4 ring-white ring-opacity-50">
                            <div class="w-full h-full bg-green-100 rounded-full flex items-center justify-center text-3xl lg:text-4xl font-bold text-green-800">
                                {{ strtoupper(substr($veterinario->name, 0, 1)) }}
                            </div>
                        </div>
                    </div>
                    
                    <!-- Información del Veterinario -->
                    <div class="text-center lg:text-left flex-1">
                        <h1 class="text-3xl lg:text-4xl font-bold mb-2 text-gray-900">{{ $veterinario->name }}</h1>
                        <p class="text-lg lg:text-xl text-gray-700 mb-4">{{ $veterinario->email }}</p>
                        
                        <!-- Información rápida -->
                        <div class="flex flex-wrap justify-center lg:justify-start gap-4 text-sm">
                            @if($veterinario->tipo_veterinario)
                                <div class="flex items-center bg-white rounded-lg px-3 py-2 shadow-sm border border-gray-200">
                                    <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="font-medium text-gray-700">{{ $veterinario->tipo_veterinario_nombre }}</span>
                                </div>
                            @endif
                            <div class="flex items-center bg-white rounded-lg px-3 py-2 shadow-sm border border-gray-200">
                                <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                                <span class="font-medium text-gray-700">{{ $stats['total_mascotas'] }} mascotas asignadas</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estadísticas -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Mascotas</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_mascotas'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Licenciados</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $stats['mascotas_principales'] }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Técnicos</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $stats['mascotas_tecnicas'] }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Auxiliares</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $stats['mascotas_auxiliares'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información detallada y mascotas asignadas -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Información del Veterinario -->
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-green-50 to-green-100 px-6 py-4 border-b border-gray-200">
                            <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                                <svg class="w-6 h-6 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Información del Veterinario
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm text-gray-500 mb-1">Nombre Completo</p>
                                        <p class="text-lg font-semibold text-gray-900">{{ $veterinario->name }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm text-gray-500 mb-1">Email</p>
                                        <p class="text-lg font-semibold text-gray-900">{{ $veterinario->email }}</p>
                                    </div>
                                </div>

                                @if($veterinario->tipo_veterinario)
                                    <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm text-gray-500 mb-1">Tipo de Veterinario</p>
                                            <p class="text-lg font-semibold text-gray-900">{{ $veterinario->tipo_veterinario_nombre }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Mascotas Asignadas -->
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-6 py-4 border-b border-gray-200">
                            <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                                <svg class="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                                Mascotas Asignadas ({{ $mascotasAsignadas->count() }})
                            </h2>
                        </div>
                        <div class="p-6">
                            @if($mascotasAsignadas->count() > 0)
                                <div class="space-y-4 max-h-96 overflow-y-auto">
                                    @foreach($mascotasAsignadas as $mascota)
                                        <div class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm font-medium text-gray-900">{{ $mascota->nombre }}</p>
                                                <p class="text-xs text-gray-500">{{ $mascota->especie }} - {{ $mascota->raza }}</p>
                                                <span class="inline-block mt-1 px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                    {{ ucfirst($mascota->pivot->tipo_asignacion) }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">Sin mascotas asignadas</h3>
                                    <p class="mt-1 text-sm text-gray-500">Este veterinario no tiene mascotas asignadas actualmente.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>
@endsection
