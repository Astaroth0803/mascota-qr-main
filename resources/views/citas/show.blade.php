@extends('layouts.app')

@section('title', 'Detalles de Cita')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <nav class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400 mb-6">
                <a href="{{ route('dashboard') }}" class="hover:text-gray-700 dark:hover:text-gray-300 transition-colors">Dashboard</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <a href="{{ route('citas.index') }}" class="hover:text-gray-700 dark:hover:text-gray-300 transition-colors">Citas</a>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="text-gray-900 dark:text-white font-medium">Detalles de Cita</span>
            </nav>

            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Detalles de Cita</h1>
                    <p class="text-gray-600 dark:text-gray-300 mt-1">Información completa de la cita</p>
                </div>
                
                <div class="flex items-center space-x-3">
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                        @if($appointmentRequest->status === 'pendiente') bg-yellow-100 text-yellow-800
                        @elseif($appointmentRequest->status === 'aceptado') bg-blue-100 text-blue-800
                        @elseif($appointmentRequest->status === 'cita_terminada') bg-green-100 text-green-800
                        @else bg-red-100 text-red-800
                        @endif">
                        {{ $appointmentRequest->status_label }}
                    </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Información Principal -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Datos de la Cita -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Información de la Cita</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Mascota</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $appointmentRequest->pet->nombre }}</p>
                            <p class="text-sm text-gray-500">{{ $appointmentRequest->pet->especie }} - {{ $appointmentRequest->pet->raza }}</p>
                        </div>
                        
                        @if(auth()->user()->hasRole('veterinario'))
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Cliente</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $appointmentRequest->client->name }}</p>
                            <p class="text-sm text-gray-500">{{ $appointmentRequest->client->email }}</p>
                        </div>
                        @else
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Veterinario</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $appointmentRequest->veterinarian->name }}</p>
                            <p class="text-sm text-gray-500">{{ $appointmentRequest->veterinarian->email }}</p>
                        </div>
                        @endif
                        
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Fecha Solicitada</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $appointmentRequest->requested_datetime->format('d/m/Y H:i') }}</p>
                        </div>
                        
                        @if($appointmentRequest->scheduled_datetime)
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Fecha Asignada</p>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $appointmentRequest->scheduled_datetime->format('d/m/Y H:i') }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Observaciones -->
                @if($appointmentRequest->description)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Observaciones</h3>
                    <p class="text-gray-700 dark:text-gray-300">{{ $appointmentRequest->description }}</p>
                </div>
                @endif

                <!-- Motivo de Rechazo -->
                @if($appointmentRequest->rejection_reason)
                <div class="bg-red-50 dark:bg-red-900/20 rounded-2xl shadow-sm border border-red-200 dark:border-red-800 p-6">
                    <h3 class="text-lg font-semibold text-red-900 dark:text-red-100 mb-4">Motivo de Rechazo</h3>
                    <p class="text-red-700 dark:text-red-300">{{ $appointmentRequest->rejection_reason }}</p>
                </div>
                @endif

                <!-- Diagnóstico y Tratamiento -->
                @if($appointmentRequest->appointment && $appointmentRequest->appointment->diagnosis_treatment)
                <div class="bg-green-50 dark:bg-green-900/20 rounded-2xl shadow-sm border border-green-200 dark:border-green-800 p-6">
                    <h3 class="text-lg font-semibold text-green-900 dark:text-green-100 mb-4">Diagnóstico y Tratamiento</h3>
                    <p class="text-green-700 dark:text-green-300">{{ $appointmentRequest->appointment->diagnosis_treatment }}</p>
                </div>
                @endif

                <!-- Información específica de vacunación -->
                @if($appointmentRequest->appointment && ($appointmentRequest->appointment->vaccine_technical_name || $appointmentRequest->appointment->vaccine_commercial_name || $appointmentRequest->appointment->vaccine_laboratory))
                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-2xl shadow-sm border border-blue-200 dark:border-blue-800 p-6">
                    <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-4">Información de la Vacuna</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if($appointmentRequest->appointment->vaccine_technical_name)
                        <div>
                            <span class="text-sm font-medium text-blue-700 dark:text-blue-300">Nombre Técnico:</span>
                            <p class="text-blue-900 dark:text-blue-100">{{ $appointmentRequest->appointment->vaccine_technical_name }}</p>
                        </div>
                        @endif
                        
                        @if($appointmentRequest->appointment->vaccine_commercial_name)
                        <div>
                            <span class="text-sm font-medium text-blue-700 dark:text-blue-300">Nombre Comercial:</span>
                            <p class="text-blue-900 dark:text-blue-100">{{ $appointmentRequest->appointment->vaccine_commercial_name }}</p>
                        </div>
                        @endif
                        
                        @if($appointmentRequest->appointment->vaccine_laboratory)
                        <div>
                            <span class="text-sm font-medium text-blue-700 dark:text-blue-300">Laboratorio:</span>
                            <p class="text-blue-900 dark:text-blue-100">{{ $appointmentRequest->appointment->vaccine_laboratory }}</p>
                        </div>
                        @endif
                        
                        @if($appointmentRequest->appointment->vaccine_lot)
                        <div>
                            <span class="text-sm font-medium text-blue-700 dark:text-blue-300">Lote:</span>
                            <p class="text-blue-900 dark:text-blue-100">{{ $appointmentRequest->appointment->vaccine_lot }}</p>
                        </div>
                        @endif
                        
                        @if($appointmentRequest->appointment->vaccine_expiry_date)
                        <div>
                            <span class="text-sm font-medium text-blue-700 dark:text-blue-300">Fecha de Caducidad:</span>
                            <p class="text-blue-900 dark:text-blue-100">{{ \Carbon\Carbon::parse($appointmentRequest->appointment->vaccine_expiry_date)->format('d/m/Y') }}</p>
                        </div>
                        @endif
                        
                        @if($appointmentRequest->appointment->vaccine_creation_date)
                        <div>
                            <span class="text-sm font-medium text-blue-700 dark:text-blue-300">Fecha de Expedición:</span>
                            <p class="text-blue-900 dark:text-blue-100">{{ \Carbon\Carbon::parse($appointmentRequest->appointment->vaccine_creation_date)->format('d/m/Y') }}</p>
                        </div>
                        @endif
                        
                        @if($appointmentRequest->appointment->vaccine_application_date)
                        <div>
                            <span class="text-sm font-medium text-blue-700 dark:text-blue-300">Fecha de Aplicación:</span>
                            <p class="text-blue-900 dark:text-blue-100">{{ \Carbon\Carbon::parse($appointmentRequest->appointment->vaccine_application_date)->format('d/m/Y') }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Información de subtipo de consulta -->
                @if($appointmentRequest->appointment && $appointmentRequest->appointment->consultation_subtype)
                <div class="bg-purple-50 dark:bg-purple-900/20 rounded-2xl shadow-sm border border-purple-200 dark:border-purple-800 p-6">
                    <h3 class="text-lg font-semibold text-purple-900 dark:text-purple-100 mb-4">Tipo de Consulta</h3>
                    <div>
                        <span class="text-sm font-medium text-purple-700 dark:text-purple-300">Subtipo:</span>
                        <p class="text-purple-900 dark:text-purple-100">
                            @switch($appointmentRequest->appointment->consultation_subtype)
                                @case('cirugia')
                                    Cirugía
                                    @break
                                @case('emergencia')
                                    Emergencia
                                    @break
                                @case('chequeo_rutinario')
                                    Chequeo Rutinario
                                    @break
                                @default
                                    {{ $cita->consulta_subtipo }}
                            @endswitch
                        </p>
                    </div>
                </div>
                @endif
            </div>

            <!-- Panel de Acciones -->
            <div class="space-y-6">
                <!-- Acciones según el estado -->
                @if(auth()->user()->hasRole('veterinario'))
                    @if($appointmentRequest->isPending())
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Acciones</h3>
                        
                        <div class="space-y-3">
                            <button onclick="aceptarCita({{ $appointmentRequest->id }})" 
                                    class="w-full flex items-center justify-center px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Aceptar Cita
                            </button>

                            <button onclick="rechazarCita({{ $appointmentRequest->id }})" 
                                    class="w-full flex items-center justify-center px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Rechazar Cita
                            </button>
                        </div>
                    </div>
                    @elseif($appointmentRequest->isAccepted())
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Acciones</h3>
                        
                        <div class="space-y-3">
                            <button onclick="finalizarCita({{ $appointmentRequest->id }})" 
                                    class="w-full flex items-center justify-center px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Finalizar Cita
                            </button>

                            <button onclick="cancelarCita({{ $appointmentRequest->id }})" 
                                    class="w-full flex items-center justify-center px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Cancelar Cita
                            </button>

                            @if(auth()->user()->hasRole('veterinario') && $appointmentRequest->isAccepted())
                            <div class="pt-3 border-t border-gray-200 dark:border-gray-700">
                                <a href="{{ route('citas.edit', $appointmentRequest) }}" 
                                   class="w-full flex items-center justify-center px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors mb-3"
                                   onclick="console.log('URL generada: {{ route('citas.edit', $appointmentRequest) }}');">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Editar Cita
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                @else
                    @if($appointmentRequest->isPending() || $appointmentRequest->isAccepted())
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Acciones</h3>
                        
                        <div class="space-y-3">
                            <button onclick="cancelarCita({{ $appointmentRequest->id }})" 
                                    class="w-full flex items-center justify-center px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Cancelar Cita
                            </button>
                        </div>
                    </div>
                    @endif
                @endif

                <!-- Información Adicional -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Información</h3>
                    
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Creada:</span>
                            <span class="font-medium">{{ $appointmentRequest->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Actualizada:</span>
                            <span class="font-medium">{{ $appointmentRequest->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modales -->
@include('citas.modals.aceptar')
@include('citas.modals.rechazar')
@include('citas.modals.finalizar')
@include('citas.modals.cancelar')

<script>
function aceptarCita(citaId) {
    document.getElementById('aceptarModal').classList.remove('hidden');
    document.getElementById('aceptarForm').action = `{{ route('citas.aceptar', ':id') }}`.replace(':id', citaId);
    
    // Precargar la fecha solicitada por el cliente
    const fechaSolicitada = '{{ $appointmentRequest->requested_datetime->format("Y-m-d\TH:i") }}';
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

function finalizarCita(citaId) {
    document.getElementById('finalizarModal').classList.remove('hidden');
    document.getElementById('finalizarForm').action = `{{ route('citas.finalizar', ':id') }}`.replace(':id', citaId);
}

function cancelarCita(citaId) {
    document.getElementById('cancelarModal').classList.remove('hidden');
    document.getElementById('cancelarForm').action = `{{ route('citas.cancelar', ':id') }}`.replace(':id', citaId);
}


// Cerrar modales
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal-backdrop')) {
        e.target.classList.add('hidden');
    }
});
</script>
@endsection
