@extends('layouts.standard')

@section('title', 'Detalles de Solicitud')

@php
    $title = 'Detalles de Solicitud';
    $subtitle = $appointmentRequest->pet->nombre . ' - ' . $appointmentRequest->getAppointmentTypeLabelAttribute();
@endphp

@section('main-content')
<div class="w-full">
    <!-- Breadcrumb -->
    <nav class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400 mb-6">
        <a href="{{ route('dashboard') }}" class="hover:text-gray-700 dark:hover:text-gray-300 transition-colors">Dashboard</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
        @if(auth()->user()->hasRole('veterinario'))
            <a href="{{ route('citas.index') }}" class="hover:text-gray-700 dark:hover:text-gray-300 transition-colors">Solicitudes</a>
        @else
            <a href="{{ route('citas.index') }}" class="hover:text-gray-700 dark:hover:text-gray-300 transition-colors">Solicitudes</a>
        @endif
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
        <span class="text-gray-900 dark:text-white font-medium">Detalles de Solicitud</span>
    </nav>
    
    <!-- Título Principal -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white">Solicitud de Cita</h1>
            <p class="text-gray-600 dark:text-gray-300 mt-1">{{ $appointmentRequest->pet->nombre }} - {{ $appointmentRequest->getAppointmentTypeLabelAttribute() }}</p>
        </div>
        
        <!-- Estado -->
        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
            @if($appointmentRequest->status === 'pendiente') bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300
            @elseif($appointmentRequest->status === 'aceptado') bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300
            @elseif($appointmentRequest->status === 'rechazado') bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300
            @elseif($appointmentRequest->status === 'cita_terminada') bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300
            @elseif($appointmentRequest->status === 'cita_cancelada') bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300
            @elseif($appointmentRequest->status === 'cita_reagendada') bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300
            @endif">
            {{ $appointmentRequest->status_label }}
        </span>
    </div>

    <!-- Contenido principal -->
    <div class="w-full">
        <div class="px-2 sm:px-4 lg:px-6 xl:px-8 py-4 lg:py-6">
            <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Información Principal -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Detalles de la Solicitud -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-3 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Detalles de la Solicitud
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Tipo de Cita</label>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $appointmentRequest->appointment_type_label }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Fecha Solicitada</label>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $appointmentRequest->requested_datetime->format('d/m/Y H:i') }}</p>
                            </div>
                            
                            @if($appointmentRequest->scheduled_datetime)
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Fecha Agendada</label>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $appointmentRequest->scheduled_datetime->format('d/m/Y H:i') }}</p>
                            </div>
                            @endif
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Estado</label>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $appointmentRequest->status_label }}</p>
                            </div>
                        </div>
                        
                        @if($appointmentRequest->description)
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Descripción</label>
                            <p class="text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">{{ $appointmentRequest->description }}</p>
                        </div>
                        @endif
                        
                        @if($appointmentRequest->notes)
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Notas del Veterinario</label>
                            <p class="text-gray-900 dark:text-white bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">{{ $appointmentRequest->notes }}</p>
                        </div>
                        @endif
                        
                        @if($appointmentRequest->rejection_reason)
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Motivo de Rechazo</label>
                            <p class="text-gray-900 dark:text-white bg-red-50 dark:bg-red-900/20 p-4 rounded-lg">{{ $appointmentRequest->rejection_reason }}</p>
                        </div>
                        @endif
                        
                        @if($appointmentRequest->cancellation_reason)
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Motivo de Cancelación</label>
                            <p class="text-gray-900 dark:text-white bg-red-50 dark:bg-red-900/20 p-4 rounded-lg">{{ $appointmentRequest->cancellation_reason }}</p>
                        </div>
                        @endif
                        
                        @if($appointmentRequest->reschedule_reason)
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Motivo de Reagendamiento</label>
                            <p class="text-gray-900 dark:text-white bg-purple-50 dark:bg-purple-900/20 p-4 rounded-lg">{{ $appointmentRequest->reschedule_reason }}</p>
                        </div>
                        @endif
                    </div>

                    <!-- Información de la Mascota -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            Información de la Mascota
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Nombre</label>
                                <p class="text-lg font-semibold text-gray-900">{{ $appointmentRequest->pet->nombre }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Especie</label>
                                <p class="text-lg font-semibold text-gray-900">{{ $appointmentRequest->pet->especie }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Raza</label>
                                <p class="text-lg font-semibold text-gray-900">{{ $appointmentRequest->pet->raza }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Edad</label>
                                <p class="text-lg font-semibold text-gray-900">{{ $appointmentRequest->pet->edad_anios }} años, {{ $appointmentRequest->pet->edad_meses }} meses</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Panel Lateral -->
                <div class="space-y-6">
                    <!-- Acciones -->
                    @if(auth()->user()->hasRole('veterinario') && $appointmentRequest->status === 'pendiente')
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Acciones</h3>
                        
                        <div class="space-y-3">
                            <button onclick="openAcceptModal()" 
                                    class="w-full flex items-center justify-center px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Aceptar Solicitud
                            </button>
                            
                            <button onclick="openRejectModal()" 
                                    class="w-full flex items-center justify-center px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Rechazar Solicitud
                            </button>
                        </div>
                    </div>
                    @endif

                    <!-- Información del Veterinario/Cliente -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            @if(auth()->user()->hasRole('veterinario'))
                                Información del Cliente
                            @else
                                Información del Veterinario
                            @endif
                        </h3>
                        
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Nombre</label>
                                <p class="text-lg font-semibold text-gray-900">
                                    @if(auth()->user()->hasRole('veterinario'))
                                        {{ $appointmentRequest->client->name }}
                                    @else
                                        {{ $appointmentRequest->veterinarian->name }}
                                    @endif
                                </p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Email</label>
                                <p class="text-gray-900">
                                    @if(auth()->user()->hasRole('veterinario'))
                                        {{ $appointmentRequest->client->email }}
                                    @else
                                        {{ $appointmentRequest->veterinarian->email }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Cita Asociada -->
                    @if($appointmentRequest->appointment)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Cita Asociada</h3>
                        
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Estado</label>
                                <p class="text-lg font-semibold text-gray-900">{{ $appointmentRequest->appointment->status_label }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Fecha</label>
                                <p class="text-gray-900">{{ $appointmentRequest->appointment->scheduled_datetime->format('d/m/Y H:i') }}</p>
                            </div>
                            
                            @if($appointmentRequest->appointment)
                            <a href="{{ route('appointment-requests.show', $appointmentRequest->id) }}" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                Ver Solicitud
                            </a>
                            @else
                            <p class="text-sm text-gray-500">Cita en proceso de creación...</p>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Aceptación -->
<div id="acceptModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Aceptar Solicitud</h3>
            
            <form action="{{ route('citas.aceptar', $appointmentRequest) }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label for="scheduled_datetime" class="block text-sm font-medium text-gray-700 mb-2">
                        Fecha y Hora de la Cita *
                    </label>
                    <div class="mb-2 p-2 bg-blue-50 border border-blue-200 rounded-md">
                        <p class="text-sm text-blue-800">
                            <strong>Fecha solicitada por el cliente:</strong><br>
                            {{ $appointmentRequest->requested_datetime->format('d/m/Y H:i') }}
                        </p>
                    </div>
                    <input type="datetime-local" name="scheduled_datetime" id="scheduled_datetime" required 
                           value="{{ $appointmentRequest->requested_datetime->format('Y-m-d\TH:i') }}"
                           min="{{ now()->addHour()->format('Y-m-d\TH:i') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <p class="text-xs text-gray-500 mt-1">
                        Puedes cambiar la fecha si es necesario. La fecha solicitada por el cliente está preseleccionada.
                    </p>
                </div>
                
                <div class="mb-4">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Notas (opcional)
                    </label>
                    <textarea name="notes" id="notes" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
                
                <div class="flex items-center justify-end space-x-3">
                    <button type="button" onclick="closeAcceptModal()" 
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                        Aceptar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Rechazo -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Rechazar Solicitud</h3>
            
            <form action="{{ route('citas.rechazar', $appointmentRequest) }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">
                        Motivo del Rechazo *
                    </label>
                    <textarea name="rejection_reason" id="rejection_reason" rows="4" required 
                              placeholder="Explica por qué rechazas esta solicitud..."
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
                
                <div class="flex items-center justify-end space-x-3">
                    <button type="button" onclick="closeRejectModal()" 
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                        Rechazar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openAcceptModal() {
    document.getElementById('acceptModal').classList.remove('hidden');
}

function closeAcceptModal() {
    document.getElementById('acceptModal').classList.add('hidden');
}

function openRejectModal() {
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}

// Manejar envío del formulario de aceptar
document.addEventListener('DOMContentLoaded', function() {
    const acceptForm = document.querySelector('#acceptModal form');
    if (acceptForm) {
        acceptForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const scheduledDatetime = formData.get('scheduled_datetime');
            
            // Validar que la fecha sea futura
            const selectedDate = new Date(scheduledDatetime);
            const now = new Date();
            
            if (selectedDate <= now) {
                alert('La fecha y hora deben ser futuras');
                return;
            }
            
            // Mostrar indicador de carga
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Procesando...';
            submitBtn.disabled = true;
            
            // Enviar formulario
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (response.ok) {
                    return response.json();
                } else {
                    return response.json().then(data => Promise.reject(data));
                }
            })
            .then(data => {
                if (data.success) {
                    alert('Solicitud aceptada exitosamente');
                    window.location.reload();
                } else {
                    alert('Error: ' + (data.message || 'No se pudo aceptar la solicitud'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al aceptar la solicitud: ' + (error.message || 'Error desconocido'));
            })
            .finally(() => {
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
        });
    }
});

// Cerrar modales al hacer clic fuera
window.onclick = function(event) {
    const acceptModal = document.getElementById('acceptModal');
    const rejectModal = document.getElementById('rejectModal');
    
    if (event.target === acceptModal) {
        closeAcceptModal();
    }
    if (event.target === rejectModal) {
        closeRejectModal();
    }
}
</script>
@endsection
