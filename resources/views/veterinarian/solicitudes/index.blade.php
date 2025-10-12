@extends('layouts.standard')

@section('title', 'Solicitudes')

@php
    $title = 'Solicitudes';
    $subtitle = 'Gestiona las solicitudes de atención veterinaria';
@endphp

@section('main-content')
<div>
    <!-- Acciones rápidas -->
    <div class="flex items-center justify-end mb-6">
        <div class="flex items-center space-x-2">
            <a href="{{ route('dashboard.veterinario.calendario.index') }}" 
               class="inline-flex items-center px-3 py-2 bg-gray-100 border border-gray-200 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition-colors">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span class="hidden sm:inline">Calendario</span>
            </a>
        </div>
    </div>

            <!-- Citas Pendientes -->
            @if(isset($solicitudesCitas) && $solicitudesCitas->count() > 0)
                <div class="mb-8">
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                        <div class="bg-gradient-to-r from-orange-50 to-orange-100 px-6 py-4 border-b border-orange-200">
                            <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                                <svg class="w-6 h-6 mr-3 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Solicitudes de Citas Pendientes ({{ $solicitudesCitas->count() }})
                            </h2>
                            <p class="text-sm text-gray-600 mt-1">Nuevas solicitudes de citas que requieren tu atención</p>
                        </div>
                        
                        <div class="p-6">
                            <div class="space-y-4">
                                @foreach($solicitudesCitas as $cita)
                                    <div class="bg-orange-50 border border-orange-200 rounded-2xl p-4 hover:bg-orange-100 transition-colors">
                                        <div class="flex items-center justify-between gap-4">
                                            <!-- Información de la cita -->
                                            <div class="flex items-center space-x-3 flex-1 min-w-0">
                                                <!-- Avatar del cliente -->
                                                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center text-sm font-bold text-orange-800 flex-shrink-0">
                                                    {{ strtoupper(substr($cita->client->name, 0, 1)) }}
                                                </div>
                                                
                                                <!-- Información compacta -->
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center space-x-2 mb-1">
                                                        <h3 class="text-base font-semibold text-gray-900">{{ $cita->pet->nombre }}</h3>
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                                            @if($cita->record_type === 'vacuna') bg-green-100 text-green-800
                                                            @elseif($cita->record_type === 'operacion') bg-red-100 text-red-800
                                                            @elseif($cita->record_type === 'emergencia') bg-orange-100 text-orange-800
                                                            @elseif($cita->record_type === 'checkeo') bg-blue-100 text-blue-800
                                                            @else bg-gray-100 text-gray-800
                                                            @endif">
                                                            {{ $cita->record_type_label }}
                                                        </span>
                                                    </div>
                                                    <p class="text-sm text-gray-600 truncate">
                                                        <strong>Cliente:</strong> {{ $cita->client->name }} • 
                                                        <strong>Solicitada para:</strong> {{ $cita->requested_datetime->format('d/m/Y H:i') }}
                                                    </p>
                                                    <p class="text-xs text-gray-500 mt-1">
                                                        Mascota: {{ $cita->pet->especie }} • Solicitado: {{ $cita->created_at->diffForHumans() }}
                                                    </p>
                                                </div>
                                            </div>
                                            
                                            <!-- Botones de acción -->
                                            <div class="flex gap-2 flex-shrink-0">
                                                <button onclick="aceptarCita({{ $cita->id }}, '{{ $cita->pet->nombre }}', '{{ $cita->requested_datetime->format('Y-m-d\TH:i') }}')" 
                                                        class="inline-flex items-center px-3 py-1.5 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                    <span class="hidden sm:inline">Agendar</span>
                                                </button>
                                                <button onclick="rechazarCita({{ $cita->id }}, '{{ $cita->pet->nombre }}')" 
                                                        class="inline-flex items-center px-3 py-1.5 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                    <span class="hidden sm:inline">Rechazar</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Lista de Solicitudes de Asignación -->
            @if(isset($solicitudesMascotas) && $solicitudesMascotas->count() > 0)
                <div class="space-y-4">
                    @foreach($solicitudesMascotas as $solicitud)
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition-all duration-200">
                            <div class="flex items-center justify-between gap-3">
                                <!-- Información principal -->
                                <div class="flex items-center space-x-3 flex-1 min-w-0">
                                    <!-- Avatar del cliente -->
                                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center text-sm font-bold text-blue-800 flex-shrink-0">
                                        {{ strtoupper(substr($solicitud->mascota->user->name, 0, 1)) }}
                                    </div>
                                    
                                    <!-- Información compacta -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center space-x-2 mb-1">
                                            <h3 class="text-base font-semibold text-gray-900 truncate">
                                                {{ $solicitud->mascota->user->name }}
                                            </h3>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                Pendiente
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-600 mb-2">
                                            <span class="font-medium">{{ $solicitud->mascota->nombre }}</span> - {{ $solicitud->mascota->especie }}
                                        </p>
                                        
                                        <!-- Tags compactos -->
                                        <div class="flex flex-wrap gap-1.5">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                {{ $solicitud->mascota->raza }}
                                            </span>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                {{ ucfirst($solicitud->tipo_asignacion) }}
                                            </span>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $solicitud->created_at->format('d/m H:i') }}
                                            </span>
                                        </div>
                                        
                                        @if($solicitud->notas)
                                            <p class="text-xs text-gray-500 mt-2 truncate">
                                                <strong>Notas:</strong> {{ Str::limit($solicitud->notas, 60) }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Botones de acción compactos -->
                                <div class="flex items-center space-x-2 flex-shrink-0">
                                    <a href="{{ route('dashboard.veterinario.solicitudes.show', $solicitud) }}" 
                                       class="inline-flex items-center justify-center w-8 h-8 border border-gray-200 text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-300 transition-all duration-200 rounded-lg">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    
                                    <button onclick="aceptarSolicitud({{ $solicitud->id }})" 
                                            class="inline-flex items-center justify-center w-8 h-8 bg-emerald-500 text-white hover:bg-emerald-600 hover:shadow-md transition-all duration-200 rounded-lg">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </button>
                                    
                                    <button onclick="rechazarSolicitud({{ $solicitud->id }})" 
                                            class="inline-flex items-center justify-center w-8 h-8 bg-rose-500 text-white hover:bg-rose-600 hover:shadow-md transition-all duration-200 rounded-lg">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No hay solicitudes pendientes</h3>
                    <p class="mt-1 text-sm text-gray-500">No tienes solicitudes de clientes en este momento.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal para rechazar solicitud -->
<div id="rechazarModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-6 border w-full max-w-md shadow-lg rounded-2xl bg-white">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center mr-3">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900">Rechazar Solicitud</h3>
            </div>
            <button onclick="cerrarModalRechazar()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <form id="rechazarForm">
            @csrf
            <input type="hidden" name="solicitud_id" id="solicitud_id" value="">
            
            <div class="space-y-4">
                <div>
                    <label for="motivo" class="block text-sm font-semibold text-gray-900 mb-2">Motivo del rechazo (Opcional)</label>
                    <textarea name="motivo" id="motivo" rows="3"
                              class="w-full px-4 py-3 border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors"
                              placeholder="Explica brevemente el motivo del rechazo..."></textarea>
                    <p class="text-xs text-gray-500 mt-1">Esta información será enviada al cliente</p>
                </div>
            </div>
            
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="cerrarModalRechazar()" 
                        class="px-6 py-3 bg-gray-100 border border-gray-200 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition-colors">
                    Cancelar
                </button>
                <button type="submit" 
                        class="px-6 py-3 bg-red-600 text-white text-sm font-medium rounded-xl hover:bg-red-700 transition-colors">
                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Rechazar Solicitud
                </button>
            </div>
        </form>
    </div>
</div>

    <script>
        function aceptarSolicitud(solicitudId) {
            if (confirm('¿Estás seguro de que quieres aceptar esta solicitud?')) {
                fetch(`/dashboard/veterinario/solicitudes/${solicitudId}/aceptar`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        window.location.reload();
                    } else {
                        alert('Error: ' + (data.message || 'No se pudo aceptar la solicitud'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al aceptar la solicitud');
                });
            }
        }

        function rechazarSolicitud(solicitudId) {
            document.getElementById('solicitud_id').value = solicitudId;
            document.getElementById('rechazarModal').classList.remove('hidden');
        }

        function cerrarModalRechazar() {
            document.getElementById('rechazarModal').classList.add('hidden');
            document.getElementById('rechazarForm').reset();
        }

        // Manejar envío del formulario de rechazo
        document.getElementById('rechazarForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const solicitudId = document.getElementById('solicitud_id').value;
            
            fetch(`/dashboard/veterinario/solicitudes/${solicitudId}/rechazar`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    cerrarModalRechazar();
                    window.location.reload();
                } else {
                    alert('Error: ' + (data.message || 'No se pudo rechazar la solicitud'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al rechazar la solicitud');
            });
        });

        // Funciones para citas pendientes
        function aceptarCita(citaId, nombreMascota, fechaSolicitada) {
            const fecha = new Date(fechaSolicitada).toLocaleString('es-ES', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
            
            const confirmacion = confirm(
                `¿Confirmas agendar la cita para ${nombreMascota}?\n\n` +
                `Fecha solicitada: ${fecha}\n\n` +
                `Puedes ajustar la fecha y hora en el siguiente paso.`
            );
            
            if (confirmacion) {
                // Enviar petición AJAX para aceptar la cita
                const formData = new FormData();
                formData.append('scheduled_datetime', fechaSolicitada);
                formData.append('location', '{{ Auth::user()->ubicacion ?? "" }}');
                
                fetch(`/appointment-requests/${citaId}/aceptar`, {
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
                        alert(data.message);
                        window.location.reload();
                    } else {
                        alert('Error: ' + (data.message || 'No se pudo agendar la cita'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    if (error && error.message) {
                        alert('Error: ' + error.message);
                    } else {
                        alert('Error al procesar la solicitud');
                    }
                });
            }
        }
        
        function rechazarCita(citaId, nombreMascota) {
            const razon = prompt(
                `¿Por qué rechazas la cita para ${nombreMascota}?\n\n` +
                `Esta información será enviada al cliente:`
            );
            
            if (razon && razon.trim() !== '') {
                // Enviar petición AJAX para rechazar la cita
                const formData = new FormData();
                formData.append('cancellation_reason', razon);
                
                fetch(`/appointment-requests/${citaId}/rechazar`, {
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
                        alert(data.message);
                        window.location.reload();
                    } else {
                        alert('Error: ' + (data.message || 'No se pudo rechazar la cita'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    if (error && error.message) {
                        alert('Error: ' + error.message);
                    } else {
                        alert('Error al procesar la solicitud');
                    }
                });
            }
        }
    </script>
@endsection
