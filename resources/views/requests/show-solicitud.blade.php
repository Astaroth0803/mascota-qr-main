@extends('layouts.dashboard')

@section('title', 'Detalles de la Solicitud')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="lg:ml-64">
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="py-4 lg:py-6">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <h1 class="text-xl lg:text-2xl xl:text-3xl font-bold text-gray-900">Detalles de la Solicitud #{{ $solicitud->id }}</h1>
                            <p class="text-sm lg:text-base text-gray-600 mt-1">Información completa de la solicitud de registro</p>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-2 lg:gap-3">
                            <a href="{{ route('dashboard.solicitudes') }}" 
                               class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
                                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                <span>Volver a Solicitudes</span>
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
            <div class="max-w-4xl mx-auto">
                <!-- Información del Dueño -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                    <div class="px-6 py-5 border-b border-gray-200">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Información del Dueño</h3>
                                <p class="text-sm text-gray-500">Datos personales del solicitante</p>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 py-6">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <label class="text-sm font-medium text-gray-700">Nombre Completo</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $solicitud->nombre_owner }} {{ $solicitud->apellido_owner }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700">Correo Electrónico</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $solicitud->correo_owner }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700">Teléfono</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $solicitud->telefono_owner ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información de la Mascota -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                    <div class="px-6 py-5 border-b border-gray-200">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Información de la Mascota</h3>
                                <p class="text-sm text-gray-500">Datos del animal a registrar</p>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 py-6">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <label class="text-sm font-medium text-gray-700">Nombre</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $solicitud->nombre }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700">Especie</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $solicitud->especie }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700">Raza</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $solicitud->raza }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700">Sexo</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $solicitud->sexo }}</p>
                            </div>
                            <div class="lg:col-span-2">
                                <label class="text-sm font-medium text-gray-700">Edad</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    @if($solicitud->edad_anios || $solicitud->edad_meses)
                                        @if($solicitud->edad_anios)
                                            {{ $solicitud->edad_anios }} año{{ $solicitud->edad_anios > 1 ? 's' : '' }}
                                        @endif
                                        @if($solicitud->edad_meses)
                                            @if($solicitud->edad_anios) y @endif
                                            {{ $solicitud->edad_meses }} mes{{ $solicitud->edad_meses > 1 ? 'es' : '' }}
                                        @endif
                                    @else
                                        No especificada
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información de Pago y Estado -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                    <div class="px-6 py-5 border-b border-gray-200">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Información de Pago</h3>
                                <p class="text-sm text-gray-500">Datos de transacción y estado</p>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 py-6">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <label class="text-sm font-medium text-gray-700">ID de Pago Yappy</label>
                                <p class="mt-1 text-sm text-gray-900 font-mono">{{ $solicitud->id_pago_yappy ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700">Estado</label>
                                <div class="mt-1">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                        @if($solicitud->status == 'verified') bg-green-100 text-green-800
                                        @elseif($solicitud->status == 'pending') bg-yellow-100 text-yellow-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ ucfirst($solicitud->status ?? 'pendiente') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Acciones -->
                @if($solicitud->status == 'pending')
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-5 border-b border-gray-200">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Acciones Disponibles</h3>
                                <p class="text-sm text-gray-500">Gestiona esta solicitud pendiente</p>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 py-6">
                        <div class="flex flex-col sm:flex-row gap-4 justify-end">
                            {{-- Botón Aceptar --}}
                            <form action="{{ route('solicitudes.accept', $solicitud->id) }}" method="POST" class="flex-1 sm:flex-none">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-6 rounded-lg flex items-center justify-center transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>Aceptar Solicitud</span>
                                </button>
                            </form>
                            {{-- Botón Rechazar --}}
                            <form action="{{ route('solicitudes.reject', $solicitud->id) }}" method="POST" class="flex-1 sm:flex-none" onsubmit="return confirm('¿Estás seguro de rechazar esta solicitud?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="w-full sm:w-auto bg-red-600 hover:bg-red-700 text-white font-medium py-3 px-6 rounded-lg flex items-center justify-center transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    <span>Rechazar Solicitud</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 