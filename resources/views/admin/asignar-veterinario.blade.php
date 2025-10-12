@extends('layouts.dashboard')

@section('title', 'Asignar Veterinario')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Móvil Optimizado -->
    <div class="bg-white shadow-sm border-b border-gray-200 pt-16 lg:pt-0">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="py-4 lg:py-6">
                <!-- Header Principal -->
                <div class="mb-4">
                    <h1 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900">Asignar Veterinario</h1>
                    <p class="text-sm text-gray-600 mt-1">Gestiona las asignaciones de veterinarios a mascotas</p>
                </div>
                
                <!-- Acciones Rápidas -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('dashboard.administrador') }}" 
                       class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Volver al Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="lg:ml-64">
        <div class="px-4 sm:px-6 lg:px-8 py-8">
            <!-- Filtros -->
            <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
                <div class="flex flex-col lg:flex-row gap-4">
                    <div class="flex-1">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Buscar Mascota</label>
                        <input type="text" id="search" placeholder="Nombre de la mascota o dueño..." 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="lg:w-48">
                        <label for="especie" class="block text-sm font-medium text-gray-700 mb-2">Especie</label>
                        <select id="especie" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Todas las especies</option>
                            <option value="perro">Perro</option>
                            <option value="gato">Gato</option>
                            <option value="otro">Otro</option>
                        </select>
                    </div>
                    <div class="lg:w-48">
                        <label for="estado" class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                        <select id="estado" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Todos los estados</option>
                            <option value="sin_veterinario">Sin Veterinario</option>
                            <option value="con_veterinario">Con Veterinario</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Lista de Mascotas -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                        <svg class="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        Mascotas ({{ $mascotas->count() }})
                    </h2>
                </div>
                
                <div class="p-6">
                    @if($mascotas->count() > 0)
                        <div class="space-y-4">
                            @foreach($mascotas as $mascota)
                                <div class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors border border-gray-200 mascota-item" 
                                     data-nombre="{{ strtolower($mascota->nombre) }}" 
                                     data-dueño="{{ strtolower($mascota->user->name) }}"
                                     data-especie="{{ $mascota->especie }}"
                                     data-veterinario="{{ $mascota->veterinariosActivos()->count() > 0 ? 'con_veterinario' : 'sin_veterinario' }}">
                                    
                                    <!-- Avatar de la Mascota -->
                                    <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0 mr-4">
                                        @if($mascota->profile_image)
                                            <img src="{{ Storage::url($mascota->profile_image) }}" alt="{{ $mascota->nombre }}" class="w-full h-full object-cover rounded-lg">
                                        @else
                                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                            </svg>
                                        @endif
                                    </div>

                                    <!-- Información de la Mascota -->
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $mascota->nombre }}</h3>
                                        <p class="text-sm text-gray-600">{{ ucfirst($mascota->especie) }} - {{ $mascota->raza }}</p>
                                        <p class="text-xs text-gray-500">Dueño: {{ $mascota->user->name }}</p>
                                        
                                        <!-- Veterinarios Asignados -->
                                        @if($mascota->veterinariosActivos()->count() > 0)
                                            <div class="mt-2">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    Con Veterinario
                                                </span>
                                                <div class="mt-1 text-xs text-gray-600">
                                                    @foreach($mascota->veterinariosActivos as $veterinario)
                                                        <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded mr-1 mb-1">
                                                            {{ $veterinario->name }} ({{ ucfirst($veterinario->pivot->tipo_asignacion) }})
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @else
                                            <div class="mt-2">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                    Sin Veterinario
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                    <!-- Botones de Acción -->
                                    <div class="flex gap-2 ml-4">
                                        @if($mascota->veterinariosActivos()->count() > 0)
                                            <button onclick="manageVeterinarios({{ $mascota->id }})" 
                                                    class="inline-flex items-center px-4 py-2 bg-blue-500 text-white text-sm font-semibold rounded-lg shadow-md">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                                                </svg>
                                                Gestionar
                                            </button>
                                        @else
                                            <button onclick="assignVeterinario({{ $mascota->id }})" 
                                                    class="inline-flex items-center px-4 py-2 bg-green-500 text-white text-sm font-semibold rounded-lg shadow-md">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                </svg>
                                                Asignar
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No hay mascotas</h3>
                            <p class="mt-1 text-sm text-gray-500">No se encontraron mascotas para asignar veterinarios.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Asignar/Gestionar Veterinarios -->
