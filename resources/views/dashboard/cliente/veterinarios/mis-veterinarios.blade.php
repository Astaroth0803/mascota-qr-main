<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Mis Veterinarios') }}
            </h2>
        </div>
    </x-slot>

    {{-- Incluir el sidebar --}}
    <x-sidebar-menu :active="'veterinarios'" />

    {{-- Contenido principal --}}
    <div class="lg:ml-64 transition-all duration-300 ease-in-out">
        <div class="min-h-screen bg-gray-50">
            <div class="p-4 sm:p-6 lg:p-8">
                
                <!-- Botón de regreso -->
                <div class="mb-6">
                    <a href="{{ route('dashboard.cliente.veterinarios.index') }}"
                       class="inline-flex items-center text-sm text-green-600 hover:text-green-800">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Volver a Veterinarios Disponibles
                    </a>
                </div>

                <!-- Información sobre cambios -->
                <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Gestión de Veterinarios</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <p>Aquí puedes ver todos los veterinarios asignados a tus mascotas. Puedes cambiar o desasignar veterinarios en cualquier momento.</p>
                                <p class="mt-1">Los cambios notificarán automáticamente a los veterinarios involucrados.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lista de Asignaciones -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-green-50 to-green-100 px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                            <svg class="w-6 h-6 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Veterinarios Asignados ({{ $asignaciones->count() }})
                        </h2>
                    </div>
                    
                    <div class="p-6">
                        @if($asignaciones->count() > 0)
                            <div class="space-y-6">
                                @foreach($asignaciones as $asignacion)
                                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 hover:shadow-md transition-shadow duration-300">
                                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                                            <!-- Información de la asignación -->
                                            <div class="flex-1 mb-4 lg:mb-0">
                                                <div class="flex items-start space-x-4">
                                                    <!-- Avatar del veterinario -->
                                                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-lg font-bold text-green-800 flex-shrink-0">
                                                        {{ strtoupper(substr($asignacion->veterinario->name, 0, 1)) }}
                                                    </div>
                                                    
                                                    <!-- Información -->
                                                    <div class="flex-1 min-w-0">
                                                        <div class="flex items-start justify-between">
                                                            <div>
                                                                <h3 class="text-lg font-semibold text-gray-900">
                                                                    {{ $asignacion->veterinario->name }}
                                                                </h3>
                                                                <p class="text-sm text-gray-600 mb-2">
                                                                    Veterinario de <strong>{{ $asignacion->mascota->nombre }}</strong>
                                                                </p>
                                                            </div>
                                                            @if($asignacion->veterinario->tipo_veterinario)
                                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                                    {{ $asignacion->veterinario->tipo_veterinario_nombre }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                        
                                                        <div class="flex flex-wrap gap-2 mb-3">
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                                                </svg>
                                                                {{ $asignacion->mascota->especie }} - {{ $asignacion->mascota->raza }}
                                                            </span>
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                                {{ ucfirst($asignacion->tipo_asignacion) }}
                                                            </span>
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                                Desde {{ $asignacion->fecha_asignacion->format('d/m/Y') }}
                                                            </span>
                                                        </div>
                                                        
                                                        <div class="text-sm text-gray-600">
                                                            <strong>Email:</strong> {{ $asignacion->veterinario->email }}
                                                        </div>
                                                        
                                                        @if($asignacion->notas && !str_contains($asignacion->notas, '[Aceptada por el veterinario'))
                                                            <div class="bg-white rounded-lg p-3 border border-gray-200 mt-3">
                                                                <p class="text-sm text-gray-700">
                                                                    <strong>Notas:</strong> {{ $asignacion->notas }}
                                                                </p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Botones de acción -->
                                            <div class="flex flex-col sm:flex-row gap-2 lg:ml-6">
                                                <a href="{{ route('dashboard.cliente.veterinarios.show', $asignacion->veterinario) }}" 
                                                   class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    Ver Perfil
                                                </a>
                                                
                                                <button onclick="cambiarVeterinario({{ $asignacion->id }}, '{{ $asignacion->mascota->nombre }}')" 
                                                        class="inline-flex items-center justify-center px-4 py-2 bg-yellow-600 text-white text-sm font-medium rounded-lg hover:bg-yellow-700 transition-colors">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                                    </svg>
                                                    Cambiar
                                                </button>
                                                
                                                <button onclick="desasignarVeterinario({{ $asignacion->id }}, '{{ $asignacion->veterinario->name }}', '{{ $asignacion->mascota->nombre }}')" 
                                                        class="inline-flex items-center justify-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                    Desasignar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No tienes veterinarios asignados</h3>
                                <p class="mt-1 text-sm text-gray-500">Ve a la sección de "Veterinarios Disponibles" para solicitar un veterinario para tus mascotas.</p>
                                <div class="mt-6">
                                    <a href="{{ route('dashboard.cliente.veterinarios.index') }}" 
                                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Ver Veterinarios Disponibles
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para cambiar veterinario -->
    <div id="cambiarModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Cambiar Veterinario</h3>
                    <button onclick="cerrarModalCambiar()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <form id="cambiarForm">
                    @csrf
                    <input type="hidden" name="asignacion_id" id="asignacion_id" value="">
                    
                    <div class="space-y-4">
                        <div>
                            <label for="nuevo_veterinario_id" class="block text-sm font-medium text-gray-700">Nuevo Veterinario</label>
                            <select name="nuevo_veterinario_id" id="nuevo_veterinario_id" required
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-yellow-500 focus:border-yellow-500">
                                <option value="">Seleccionar veterinario</option>
                                <!-- Se llenará dinámicamente -->
                            </select>
                        </div>
                        
                        <div>
                            <label for="tipo_asignacion_cambio" class="block text-sm font-medium text-gray-700">Tipo de Asignación</label>
                            <select name="tipo_asignacion" id="tipo_asignacion_cambio" required
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-yellow-500 focus:border-yellow-500">
                                <option value="licenciado">Lic. Veterinario</option>
                                <option value="tecnico">Tec. Veterinario</option>
                                <option value="auxiliar">Auxiliar de Vet</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="notas_cambio" class="block text-sm font-medium text-gray-700">Notas (Opcional)</label>
                            <textarea name="notas" id="notas_cambio" rows="3"
                                      class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-yellow-500 focus:border-yellow-500"
                                      placeholder="Motivo del cambio o notas adicionales..."></textarea>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" onclick="cerrarModalCambiar()" 
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                            Cancelar
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700">
                            Cambiar Veterinario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let asignacionActualId = null;
        let mascotaActualNombre = null;

        // Cargar veterinarios disponibles al abrir la página
        let veterinariosDisponibles = [];
        
        // Función para cargar veterinarios disponibles
        async function cargarVeterinarios() {
            try {
                const response = await fetch('{{ route("dashboard.cliente.veterinarios.index") }}?ajax=1');
                if (response.ok) {
                    const data = await response.json();
                    veterinariosDisponibles = data.veterinarios || [];
                }
            } catch (error) {
                console.error('Error cargando veterinarios:', error);
            }
        }

        function cambiarVeterinario(asignacionId, mascotaNombre) {
            asignacionActualId = asignacionId;
            mascotaActualNombre = mascotaNombre;
            
            document.getElementById('asignacion_id').value = asignacionId;
            
            // Llenar el select de veterinarios
            const select = document.getElementById('nuevo_veterinario_id');
            select.innerHTML = '<option value="">Seleccionar veterinario</option>';
            
            // Aquí deberías cargar los veterinarios disponibles
            // Por simplicidad, usaré un fetch a la API
            fetch('/dashboard/cliente/veterinarios?format=json')
                .then(response => response.json())
                .then(data => {
                    if (data.veterinarios) {
                        data.veterinarios.forEach(vet => {
                            const option = document.createElement('option');
                            option.value = vet.id;
                            option.textContent = `${vet.name} - ${vet.tipo_veterinario_nombre || 'Sin tipo'}`;
                            select.appendChild(option);
                        });
                    }
                })
                .catch(error => console.error('Error:', error));
            
            document.getElementById('cambiarModal').classList.remove('hidden');
        }

        function cerrarModalCambiar() {
            document.getElementById('cambiarModal').classList.add('hidden');
            document.getElementById('cambiarForm').reset();
            asignacionActualId = null;
            mascotaActualNombre = null;
        }

        function desasignarVeterinario(asignacionId, veterinarioNombre, mascotaNombre) {
            if (confirm(`¿Estás seguro de que quieres desasignar al Dr. ${veterinarioNombre} de ${mascotaNombre}?`)) {
                fetch(`/dashboard/cliente/veterinarios/desasignar/${asignacionId}`, {
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
                        alert('Error: ' + (data.message || 'No se pudo desasignar el veterinario'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al desasignar el veterinario');
                });
            }
        }

        // Manejar envío del formulario de cambio
        document.getElementById('cambiarForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('{{ route("dashboard.cliente.veterinarios.cambiar") }}', {
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
                    cerrarModalCambiar();
                    window.location.reload();
                } else {
                    alert('Error: ' + (data.message || 'No se pudo cambiar el veterinario'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (error && error.message) {
                    alert('Error: ' + error.message);
                } else {
                    alert('Error al cambiar el veterinario');
                }
            });
        });
    </script>
</x-app-layout>
