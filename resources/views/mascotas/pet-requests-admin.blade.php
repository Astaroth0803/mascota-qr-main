@extends('layouts.dashboard')

@section('title', 'Gestión de Solicitudes de Mascotas')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="lg:ml-64">
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="py-4 lg:py-6">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <h1 class="text-xl lg:text-2xl xl:text-3xl font-bold text-gray-900">Gestión de Solicitudes de Mascotas</h1>
                            <p class="text-sm lg:text-base text-gray-600 mt-1">Administra las solicitudes de registro de mascotas enviadas por los usuarios</p>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-2 lg:gap-3">
                            <div class="flex items-center space-x-3">
                                <div class="text-sm text-gray-500">
                                    <span class="font-medium">Total:</span> {{ $stats['total'] }}
                                </div>
                                @if($stats['pending'] > 0)
                                    <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $stats['pending'] }} pendientes
                                    </div>
                                @endif
                            </div>
                            <a href="{{ route('dashboard.administrador') }}" 
                               class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
                                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                <span>Volver al Dashboard</span>
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

            <!-- Formulario de filtrado -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6 lg:mb-8">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Filtros de Solicitudes</h3>
                    <p class="text-sm text-gray-500 mt-1">Busca y filtra las solicitudes por diferentes criterios</p>
                </div>
                <div class="px-6 py-6">
                    <form action="{{ route('pet-requests.index') }}" method="GET" class="flex flex-col lg:flex-row items-stretch lg:items-center gap-4">
                        <div class="relative flex-grow">
                            <input type="text" name="search" 
                                   class="w-full border border-gray-300 rounded-lg py-3 px-4 pl-12 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm lg:text-base" 
                                   placeholder="Buscar por nombre de mascota o usuario" 
                                   value="{{ request('search') }}">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="w-full lg:w-64">
                            <select id="status" name="status" 
                                    class="w-full border border-gray-300 rounded-lg shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm lg:text-base">
                                <option value="">Todos los estados</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Aprobadas</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendientes</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rechazadas</option>
                            </select>
                        </div>
                        <button type="submit" 
                                class="w-full lg:w-auto bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg shadow-sm flex items-center justify-center text-sm lg:text-base transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.707A1 1 0 013 7V4z"></path>
                            </svg>
                            <span>Filtrar</span>
                        </button>
                        <a href="{{ route('pet-requests.index') }}" 
                           class="w-full lg:w-auto bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-3 px-6 rounded-lg shadow-sm inline-flex items-center justify-center text-sm lg:text-base transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            <span>Limpiar</span>
                        </a>
                    </form>
                </div>
            </div>

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
                                            {{ strtoupper(substr($request->user->name, 0, 1)) }}{{ strtoupper(substr($request->user->name, 1, 1)) ?? '' }}
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
                                    
                                    {{-- Información del usuario y mascota --}}
                                    <div class="mb-4 flex-1">
                                        <p class="text-lg font-semibold text-gray-800 truncate">{{ $request->user->name }}</p>
                                        <p class="text-sm text-gray-600 truncate">{{ $request->user->email }}</p>
                                        <div class="mt-3 p-3 bg-white rounded-lg border border-gray-200">
                                            <p class="text-sm font-medium text-gray-700 mb-2">Mascota: {{ $request->nombre }}</p>
                                            <p class="text-xs text-gray-600">{{ $request->especie }} - {{ $request->raza }}</p>
                                            <div class="mt-2 space-y-1">
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
                                        <div class="mt-3 text-xs text-gray-500">
                                            <strong>Solicitado:</strong> {{ $request->created_at->format('d/m/Y H:i') }}
                                        </div>
                                    </div>

                                    {{-- Botones de Acción --}}
                                    <div class="flex flex-wrap gap-2 mt-auto pt-4">
                                        @if($request->isPending())
                                            {{-- Botón Aceptar --}}
                                            <form action="{{ route('pet-requests.approve', $request) }}" method="POST" class="flex-1 min-w-0">
                                                @csrf
                                                <button type="submit" 
                                                        class="w-full bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-3 rounded-lg text-xs flex items-center justify-center transition-colors">
                                                    <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                    <span class="hidden sm:inline">Aceptar</span>
                                                    <span class="sm:hidden">✓</span>
                                                </button>
                                            </form>
                                            {{-- Botón Rechazar --}}
                                            <button onclick="openRejectModal({{ $request->id }})" 
                                                    class="flex-1 min-w-0 bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-3 rounded-lg text-xs flex items-center justify-center transition-colors">
                                                <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                                <span class="hidden sm:inline">Rechazar</span>
                                                <span class="sm:hidden">✕</span>
                                            </button>
                                        @endif
                                        {{-- Botón Ver Detalles --}}
                                        <a href="{{ route('pet-requests.show', $request) }}" 
                                           class="flex-1 min-w-0 bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-3 rounded-lg text-xs flex items-center justify-center transition-colors">
                                            <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            <span class="hidden sm:inline">Ver</span>
                                            <span class="sm:hidden">👁</span>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-span-full text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No hay solicitudes</h3>
                                <p class="mt-1 text-sm text-gray-500">No se encontraron solicitudes con los criterios seleccionados.</p>
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
    </div>
</div>

<!-- Modal para Rechazar Solicitud -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Rechazar Solicitud</h3>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">
                        Motivo del rechazo <span class="text-red-500">*</span>
                    </label>
                    <textarea name="rejection_reason" id="rejection_reason" rows="4" 
                              class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                              placeholder="Explica por qué se rechaza esta solicitud..."
                              required></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeRejectModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-400">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700">
                        Rechazar Solicitud
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openRejectModal(requestId) {
    document.getElementById('rejectForm').action = `/admin/pet-requests/${requestId}/reject`;
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('rejection_reason').value = '';
}
</script>
@endsection
