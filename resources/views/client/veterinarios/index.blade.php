@extends('layouts.standard')

@section('title', 'Veterinarios Disponibles')

@php
    $title = 'Veterinarios Disponibles';
    $subtitle = 'Encuentra y contacta veterinarios especializados';
@endphp

@section('main-content')
<div class="w-full">
    <!-- Header Principal -->
    <div class="mb-6">
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Veterinarios Disponibles</h1>
        <p class="text-gray-600 mt-1">Encuentra y contacta veterinarios especializados</p>
    </div>
    
    <!-- Botón de acceso rápido a Mis Veterinarios -->
    <div class="mb-6">
        <a href="{{ route('dashboard.cliente.veterinarios.mis-veterinarios') }}" 
           class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors shadow-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Ver Mis Veterinarios Asignados
        </a>
    </div>

    <!-- Contenido principal -->
    <div class="w-full">
        <div class="px-2 sm:px-4 lg:px-6 xl:px-8 py-4 lg:py-6">
            <!-- Filtros -->
            <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
                <form method="GET" action="{{ route('dashboard.cliente.veterinarios.index') }}" class="flex flex-col lg:flex-row gap-4">
                    <div class="flex-1">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Buscar Veterinario</label>
                        <input type="text" id="search" name="search" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                               placeholder="Nombre o email del veterinario..." 
                               value="{{ request('search') }}">
                    </div>
                    <div class="lg:w-48">
                        <label for="tipo_veterinario" class="block text-sm font-medium text-gray-700 mb-2">Tipo</label>
                        <select id="tipo_veterinario" name="tipo_veterinario" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <option value="">Todos los tipos</option>
                            @foreach($tiposVeterinarios as $key => $value)
                                <option value="{{ $key }}" {{ request('tipo_veterinario') == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="lg:w-auto flex items-end">
                        <button type="submit" 
                                class="w-full lg:w-auto bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.707A1 1 0 013 7V4z"></path>
                            </svg>
                            Filtrar
                        </button>
                    </div>
                    <div class="lg:w-auto flex items-end">
                        <a href="{{ route('dashboard.cliente.veterinarios.index') }}" 
                           class="w-full lg:w-auto bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-4 rounded-lg shadow-sm flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Limpiar
                        </a>
                    </div>
                </form>
            </div>

            <!-- Lista de Veterinarios -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-green-50 to-green-100 px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                        <svg class="w-6 h-6 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Veterinarios Disponibles ({{ $veterinarios->total() }})
                    </h2>
                </div>
                
                <div class="p-6">
                    @if($veterinarios && $veterinarios->total() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                            @foreach($veterinarios as $veterinario)
                                <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 p-6">
                                    <!-- Header del veterinario -->
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="flex items-center">
                                            <!-- Avatar -->
                                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-lg font-bold text-green-800">
                                                {{ strtoupper(substr($veterinario->name, 0, 1)) }}
                                            </div>
                                            <div class="ml-3">
                                                <h3 class="text-lg font-semibold text-gray-900">{{ $veterinario->name }}</h3>
                                                <p class="text-sm text-gray-600">{{ $veterinario->email }}</p>
                                            </div>
                                        </div>
                                        @if($veterinario->tipo_veterinario)
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                {{ $veterinario->tipo_veterinario_nombre }}
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <!-- Información del veterinario -->
                                    <div class="mb-4">
                                        <div class="flex items-center text-sm text-gray-600 mb-2">
                                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                            </svg>
                                            <span>Ubicación: {{ $veterinario->ubicacion ?? 'No especificada' }}</span>
                                        </div>
                                    </div>
                                    
                                    <!-- Botones de acción -->
                                    <div class="flex flex-col gap-2">
                                        <a href="{{ route('dashboard.cliente.veterinarios.show', $veterinario) }}" 
                                           class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Ver Perfil
                                        </a>
                                        
                                        <a href="{{ route('dashboard.cliente.veterinarios.mis-veterinarios') }}" 
                                           class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Mis Veterinarios
                                        </a>
                                    </div>
                                    
                                    <!-- Botones de acción siempre disponibles -->
                                    <div class="space-y-2">
                                        <button onclick="solicitarCita({{ $veterinario->id }})" 
                                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            Solicitar Cita
                                        </button>
                                        <button onclick="asignarVeterinario({{ $veterinario->id }}, '{{ $veterinario->name }}')" 
                                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                            </svg>
                                            Asignar Veterinario
                                        </button>
                                        @if(in_array($veterinario->id, $veterinariosAsignados))
                                            <div class="text-center">
                                                <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    Asignado a alguna mascota
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Paginación -->
                        @if(method_exists($veterinarios, 'hasPages') && $veterinarios->hasPages())
                            <div class="mt-8">
                                {{ $veterinarios->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No hay veterinarios disponibles</h3>
                            <p class="mt-1 text-sm text-gray-500">No se encontraron veterinarios con los filtros aplicados.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para asignar veterinario -->
    <div id="asignarModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Asignar Veterinario</h3>
                    <button onclick="cerrarModalAsignar()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <form id="asignarForm">
                    @csrf
                    <input type="hidden" name="veterinarian_id" id="veterinarian_id" value="">
                    
                    <div class="space-y-4">
                        <div>
                            <label for="pet_id" class="block text-sm font-medium text-gray-700">Seleccionar Mascota</label>
                            <select name="pet" id="pet_id" required
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                                <option value="">Seleccionar mascota</option>
                                @foreach($mascotasCliente as $mascota)
                                    <option value="{{ $mascota->id }}">{{ $mascota->nombre }} ({{ $mascota->especie }})</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label for="tipo_asignacion" class="block text-sm font-medium text-gray-700">Tipo de Asignación</label>
                            <select name="tipo_asignacion" id="tipo_asignacion" required
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                                <option value="licenciado">Lic. Veterinario</option>
                                <option value="tecnico">Tec. Veterinario</option>
                                <option value="auxiliar">Auxiliar de Vet</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="notas" class="block text-sm font-medium text-gray-700">Notas (Opcional)</label>
                            <textarea name="notas" id="notas" rows="3"
                                      class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                      placeholder="Observaciones sobre la solicitud..."></textarea>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" onclick="cerrarModalAsignar()" 
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                            Cancelar
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                            Asignar Veterinario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para solicitar cita -->
    <div id="citaModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Solicitar Cita</h3>
                    <button onclick="cerrarModalCita()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <form id="citaForm">
                    @csrf
                    <input type="hidden" name="veterinarian_id" id="cita_veterinario_id" value="">
                    
                    <div class="space-y-4">
                        <div>
                            <label for="cita_pet_id" class="block text-sm font-medium text-gray-700">Seleccionar Mascota</label>
                            <select name="pet_id" id="cita_pet_id" required
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Seleccionar mascota</option>
                                @foreach($mascotasCliente as $mascota)
                                    <option value="{{ $mascota->id }}">{{ $mascota->nombre }} ({{ $mascota->especie }})</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label for="tipo_cita" class="block text-sm font-medium text-gray-700">Tipo de Cita</label>
                            <select name="appointment_type" id="tipo_cita" required
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Seleccionar tipo de cita</option>
                                <option value="consulta">Consulta General</option>
                                <option value="vacunacion">Vacunación</option>
                                <option value="cirugia">Cirugía</option>
                                <option value="emergencia">Emergencia</option>
                                <option value="chequeo">Chequeo</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="requested_datetime" class="block text-sm font-medium text-gray-700">Fecha y Hora Solicitada</label>
                            <input type="datetime-local" name="requested_datetime" id="requested_datetime" required
                                   min="{{ now()->format('Y-m-d\TH:i') }}"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Descripción (Opcional)</label>
                            <textarea name="description" id="description" rows="3"
                                      class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Describe los síntomas o motivo de la cita..."></textarea>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" onclick="cerrarModalCita()" 
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                            Cancelar
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Solicitar Cita
                        </button>
                    </div>
                </form>
            </div>
        </div>A
    </div>

    <script>
        let veterinarioActualId = null;

        // Funciones para modal de asignación
        function asignarVeterinario(veterinarioId, veterinarioNombre) {
            veterinarioActualId = veterinarioId;
            document.getElementById('veterinario_id').value = veterinarioId;
            document.getElementById('asignarModal').classList.remove('hidden');
        }

        function cerrarModalAsignar() {
            document.getElementById('asignarModal').classList.add('hidden');
            document.getElementById('asignarForm').reset();
            veterinarioActualId = null;
        }

        // Funciones para modal de cita
        function solicitarCita(veterinarioId) {
            veterinarioActualId = veterinarioId;
            document.getElementById('cita_veterinario_id').value = veterinarioId;
            document.getElementById('citaModal').classList.remove('hidden');
        }

        function cerrarModalCita() {
            document.getElementById('citaModal').classList.add('hidden');
            document.getElementById('citaForm').reset();
            veterinarioActualId = null;
        }

        // Manejar envío del formulario de asignación
        document.getElementById('asignarForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('{{ route("dashboard.cliente.veterinarios.solicitar") }}', {
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
                    cerrarModalAsignar();
                    window.location.reload();
                } else {
                    alert('Error: ' + (data.message || 'No se pudo enviar la solicitud de asignación'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (error && error.message) {
                    alert('Error: ' + error.message);
                } else {
                    alert('Error al enviar la solicitud de asignación');
                }
            });
        });

        // Manejar envío del formulario de cita
        document.getElementById('citaForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            // Verificar que se haya seleccionado un veterinario
            const veterinarianId = formData.get('veterinarian_id');
            if (!veterinarianId) {
                alert('Error: No se ha seleccionado un veterinario');
                return;
            }
            
            fetch('{{ route("citas.store") }}', {
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
                    alert(data.message || 'Cita solicitada exitosamente');
                    cerrarModalCita();
                    // Redirigir a la vista de la cita creada
                    if (data.redirect_url) {
                        window.location.href = data.redirect_url;
                    } else {
                        window.location.reload();
                    }
                } else {
                    alert('Error: ' + (data.message || 'No se pudo solicitar la cita'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (error && error.message) {
                    alert('Error: ' + error.message);
                } else if (error && error.errors) {
                    // Mostrar errores de validación
                    let errorMessages = [];
                    for (let field in error.errors) {
                        errorMessages.push(error.errors[field].join(', '));
                    }
                    alert('Error de validación: ' + errorMessages.join('; '));
                } else {
                    alert('Error: No se pudo solicitar la cita. Inténtalo de nuevo.');
                }
            });
        });
    </script>
@endsection