<div id="veterinarioModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Asignar Veterinario</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form id="veterinarioForm" method="POST">
                @csrf
                <input type="hidden" name="mascota_id" id="mascota_id" value="">
                <div class="space-y-4">
                    <div>
                        <label for="veterinario_id" class="block text-sm font-medium text-gray-700">Veterinario</label>
                        <select name="veterinario_id" id="veterinario_id" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Seleccionar veterinario</option>
                            @foreach($veterinarios as $veterinario)
                                <option value="{{ $veterinario->id }}">{{ $veterinario->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label for="tipo_asignacion" class="block text-sm font-medium text-gray-700">Tipo de Asignación</label>
                        <select name="tipo_asignacion" id="tipo_asignacion" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="licenciado">Lic. Veterinario</option>
                            <option value="tecnico">Tec. Veterinario</option>
                            <option value="auxiliar">Auxiliar de Vet</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="notas" class="block text-sm font-medium text-gray-700">Notas (Opcional)</label>
                        <textarea name="notas" id="notas" rows="3"
                                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Observaciones sobre la asignación..."></textarea>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Asignar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let currentMascotaId = null;

function assignVeterinario(mascotaId) {
    currentMascotaId = mascotaId;
    document.getElementById('modalTitle').textContent = 'Asignar Veterinario';
    document.getElementById('veterinarioForm').action = '{{ route("dashboard.administrador.asignar-veterinario.store") }}';
    document.getElementById('veterinarioForm').method = 'POST';
    document.getElementById('mascota_id').value = mascotaId;
    document.getElementById('veterinarioForm').reset();
    document.getElementById('mascota_id').value = mascotaId; // Re-establecer después del reset
    document.getElementById('veterinarioModal').classList.remove('hidden');
}

function manageVeterinarios(mascotaId) {
    currentMascotaId = mascotaId;
    document.getElementById('modalTitle').textContent = 'Gestionar Veterinarios';
    document.getElementById('veterinarioForm').action = '{{ route("dashboard.administrador.asignar-veterinario.manage") }}';
    document.getElementById('veterinarioForm').method = 'POST';
    document.getElementById('mascota_id').value = mascotaId;
    document.getElementById('veterinarioForm').reset();
    document.getElementById('mascota_id').value = mascotaId; // Re-establecer después del reset
    document.getElementById('veterinarioModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('veterinarioModal').classList.add('hidden');
}

// Filtros
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const especieFilter = document.getElementById('especie');
    const estadoFilter = document.getElementById('estado');
    
    function filterMascotas() {
        const searchTerm = searchInput.value.toLowerCase();
        const especieValue = especieFilter.value;
        const estadoValue = estadoFilter.value;
        
        const mascotas = document.querySelectorAll('.mascota-item');
        
        mascotas.forEach(mascota => {
            const nombre = mascota.dataset.nombre;
            const dueño = mascota.dataset.dueño;
            const especie = mascota.dataset.especie;
            const veterinario = mascota.dataset.veterinario;
            
            const matchesSearch = nombre.includes(searchTerm) || dueño.includes(searchTerm);
            const matchesEspecie = !especieValue || especie === especieValue;
            const matchesEstado = !estadoValue || veterinario === estadoValue;
            
            if (matchesSearch && matchesEspecie && matchesEstado) {
                mascota.style.display = 'block';
            } else {
                mascota.style.display = 'none';
            }
        });
    }
    
    searchInput.addEventListener('input', filterMascotas);
    especieFilter.addEventListener('change', filterMascotas);
    estadoFilter.addEventListener('change', filterMascotas);
});
</script>
@endsection
