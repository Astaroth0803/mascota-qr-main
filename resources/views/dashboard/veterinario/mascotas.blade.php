@extends('layouts.dashboard')

@section('title', 'Mascotas Asignadas')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="lg:ml-64">
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="py-4 lg:py-6">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <h1 class="text-xl lg:text-2xl xl:text-3xl font-bold text-gray-900">Mascotas Asignadas</h1>
                            <p class="text-sm lg:text-base text-gray-600 mt-1">Gestiona las mascotas bajo tu cuidado veterinario</p>
                        </div>
                        <div class="flex gap-3">
                            <a href="{{ route('dashboard.veterinario') }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Volver al Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="lg:ml-64">
        <div class="px-4 sm:px-6 lg:px-8 py-8">
            <!-- Filtros y Búsqueda -->
            <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
                <div class="flex flex-col lg:flex-row gap-4">
                    <!-- Búsqueda -->
                    <div class="flex-1">
                        <div class="relative">
                            <input type="text" 
                                   placeholder="Buscar mascotas..." 
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <svg class="w-5 h-5 absolute left-3 top-2.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                    
                    <!-- Filtros -->
                    <div class="flex gap-3">
                        <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Todos los tipos</option>
                            <option value="principal">Principal</option>
                            <option value="especialista">Especialista</option>
                            <option value="emergencia">Emergencia</option>
                        </select>
                        
                        <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Todas las especies</option>
                            <option value="perro">Perro</option>
                            <option value="gato">Gato</option>
                            <option value="otro">Otro</option>
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
                        Mascotas Asignadas ({{ $mascotasAsignadas->count() }})
                    </h2>
                </div>
                
                <div class="p-6">
                    @if($mascotasAsignadas->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($mascotasAsignadas as $mascota)
                                <div class="bg-gray-50 rounded-lg p-6 hover:bg-gray-100 transition-colors duration-200 border border-gray-200">
                                    <!-- Header de la tarjeta -->
                                    <div class="flex items-center space-x-4 mb-4">
                                        <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                            @if($mascota->profile_image)
                                                <img src="{{ Storage::url($mascota->profile_image) }}" alt="{{ $mascota->nombre }}" class="w-full h-full object-cover rounded-lg">
                                            @else
                                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                </svg>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-lg font-semibold text-gray-900 truncate">{{ $mascota->nombre }}</h3>
                                            <p class="text-sm text-gray-600">{{ ucfirst($mascota->especie) }} - {{ $mascota->raza }}</p>
                                            <p class="text-xs text-gray-500">Dueño: {{ $mascota->user->name }}</p>
                                        </div>
                                    </div>

                                    <!-- Información de la mascota -->
                                    <div class="space-y-2 mb-4">
                                        <div class="flex items-center text-sm text-gray-600">
                                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <span>{{ $mascota->edad_anios }} años, {{ $mascota->edad_meses }} meses</span>
                                        </div>
                                        <div class="flex items-center text-sm text-gray-600">
                                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                            </svg>
                                            <span>{{ ucfirst($mascota->sexo) }}</span>
                                        </div>
                                        <div class="flex items-center text-sm text-gray-600">
                                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                            </svg>
                                            <span>{{ $mascota->telefono_owner }}</span>
                                        </div>
                                    </div>

                                    <!-- Tipo de asignación -->
                                    <div class="mb-4">
                                        @php
                                            $asignacion = $mascota->pivot;
                                            $tipoColor = match($asignacion->tipo_asignacion) {
                                                'principal' => 'bg-green-100 text-green-800',
                                                'especialista' => 'bg-blue-100 text-blue-800',
                                                'emergencia' => 'bg-red-100 text-red-800',
                                                default => 'bg-gray-100 text-gray-800'
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $tipoColor }}">
                                            {{ ucfirst($asignacion->tipo_asignacion) }}
                                        </span>
                                    </div>

                                    <!-- Botones de acción -->
                                    <div class="flex gap-2">
                                        <a href="{{ route('dashboard.veterinario.mascota.show', $mascota) }}" 
                                           class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            Ver
                                        </a>
                                        <a href="{{ route('dashboard.veterinario.historial', $mascota) }}" 
                                           class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            Historial
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Paginación -->
                        @if($mascotasAsignadas->hasPages())
                            <div class="mt-8">
                                {{ $mascotasAsignadas->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No hay mascotas asignadas</h3>
                            <p class="mt-1 text-sm text-gray-500">Contacta al administrador para que te asigne mascotas.</p>
                            <div class="mt-6">
                                <a href="{{ route('dashboard.veterinario') }}" 
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                    Volver al Dashboard
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Funcionalidad de búsqueda y filtros
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('input[placeholder="Buscar mascotas..."]');
    const tipoFilter = document.querySelector('select:first-of-type');
    const especieFilter = document.querySelector('select:last-of-type');
    
    function filterMascotas() {
        const searchTerm = searchInput.value.toLowerCase();
        const tipoValue = tipoFilter.value;
        const especieValue = especieFilter.value;
        
        const mascotas = document.querySelectorAll('.bg-gray-50.rounded-lg');
        
        mascotas.forEach(mascota => {
            const nombre = mascota.querySelector('h3').textContent.toLowerCase();
            const especie = mascota.querySelector('p').textContent.toLowerCase();
            const tipo = mascota.querySelector('.inline-flex.items-center.px-2\\.5').textContent.toLowerCase();
            
            const matchesSearch = nombre.includes(searchTerm);
            const matchesTipo = !tipoValue || tipo.includes(tipoValue);
            const matchesEspecie = !especieValue || especie.includes(especieValue);
            
            if (matchesSearch && matchesTipo && matchesEspecie) {
                mascota.style.display = 'block';
            } else {
                mascota.style.display = 'none';
            }
        });
    }
    
    searchInput.addEventListener('input', filterMascotas);
    tipoFilter.addEventListener('change', filterMascotas);
    especieFilter.addEventListener('change', filterMascotas);
});
</script>
@endsection
