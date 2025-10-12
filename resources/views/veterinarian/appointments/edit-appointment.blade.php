@extends('layouts.standard')

@section('title', 'Editar Cita')

@php
    $title = 'Editar Cita';
    $subtitle = $appointment->pet->nombre . ' - ' . ucfirst($appointment->record_type);
@endphp

@section('main-content')
<div>
                <form action="{{ route('dashboard.veterinario.calendario.update', $appointment->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <!-- Debug info -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                        <p class="text-sm text-yellow-800">
                            <strong>Debug:</strong> Current status: {{ $appointment->status }}
                        </p>
                    </div>
                    
                    <!-- Información de la cita -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:p-8">
                        <div class="mb-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-2">Información de la cita</h2>
                            <p class="text-gray-600">Modifica los detalles de la cita</p>
                        </div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 lg:gap-6">
                            <!-- Tipo de cita -->
                            <div>
                                <label for="record_type" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tipo de cita
                                </label>
                                <select name="record_type" id="record_type" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    @foreach($appointmentTypes as $key => $label)
                                        <option value="{{ $key }}" {{ old('record_type', $appointment->record_type) == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Peso de mascota -->
                            <div>
                                <label for="pet_weight" class="block text-sm font-medium text-gray-700 mb-2">
                                    Peso de mascota (kg)
                                </label>
                                <input type="number" name="pet_weight" id="pet_weight" step="0.01" min="0" max="999.99"
                                       value="{{ old('pet_weight', $appointment->pet->peso) }}"
                                       placeholder="Ej: 15.5"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <!-- Fecha -->
                            <div>
                                <label for="date" class="block text-sm font-medium text-gray-700 mb-2">
                                    Fecha
                                </label>
                                <input type="date" name="date" id="date" required
                                       value="{{ old('date', $appointment->scheduled_datetime ? $appointment->scheduled_datetime->format('Y-m-d') : $appointment->requested_datetime->format('Y-m-d')) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <!-- Hora -->
                            <div>
                                <label for="time" class="block text-sm font-medium text-gray-700 mb-2">
                                    Hora
                                </label>
                                <input type="time" name="time" id="time" required
                                       value="{{ old('time', $appointment->scheduled_datetime ? $appointment->scheduled_datetime->format('H:i') : $appointment->requested_datetime->format('H:i')) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <!-- Status de la cita -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                    Status de la cita
                                </label>
                                <select name="status" id="status" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        @if($appointment->status === 'finalizada') disabled @endif>
                                    <option value="pendiente" {{ old('status', $appointment->status) == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                    <option value="agendada" {{ old('status', $appointment->status) == 'agendada' ? 'selected' : '' }}>Agendada</option>
                                    <option value="en_progreso" {{ old('status', $appointment->status) == 'en_progreso' ? 'selected' : '' }}>En Progreso</option>
                                    <option value="finalizada" {{ old('status', $appointment->status) == 'finalizada' ? 'selected' : '' }}>Finalizada</option>
                                    <option value="cancelada" {{ old('status', $appointment->status) == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                                </select>
                                @if($appointment->status === 'finalizada')
                                    <input type="hidden" name="status" value="finalizada">
                                    <p class="text-sm text-gray-500 mt-1">Esta cita ya está finalizada y no se puede modificar</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Diagnóstico y tratamiento -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:p-8">
                        <div class="mb-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-2">Diagnóstico y tratamiento</h2>
                        </div>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <div>
                                <label for="diagnosis_treatment" class="block text-sm font-medium text-gray-700 mb-2">
                                    Diagnóstico y Tratamiento
                                </label>
                                <textarea name="diagnosis_treatment" id="diagnosis_treatment" rows="8"
                                          placeholder="Ingresa el diagnóstico médico y el tratamiento prescrito..."
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('diagnosis_treatment', $appointment->diagnosis_treatment) }}</textarea>
                            </div>

                            <div>
                                <label for="observations" class="block text-sm font-medium text-gray-700 mb-2">
                                    Observaciones
                                </label>
                                <textarea name="observations" id="observations" rows="8"
                                          placeholder="Observaciones adicionales sobre la cita..."
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('observations', $appointment->observations) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Información de vacuna (si es vacuna) -->
                    <div id="vaccine_section" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:p-8" style="display: none;">
                        <div class="mb-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-2">Información de Vacunación</h2>
                            <p class="text-gray-600">Detalles específicos de la vacuna aplicada</p>
                        </div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 lg:gap-6">
                            <div>
                                <label for="vaccine_type" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tipo de Vacuna
                                </label>
                                <select name="vaccine_type" id="vaccine_type"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">Seleccionar vacuna...</option>
                                    <option value="pentavalente" data-technical="Pentavalente" data-lab="Zoetis" data-lot="PENT-2024-001" data-creation="2024-01-15" data-expiry="2025-01-15">Pentavalente</option>
                                    <option value="rabia" data-technical="Rabia" data-lab="Merial" data-lot="RAB-2024-002" data-creation="2024-02-01" data-expiry="2025-02-01">Rabia</option>
                                    <option value="moquillo" data-technical="Moquillo" data-lab="Boehringer" data-lot="MOQ-2024-003" data-creation="2024-01-20" data-expiry="2025-01-20">Moquillo</option>
                                    <option value="parvovirus" data-technical="Parvovirus" data-lab="Vanguard" data-lot="PAR-2024-004" data-creation="2024-02-10" data-expiry="2025-02-10">Parvovirus</option>
                                    <option value="hepatitis" data-technical="Hepatitis" data-lab="Intervet" data-lot="HEP-2024-005" data-creation="2024-01-25" data-expiry="2025-01-25">Hepatitis</option>
                                    <option value="leptospirosis" data-technical="Leptospirosis" data-lab="Zoetis" data-lot="LEP-2024-006" data-creation="2024-02-05" data-expiry="2025-02-05">Leptospirosis</option>
                                    <option value="bordetella" data-technical="Bordetella" data-lab="Merial" data-lot="BOR-2024-007" data-creation="2024-01-30" data-expiry="2025-01-30">Bordetella</option>
                                    <option value="personalizada">Información personalizada</option>
                                </select>
                            </div>

                            <div>
                                <label for="vaccine_technical_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nombre Técnico
                                </label>
                                <input type="text" name="vaccine_technical_name" id="vaccine_technical_name"
                                       value="{{ old('vaccine_technical_name', $appointment->vaccine_technical_name ?? '') }}"
                                       placeholder="Nombre técnico de la vacuna"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label for="vaccine_laboratory" class="block text-sm font-medium text-gray-700 mb-2">
                                    Laboratorio
                                </label>
                                <input type="text" name="vaccine_laboratory" id="vaccine_laboratory"
                                       value="{{ old('vaccine_laboratory', $appointment->vaccine_laboratory ?? '') }}"
                                       placeholder="Laboratorio"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label for="vaccine_lot" class="block text-sm font-medium text-gray-700 mb-2">
                                    Número de Lote
                                </label>
                                <input type="text" name="vaccine_lot" id="vaccine_lot"
                                       value="{{ old('vaccine_lot', $appointment->vaccine_lot ?? '') }}"
                                       placeholder="Número de lote"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label for="vaccine_creation_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    Fecha de Creación
                                </label>
                                <input type="date" name="vaccine_creation_date" id="vaccine_creation_date"
                                       value="{{ old('vaccine_creation_date', $appointment->vaccine_creation_date ?? '') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label for="vaccine_expiry_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    Fecha de Vencimiento
                                </label>
                                <input type="date" name="vaccine_expiry_date" id="vaccine_expiry_date"
                                       value="{{ old('vaccine_expiry_date', $appointment->vaccine_expiry_date ?? '') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="flex items-center justify-end space-x-4">
                        <a href="{{ route('dashboard.veterinario.calendario.show', $appointment->id) }}" 
                           class="inline-flex items-center px-6 py-3 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Cancelar
                        </a>
                        <button type="button" onclick="debugForm()" 
                                class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Debug
                        </button>
                        <button type="submit" 
                                class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const recordTypeSelect = document.getElementById('record_type');
    const vaccineSection = document.getElementById('vaccine_section');
    const vaccineTypeSelect = document.getElementById('vaccine_type');
    const vaccineTechnicalNameInput = document.getElementById('vaccine_technical_name');
    const vaccineLaboratoryInput = document.getElementById('vaccine_laboratory');
    const vaccineLotInput = document.getElementById('vaccine_lot');
    const vaccineCreationDateInput = document.getElementById('vaccine_creation_date');
    const vaccineExpiryDateInput = document.getElementById('vaccine_expiry_date');
    
    function toggleVaccineSection() {
        if (recordTypeSelect.value === 'vacuna') {
            vaccineSection.style.display = 'block';
        } else {
            vaccineSection.style.display = 'none';
        }
    }
    
    function populateVaccineFields() {
        const selectedOption = vaccineTypeSelect.options[vaccineTypeSelect.selectedIndex];
        
        if (selectedOption.value && selectedOption.value !== 'personalizada') {
            // Precargar campos con datos de la vacuna seleccionada
            vaccineTechnicalNameInput.value = selectedOption.dataset.technical || '';
            vaccineLaboratoryInput.value = selectedOption.dataset.lab || '';
            vaccineLotInput.value = selectedOption.dataset.lot || '';
            vaccineCreationDateInput.value = selectedOption.dataset.creation || '';
            vaccineExpiryDateInput.value = selectedOption.dataset.expiry || '';
        } else if (selectedOption.value === 'personalizada') {
            // Limpiar campos para información personalizada
            vaccineTechnicalNameInput.value = '';
            vaccineLaboratoryInput.value = '';
            vaccineLotInput.value = '';
            vaccineCreationDateInput.value = '';
            vaccineExpiryDateInput.value = '';
        }
    }
    
    // Event listeners
    recordTypeSelect.addEventListener('change', toggleVaccineSection);
    vaccineTypeSelect.addEventListener('change', populateVaccineFields);
    
    // Ejecutar al cargar la página
    toggleVaccineSection();
    populateVaccineFields();
});

function debugForm() {
    const form = document.querySelector('form');
    const formData = new FormData(form);
    
    console.log('Form data:');
    for (let [key, value] of formData.entries()) {
        console.log(key + ': ' + value);
    }
    
    // Mostrar en alert
    let debugInfo = 'Form data:\n';
    for (let [key, value] of formData.entries()) {
        debugInfo += key + ': ' + value + '\n';
    }
    alert(debugInfo);
}
</script>
@endsection
