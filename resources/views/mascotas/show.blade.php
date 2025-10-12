@extends('layouts.dashboard')

@section('title', 'Detalles de Mascota')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Optimizado -->
    <div class="bg-white shadow-sm border-b border-gray-200 pt-16 lg:pt-0">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="py-4 lg:py-6">
                <!-- Breadcrumb y Navegación -->
                <nav class="flex items-center space-x-2 text-sm text-gray-500 mb-4">
                    <a href="{{ route('dashboard.cliente.index') }}" class="hover:text-gray-700 transition-colors">Dashboard</a>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                    <span class="text-gray-900 font-medium">Detalles de Mascota</span>
                </nav>
                
                <!-- Título Principal -->
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">{{ $pet->nombre }}</h1>
                        <p class="text-gray-600 mt-1">Información completa de tu mascota</p>
                    </div>
                    
                    <!-- Acciones Rápidas -->
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('dashboard.cliente.mascotas.edit', $pet) }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Editar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="lg:ml-64">
        <div class="px-4 sm:px-6 lg:px-8 py-8">
            <!-- Banner de la Mascota Optimizado -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6 relative overflow-hidden">
                <!-- QR Code Badge -->
                @if($pet->qr_code)
                    <div class="absolute top-4 right-4">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                            </svg>
                        </div>
                    </div>
                @endif

                <div class="flex flex-col md:flex-row items-center space-y-4 md:space-y-0 md:space-x-6">
                    <!-- Avatar de la Mascota -->
                    <div class="relative group">
                        <div class="w-20 h-20 md:w-24 md:h-24 bg-gradient-to-br from-blue-100 to-purple-100 rounded-2xl shadow-lg flex items-center justify-center overflow-hidden">
                            @if($pet->profile_image)
                                <img src="{{ Storage::url($pet->profile_image) }}" alt="{{ $pet->nombre }}" class="w-full h-full object-cover rounded-2xl">
                            @else
                                <svg class="w-10 h-10 md:w-12 md:h-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            @endif
                        </div>
                        <!-- Botón para cambiar imagen -->
                        <form action="{{ route('dashboard.cliente.mascotas.update-image', $pet) }}" method="POST" enctype="multipart/form-data" class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            @csrf
                            @method('PUT')
                            <label for="profile_image" class="cursor-pointer bg-black bg-opacity-70 rounded-xl p-2 hover:bg-opacity-90 transition-all">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <input type="file" name="profile_image" id="profile_image" class="hidden" accept="image/*" onchange="this.form.submit()">
                            </label>
                        </form>
                    </div>
                    
                    <!-- Información de la Mascota -->
                    <div class="text-center md:text-left flex-1">
                        <div class="mb-3">
                            <p class="text-lg text-gray-700 font-medium">{{ $pet->especie }} • {{ $pet->raza }}</p>
                        </div>
                        
                        <!-- Información rápida compacta -->
                        <div class="flex flex-wrap justify-center md:justify-start gap-3">
                            <div class="flex items-center bg-gray-50 rounded-xl px-3 py-2 border border-gray-200">
                                <svg class="w-4 h-4 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span class="text-sm font-medium text-gray-700">{{ $pet->edad_anios }}a {{ $pet->edad_meses }}m</span>
                            </div>
                            <div class="flex items-center bg-gray-50 rounded-xl px-3 py-2 border border-gray-200">
                                <svg class="w-4 h-4 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <span class="text-sm font-medium text-gray-700">{{ $pet->sexo }}</span>
                            </div>
                            @if($pet->peso)
                                <div class="flex items-center bg-gray-50 rounded-xl px-3 py-2 border border-gray-200">
                                    <svg class="w-4 h-4 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16l3-1m-3 1l-3-1" />
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700">{{ $pet->peso }}kg</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información Detallada -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-6">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Información Detallada
                    </h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <!-- Raza -->
                        <div class="flex items-center p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-500 mb-1">Raza</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $pet->raza }}</p>
                            </div>
                        </div>

                        <!-- Edad -->
                        <div class="flex items-center p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-500 mb-1">Edad</p>
                                <p class="text-lg font-semibold text-gray-900">
                                    {{ $pet->edad_anios }} años y {{ $pet->edad_meses }} meses
                                </p>
                            </div>
                        </div>

                        <!-- Sexo -->
                        <div class="flex items-center p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-500 mb-1">Sexo</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $pet->sexo }}</p>
                            </div>
                        </div>

                        <!-- Peso -->
                        <div class="flex items-center p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                            <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16l3-1m-3 1l-3-1" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-500 mb-1">Peso</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $pet->peso ? $pet->peso . ' kg' : 'No especificado' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acciones Rápidas -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-6">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        Acciones Rápidas
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Ver QR -->
                        @if($pet->qr_code)
                            <a href="{{ route('dashboard.cliente.qr.show', $pet) }}" 
                               class="flex items-center p-4 bg-green-50 border border-green-200 rounded-xl hover:bg-green-100 transition-colors">
                                <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center mr-4">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-green-700">Ver Código QR</p>
                                    <p class="text-xs text-green-600">Mostrar QR público</p>
                                </div>
                            </a>
                        @else
                            <a href="{{ route('dashboard.cliente.qr.generator') }}" 
                               class="flex items-center p-4 bg-yellow-50 border border-yellow-200 rounded-xl hover:bg-yellow-100 transition-colors">
                                <div class="w-10 h-10 bg-yellow-100 rounded-xl flex items-center justify-center mr-4">
                                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-yellow-700">Generar QR</p>
                                    <p class="text-xs text-yellow-600">Crear código QR</p>
                                </div>
                            </a>
                        @endif

                        <!-- Historial Médico -->
                        <a href="{{ route('dashboard.cliente.mascotas.vaccination-history', $pet) }}" 
                           class="flex items-center p-4 bg-blue-50 border border-blue-200 rounded-xl hover:bg-blue-100 transition-colors">
                            <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center mr-4">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-blue-700">Historial Médico</p>
                                <p class="text-xs text-blue-600">Ver registros médicos</p>
                            </div>
                        </a>

                        <!-- Editar Mascota -->
                        <a href="{{ route('dashboard.cliente.mascotas.edit', $pet) }}" 
                           class="flex items-center p-4 bg-purple-50 border border-purple-200 rounded-xl hover:bg-purple-100 transition-colors">
                            <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center mr-4">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-purple-700">Editar Mascota</p>
                                <p class="text-xs text-purple-600">Modificar información</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>
@endsection 