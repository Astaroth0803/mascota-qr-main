@extends('layouts.dashboard')

@section('title', 'Editar Cita - Veterinario')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Optimizado -->
    <div class="bg-white shadow-sm border-b border-gray-200 pt-16 lg:pt-0">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="py-4 lg:py-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex-1 min-w-0">
                        <h1 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900">Editar Cita</h1>
                        <p class="text-sm text-gray-600 mt-1">{{ $appointment->pet->nombre }} - {{ $appointment->getTypeOptions()[$appointment->record_type] ?? ucfirst($appointment->record_type) }}</p>
                    </div>
                    <!-- Acciones rápidas -->
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('dashboard.veterinario.calendario.show', $appointment->id) }}" 
                           class="inline-flex items-center px-3 py-2 bg-gray-100 border border-gray-200 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition-colors">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <span class="hidden sm:inline">Ver</span>
                        </a>
                        <form action="{{ route('dashboard.veterinario.calendario.destroy', $appointment->id) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta cita?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="inline-flex items-center px-3 py-2 bg-red-600 text-white text-sm font-medium rounded-xl hover:bg-red-700 transition-colors">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                <span class="hidden sm:inline">Eliminar</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div>
        <div class="px-4 sm:px-6 lg:px-8 py-6">
            <!-- Formulario optimizado -->
            <div class="max-w-2xl mx-auto">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
                    <div class="px-6 py-5 border-b border-gray-100">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900">Información de la Cita</h2>
                                <p class="text-sm text-gray-600 mt-1">Modifica los datos de la cita según sea necesario</p>
                            </div>
                        </div>
                    </div>
                
                    <form action="{{ route('dashboard.veterinario.calendario.update', $appointment->id) }}" method="POST" class="p-6 space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <!-- Información de la mascota (solo lectura) -->
                        <div class="bg-gray-50 rounded-xl p-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-900">Mascota</h3>
                                    <p class="text-sm text-gray-600">{{ $appointment->pet->nombre }} ({{ $appointment->pet->especie }} - {{ $appointment->pet->user->name ?? 'N/A' }})</p>
                                </div>
                            </div>
                        </div>

                        <!-- Tipo de cita -->
                        <div>
                            <label for="record_type" class="block text-sm font-semibold text-gray-900 mb-2">
                                Tipo de Cita <span class="text-red-500">*</span>
                            </label>
                            <select name="record_type" id="record_type" required
                                    class="block w-full px-4 py-3 border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm @error('record_type') border-red-300 @enderror">
                                @foreach($appointmentTypes as $key => $label)
                                    <option value="{{ $key }}" {{ old('record_type', $appointment->record_type) == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('record_type')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Peso de la mascota (solo veterinario puede editar) -->
                        <div>
                            <label for="pet_weight" class="block text-sm font-semibold text-gray-900 mb-2">
                                Peso de la Mascota (kg)
                            </label>
                            <input type="number" name="pet_weight" id="pet_weight" step="0.01" min="0" max="999.99"
                                   value="{{ old('pet_weight', $appointment->pet->peso) }}"
                                   placeholder="Ej: 15.5"
                                   class="block w-full px-4 py-3 border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm @error('pet_weight') border-red-300 @enderror">
                            <p class="mt-2 text-xs text-gray-500">Solo los veterinarios pueden actualizar el peso de la mascota</p>
                            @error('pet_weight')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Fecha y hora -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="date" class="block text-sm font-semibold text-gray-900 mb-2">
                                    Fecha <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="date" id="date" required
                                       value="{{ old('date', $appointment->date->format('Y-m-d')) }}"
                                       class="block w-full px-4 py-3 border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm @error('date') border-red-300 @enderror">
                                @error('date')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="time" class="block text-sm font-semibold text-gray-900 mb-2">
                                    Hora <span class="text-red-500">*</span>
                                </label>
                                <input type="time" name="time" id="time" required
                                       value="{{ old('time', $appointment->time ? ($appointment->time instanceof \Carbon\Carbon ? $appointment->time->format('H:i') : \Carbon\Carbon::parse($appointment->time)->format('H:i')) : '09:00') }}"
                                       class="block w-full px-4 py-3 border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm @error('time') border-red-300 @enderror">
                                @error('time')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <!-- Información de vacuna (si es vacuna) -->
                        <div id="vaccine_section" style="display: none;">
                            <div class="bg-blue-50 rounded-xl p-4 mb-4">
                                <div class="flex items-center space-x-2 mb-3">
                                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-sm font-semibold text-blue-900">Información de Vacunación</h3>
                                </div>
                                <p class="text-xs text-blue-700">Selecciona una vacuna estándar o ingresa información personalizada</p>
                            <p class="text-xs text-green-700 mt-1">Todos los campos son editables para ajustar según el stock disponible</p>
                            </div>

                            <!-- Vacunas estándar -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="vaccine_type" class="block text-sm font-semibold text-gray-900 mb-2">
                                        Vacuna Múltiple - Tipo de Vacuna
                                    </label>
                                    <select name="vaccine_type" id="vaccine_type"
                                            class="block w-full px-4 py-3 border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                        <option value="">Seleccionar vacuna estándar...</option>
                                        <option value="pentavalente" data-technical="Pentavalente" data-lab="Zoetis" data-lot="PENT-2024-001" data-creation="2024-01-15" data-expiry="2025-01-15">Pentavalente</option>
                                        <option value="rabia" data-technical="Rabia" data-lab="Merial" data-lot="RAB-2024-002" data-creation="2024-02-01" data-expiry="2025-02-01">Rabia</option>
                                        <option value="moquillo" data-technical="Moquillo" data-lab="Boehringer" data-lot="MOQ-2024-003" data-creation="2024-01-20" data-expiry="2025-01-20">Moquillo</option>
                                        <option value="parvovirus" data-technical="Parvovirus" data-lab="Vanguard" data-lot="PAR-2024-004" data-creation="2024-02-10" data-expiry="2025-02-10">Parvovirus</option>
                                        <option value="hepatitis" data-technical="Hepatitis" data-lab="Intervet" data-lot="HEP-2024-005" data-creation="2024-01-25" data-expiry="2025-01-25">Hepatitis</option>
                                        <option value="leptospirosis" data-technical="Leptospirosis" data-lab="Zoetis" data-lot="LEP-2024-006" data-creation="2024-02-05" data-expiry="2025-02-05">Leptospirosis</option>
                                        <option value="bordetella" data-technical="Bordetella" data-lab="Merial" data-lot="BOR-2024-007" data-creation="2024-01-30" data-expiry="2025-01-30">Bordetella</option>
                                        <option value="personalizada" data-technical="" data-lab="" data-lot="" data-creation="" data-expiry="">Información personalizada</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="vaccine_technical_name" class="block text-sm font-semibold text-gray-900 mb-2">
                                        Nombre Técnico
                                    </label>
                                    <input type="text" name="vaccine_technical_name" id="vaccine_technical_name"
                                           value="{{ old('vaccine_technical_name', $appointment->vaccine_technical_name ?? '') }}"
                                           placeholder="Nombre técnico de la vacuna"
                                           class="block w-full px-4 py-3 border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm @error('vaccine_technical_name') border-red-300 @enderror">
                                    @error('vaccine_technical_name')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Información del laboratorio -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="vaccine_laboratory" class="block text-sm font-semibold text-gray-900 mb-2">
                                        Laboratorio
                                    </label>
                                    <input type="text" name="vaccine_laboratory" id="vaccine_laboratory"
                                           value="{{ old('vaccine_laboratory', $appointment->vaccine_laboratory ?? '') }}"
                                           placeholder="Ej: Vanguard Plus 5L4 CV"
                                           class="block w-full px-4 py-3 border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm @error('vaccine_laboratory') border-red-300 @enderror">
                                    @error('vaccine_laboratory')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="vaccine_lot" class="block text-sm font-semibold text-gray-900 mb-2">
                                        Número de Lote
                                    </label>
                                    <input type="text" name="vaccine_lot" id="vaccine_lot"
                                           value="{{ old('vaccine_lot', $appointment->vaccine_lot ?? '') }}"
                                           placeholder="Número de lote"
                                           class="block w-full px-4 py-3 border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm @error('vaccine_lot') border-red-300 @enderror">
                                    @error('vaccine_lot')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Fechas de creación y vencimiento -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="vaccine_creation_date" class="block text-sm font-semibold text-gray-900 mb-2">
                                        Fecha de Creación
                                    </label>
                                    <input type="date" name="vaccine_creation_date" id="vaccine_creation_date"
                                           value="{{ old('vaccine_creation_date', $appointment->vaccine_creation_date ?? '') }}"
                                           class="block w-full px-4 py-3 border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm @error('vaccine_creation_date') border-red-300 @enderror">
                                    @error('vaccine_creation_date')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="vaccine_expiry_date" class="block text-sm font-semibold text-gray-900 mb-2">
                                        Fecha de Vencimiento
                                    </label>
                                    <input type="date" name="vaccine_expiry_date" id="vaccine_expiry_date"
                                           value="{{ old('vaccine_expiry_date', $appointment->vaccine_expiry_date ?? '') }}"
                                           class="block w-full px-4 py-3 border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm @error('vaccine_expiry_date') border-red-300 @enderror">
                                    @error('vaccine_expiry_date')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Veterinario y ubicación -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="vet_name" class="block text-sm font-semibold text-gray-900 mb-2">
                                    Veterinario Responsable
                                </label>
                                <input type="text" name="vet_name" id="vet_name"
                                       value="{{ old('vet_name', Auth::user()->name) }}"
                                       readonly
                                       class="block w-full px-4 py-3 border border-gray-200 rounded-xl shadow-sm bg-gray-50 text-gray-600 text-sm @error('vet_name') border-red-300 @enderror">
                                @error('vet_name')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                                <p class="mt-2 text-sm text-gray-500 flex items-center">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    Prellenado con tu información de perfil
                                </p>
                            </div>
                            
                            <div>
                                <label for="location" class="block text-sm font-semibold text-gray-900 mb-2">
                                    Ubicación de la Cita
                                </label>
                                <input type="text" name="location" id="location"
                                       value="{{ old('location', Auth::user()->ubicacion ?? '') }}"
                                       placeholder="Ej: Clínica Veterinaria San Martín"
                                       readonly
                                       class="block w-full px-4 py-3 border border-gray-200 rounded-xl shadow-sm bg-gray-50 text-gray-600 text-sm @error('location') border-red-300 @enderror">
                                @error('location')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                                <p class="mt-2 text-sm text-gray-500 flex items-center">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Prellenado con tu ubicación de perfil. Puedes modificarla si es necesario.
                                </p>
                            </div>
                        </div>

                        <!-- Observaciones -->
                        <div>
                            <label for="observations" class="block text-sm font-semibold text-gray-900 mb-2">
                                Observaciones
                            </label>
                            <textarea name="observations" id="observations" rows="3"
                                      placeholder="Observaciones adicionales sobre la cita..."
                                      class="block w-full px-4 py-3 border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm @error('observations') border-red-300 @enderror">{{ old('observations', $appointment->observations) }}</textarea>
                            @error('observations')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Diagnóstico y Tratamiento (unificado) -->
                        <div>
                            <label for="diagnosis" class="block text-sm font-semibold text-gray-900 mb-2">
                                Diagnóstico y Tratamiento
                            </label>
                            <textarea name="diagnosis" id="diagnosis" rows="4"
                                      placeholder="Diagnóstico médico y tratamiento prescrito..."
                                      class="block w-full px-4 py-3 border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm @error('diagnosis') border-red-300 @enderror">{{ old('diagnosis', $appointment->diagnosis) }}</textarea>
                            @error('diagnosis')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                            <p class="mt-2 text-sm text-gray-500 flex items-center">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Incluye tanto el diagnóstico como el tratamiento en un solo campo
                            </p>
                        </div>

                        <!-- Próxima fecha -->
                        <div>
                            <label for="next_date" class="block text-sm font-semibold text-gray-900 mb-2">
                                Próxima Fecha Programada
                            </label>
                            <input type="date" name="next_date" id="next_date"
                                   value="{{ old('next_date', $appointment->next_date ? ($appointment->next_date instanceof \Carbon\Carbon ? $appointment->next_date->format('Y-m-d') : \Carbon\Carbon::parse($appointment->next_date)->format('Y-m-d')) : '') }}"
                                   class="block w-full px-4 py-3 border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm @error('next_date') border-red-300 @enderror">
                            @error('next_date')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                            <p class="mt-2 text-sm text-gray-500 flex items-center">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Opcional: Fecha para la próxima cita o seguimiento
                            </p>
                        </div>

                        <!-- Botones -->
                        <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-100">
                            <a href="{{ route('dashboard.veterinario.calendario.show', $appointment->id) }}" 
                               class="inline-flex items-center px-6 py-3 border border-gray-200 text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Cancelar
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-xl text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
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
            
            // Todos los campos de vacuna son editables (excepto el selector)
            vaccineTechnicalNameInput.readOnly = false;
            vaccineLaboratoryInput.readOnly = false;
            vaccineLotInput.readOnly = false;
            vaccineCreationDateInput.readOnly = false;
            vaccineExpiryDateInput.readOnly = false;
            
            // Cambiar estilo visual para indicar que están precargados pero editables
            [vaccineTechnicalNameInput, vaccineLaboratoryInput, vaccineLotInput, vaccineCreationDateInput, vaccineExpiryDateInput].forEach(input => {
                input.classList.add('bg-blue-50', 'border-blue-200');
                input.classList.remove('bg-white', 'border-gray-200');
            });
        } else if (selectedOption.value === 'personalizada') {
            // Limpiar campos y habilitar edición
            vaccineTechnicalNameInput.value = '';
            vaccineLaboratoryInput.value = '';
            vaccineLotInput.value = '';
            vaccineCreationDateInput.value = '';
            vaccineExpiryDateInput.value = '';
            
            // Habilitar campos para edición
            vaccineTechnicalNameInput.readOnly = false;
            vaccineLaboratoryInput.readOnly = false;
            vaccineLotInput.readOnly = false;
            vaccineCreationDateInput.readOnly = false;
            vaccineExpiryDateInput.readOnly = false;
            
            // Restaurar estilo normal
            [vaccineTechnicalNameInput, vaccineLaboratoryInput, vaccineLotInput, vaccineCreationDateInput, vaccineExpiryDateInput].forEach(input => {
                input.classList.remove('bg-blue-50', 'border-blue-200');
                input.classList.add('bg-white', 'border-gray-200');
            });
        } else {
            // Opción vacía - limpiar y deshabilitar
            vaccineTechnicalNameInput.value = '';
            vaccineLaboratoryInput.value = '';
            vaccineLotInput.value = '';
            vaccineCreationDateInput.value = '';
            vaccineExpiryDateInput.value = '';
            
            // Restaurar estilo normal
            [vaccineTechnicalNameInput, vaccineLaboratoryInput, vaccineLotInput, vaccineCreationDateInput, vaccineExpiryDateInput].forEach(input => {
                input.classList.remove('bg-blue-50', 'border-blue-200');
                input.classList.add('bg-white', 'border-gray-200');
            });
        }
    }
    
    // Event listeners
    recordTypeSelect.addEventListener('change', toggleVaccineSection);
    vaccineTypeSelect.addEventListener('change', populateVaccineFields);
    
    // Ejecutar al cargar la página
    toggleVaccineSection();
    populateVaccineFields();
});
</script>
@endsection
