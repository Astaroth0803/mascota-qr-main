@extends('layouts.app')

@section('title', 'Editar Cita - ' . $cita->mascota->nombre)

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                        Editar Cita
                    </h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        {{ $cita->mascota->nombre }} - {{ $cita->mascota->especie }}
                    </p>
                </div>
                
                <div class="flex space-x-3">
                    <a href="{{ route('citas.show', $cita) }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Volver
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenido -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <form action="{{ route('citas.update', $cita) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Información de la Cita -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Información de la Cita</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Cliente
                        </label>
                        <p class="text-sm text-gray-900 dark:text-white">{{ $cita->cliente->name }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Mascota
                        </label>
                        <p class="text-sm text-gray-900 dark:text-white">{{ $cita->mascota->nombre }} ({{ $cita->mascota->especie }})</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Fecha de la Cita
                        </label>
                        <p class="text-sm text-gray-900 dark:text-white">{{ $cita->fecha_asignada_formatted }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Estado
                        </label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ ucfirst($cita->estado) }}
                        </span>
                    </div>
                </div>
            </div>


            <!-- Información Médica Detallada -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Información Médica Detallada</h2>
                
                <!-- Diagnóstico y Tratamiento (Reubicado aquí) -->
                <div class="mb-6">
                    <label for="diagnostico_tratamiento" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Diagnóstico y Tratamiento *
                    </label>
                    <textarea id="diagnostico_tratamiento" 
                              name="diagnostico_tratamiento" 
                              rows="4"
                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                              placeholder="Describe el diagnóstico y tratamiento aplicado..."
                              required>{{ old('diagnostico_tratamiento', $cita->diagnostico_tratamiento) }}</textarea>
                    @error('diagnostico_tratamiento')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Tipo de Procedimiento -->
                    <div>
                        <label for="record_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Tipo de Procedimiento *
                        </label>
                        <select id="record_type" 
                                name="record_type" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                required>
                            <option value="">Seleccionar tipo</option>
                            <option value="vacunacion" {{ old('record_type', $cita->record_type) == 'vacunacion' ? 'selected' : '' }}>Vacunación</option>
                            <option value="consulta_general" {{ old('record_type', $cita->record_type) == 'consulta_general' ? 'selected' : '' }}>Consulta General</option>
                            <option value="peluqueria" {{ old('record_type', $cita->record_type) == 'peluqueria' ? 'selected' : '' }}>Peluquería</option>
                        </select>
                        @error('record_type')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Subtipo de Consulta (dinámico) -->
                    <div id="consulta_subtipo_container" class="{{ old('record_type', $cita->record_type) == 'consulta_general' ? '' : 'hidden' }}">
                        <label for="consulta_subtipo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Subtipo de Consulta *
                        </label>
                        <select id="consulta_subtipo" 
                                name="consulta_subtipo" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="">Seleccionar subtipo</option>
                            <option value="cirugia" {{ old('consulta_subtipo', $cita->consulta_subtipo) == 'cirugia' ? 'selected' : '' }}>Cirugía</option>
                            <option value="emergencia" {{ old('consulta_subtipo', $cita->consulta_subtipo) == 'emergencia' ? 'selected' : '' }}>Emergencia</option>
                            <option value="chequeo_rutinario" {{ old('consulta_subtipo', $cita->consulta_subtipo) == 'chequeo_rutinario' ? 'selected' : '' }}>Chequeo Rutinario</option>
                        </select>
                        @error('consulta_subtipo')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nombre del Procedimiento (General) -->
                    <div id="vaccine_name_general_container" class="{{ old('record_type', $cita->record_type) == 'vacunacion' ? 'hidden' : '' }}">
                        <label for="vaccine_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Nombre del Procedimiento *
                        </label>
                        <input type="text" 
                               id="vaccine_name" 
                               name="vaccine_name" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                               placeholder="Ej: Consulta General, Corte de pelo, etc."
                               value="{{ old('vaccine_name', $cita->vaccine_name) }}">
                        @error('vaccine_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Campos específicos para vacunación -->
                <div id="vacunacion_campos" class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                    <h3 class="text-md font-semibold text-blue-900 dark:text-blue-100 mb-4">Información Específica de Vacuna</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Nombre Técnico -->
                        <div>
                            <label for="nombre_tecnico" class="block text-sm font-medium text-blue-700 dark:text-blue-300 mb-2">
                                Nombre Técnico
                            </label>
                            <select id="nombre_tecnico" 
                                    name="nombre_tecnico" 
                                    class="w-full px-3 py-2 border border-blue-300 dark:border-blue-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                <option value="">Selecciona el nombre técnico</option>
                            </select>
                        </div>

                        <!-- Nombre Comercial -->
                        <div>
                            <label for="nombre_comercial" class="block text-sm font-medium text-blue-700 dark:text-blue-300 mb-2">
                                Nombre Comercial
                            </label>
                            <select id="nombre_comercial" 
                                    name="nombre_comercial" 
                                    class="w-full px-3 py-2 border border-blue-300 dark:border-blue-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                <option value="">Selecciona el nombre comercial</option>
                            </select>
                        </div>

                        <!-- Laboratorio -->
                        <div>
                            <label for="laboratorio" class="block text-sm font-medium text-blue-700 dark:text-blue-300 mb-2">
                                Laboratorio
                            </label>
                            <input type="text" 
                                   id="laboratorio" 
                                   name="laboratorio" 
                                   class="w-full px-3 py-2 border border-blue-300 dark:border-blue-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                   readonly>
                        </div>

                        <!-- Lote -->
                        <div>
                            <label for="lote" class="block text-sm font-medium text-blue-700 dark:text-blue-300 mb-2">
                                Lote
                            </label>
                            <input type="text" 
                                   id="lote" 
                                   name="lote" 
                                   class="w-full px-3 py-2 border border-blue-300 dark:border-blue-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                   placeholder="Número de lote">
                        </div>

                        <!-- Fecha de Caducidad -->
                        <div>
                            <label for="fecha_caducidad" class="block text-sm font-medium text-blue-700 dark:text-blue-300 mb-2">
                                Fecha de Caducidad
                            </label>
                            <input type="date" 
                                   id="fecha_caducidad" 
                                   name="fecha_caducidad" 
                                   class="w-full px-3 py-2 border border-blue-300 dark:border-blue-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>

                        <!-- Fecha de Expedición -->
                        <div>
                            <label for="fecha_expedicion" class="block text-sm font-medium text-blue-700 dark:text-blue-300 mb-2">
                                Fecha de Expedición
                            </label>
                            <input type="date" 
                                   id="fecha_expedicion" 
                                   name="fecha_expedicion" 
                                   class="w-full px-3 py-2 border border-blue-300 dark:border-blue-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>
                </div>

                <!-- Diagnóstico Detallado -->
                <div class="mt-6">
                    <label for="diagnosis" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Diagnóstico Detallado
                    </label>
                    <textarea id="diagnosis" 
                              name="diagnosis" 
                              rows="3"
                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                              placeholder="Diagnóstico específico...">{{ old('diagnosis') }}</textarea>
                    @error('diagnosis')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tratamiento -->
                <div class="mt-6">
                    <label for="treatment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Tratamiento Prescrito
                    </label>
                    <textarea id="treatment" 
                              name="treatment" 
                              rows="3"
                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                              placeholder="Tratamiento específico...">{{ old('treatment') }}</textarea>
                    @error('treatment')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <!-- Ubicación (solo lectura) -->
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Ubicación de la Cita
                        </label>
                        <input type="text" 
                               id="location" 
                               name="location" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-100 dark:bg-gray-600 dark:text-white cursor-not-allowed"
                               value="{{ old('location', $veterinarioLocation) }}"
                               readonly>
                        <p class="text-xs text-gray-500 mt-1">Ubicación del veterinario (no editable)</p>
                    </div>

                    <!-- Próxima Cita -->
                    <div>
                        <label for="next_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Próxima Cita
                        </label>
                        <input type="date" 
                               id="next_date" 
                               name="next_date" 
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                               min="{{ date('Y-m-d') }}"
                               value="{{ old('next_date') }}">
                        @error('next_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Observaciones -->
                <div class="mt-6">
                    <label for="observations" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Observaciones Adicionales
                    </label>
                    <textarea id="observations" 
                              name="observations" 
                              rows="3"
                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                              placeholder="Observaciones adicionales...">{{ old('observations') }}</textarea>
                    @error('observations')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Opciones de Finalización -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Finalizar Cita</h2>
                
                <div class="flex items-center">
                    <input type="checkbox" 
                           id="finalizar_cita" 
                           name="finalizar_cita" 
                           value="1"
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="finalizar_cita" class="ml-2 block text-sm text-gray-900 dark:text-white">
                        Marcar cita como finalizada
                    </label>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    Si marcas esta opción, la cita se marcará como finalizada y se agregará al historial médico de la mascota.
                </p>
            </div>

            <!-- Botones de Acción -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('citas.show', $cita) }}" 
                   class="px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const recordTypeSelect = document.getElementById('record_type');
    const vacunacionCampos = document.getElementById('vacunacion_campos');
    const nombreTecnicoSelect = document.getElementById('nombre_tecnico');
    const nombreComercialSelect = document.getElementById('nombre_comercial');
    const laboratorioInput = document.getElementById('laboratorio');
    const mascotaEspecie = '{{ $cita->mascota->especie }}';
    
    console.log('=== ELEMENTOS ENCONTRADOS ===');
    console.log('nombreTecnicoSelect:', nombreTecnicoSelect);
    console.log('nombreComercialSelect:', nombreComercialSelect);
    console.log('laboratorioInput:', laboratorioInput);
    console.log('mascotaEspecie:', mascotaEspecie);

    const consultaSubtipoContainer = document.getElementById('consulta_subtipo_container');
    const vaccineNameGeneralContainer = document.getElementById('vaccine_name_general_container');

    // Manejar cambio de tipo de procedimiento
    recordTypeSelect.addEventListener('change', function() {
        const tipo = this.value;
        
        // Ocultar todos los campos específicos
        consultaSubtipoContainer.classList.add('hidden');
        vacunacionCampos.classList.add('hidden');
        vaccineNameGeneralContainer.classList.add('hidden');
        
        // Mostrar campos según el tipo
        if (tipo === 'consulta_general') {
            consultaSubtipoContainer.classList.remove('hidden');
            vaccineNameGeneralContainer.classList.remove('hidden');
        } else if (tipo === 'vacunacion') {
            console.log('=== SELECCIONADO VACUNACIÓN ===');
            vacunacionCampos.classList.remove('hidden');
            console.log('Llamando cargarVacunas()...');
            cargarVacunas();
        } else if (tipo === 'peluqueria') {
            vaccineNameGeneralContainer.classList.remove('hidden');
        }
    });

    // Cargar vacunas disponibles (nombres técnicos únicos)
    function cargarVacunas() {
        console.log('=== INICIANDO CARGA DE VACUNAS ===');
        console.log('Especie de mascota:', mascotaEspecie);
        console.log('Elemento nombreTecnicoSelect:', nombreTecnicoSelect);
        
        if (!mascotaEspecie) {
            console.log('ERROR: No hay especie de mascota definida');
            return;
        }

        if (!nombreTecnicoSelect) {
            console.log('ERROR: No se encontró el elemento nombreTecnicoSelect');
            return;
        }

        const url = `{{ route('citas.vacunas.nombres-tecnicos') }}?especie=${mascotaEspecie}&t=${Date.now()}`;
        console.log('URL a llamar:', url);

        // Cargar nombres técnicos únicos directamente en el campo Nombre Técnico
        fetch(url)
            .then(response => {
                console.log('Response recibida:', response);
                console.log('Response status:', response.status);
                console.log('Response ok:', response.ok);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('=== DATOS RECIBIDOS ===');
                console.log('Tipo de datos:', typeof data);
                console.log('Es array:', Array.isArray(data));
                console.log('Datos completos:', data);
                
                // Limpiar el select
                nombreTecnicoSelect.innerHTML = '<option value="">Selecciona el nombre técnico</option>';
                
                if (data && Array.isArray(data) && data.length > 0) {
                    console.log('Agregando', data.length, 'nombres técnicos al select');
                    data.forEach((nombre, index) => {
                        console.log(`Agregando opción ${index + 1}:`, nombre);
                        const option = document.createElement('option');
                        option.value = nombre;
                        option.textContent = nombre;
                        nombreTecnicoSelect.appendChild(option);
                    });
                    console.log('Total opciones en select después de agregar:', nombreTecnicoSelect.options.length);
                } else {
                    console.log('No se encontraron nombres técnicos para la especie:', mascotaEspecie);
                    console.log('Datos recibidos:', data);
                }
            })
            .catch(error => {
                console.error('=== ERROR AL CARGAR VACUNAS ===');
                console.error('Error completo:', error);
                console.error('Mensaje de error:', error.message);
                nombreTecnicoSelect.innerHTML = '<option value="">Error al cargar vacunas</option>';
            });
    }


    // Manejar cambio de nombre técnico
    nombreTecnicoSelect.addEventListener('change', function() {
        const nombreTecnico = this.value;
        
        console.log('Nombre técnico seleccionado:', nombreTecnico);
        
        if (!nombreTecnico) {
            nombreComercialSelect.innerHTML = '<option value="">Selecciona el nombre comercial</option>';
            laboratorioInput.value = '';
            return;
        }

        // Cargar nombres comerciales para el nombre técnico seleccionado
        console.log('Cargando nombres comerciales para:', nombreTecnico);
        fetch(`{{ route('citas.vacunas.nombres-comerciales') }}?nombre_tecnico=${nombreTecnico}&especie=${mascotaEspecie}&t=${Date.now()}`)
            .then(response => {
                console.log('Response status para nombres comerciales:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Nombres comerciales recibidos:', data);
                nombreComercialSelect.innerHTML = '<option value="">Selecciona el nombre comercial</option>';
                
                if (data && Array.isArray(data) && data.length > 0) {
                    data.forEach(vacuna => {
                        console.log('Agregando nombre comercial:', vacuna.nombre_comercial, 'Laboratorio:', vacuna.laboratorio);
                        const option = document.createElement('option');
                        option.value = vacuna.nombre_comercial;
                        option.textContent = vacuna.nombre_comercial;
                        option.dataset.laboratorio = vacuna.laboratorio;
                        nombreComercialSelect.appendChild(option);
                    });
                } else {
                    console.log('No se encontraron nombres comerciales para:', nombreTecnico);
                }
            })
            .catch(error => {
                console.error('Error al cargar nombres comerciales:', error);
                nombreComercialSelect.innerHTML = '<option value="">Error al cargar nombres comerciales</option>';
            });
    });

    // Manejar cambio de nombre comercial
    nombreComercialSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        console.log('Nombre comercial seleccionado:', selectedOption.value);
        console.log('Laboratorio del dataset:', selectedOption.dataset.laboratorio);
        
        if (selectedOption.dataset.laboratorio) {
            laboratorioInput.value = selectedOption.dataset.laboratorio;
            console.log('Laboratorio auto-llenado:', laboratorioInput.value);
        } else {
            laboratorioInput.value = '';
            console.log('No hay laboratorio en el dataset');
        }
    });

    // Inicializar campos al cargar la página
    recordTypeSelect.dispatchEvent(new Event('change'));
});
</script>
@endsection
