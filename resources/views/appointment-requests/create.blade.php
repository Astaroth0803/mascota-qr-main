@extends('layouts.dashboard')

@section('title', 'Nueva Solicitud de Cita')

@section('content')
<div class="min-h-screen bg-gray-50 lg:ml-64">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200 pt-16 lg:pt-0">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="py-4 lg:py-6">
                <!-- Breadcrumb -->
                <nav class="flex items-center space-x-2 text-sm text-gray-500 mb-4">
                    <a href="{{ route('dashboard') }}" class="hover:text-gray-700 transition-colors">Dashboard</a>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                    <a href="{{ route('citas.index') }}" class="hover:text-gray-700 transition-colors">Solicitudes</a>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                    <span class="text-gray-900 font-medium">Nueva Solicitud</span>
                </nav>
                
                <!-- Título Principal -->
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Nueva Solicitud de Cita</h1>
                        <p class="text-gray-600 mt-1">Solicita una cita directamente con un veterinario</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="px-4 sm:px-6 lg:px-8 py-8">
        <div class="max-w-4xl mx-auto">
            <form action="{{ route('citas.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <!-- Información de la Mascota -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                        Información de la Mascota
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="pet_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Mascota *
                            </label>
                            <select name="pet_id" id="pet_id" required 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Selecciona una mascota</option>
                                @foreach($pets as $pet)
                                    <option value="{{ $pet->id }}">{{ $pet->nombre }} ({{ $pet->especie }})</option>
                                @endforeach
                            </select>
                            @error('pet_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="veterinarian_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Veterinario *
                            </label>
                            <select name="veterinarian_id" id="veterinarian_id" required 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Selecciona un veterinario</option>
                                @foreach($veterinarians as $vet)
                                    <option value="{{ $vet->id }}">{{ $vet->name }}</option>
                                @endforeach
                            </select>
                            @error('veterinarian_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Detalles de la Cita -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        Detalles de la Cita
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="appointment_type" class="block text-sm font-medium text-gray-700 mb-2">
                                Tipo de Cita *
                            </label>
                            <select name="appointment_type" id="appointment_type" required 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Selecciona el tipo de cita</option>
                                <option value="consulta">Consulta General</option>
                                <option value="vacunacion">Vacunación</option>
                                <option value="cirugia">Cirugía</option>
                                <option value="emergencia">Emergencia</option>
                                <option value="chequeo">Chequeo Rutinario</option>
                            </select>
                            @error('appointment_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="requested_datetime" class="block text-sm font-medium text-gray-700 mb-2">
                                Fecha y Hora Deseada *
                            </label>
                            <input type="datetime-local" name="requested_datetime" id="requested_datetime" required 
                                   min="{{ now()->format('Y-m-d\TH:i') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('requested_datetime')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Descripción de la Cita
                        </label>
                        <textarea name="description" id="description" rows="4" 
                                  placeholder="Describe brevemente el motivo de la cita, síntomas, o cualquier información relevante..."
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="flex items-center justify-end space-x-4">
                    <a href="{{ route('citas.index') }}" 
                       class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Enviar Solicitud
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Actualizar veterinarios disponibles cuando se selecciona una mascota
document.getElementById('pet_id').addEventListener('change', function() {
    const petId = this.value;
    const veterinarianSelect = document.getElementById('veterinarian_id');
    
    if (petId) {
        // Hacer petición AJAX para obtener veterinarios disponibles
        fetch(`{{ route('citas.veterinarios.disponibles') }}?pet_id=${petId}`)
            .then(response => response.json())
            .then(veterinarians => {
                veterinarianSelect.innerHTML = '<option value="">Selecciona un veterinario</option>';
                veterinarians.forEach(vet => {
                    const option = document.createElement('option');
                    option.value = vet.id;
                    option.textContent = vet.name;
                    veterinarianSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error:', error);
            });
    } else {
        veterinarianSelect.innerHTML = '<option value="">Selecciona un veterinario</option>';
    }
});
</script>
@endsection
