@extends('layouts.dashboard')

@section('title', 'Gestionar Historial Médico')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Compacto -->
    <div class="bg-white shadow-sm border-b border-gray-200 pt-16 lg:pt-0">
        <div class="lg:ml-64">
            <div class="px-4 sm:px-6 lg:px-8 py-3">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="flex-1 min-w-0">
                        <nav class="flex text-sm text-gray-500 mb-2" aria-label="Breadcrumb">
                            <a href="{{ route('dashboard.veterinario.mascotas') }}" class="hover:text-gray-700">Mascotas</a>
                            <span class="mx-2">/</span>
                            <a href="{{ route('dashboard.veterinario.mascota.show', $pet) }}" class="hover:text-gray-700">{{ $pet->nombre }}</a>
                            <span class="mx-2">/</span>
                            <span class="text-gray-900">Historial Médico</span>
                        </nav>
                        <h1 class="text-xl lg:text-2xl font-bold text-gray-900">
                            Historial Médico
                        </h1>
                        <p class="text-sm text-gray-600 mt-1">
                            {{ $pet->nombre }} ({{ $pet->especie }}) • Dr. {{ Auth::user()->name }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="lg:ml-64">
        <div class="px-4 sm:px-6 lg:px-8 py-8">
            <!-- Historial Médico -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-green-50 to-green-100 px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                        <svg class="w-6 h-6 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Historial de Vacunación
                    </h2>
                </div>
                
                <div class="p-6">
                    @if($vacunas->count() > 0)
                        <div class="space-y-4">
                            @foreach($vacunas as $vacuna)
                                <div class="bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden">
                                    <!-- Header de la tarjeta -->
                                    <div class="flex items-center justify-between p-4 bg-gradient-to-r 
                                        @if($vacuna->record_type === 'vacuna') from-green-50 to-green-100 border-b border-green-200
                                        @elseif($vacuna->record_type === 'operacion') from-red-50 to-red-100 border-b border-red-200
                                        @elseif($vacuna->record_type === 'emergencia') from-orange-50 to-orange-100 border-b border-orange-200
                                        @elseif($vacuna->record_type === 'checkeo') from-blue-50 to-blue-100 border-b border-blue-200
                                        @else from-gray-50 to-gray-100 border-b border-gray-200
                                        @endif">
                                        <div class="flex items-center gap-3">
                                            <!-- Icono del tipo de registro -->
                                            <div class="w-10 h-10 rounded-xl flex items-center justify-center
                                                @if($vacuna->record_type === 'vacuna') bg-green-100
                                                @elseif($vacuna->record_type === 'operacion') bg-red-100
                                                @elseif($vacuna->record_type === 'emergencia') bg-orange-100
                                                @elseif($vacuna->record_type === 'checkeo') bg-blue-100
                                                @else bg-gray-100
                                                @endif">
                                                @if($vacuna->record_type === 'vacuna')
                                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                @elseif($vacuna->record_type === 'operacion')
                                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h4a1 1 0 011 1v2m-6 0h8m-8 0a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V6a2 2 0 00-2-2m-8 0V4a1 1 0 011-1h4a1 1 0 011 1v2"></path>
                                                    </svg>
                                                @elseif($vacuna->record_type === 'emergencia')
                                                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                                    </svg>
                                                @else
                                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                @endif
                                            </div>
                                            
                                            <!-- Título y badge -->
                                            <div>
                                                <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $vacuna->vaccine_name }}</h3>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    @if($vacuna->record_type === 'vacuna') bg-green-100 text-green-800
                                                    @elseif($vacuna->record_type === 'operacion') bg-red-100 text-red-800
                                                    @elseif($vacuna->record_type === 'emergencia') bg-orange-100 text-orange-800
                                                    @elseif($vacuna->record_type === 'checkeo') bg-blue-100 text-blue-800
                                                    @else bg-gray-100 text-gray-800
                                                    @endif">
                                                    {{ $tiposRegistros[$vacuna->record_type] ?? ucfirst($vacuna->record_type) }}
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <!-- Botones de acción -->
                                        <div class="flex gap-2">
                                            <button onclick="editVaccinationRecord({{ $vacuna->id }})" 
                                                    class="inline-flex items-center px-2.5 py-1.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </button>
                                            <button onclick="deleteVaccinationRecord({{ $vacuna->id }})" 
                                                    class="inline-flex items-center px-2.5 py-1.5 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <!-- Contenido de la tarjeta -->
                                    <div class="p-4">
                                        <!-- Información de fechas y veterinario -->
                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                                            <div class="flex items-center gap-2">
                                                <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="text-xs text-gray-500">Fecha</p>
                                                    <p class="text-sm font-medium text-gray-900">{{ $vacuna->date ? $vacuna->date->format('d/m/Y') : 'No especificada' }}</p>
                                                </div>
                                            </div>
                                            
                                            <div class="flex items-center gap-2">
                                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="text-xs text-gray-500">Próxima</p>
                                                    <p class="text-sm font-medium text-gray-900">
                                                        @if($vacuna->next_date)
                                                            {{ $vacuna->next_date->format('d/m/Y') }}
                                                        @else
                                                            No programada
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                            
                                            <div class="flex items-center gap-2">
                                                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="text-xs text-gray-500">Veterinario</p>
                                                    <p class="text-sm font-medium text-gray-900">{{ $vacuna->vet_name ?? 'No especificado' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Detalles médicos -->
                                        @if($vacuna->diagnosis || $vacuna->treatment || $vacuna->observations)
                                            <div class="border-t border-gray-100 pt-4 space-y-3">
                                                @if($vacuna->diagnosis)
                                                    <div>
                                                        <p class="text-xs font-medium text-gray-500 mb-1">Diagnóstico</p>
                                                        <p class="text-sm text-gray-700 bg-gray-50 rounded-lg p-3">{{ $vacuna->diagnosis }}</p>
                                                    </div>
                                                @endif
                                                
                                                @if($vacuna->treatment)
                                                    <div>
                                                        <p class="text-xs font-medium text-gray-500 mb-1">Tratamiento</p>
                                                        <p class="text-sm text-gray-700 bg-gray-50 rounded-lg p-3">{{ $vacuna->treatment }}</p>
                                                    </div>
                                                @endif
                                                
                                                @if($vacuna->observations)
                                                    <div>
                                                        <p class="text-xs font-medium text-gray-500 mb-1">Observaciones</p>
                                                        <p class="text-sm text-gray-700 bg-gray-50 rounded-lg p-3">{{ $vacuna->observations }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No hay registros de vacunación</h3>
                            <p class="mt-1 text-sm text-gray-500">Agrega el primer registro de vacunación para esta mascota.</p>
                        </div>
                    @endif
                    
                    <!-- Botón para agregar nuevo registro -->
                    <div class="mt-6 text-center">
                        <button onclick="addVaccinationRecord()" 
                                class="inline-flex items-center px-6 py-3 bg-green-600 text-white font-medium rounded-xl hover:bg-green-700 transition-all duration-200 shadow-sm hover:shadow-md">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Agregar Nuevo Registro Médico
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Agregar/Editar Registro Médico -->
<div id="vaccinationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-4 mx-auto p-4 border w-full max-w-2xl shadow-lg rounded-2xl bg-white m-4">
        <div class="max-h-[90vh] overflow-y-auto">
            <!-- Header del Modal -->
            <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 rounded-t-2xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-semibold text-gray-900" id="modalTitle">Agregar Registro Médico</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <form id="vaccinationForm" method="POST" class="p-6">
                @csrf
                <div class="space-y-6">
                    <!-- Tipo de Registro -->
                    <div>
                        <label for="record_type" class="block text-sm font-medium text-gray-700 mb-2">
                            <span class="text-red-500">*</span> Tipo de Registro
                        </label>
                        <select name="record_type" id="record_type" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                            <option value="">Seleccionar tipo de registro</option>
                            @foreach($tiposRegistros as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Selecciona el tipo de procedimiento médico</p>
                    </div>
                    
                    <!-- Nombre del Procedimiento/Vacuna -->
                    <div id="vaccine_name_field">
                        <label for="vaccine_name" class="block text-sm font-medium text-gray-700 mb-2">
                            <span class="text-red-500">*</span> Nombre del Procedimiento/Vacuna
                        </label>
                        
                        <!-- Selector de Vacuna Estándar -->
                        <div id="vaccine_selector_section">
                            <select name="vaccine_name" id="vaccine_name" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors mb-3">
                                <option value="">Seleccionar vacuna estándar</option>
                                @foreach($vacunasComunes as $vacuna)
                                    <option value="{{ $vacuna }}">{{ $vacuna }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Campo personalizado -->
                        <input type="text" name="vaccine_name_custom" id="vaccine_name_custom" 
                               placeholder="O escribir nombre personalizado"
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                        
                        <!-- Campos adicionales para vacunas estándar -->
                        <div id="vaccine_details" class="hidden mt-4 space-y-4 p-4 bg-blue-50 rounded-xl border border-blue-200">
                            <h4 class="font-medium text-blue-900 mb-3">Detalles de la Vacuna</h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="technical_name" class="block text-sm font-medium text-gray-700 mb-1">Nombre Técnico</label>
                                    <input type="text" name="technical_name" id="technical_name" 
                                           class="w-full px-3 py-2 border border-blue-200 rounded-lg bg-blue-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                </div>
                                
                                <div>
                                    <label for="laboratory" class="block text-sm font-medium text-gray-700 mb-1">Laboratorio</label>
                                    <input type="text" name="laboratory" id="laboratory" 
                                           class="w-full px-3 py-2 border border-blue-200 rounded-lg bg-blue-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                </div>
                                
                                <div>
                                    <label for="lot_number" class="block text-sm font-medium text-gray-700 mb-1">Número de lote</label>
                                    <input type="text" name="lot_number" id="lot_number" 
                                           class="w-full px-3 py-2 border border-blue-200 rounded-lg bg-blue-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                </div>
                                
                                <div>
                                    <label for="creation_date" class="block text-sm font-medium text-gray-700 mb-1">F. creación</label>
                                    <input type="date" name="creation_date" id="creation_date" 
                                           class="w-full px-3 py-2 border border-blue-200 rounded-lg bg-blue-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                </div>
                                
                                <div class="md:col-span-2">
                                    <label for="expiry_date" class="block text-sm font-medium text-gray-700 mb-1">F. vencimiento</label>
                                    <input type="date" name="expiry_date" id="expiry_date" 
                                           class="w-full px-3 py-2 border border-blue-200 rounded-lg bg-blue-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Fechas -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700 mb-2">
                                <span class="text-red-500">*</span> Fecha del Procedimiento
                            </label>
                            <input type="date" name="date" id="date" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                        </div>
                        
                        <div>
                            <label for="next_date" class="block text-sm font-medium text-gray-700 mb-2">Próxima Cita</label>
                            <input type="date" name="next_date" id="next_date"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                            <p class="text-xs text-gray-500 mt-1">Opcional - Para programar seguimiento</p>
                        </div>
                    </div>
                    
                    <!-- Diagnóstico y Tratamiento Unificado -->
                    <div>
                        <label for="diagnosis_treatment" class="block text-sm font-medium text-gray-700 mb-2">
                            Diagnóstico y Tratamiento
                        </label>
                        <textarea name="diagnosis_treatment" id="diagnosis_treatment" rows="4" 
                                  placeholder="Describe el diagnóstico y el tratamiento aplicado. Ejemplo:&#10;&#10;Diagnóstico: Vacunación preventiva contra rabia&#10;Tratamiento: Aplicación de vacuna antirrábica, observación por 15 minutos"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors resize-none"></textarea>
                        <p class="text-xs text-gray-500 mt-1">Combina diagnóstico y tratamiento para mayor eficiencia</p>
                    </div>
                    
                    <!-- Observaciones -->
                    <div>
                        <label for="observations" class="block text-sm font-medium text-gray-700 mb-2">Observaciones Adicionales</label>
                        <textarea name="observations" id="observations" rows="3" 
                                  placeholder="Observaciones, reacciones, recomendaciones para el dueño, etc."
                                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors resize-none"></textarea>
                        <p class="text-xs text-gray-500 mt-1">Información adicional relevante</p>
                    </div>
                </div>
                
                <!-- Botones del Modal -->
                <div class="sticky bottom-0 bg-white border-t border-gray-200 px-6 py-4 mt-6 -mx-6 -mb-6 rounded-b-2xl">
                    <div class="flex flex-col sm:flex-row gap-3 sm:justify-end">
                        <button type="button" onclick="closeModal()" 
                                class="w-full sm:w-auto px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors font-medium">
                            Cancelar
                        </button>
                        <button type="submit" 
                                class="w-full sm:w-auto px-6 py-3 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-colors font-medium shadow-sm">
                            <span class="hidden sm:inline">Guardar Registro</span>
                            <span class="sm:hidden">Guardar</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let currentVaccinationId = null;

function addVaccinationRecord() {
    currentVaccinationId = null;
    document.getElementById('modalTitle').textContent = 'Agregar Registro Médico';
    document.getElementById('vaccinationForm').action = '{{ route("dashboard.veterinario.vacunas.store", $pet) }}';
    document.getElementById('vaccinationForm').method = 'POST';
    document.getElementById('vaccinationForm').reset();
    document.getElementById('vaccinationModal').classList.remove('hidden');
}

function editVaccinationRecord(id) {
    currentVaccinationId = id;
    document.getElementById('modalTitle').textContent = 'Editar Vacuna';
    document.getElementById('vaccinationForm').action = '{{ route("dashboard.veterinario.vacunas.update", [$pet, ":id"]) }}'.replace(':id', id);
    document.getElementById('vaccinationForm').method = 'POST';
    
    // Aquí podrías cargar los datos de la vacuna via AJAX
    // Por simplicidad, solo mostramos el modal
    document.getElementById('vaccinationModal').classList.remove('hidden');
}

function deleteVaccinationRecord(id) {
    if (confirm('¿Estás seguro de que quieres eliminar este registro de vacunación?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("dashboard.veterinario.vacunas.destroy", [$pet, ":id"]) }}'.replace(':id', id);
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}

function closeModal() {
    document.getElementById('vaccinationModal').classList.add('hidden');
}

// Datos de vacunas estándar
const standardVaccines = {
    'Vacuna Múltiple (DHPP)': {
        technical_name: 'Vacuna Múltiple Canina DHPP',
        laboratory: 'Zoetis',
        lot_number: '',
        creation_date: '',
        expiry_date: ''
    },
    'Vacuna Antirrábica': {
        technical_name: 'Vacuna Antirrábica Inactivada',
        laboratory: 'Merck Animal Health',
        lot_number: '',
        creation_date: '',
        expiry_date: ''
    },
    'Vacuna Triple Felina': {
        technical_name: 'Vacuna Felina FVRCP',
        laboratory: 'Boehringer Ingelheim',
        lot_number: '',
        creation_date: '',
        expiry_date: ''
    }
};

// Función para mostrar/ocultar detalles de vacuna
function toggleVaccineDetails() {
    const recordType = document.getElementById('record_type').value;
    const vaccineSelect = document.getElementById('vaccine_name');
    const vaccineDetails = document.getElementById('vaccine_details');
    const vaccineSelectorSection = document.getElementById('vaccine_selector_section');
    
    if (recordType === 'vacuna') {
        vaccineSelectorSection.style.display = 'block';
        if (vaccineSelect.value && standardVaccines[vaccineSelect.value]) {
            vaccineDetails.classList.remove('hidden');
            populateVaccineFields(vaccineSelect.value);
        } else {
            vaccineDetails.classList.add('hidden');
        }
    } else {
        vaccineSelectorSection.style.display = 'none';
        vaccineDetails.classList.add('hidden');
    }
}

// Función para llenar campos de vacuna estándar
function populateVaccineFields(vaccineName) {
    const vaccine = standardVaccines[vaccineName];
    if (vaccine) {
        document.getElementById('technical_name').value = vaccine.technical_name;
        document.getElementById('laboratory').value = vaccine.laboratory;
        document.getElementById('lot_number').value = vaccine.lot_number;
        document.getElementById('creation_date').value = vaccine.creation_date;
        document.getElementById('expiry_date').value = vaccine.expiry_date;
        
        // Hacer editables los campos que deben ser completados
        document.getElementById('lot_number').readOnly = false;
        document.getElementById('creation_date').readOnly = false;
        document.getElementById('expiry_date').readOnly = false;
        
        // Aplicar estilos para campos editables
        ['lot_number', 'creation_date', 'expiry_date'].forEach(fieldId => {
            const field = document.getElementById(fieldId);
            field.classList.remove('bg-gray-50', 'border-gray-200');
            field.classList.add('bg-blue-50', 'border-blue-200');
        });
    }
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    const recordTypeSelect = document.getElementById('record_type');
    const vaccineNameSelect = document.getElementById('vaccine_name');
    const vaccineNameCustom = document.getElementById('vaccine_name_custom');
    
    // Manejar cambio en tipo de registro
    if (recordTypeSelect) {
        recordTypeSelect.addEventListener('change', function() {
            toggleVaccineDetails();
            
            if (this.value === 'vacuna') {
                vaccineNameCustom.placeholder = 'O escribir nombre de vacuna personalizada';
            } else {
                vaccineNameCustom.placeholder = 'Nombre del procedimiento o consulta';
            }
        });
    }
    
    // Manejar cambio en selector de vacuna
    if (vaccineNameSelect) {
        vaccineNameSelect.addEventListener('change', function() {
            toggleVaccineDetails();
            
            if (this.value) {
                vaccineNameCustom.style.display = 'none';
                vaccineNameCustom.value = '';
            } else {
                vaccineNameCustom.style.display = 'block';
            }
        });
    }
    
    // Manejar input personalizado
    if (vaccineNameCustom) {
        vaccineNameCustom.addEventListener('input', function() {
            if (this.value !== '') {
                vaccineNameSelect.value = '';
                document.getElementById('vaccine_details').classList.add('hidden');
            }
        });
    }
});

// Cerrar modal al hacer clic fuera
document.getElementById('vaccinationModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>
@endsection
