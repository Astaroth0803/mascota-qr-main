@extends('layouts.dashboard')

@section('title', 'Crear Nueva Cita - Cliente')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="lg:ml-64">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="py-4 lg:py-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <!-- Título -->
                        <div class="flex-1 min-w-0">
                            <h1 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900">Crear Nueva Cita</h1>
                            <p class="text-sm text-gray-600 mt-1">Programa una nueva cita médica para tu mascota</p>
                        </div>
                        
                        <!-- Botón de regreso -->
                        <div class="flex items-center gap-3">
                            <a href="{{ route('dashboard.cliente.calendario.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Volver al Calendario
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="lg:ml-64">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Información de la Cita</h2>
                    <p class="text-sm text-gray-500 mt-1">Completa los datos para programar la cita médica</p>
                </div>
                
                <form action="{{ route('dashboard.cliente.calendario.store') }}" method="POST" class="p-6 space-y-6">
                    @csrf
                    
                    <!-- Mascota -->
                    <div>
                        <label for="pet_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Mascota <span class="text-red-500">*</span>
                        </label>
                        <select name="pet_id" id="pet_id" required
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('pet_id') border-red-300 @enderror">
                            <option value="">Selecciona una mascota</option>
                            @foreach($pets as $pet)
                                <option value="{{ $pet->id }}" {{ old('pet_id') == $pet->id ? 'selected' : '' }}>
                                    {{ $pet->nombre }} - {{ $pet->especie }} ({{ $pet->raza }})
                                </option>
                            @endforeach
                        </select>
                        @error('pet_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Tipo de Cita -->
                    <div>
                        <label for="record_type" class="block text-sm font-medium text-gray-700 mb-2">
                            Tipo de Cita <span class="text-red-500">*</span>
                        </label>
                        <select name="record_type" id="record_type" required
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('record_type') border-red-300 @enderror">
                            <option value="">Selecciona el tipo de cita</option>
                            @foreach($appointmentTypes as $key => $label)
                                <option value="{{ $key }}" {{ old('record_type') == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('record_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Fecha y Hora -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700 mb-2">
                                Fecha de la Cita <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="date" id="date" required
                                   min="{{ now()->format('Y-m-d') }}"
                                   value="{{ old('date') }}"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('date') border-red-300 @enderror">
                            @error('date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="time" class="block text-sm font-medium text-gray-700 mb-2">
                                Hora de la Cita <span class="text-red-500">*</span>
                            </label>
                            <input type="time" name="time" id="time" required
                                   value="{{ old('time', '09:00') }}"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('time') border-red-300 @enderror">
                            @error('time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Información del Veterinario -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-blue-800 mb-3">Información del Veterinario</h3>
                        <div id="veterinario-info" class="text-sm text-blue-700">
                            <p>Selecciona una mascota para ver su veterinario asignado</p>
                        </div>
                        <input type="hidden" name="veterinarian_id" id="veterinarian_id">
                        <input type="hidden" name="location" id="location">
                    </div>
                    
                    <!-- Información Adicional -->
                    <div class="space-y-6">
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Nombre de Vacuna/Tratamiento
                            </label>
                            <div class="text-sm text-gray-600 bg-white border border-gray-300 rounded-md px-3 py-2">
                                <span class="text-gray-500 italic">Será determinado por el veterinario durante la consulta</span>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                El veterinario especificará el tipo de tratamiento o vacuna según la evaluación médica.
                            </p>
                            <input type="hidden" name="vaccine_name" value="">
                        </div>
                        
                        <div>
                            <label for="observations" class="block text-sm font-medium text-gray-700 mb-2">
                                Observaciones
                            </label>
                            <textarea name="observations" id="observations" rows="3"
                                      placeholder="Observaciones adicionales sobre la cita..."
                                      class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('observations') border-red-300 @enderror">{{ old('observations') }}</textarea>
                            @error('observations')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="next_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Próxima Cita (Opcional)
                            </label>
                            <input type="date" name="next_date" id="next_date"
                                   value="{{ old('next_date') }}"
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('next_date') border-red-300 @enderror">
                            <p class="mt-1 text-xs text-gray-500">Si esta cita requiere un seguimiento, programa la próxima cita aquí.</p>
                            @error('next_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Botones -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('dashboard.cliente.calendario.index') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Crear Cita
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Datos de mascotas con veterinarios asignados
const mascotasData = {!! json_encode($pets->map(function($pet) {
    return [
        'id' => $pet->id,
        'nombre' => $pet->nombre,
        'veterinarios' => $pet->veterinariosActivos->map(function($vet) {
            return [
                'id' => $vet->id,
                'name' => $vet->name,
                'ubicacion' => $vet->ubicacion ?? 'Panamá, Panamá, Clínica Test',
                'tipo' => $vet->tipo_veterinario_nombre ?? 'Veterinario'
            ];
        })->toArray()
    ];
})->toArray()) !!};

// Manejar cambio de mascota
document.getElementById('pet_id').addEventListener('change', function() {
    const petId = parseInt(this.value);
    const veterinarioInfo = document.getElementById('veterinario-info');
    const veterinarianIdInput = document.getElementById('veterinarian_id');
    const locationInput = document.getElementById('location');
    
    if (petId) {
        const mascota = mascotasData.find(p => p.id === petId);
        
        if (mascota && mascota.veterinarios.length > 0) {
            // Si hay veterinarios asignados, mostrar el primero (principal)
            const veterinario = mascota.veterinarios[0];
            
            veterinarioInfo.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <span class="font-medium">Veterinario:</span>
                        <span>${veterinario.name}</span>
                    </div>
                    <div>
                        <span class="font-medium">Tipo:</span>
                        <span>${veterinario.tipo}</span>
                    </div>
                    <div class="md:col-span-2">
                        <span class="font-medium">Ubicación:</span>
                        <span>${veterinario.ubicacion}</span>
                    </div>
                </div>
            `;
            
            veterinarianIdInput.value = veterinario.id;
            locationInput.value = veterinario.ubicacion;
        } else {
            veterinarioInfo.innerHTML = `
                <div class="text-orange-600">
                    <p><strong>⚠️ Advertencia:</strong> Esta mascota no tiene veterinario asignado.</p>
                    <p class="text-xs mt-1">Ve a "Veterinarios Disponibles" para asignar un veterinario a esta mascota.</p>
                </div>
            `;
            veterinarianIdInput.value = '';
            locationInput.value = 'Panamá, Panamá, Clínica Test';
        }
    } else {
        veterinarioInfo.innerHTML = '<p>Selecciona una mascota para ver su veterinario asignado</p>';
        veterinarianIdInput.value = '';
        locationInput.value = '';
    }
});

// Validación adicional para asegurar que next_date sea después de date
document.getElementById('next_date').addEventListener('change', function() {
    const dateValue = document.getElementById('date').value;
    const nextDateValue = this.value;
    
    if (dateValue && nextDateValue && nextDateValue <= dateValue) {
        alert('La fecha de la próxima cita debe ser posterior a la fecha de la cita actual.');
        this.value = '';
    }
});
</script>
@endsection
