@extends('layouts.dashboard')

@section('title', 'Crear Nueva Cita')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Crear Nueva Cita</h1>
            <p class="text-gray-600 mt-2">Programa una nueva cita para una mascota asignada</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('dashboard.veterinario.calendario.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <div>
                    <label for="pet_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Mascota *
                    </label>
                    <select name="pet_id" id="pet_id" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Selecciona una mascota</option>
                        @foreach($assignedPets as $pet)
                            <option value="{{ $pet->id }}" {{ old('pet_id') == $pet->id ? 'selected' : '' }}>
                                {{ $pet->nombre }} - {{ $pet->user->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('pet_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="client_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Cliente *
                    </label>
                    <select name="client_id" id="client_id" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Selecciona un cliente</option>
                        @foreach($assignedPets as $pet)
                            <option value="{{ $pet->user_id }}" {{ old('client_id') == $pet->user_id ? 'selected' : '' }}>
                                {{ $pet->user->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('client_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="scheduled_datetime" class="block text-sm font-medium text-gray-700 mb-2">
                        Fecha y Hora *
                    </label>
                    <input type="datetime-local" name="scheduled_datetime" id="scheduled_datetime" 
                           value="{{ old('scheduled_datetime') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @error('scheduled_datetime')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                        Tipo de Cita *
                    </label>
                    <select name="type" id="type" required 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Selecciona el tipo de cita</option>
                        @foreach($appointmentTypes as $key => $value)
                            <option value="{{ $key }}" {{ old('type') == $key ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                        @endforeach
                    </select>
                    @error('type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Notas
                    </label>
                    <textarea name="notes" id="notes" rows="4" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Notas adicionales sobre la cita...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="{{ route('dashboard.veterinario.calendario.index') }}" 
                       class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        Crear Cita
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const petSelect = document.getElementById('pet_id');
    const clientSelect = document.getElementById('client_id');
    
    petSelect.addEventListener('change', function() {
        const selectedPet = this.options[this.selectedIndex];
        if (selectedPet.value) {
            // Encontrar el cliente correspondiente a la mascota seleccionada
            const clientId = selectedPet.dataset.clientId || selectedPet.textContent.split(' - ')[1];
            // Actualizar el select de cliente si es necesario
        }
    });
});
</script>
@endsection
