@extends('layouts.dashboard')

@section('title', 'Mis Solicitudes de Mascotas')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="lg:ml-64">
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="py-4 lg:py-6">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            @if(Auth::user()->hasAnyRole(['administrador', 'super_admin']))
                                <h1 class="text-xl lg:text-2xl xl:text-3xl font-bold text-gray-900">Gestión de Solicitudes de Mascotas</h1>
                                <p class="text-sm lg:text-base text-gray-600 mt-1">Gestiona las solicitudes de registro de mascotas enviadas por los usuarios</p>
                            @else
                                <h1 class="text-xl lg:text-2xl xl:text-3xl font-bold text-gray-900">Mis Solicitudes de Mascotas</h1>
                                <p class="text-sm lg:text-base text-gray-600 mt-1">Revisa el estado de tus solicitudes de registro de mascotas</p>
                            @endif
                        </div>
                        <div class="flex flex-col sm:flex-row gap-2 lg:gap-3">
                            @if(!Auth::user()->hasAnyRole(['administrador', 'super_admin']))
                                <a href="{{ route('dashboard.cliente.pet-requests.create') }}" 
                                   class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                    <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    <span>Nueva Solicitud</span>
                                </a>
                            @endif
                            @if(Auth::user()->hasAnyRole(['administrador', 'super_admin']))
                                <a href="{{ route('dashboard.administrador') }}" 
                                   class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
                                    <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                    <span>Volver al Dashboard</span>
                                </a>
                            @else
                                <a href="{{ route('dashboard.cliente.index') }}" 
                                   class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
                                    <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                    <span>Volver al Dashboard</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenido principal -->
    <div class="lg:ml-64">
        <div class="px-4 sm:px-6 lg:px-8 py-6 lg:py-8">

            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
                    <p class="font-bold">Éxito</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded" role="alert">
                    <p class="font-bold">Error</p>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <!-- Listado de Solicitudes -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Listado de Solicitudes</h3>
                    <p class="text-sm text-gray-500 mt-1">Gestiona las solicitudes de registro de mascotas</p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
                        @if($petRequests->count() > 0)
                            @foreach($petRequests as $request)
                                <div class="bg-gray-50 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300 p-6 border border-gray-200 h-full flex flex-col">
                                    <div class="flex items-center justify-between mb-4">
                                        {{-- Avatar o iniciales --}}
                                        <div class="flex-shrink-0 w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-lg font-bold text-blue-800">
                                            {{ strtoupper(substr($request->nombre, 0, 1)) }}{{ strtoupper(substr($request->nombre, 1, 1)) ?? '' }}
                                        </div>
                                        {{-- Estado de la solicitud --}}
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                            @if($request->isPending()) bg-yellow-100 text-yellow-800
                                            @elseif($request->isApproved()) bg-green-100 text-green-800
                                            @else bg-red-100 text-red-800 @endif">
                                            @if($request->isPending())
                                                Pendiente
                                            @elseif($request->isApproved())
                                                Aprobada
                                            @else
                                                Rechazada
                                            @endif
                                        </span>
                                    </div>
                                    
                                    {{-- Información de la mascota --}}
                                    <div class="mb-4 flex-1">
                                        <p class="text-lg font-semibold text-gray-800 truncate">{{ $request->nombre }}</p>
                                        <p class="text-sm text-gray-600 truncate">{{ $request->especie }} - {{ $request->raza }}</p>
                                        <div class="mt-3 p-3 bg-white rounded-lg border border-gray-200">
                                            <div class="space-y-1">
                                                <p class="text-xs text-gray-600">
                                                    Edad: {{ $request->edad_anios }} año{{ $request->edad_anios > 1 ? 's' : '' }}, {{ $request->edad_meses }} mes{{ $request->edad_meses > 1 ? 'es' : '' }}
                                                </p>
                                                <p class="text-xs text-gray-600">Sexo: {{ $request->sexo }}</p>
                                            </div>
                                        </div>
                                        @if($request->payment_id)
                                            <div class="mt-3 text-xs text-gray-700">
                                                <strong>ID Pago:</strong> {{ $request->payment_id }}
                                            </div>
                                        @endif
                                        @if($request->isRejected() && $request->rejection_reason)
                                            <div class="mt-3 p-3 bg-red-50 border border-red-200 rounded-lg">
                                                <p class="text-xs text-red-800">
                                                    <strong>Motivo del rechazo:</strong> {{ $request->rejection_reason }}
                                                </p>
                                            </div>
                                        @endif
                                        <div class="mt-3 text-xs text-gray-500">
                                            <strong>Solicitado:</strong> {{ $request->created_at->format('d/m/Y H:i') }}
                                        </div>
                                        @if($request->reviewed_at)
                                            <div class="mt-2 text-xs text-gray-500">
                                                <strong>Revisado:</strong> {{ $request->reviewed_at->format('d/m/Y H:i') }}
                                                @if($request->reviewer)
                                                    por {{ $request->reviewer->name }}
                                                @endif
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Botón Ver Detalles --}}
                                    <div class="flex flex-wrap gap-2 mt-auto pt-4">
                                        <a href="{{ route('dashboard.cliente.pet-requests.show', $request) }}" 
                                           class="flex-1 min-w-0 bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-3 rounded-lg text-xs flex items-center justify-center transition-colors">
                                            <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            <span class="hidden sm:inline">Ver Detalles</span>
                                            <span class="sm:hidden">Ver</span>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-span-full text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                @if(Auth::user()->hasAnyRole(['administrador', 'super_admin']))
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No hay solicitudes</h3>
                                    <p class="mt-1 text-sm text-gray-500">No se encontraron solicitudes con los criterios seleccionados.</p>
                                @else
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No hay solicitudes</h3>
                                    <p class="mt-1 text-sm text-gray-500">Comienza solicitando el registro de una nueva mascota.</p>
                                    <div class="mt-6">
                                        <a href="{{ route('dashboard.cliente.pet-requests.create') }}" 
                                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            Nueva Solicitud
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- Paginación -->
                    @if($petRequests->hasPages())
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            {{ $petRequests->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
