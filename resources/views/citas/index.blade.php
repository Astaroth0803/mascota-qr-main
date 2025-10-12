@extends('layouts.app')

@section('title', 'Citas')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Citas</h1>
                    <p class="text-gray-600 dark:text-gray-300 mt-1">
                        @if(auth()->user()->hasRole('veterinario'))
                            Gestiona las citas de tus pacientes
                        @else
                            Gestiona las citas de tus mascotas
                        @endif
                    </p>
                </div>
                
                @if(auth()->user()->hasRole('cliente'))
                <a href="{{ route('citas.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Nueva Cita
                </a>
                @endif
            </div>
        </div>

        <!-- Filtros -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 mb-6">
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('citas.index') }}" 
                   class="px-4 py-2 rounded-lg {{ request()->get('estado') == null ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700' }}">
                    Todas
                </a>
                <a href="{{ route('citas.index', ['estado' => 'pendiente']) }}" 
                   class="px-4 py-2 rounded-lg {{ request()->get('estado') == 'pendiente' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700' }}">
                    Pendientes
                </a>
                <a href="{{ route('citas.index', ['estado' => 'agendada']) }}" 
                   class="px-4 py-2 rounded-lg {{ request()->get('estado') == 'agendada' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700' }}">
                    Agendadas
                </a>
                <a href="{{ route('citas.index', ['estado' => 'finalizada']) }}" 
                   class="px-4 py-2 rounded-lg {{ request()->get('estado') == 'finalizada' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                    Finalizadas
                </a>
                <a href="{{ route('citas.index', ['estado' => 'cancelada']) }}" 
                   class="px-4 py-2 rounded-lg {{ request()->get('estado') == 'cancelada' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700' }}">
                    Canceladas
                </a>
            </div>
        </div>

        <!-- Lista de Citas -->
        <div class="space-y-4">
            @forelse($citas as $cita)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                @if($cita->estado === 'pendiente') bg-yellow-100 text-yellow-800
                                @elseif($cita->estado === 'agendada') bg-blue-100 text-blue-800
                                @elseif($cita->estado === 'finalizada') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ $cita->estado_label }}
                            </span>
                            
                            @if(auth()->user()->hasRole('veterinario'))
                            <span class="text-sm text-gray-500">
                                Cliente: {{ $cita->cliente->name }}
                            </span>
                            @else
                            <span class="text-sm text-gray-500">
                                Veterinario: {{ $cita->veterinario->name }}
                            </span>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <p class="text-sm text-gray-500">Mascota</p>
                                <p class="font-medium">{{ $cita->mascota->nombre }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Fecha Solicitada</p>
                                <p class="font-medium">{{ $cita->fecha_solicitada_formatted }}</p>
                            </div>
                            @if($cita->fecha_asignada)
                            <div>
                                <p class="text-sm text-gray-500">Fecha Asignada</p>
                                <p class="font-medium">{{ $cita->fecha_asignada_formatted }}</p>
                            </div>
                            @endif
                        </div>

                        @if($cita->observaciones)
                        <div class="mb-4">
                            <p class="text-sm text-gray-500">Observaciones</p>
                            <p class="text-sm">{{ $cita->observaciones }}</p>
                        </div>
                        @endif

                        @if($cita->motivo_rechazo)
                        <div class="mb-4">
                            <p class="text-sm text-red-500">Motivo de Rechazo</p>
                            <p class="text-sm text-red-600">{{ $cita->motivo_rechazo }}</p>
                        </div>
                        @endif

                        @if($cita->diagnostico_tratamiento)
                        <div class="mb-4">
                            <p class="text-sm text-gray-500">Diagnóstico y Tratamiento</p>
                            <p class="text-sm">{{ $cita->diagnostico_tratamiento }}</p>
                        </div>
                        @endif
                    </div>

                    <div class="flex items-center space-x-2">
                        <a href="{{ route('citas.show', $cita) }}" 
                           class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            Ver
                        </a>

                        @if(auth()->user()->hasRole('veterinario') && $cita->isPendiente())
                        <button onclick="aceptarCita({{ $cita->id }}, '{{ $cita->fecha_solicitada->format("Y-m-d\TH:i") }}')" 
                                class="inline-flex items-center px-3 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Aceptar
                        </button>

                        <button onclick="rechazarCita({{ $cita->id }})" 
                                class="inline-flex items-center px-3 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Rechazar
                        </button>
                        @endif

                        @if(auth()->user()->hasRole('veterinario') && $cita->isAgendada())
                        <a href="{{ route('citas.edit', $cita) }}" 
                           class="inline-flex items-center px-3 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Editar
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No hay citas</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    @if(auth()->user()->hasRole('cliente'))
                        Comienza solicitando una cita para tu mascota.
                    @else
                        No tienes citas asignadas en este momento.
                    @endif
                </p>
                @if(auth()->user()->hasRole('cliente'))
                <div class="mt-6">
                    <a href="{{ route('citas.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Nueva Cita
                    </a>
                </div>
                @endif
            </div>
            @endforelse
        </div>

        <!-- Paginación -->
        @if($citas->hasPages())
        <div class="mt-8">
            {{ $citas->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modales para acciones -->
@include('citas.modals.aceptar')
@include('citas.modals.rechazar')

<script>
function aceptarCita(citaId, fechaSolicitada) {
    document.getElementById('aceptarModal').classList.remove('hidden');
    document.getElementById('aceptarForm').action = `{{ route('citas.aceptar', ':id') }}`.replace(':id', citaId);
    
    // Precargar la fecha solicitada por el cliente
    document.getElementById('fecha_asignada').value = fechaSolicitada;
    
    // Mostrar la fecha solicitada en formato legible
    const fecha = new Date(fechaSolicitada);
    const fechaFormateada = fecha.toLocaleString('es-ES', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit'
    });
    document.getElementById('fecha-solicitada-info').textContent = fechaFormateada;
}

function rechazarCita(citaId) {
    document.getElementById('rechazarModal').classList.remove('hidden');
    document.getElementById('rechazarForm').action = `{{ route('citas.rechazar', ':id') }}`.replace(':id', citaId);
}


// Cerrar modales
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal-backdrop')) {
        e.target.classList.add('hidden');
    }
});
</script>
@endsection
