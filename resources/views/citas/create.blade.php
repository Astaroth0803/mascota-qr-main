@extends('layouts.app')

@section('title', 'Solicitar Cita')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                        Solicitar Nueva Cita
                    </h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        Completa la información para solicitar una cita con el veterinario
                    </p>
                </div>
                
                <a href="{{ route('citas.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Volver
                </a>
            </div>
        </div>
    </div>

    <!-- Contenido -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <form action="{{ route('citas.store') }}" method="POST" id="citaForm">
            @csrf
            
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Información de la Cita</h2>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Mascota -->
                    <div>
                        <label for="mascota_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Mascota *
                        </label>
                        <select id="mascota_id" 
                                name="mascota_id" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                required>
                            <option value="">Selecciona una mascota</option>
                            @foreach($mascotas as $mascota)
                            <option value="{{ $pet->id }}">{{ $pet->nombre }} ({{ $pet->especie }})</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Veterinario -->
                    <div>
                        <label for="veterinario_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Veterinario *
                        </label>
                        <select id="veterinario_id" 
                                name="veterinario_id" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                required>
                            <option value="">Selecciona un veterinario</option>
                            @foreach($veterinarios as $veterinario)
                            <option value="{{ $veterinario->id }}">{{ $veterinario->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tipo de Cita -->
                    <div>
                        <label for="tipo_cita" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Tipo de Cita *
                        </label>
                        <select id="tipo_cita" 
                                name="tipo_cita" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                required>
                            <option value="">Selecciona el tipo de cita</option>
                            <option value="vacunacion">Vacunación</option>
                            <option value="consulta_general">Consulta General</option>
                            <option value="peluqueria">Peluquería</option>
                        </select>
                    </div>

                    <!-- Subtipo de Consulta (solo para consulta general) -->
                    <div id="consulta_subtipo_container" class="hidden">
                        <label for="consulta_subtipo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Subtipo de Consulta *
                        </label>
                        <select id="consulta_subtipo" 
                                name="consulta_subtipo" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="">Selecciona el subtipo</option>
                            <option value="cirugia">Cirugía</option>
                            <option value="emergencia">Emergencia</option>
                            <option value="chequeo_rutinario">Chequeo Rutinario</option>
                        </select>
                    </div>

                    <!-- Fecha Solicitada -->
                    <div>
                        <label for="fecha_solicitada" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Fecha y Hora Deseada *
                        </label>
                        <input type="datetime-local" 
                               id="fecha_solicitada" 
                               name="fecha_solicitada" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                               min="{{ now()->format('Y-m-d\TH:i') }}"
                               required>
                    </div>

                    <!-- Observaciones -->
                    <div class="lg:col-span-2">
                        <label for="observaciones" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Observaciones
                        </label>
                        <textarea id="observaciones" 
                                  name="observaciones" 
                                  rows="4" 
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                  placeholder="Describe el motivo de la consulta, síntomas, etc."></textarea>
                    </div>
                </div>

                <!-- Información para el cliente -->
                <div id="vacunacion_info" class="hidden mt-8">
                    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-2xl shadow-sm border border-blue-200 dark:border-blue-800 p-6">
                        <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-4">Información de Vacunación</h3>
                        <p class="text-blue-700 dark:text-blue-300">
                            Has seleccionado una cita de vacunación. El veterinario se encargará de registrar toda la información médica específica de la vacuna durante la consulta.
                        </p>
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex justify-end space-x-4 mt-8">
                    <a href="{{ route('citas.index') }}" 
                       class="px-6 py-3 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Solicitar Cita
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tipoCitaSelect = document.getElementById('tipo_cita');
    const consultaSubtipoContainer = document.getElementById('consulta_subtipo_container');
    const vacunacionInfo = document.getElementById('vacunacion_info');

    // Manejar cambio de tipo de cita
    tipoCitaSelect.addEventListener('change', function() {
        const tipo = this.value;
        
        // Ocultar todos los campos específicos
        consultaSubtipoContainer.classList.add('hidden');
        vacunacionInfo.classList.add('hidden');
        
        // Mostrar campos según el tipo
        if (tipo === 'consulta_general') {
            consultaSubtipoContainer.classList.remove('hidden');
        } else if (tipo === 'vacunacion') {
            vacunacionInfo.classList.remove('hidden');
        }
    });
});
</script>
@endsection