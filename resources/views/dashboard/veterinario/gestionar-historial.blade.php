@extends('layouts.dashboard')

@section('title', 'Gestionar Historial Médico')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="lg:ml-64">
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="py-4 lg:py-6">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <h1 class="text-xl lg:text-2xl xl:text-3xl font-bold text-gray-900">
                                Gestionar Historial Médico
                            </h1>
                            <p class="text-sm lg:text-base text-gray-600 mt-1">
                                {{ $pet->nombre }} - {{ $pet->especie }}
                            </p>
                            <p class="text-xs text-gray-500 mt-1">
                                Veterinario: {{ Auth::user()->name }}
                            </p>
                        </div>
                        <div class="flex gap-3">
                            <a href="{{ route('dashboard.veterinario.mascota.show', $pet) }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Ver Mascota
                            </a>
                            <button onclick="addVaccinationRecord()" 
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Agregar Registro Médico
                            </button>
                        </div>
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
                                <div class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors border border-gray-200">
                                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($vacuna->record_type === 'vacuna') bg-green-100 text-green-800
                                                @elseif($vacuna->record_type === 'operacion') bg-red-100 text-red-800
                                                @elseif($vacuna->record_type === 'emergencia') bg-orange-100 text-orange-800
                                                @elseif($vacuna->record_type === 'checkeo') bg-blue-100 text-blue-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ $tiposRegistros[$vacuna->record_type] ?? ucfirst($vacuna->record_type) }}
                                            </span>
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $vacuna->vaccine_name }}</h3>
                                        </div>
                                        <div class="flex flex-wrap gap-4 mt-2 text-sm text-gray-600">
                                            <span><strong>Fecha:</strong> {{ $vacuna->date ? $vacuna->date->format('d/m/Y') : 'No especificada' }}</span>
                                            @if($vacuna->next_date)
                                                <span><strong>Próxima:</strong> {{ $vacuna->next_date->format('d/m/Y') }}</span>
                                            @else
                                                <span><strong>Próxima:</strong> No programada</span>
                                            @endif
                                            <span><strong>Veterinario:</strong> {{ $vacuna->vet_name ?? 'No especificado' }}</span>
                                        </div>
                                        @if($vacuna->diagnosis)
                                            <p class="text-sm text-gray-700 mt-2"><strong>Diagnóstico:</strong> {{ $vacuna->diagnosis }}</p>
                                        @endif
                                        @if($vacuna->treatment)
                                            <p class="text-sm text-gray-700 mt-1"><strong>Tratamiento:</strong> {{ $vacuna->treatment }}</p>
                                        @endif
                                        @if($vacuna->observations)
                                            <p class="text-sm text-gray-700 mt-1"><strong>Observaciones:</strong> {{ $vacuna->observations }}</p>
                                        @endif
                                    </div>
                                    <div class="flex gap-2 ml-4">
                                        <button onclick="editVaccinationRecord({{ $vacuna->id }})" 
                                                class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Editar
                                        </button>
                                        <button onclick="deleteVaccinationRecord({{ $vacuna->id }})" 
                                                class="inline-flex items-center px-3 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Eliminar
                                        </button>
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
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Agregar/Editar Vacuna -->
<div id="vaccinationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Agregar Vacuna</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form id="vaccinationForm" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="record_type" class="block text-sm font-medium text-gray-700">Tipo de Registro</label>
                        <select name="record_type" id="record_type" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Seleccionar tipo de registro</option>
                            @foreach($tiposRegistros as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div id="vaccine_name_field">
                        <label for="vaccine_name" class="block text-sm font-medium text-gray-700">Nombre del Procedimiento/Vacuna</label>
                        <select name="vaccine_name" id="vaccine_name" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Seleccionar vacuna</option>
                            @foreach($vacunasComunes as $vacuna)
                                <option value="{{ $vacuna }}">{{ $vacuna }}</option>
                            @endforeach
                        </select>
                        <input type="text" name="vaccine_name_custom" id="vaccine_name_custom" placeholder="O escribir nombre personalizado"
                               class="mt-2 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" style="display: none;">
                    </div>
                    
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700">Fecha del Procedimiento</label>
                        <input type="date" name="date" id="date" required
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label for="next_date" class="block text-sm font-medium text-gray-700">Próxima Cita (Opcional)</label>
                        <input type="date" name="next_date" id="next_date"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    
                    <div>
                        <label for="diagnosis" class="block text-sm font-medium text-gray-700">Diagnóstico</label>
                        <textarea name="diagnosis" id="diagnosis" rows="2" placeholder="Diagnóstico o motivo de la consulta"
                                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                    
                    <div>
                        <label for="treatment" class="block text-sm font-medium text-gray-700">Tratamiento</label>
                        <textarea name="treatment" id="treatment" rows="2" placeholder="Tratamiento prescrito"
                                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                    
                    <div>
                        <label for="observations" class="block text-sm font-medium text-gray-700">Observaciones</label>
                        <textarea name="observations" id="observations" rows="3" placeholder="Observaciones adicionales"
                                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Guardar
                    </button>
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

// Manejar cambio en el tipo de registro
document.addEventListener('DOMContentLoaded', function() {
    const recordTypeSelect = document.getElementById('record_type');
    const vaccineNameSelect = document.getElementById('vaccine_name');
    const vaccineNameCustom = document.getElementById('vaccine_name_custom');
    
    if (recordTypeSelect) {
        recordTypeSelect.addEventListener('change', function() {
            if (this.value === 'vacuna') {
                vaccineNameSelect.style.display = 'block';
                vaccineNameCustom.style.display = 'block';
                vaccineNameSelect.required = true;
            } else {
                vaccineNameSelect.style.display = 'none';
                vaccineNameCustom.style.display = 'block';
                vaccineNameSelect.required = false;
                vaccineNameCustom.placeholder = 'Nombre del procedimiento o consulta';
            }
        });
    }
    
    // Manejar cambio entre select y input personalizado
    if (vaccineNameSelect && vaccineNameCustom) {
        vaccineNameSelect.addEventListener('change', function() {
            if (this.value === '') {
                vaccineNameCustom.style.display = 'block';
            } else {
                vaccineNameCustom.style.display = 'none';
                vaccineNameCustom.value = '';
            }
        });
        
        vaccineNameCustom.addEventListener('input', function() {
            if (this.value !== '') {
                vaccineNameSelect.style.display = 'none';
                vaccineNameSelect.value = '';
            } else {
                vaccineNameSelect.style.display = 'block';
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
