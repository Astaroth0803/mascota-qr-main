@extends('layouts.standard')

@section('title', 'Solicitudes de Citas')

@php
    $title = 'Solicitudes de Citas';
    $subtitle = auth()->user()->hasRole('veterinario') ? 'Gestiona las solicitudes de citas de tus clientes' : 'Gestiona tus solicitudes de citas';
@endphp

@section('main-content')
<div class="w-full">
    <!-- Breadcrumb -->
    <nav class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400 mb-6">
        <a href="{{ route('dashboard') }}" class="hover:text-gray-700 dark:hover:text-gray-300 transition-colors">Dashboard</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
        <span class="text-gray-900 dark:text-white font-medium">Solicitudes de Citas</span>
    </nav>
    
    <!-- Título Principal -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white">Solicitudes de Citas</h1>
            <p class="text-gray-600 dark:text-gray-300 mt-1">
                @if(auth()->user()->hasRole('veterinario'))
                    Gestiona las solicitudes de citas de tus clientes
                @else
                    Mis solicitudes de citas
                @endif
            </p>
        </div>
        
        @if(auth()->user()->hasRole('cliente_qr'))
        <!-- Botón Nueva Solicitud -->
        <a href="{{ auth()->user()->hasRole('veterinario') ? '#' : route('citas.create') }}" 
           class="inline-flex items-center px-4 py-2 bg-blue-600 dark:bg-blue-700 text-white text-sm font-medium rounded-xl hover:bg-blue-700 dark:hover:bg-blue-800 transition-colors shadow-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Nueva Solicitud
        </a>
        @endif
    </div>
    <!-- Filtros -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 mb-6">
        <div class="flex flex-wrap gap-4">
            <select id="statusFilter" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                <option value="">Todos los estados</option>
                <option value="pendiente">Pendientes</option>
                <option value="aceptado">Aceptados</option>
                <option value="rechazado">Rechazados</option>
                <option value="cita_terminada">Citas Terminadas</option>
                <option value="cita_cancelada">Citas Canceladas</option>
                <option value="cita_reagendada">Citas Reagendadas</option>
            </select>
            
            <button onclick="applyFilters()" class="px-4 py-2 bg-blue-600 dark:bg-blue-700 text-white rounded-lg hover:bg-blue-700 dark:hover:bg-blue-800 transition-colors">
                Aplicar Filtros
            </button>
            
            <button onclick="clearFilters()" class="px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-500 transition-colors">
                Limpiar
            </button>
        </div>
    </div>

    <!-- Lista de Solicitudes -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        @if($requests->count() > 0)
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($requests as $request)
                    <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-4">
                                    <!-- Estado -->
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        @if($request->status === 'pendiente') bg-yellow-100 text-yellow-800
                                        @elseif($request->status === 'aceptado') bg-green-100 text-green-800
                                        @elseif($request->status === 'rechazado') bg-red-100 text-red-800
                                        @elseif($request->status === 'cita_terminada') bg-blue-100 text-blue-800
                                        @elseif($request->status === 'cita_cancelada') bg-red-100 text-red-800
                                        @elseif($request->status === 'cita_reagendada') bg-purple-100 text-purple-800
                                        @endif">
                                        {{ $request->status_label }}
                                    </span>
                                    
                                    <!-- Tipo de cita -->
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ $request->appointment_type_label }}
                                    </span>
                                </div>
                                
                                <div class="mt-3">
                                    <h3 class="text-lg font-medium text-gray-900">
                                        @if(auth()->user()->hasRole('veterinario'))
                                            {{ $request->client->name }} - {{ $request->pet->nombre }}
                                        @else
                                            {{ $request->veterinarian->name }} - {{ $request->pet->nombre }}
                                        @endif
                                    </h3>
                                    
                                    <p class="text-sm text-gray-600 mt-1">
                                        <strong>Fecha solicitada:</strong> {{ $request->requested_datetime->format('d/m/Y H:i') }}
                                    </p>
                                    
                                    @if($request->scheduled_datetime)
                                    <p class="text-sm text-gray-600">
                                        <strong>Fecha agendada:</strong> {{ $request->scheduled_datetime->format('d/m/Y H:i') }}
                                    </p>
                                    @endif
                                    
                                    @if($request->description)
                                    <p class="text-sm text-gray-600 mt-2">
                                        <strong>Descripción:</strong> {{ Str::limit($request->description, 100) }}
                                    </p>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Acciones -->
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('citas.show', $request) }}" 
                                   class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    Ver
                                </a>
                                
                                @if(auth()->user()->hasRole('veterinario') && $request->status === 'pendiente')
                                <button onclick="acceptRequest({{ $request->id }})" 
                                        class="inline-flex items-center px-3 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Aceptar
                                </button>
                                
                                <button onclick="rejectRequest({{ $request->id }})" 
                                        class="inline-flex items-center px-3 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Rechazar
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <!-- Paginación -->
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    {{ $requests->links() }}
                </div>
            @else
                <!-- Estado vacío -->
                <div class="px-6 py-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No hay solicitudes</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        @if(auth()->user()->hasRole('cliente_qr'))
                            Comienza creando tu primera solicitud de cita.
                        @else
                            No tienes solicitudes pendientes.
                        @endif
                    </p>
                    @if(auth()->user()->hasRole('cliente_qr'))
                    <div class="mt-6">
                        <a href="{{ auth()->user()->hasRole('veterinario') ? '#' : route('citas.create') }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
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
    </div>
</div>

<script>
function applyFilters() {
    const status = document.getElementById('statusFilter').value;
    const url = new URL(window.location);
    
    if (status) {
        url.searchParams.set('status', status);
    } else {
        url.searchParams.delete('status');
    }
    
    window.location.href = url.toString();
}

function clearFilters() {
    window.location.href = window.location.pathname;
}

function acceptRequest(requestId) {
    // Redirigir a la vista de detalles para aceptar con modal
    window.location.href = '{{ route("citas.show", ":id") }}'.replace(':id', requestId);
}

function rejectRequest(requestId) {
    const reason = prompt('Por favor, proporciona el motivo del rechazo:');
    if (reason && reason.trim() !== '') {
        // Crear formulario temporal para enviar la solicitud
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("citas.rechazar", ":id") }}'.replace(':id', requestId);
        
        // Agregar token CSRF
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        form.appendChild(csrfToken);
        
        // Agregar motivo del rechazo
        const rejectionReason = document.createElement('input');
        rejectionReason.type = 'hidden';
        rejectionReason.name = 'rejection_reason';
        rejectionReason.value = reason.trim();
        form.appendChild(rejectionReason);
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection
