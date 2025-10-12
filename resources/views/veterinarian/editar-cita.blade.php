@extends('layouts.standard')

@section('title', 'Editar Cita')

@php
    $title = 'Editar Cita';
    $subtitle = $appointment->pet->nombre . ' - ' . ucfirst($appointment->record_type);
@endphp

@section('main-content')
<div class="w-full">
    <!-- Header con acciones -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4">
<div>
            <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white">Editar Cita</h1>
            <p class="text-gray-600 dark:text-gray-300 mt-1">{{ $appointment->pet->nombre }} - {{ ucfirst($appointment->record_type) }}</p>
        </div>
        
    <!-- Acciones rápidas -->
        <div class="flex items-center space-x-2">
            <a href="{{ route('dashboard.veterinario.calendario.show', $appointment->id) }}" 
               class="inline-flex items-center px-3 py-2 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
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

    <!-- Formulario principal con layout estructurado -->
    <div class="w-full max-w-none">
        <form action="{{ route('dashboard.veterinario.calendario.update', $appointment->id) }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')
            
            <!-- INFORMACIÓN DE LA MASCOTA -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-8">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">INFORMACIÓN DE LA MASCOTA</h2>
                </div>
                
                <!-- Imagen/Resumen de la mascota -->
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-6 mb-6">
                    <div class="flex items-center space-x-4">
                        <div class="w-16 h-16 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            </div>
                            <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $appointment->pet->nombre }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-300">{{ $appointment->pet->especie }} - {{ $appointment->pet->user->name ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Datos de la mascota (no editables)</p>
                        </div>
                    </div>
                </div>

                <!-- Grid de información de la mascota -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                    <!-- Especie -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Especie</label>
                        <input type="text" value="{{ $appointment->pet->especie }}" readonly
                               class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-sm text-gray-500 dark:text-gray-400">
                    </div>

                    <!-- Raza -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Raza</label>
                        <input type="text" value="{{ $appointment->pet->raza ?? 'No especificada' }}" readonly
                               class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-sm text-gray-500 dark:text-gray-400">
                    </div>

                    <!-- Edad -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Edad</label>
                        <input type="text" value="{{ $appointment->pet->edad ?? 'No especificada' }}" readonly
                               class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-sm text-gray-500 dark:text-gray-400">
                    </div>

                    <!-- Sexo -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Sexo</label>
                        <input type="text" value="{{ $appointment->pet->sexo ?? 'No especificado' }}" readonly
                               class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-sm text-gray-500 dark:text-gray-400">
                    </div>

                    <!-- Peso Actual -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Peso Actual (kg)</label>
                        <input type="number" name="pet_weight" id="pet_weight" step="0.01" min="0" max="999.99"
                               value="{{ old('pet_weight', $appointment->pet->peso) }}"
                               placeholder="Ej: 15.5"
                               class="block w-full px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white @error('pet_weight') border-red-300 @enderror">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Solo veterinarios pueden actualizar</p>
                        @error('pet_weight')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                {{ $message }}
                            </p>
                        @enderror
                                </div>

                    <!-- Propietario -->
                                <div>
                        <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Propietario</label>
                        <input type="text" value="{{ $appointment->pet->user->name ?? 'N/A' }}" readonly
                               class="block w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl text-sm text-gray-500 dark:text-gray-400">
                                </div>
                            </div>
                        </div>

            <!-- INFORMACIÓN DE LA CITA -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-8">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">INFORMACIÓN DE LA CITA</h2>
                </div>

                <!-- Primera fila: 6 campos -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-6 mb-6">
                        <div>
                        <label for="record_type" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                                Tipo de Cita <span class="text-red-500">*</span>
                            </label>
                            <select name="record_type" id="record_type" required
                                class="block w-full px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white @error('record_type') border-red-300 @enderror">
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

                        <div>
                        <label for="date" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                            Fecha <span class="text-red-500">*</span>
                            </label>
                        <input type="date" name="date" id="date" required
                               value="{{ old('date', $appointment->scheduled_datetime ? $appointment->scheduled_datetime->format('Y-m-d') : $appointment->requested_datetime->format('Y-m-d')) }}"
                               class="block w-full px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white @error('date') border-red-300 @enderror">
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
                        <label for="time" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                            Hora <span class="text-red-500">*</span>
                                </label>
                        <input type="time" name="time" id="time" required
                               value="{{ old('time', $appointment->scheduled_datetime ? $appointment->scheduled_datetime->format('H:i') : $appointment->requested_datetime->format('H:i')) }}"
                               class="block w-full px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white @error('time') border-red-300 @enderror">
                        @error('time')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            
                            <div>
                        <label for="vet_name" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                            Veterinario
                        </label>
                        <input type="text" name="vet_name" id="vet_name" 
                               value="{{ old('vet_name', $appointment->vet_name ?? Auth::user()->name) }}"
                               class="block w-full px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white @error('vet_name') border-red-300 @enderror">
                        @error('vet_name')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="location" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                            Ubicación
                                </label>
                        <input type="text" name="location" id="location" 
                               value="{{ old('location', $appointment->location ?? Auth::user()->ubicacion ?? '') }}"
                               placeholder="Ej: Clínica Veterinaria Central"
                               class="block w-full px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white @error('location') border-red-300 @enderror">
                        @error('location')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                <!-- Segunda fila: campos adicionales -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div>
                        <label for="diagnosis_treatment" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                            Diagnóstico/Tratamiento
                        </label>
                        <input type="text" name="diagnosis_treatment" id="diagnosis_treatment" 
                               value="{{ old('diagnosis_treatment', $appointment->diagnosis_treatment ?? '') }}"
                               placeholder="Diagnóstico o tratamiento"
                               class="block w-full px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white @error('diagnosis_treatment') border-red-300 @enderror">
                        @error('diagnosis_treatment')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="observations" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                            Observaciones
                        </label>
                        <textarea name="observations" id="observations" rows="4"
                                  placeholder="Observaciones adicionales sobre la cita..."
                                  class="block w-full px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white resize-none @error('observations') border-red-300 @enderror">{{ old('observations', $appointment->observations ?? '') }}</textarea>
                        @error('observations')
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

            <!-- REGISTROS MÉDICOS (solo para veterinarios) -->
            @if(auth()->user()->hasRole('veterinario'))
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-8">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">REGISTROS MÉDICOS</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-300">Información médica detallada de la mascota</p>
                    </div>
                </div>

                <div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-4 mb-6">
                    <p class="text-sm text-green-700 dark:text-green-300">Esta sección es exclusiva para veterinarios. Aquí puedes registrar información médica detallada de la mascota.</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <!-- Tipo de Registro Médico -->
                    <div>
                        <label for="medical_record_type" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                            Tipo de Registro <span class="text-red-500">*</span>
                        </label>
                        <select name="medical_record_type" id="medical_record_type"
                                class="block w-full px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white @error('medical_record_type') border-red-300 @enderror">
                            <option value="">Seleccionar tipo...</option>
                            <option value="vacuna" {{ old('medical_record_type') == 'vacuna' ? 'selected' : '' }}>Vacunación</option>
                            <option value="checkeo" {{ old('medical_record_type') == 'checkeo' ? 'selected' : '' }}>Cita de control</option>
                            <option value="operacion" {{ old('medical_record_type') == 'operacion' ? 'selected' : '' }}>Operación/Cirugía</option>
                            <option value="emergencia" {{ old('medical_record_type') == 'emergencia' ? 'selected' : '' }}>Emergencia</option>
                            <option value="peluqueria" {{ old('medical_record_type') == 'peluqueria' ? 'selected' : '' }}>Peluquería/Estética</option>
                            <option value="dental" {{ old('medical_record_type') == 'dental' ? 'selected' : '' }}>Consulta dental</option>
                            <option value="dermatologia" {{ old('medical_record_type') == 'dermatologia' ? 'selected' : '' }}>Dermatología</option>
                            <option value="neurologia" {{ old('medical_record_type') == 'neurologia' ? 'selected' : '' }}>Neurología</option>
                            <option value="cardiologia" {{ old('medical_record_type') == 'cardiologia' ? 'selected' : '' }}>Cardiología</option>
                        </select>
                        @error('medical_record_type')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Fecha del Procedimiento -->
                    <div>
                        <label for="medical_date" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                            Fecha del Procedimiento <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="medical_date" id="medical_date"
                               value="{{ old('medical_date', now()->format('Y-m-d')) }}"
                               class="block w-full px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white @error('medical_date') border-red-300 @enderror">
                        @error('medical_date')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Hora del Procedimiento -->
                    <div>
                        <label for="medical_time" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                            Hora del Procedimiento
                        </label>
                        <input type="time" name="medical_time" id="medical_time"
                               value="{{ old('medical_time', now()->format('H:i')) }}"
                               class="block w-full px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white @error('medical_time') border-red-300 @enderror">
                        @error('medical_time')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Próxima Fecha -->
                    <div>
                        <label for="next_medical_date" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                            Próxima Fecha
                        </label>
                        <input type="date" name="next_medical_date" id="next_medical_date"
                               value="{{ old('next_medical_date') }}"
                               class="block w-full px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white @error('next_medical_date') border-red-300 @enderror">
                        @error('next_medical_date')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                <!-- Campos específicos por tipo de registro -->
                <div id="vaccine_medical_fields" class="mt-6" style="display: none;">
                    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-4 mb-6">
                        <p class="text-sm text-blue-700 dark:text-blue-300">Información específica para vacunación</p>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        <!-- Nombre de la Vacuna -->
                        <div>
                            <label for="vaccine_name" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                                Nombre de la Vacuna <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="vaccine_name" id="vaccine_name"
                                   value="{{ old('vaccine_name') }}"
                                   placeholder="Ej: Pentavalente"
                                   class="block w-full px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white @error('vaccine_name') border-red-300 @enderror">
                            @error('vaccine_name')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Nombre Técnico -->
                        <div>
                            <label for="technical_name" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                                Nombre Técnico
                            </label>
                            <input type="text" name="technical_name" id="technical_name"
                                   value="{{ old('technical_name') }}"
                                   placeholder="Nombre técnico de la vacuna"
                                   class="block w-full px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white @error('technical_name') border-red-300 @enderror">
                            @error('technical_name')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Laboratorio -->
                        <div>
                            <label for="laboratory" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                                Laboratorio
                            </label>
                            <input type="text" name="laboratory" id="laboratory"
                                   value="{{ old('laboratory') }}"
                                   placeholder="Ej: Zoetis, Merial"
                                   class="block w-full px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white @error('laboratory') border-red-300 @enderror">
                            @error('laboratory')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Número de Lote -->
                        <div>
                            <label for="lot_number" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                                Número de Lote
                            </label>
                            <input type="text" name="lot_number" id="lot_number"
                                   value="{{ old('lot_number') }}"
                                   placeholder="Número de lote"
                                   class="block w-full px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white @error('lot_number') border-red-300 @enderror">
                            @error('lot_number')
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

                <!-- Campos para diagnóstico y tratamiento -->
                <div id="diagnosis_treatment_fields" class="mt-6" style="display: none;">
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-xl p-4 mb-6">
                        <p class="text-sm text-yellow-700 dark:text-yellow-300">Información médica detallada</p>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Diagnóstico -->
                        <div>
                            <label for="medical_diagnosis" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                                Diagnóstico
                            </label>
                            <textarea name="medical_diagnosis" id="medical_diagnosis" rows="4"
                                      placeholder="Diagnóstico médico detallado..."
                                      class="block w-full px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white resize-none @error('medical_diagnosis') border-red-300 @enderror">{{ old('medical_diagnosis') }}</textarea>
                            @error('medical_diagnosis')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Tratamiento -->
                        <div>
                            <label for="medical_treatment" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                                Tratamiento
                            </label>
                            <textarea name="medical_treatment" id="medical_treatment" rows="4"
                                      placeholder="Tratamiento prescrito..."
                                      class="block w-full px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white resize-none @error('medical_treatment') border-red-300 @enderror">{{ old('medical_treatment') }}</textarea>
                            @error('medical_treatment')
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

                <!-- Observaciones médicas -->
                <div class="mt-6">
                    <label for="medical_observations" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                        Observaciones Médicas
                    </label>
                    <textarea name="medical_observations" id="medical_observations" rows="4"
                              placeholder="Observaciones adicionales sobre el procedimiento médico..."
                              class="block w-full px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white resize-none @error('medical_observations') border-red-300 @enderror">{{ old('medical_observations') }}</textarea>
                    @error('medical_observations')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Documento adjunto -->
                <div class="mt-6">
                    <label for="medical_document" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                        Documento Adjunto
                    </label>
                    <input type="file" name="medical_document" id="medical_document"
                           accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                           class="block w-full px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white @error('medical_document') border-red-300 @enderror">
                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Formatos permitidos: PDF, JPG, PNG, DOC, DOCX (máx. 10MB)</p>
                    @error('medical_document')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>
            @endif

            <!-- INFORMACIÓN DE VACUNA (condicional) -->
            <div id="vaccine_section" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-8" style="display: none;">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                        </svg>
                                    </div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">INFORMACIÓN DE VACUNACIÓN</h2>
                                </div>

                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-4 mb-6">
                    <p class="text-sm text-blue-700 dark:text-blue-300">Selecciona una vacuna estándar o ingresa información personalizada. Todos los campos son editables.</p>
                            </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-6">
                            <!-- Vacunas estándar -->
                                <div>
                        <label for="vaccine_type" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                            Tipo de Vacuna
                                    </label>
                                    <select name="vaccine_type" id="vaccine_type"
                                class="block w-full px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
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

                    <!-- Nombre técnico -->
                                <div>
                        <label for="vaccine_technical_name" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                                        Nombre Técnico
                                    </label>
                                    <input type="text" name="vaccine_technical_name" id="vaccine_technical_name"
                                           value="{{ old('vaccine_technical_name', $appointment->vaccine_technical_name ?? '') }}"
                                           placeholder="Nombre técnico de la vacuna"
                               class="block w-full px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white @error('vaccine_technical_name') border-red-300 @enderror">
                                    @error('vaccine_technical_name')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                            </div>

                    <!-- Laboratorio -->
                                <div>
                        <label for="vaccine_laboratory" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                                        Laboratorio
                                    </label>
                                    <input type="text" name="vaccine_laboratory" id="vaccine_laboratory"
                                           value="{{ old('vaccine_laboratory', $appointment->vaccine_laboratory ?? '') }}"
                                           placeholder="Ej: Vanguard Plus 5L4 CV"
                               class="block w-full px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white @error('vaccine_laboratory') border-red-300 @enderror">
                                    @error('vaccine_laboratory')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                    <!-- Número de lote -->
                                <div>
                        <label for="vaccine_lot" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                                        Número de Lote
                                    </label>
                                    <input type="text" name="vaccine_lot" id="vaccine_lot"
                                           value="{{ old('vaccine_lot', $appointment->vaccine_lot ?? '') }}"
                                           placeholder="Número de lote"
                               class="block w-full px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white @error('vaccine_lot') border-red-300 @enderror">
                                    @error('vaccine_lot')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                            </div>

                    <!-- Fecha de creación -->
                                <div>
                        <label for="vaccine_creation_date" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                                        Fecha de Creación
                                    </label>
                                    <input type="date" name="vaccine_creation_date" id="vaccine_creation_date"
                               value="{{ old('vaccine_creation_date', $appointment->creation_date ? $appointment->creation_date->format('Y-m-d') : '') }}"
                               class="block w-full px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white @error('vaccine_creation_date') border-red-300 @enderror">
                                    @error('vaccine_creation_date')
                                        <p class="mt-2 text-sm text-red-600 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                    <!-- Fecha de vencimiento -->
                                <div>
                        <label for="vaccine_expiry_date" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                                        Fecha de Vencimiento
                                    </label>
                                    <input type="date" name="vaccine_expiry_date" id="vaccine_expiry_date"
                               value="{{ old('vaccine_expiry_date', $appointment->expiry_date ? $appointment->expiry_date->format('Y-m-d') : '') }}"
                               class="block w-full px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white @error('vaccine_expiry_date') border-red-300 @enderror">
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

            <!-- Botones de acción -->
            <div class="flex flex-col sm:flex-row items-center justify-end gap-4 pt-6">
                            <a href="{{ route('dashboard.veterinario.calendario.show', $appointment->id) }}" 
                   class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Cancelar
                            </a>
                            <button type="submit" 
                        class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Guardar Cambios
                            </button>
                        </div>
                </form>
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
        if (recordTypeSelect.value === 'vacunacion') {
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
    
    // Funcionalidad para registros médicos (solo para veterinarios)
    const medicalRecordTypeSelect = document.getElementById('medical_record_type');
    const vaccineMedicalFields = document.getElementById('vaccine_medical_fields');
    const diagnosisTreatmentFields = document.getElementById('diagnosis_treatment_fields');
    
    if (medicalRecordTypeSelect) {
        function toggleMedicalFields() {
            const selectedType = medicalRecordTypeSelect.value;
            
            // Ocultar todos los campos específicos
            if (vaccineMedicalFields) vaccineMedicalFields.style.display = 'none';
            if (diagnosisTreatmentFields) diagnosisTreatmentFields.style.display = 'none';
            
            // Mostrar campos según el tipo seleccionado
            if (selectedType === 'vacuna') {
                if (vaccineMedicalFields) vaccineMedicalFields.style.display = 'block';
            } else if (['checkeo', 'operacion', 'emergencia', 'dental', 'dermatologia', 'neurologia', 'cardiologia'].includes(selectedType)) {
                if (diagnosisTreatmentFields) diagnosisTreatmentFields.style.display = 'block';
            }
        }
        
        medicalRecordTypeSelect.addEventListener('change', toggleMedicalFields);
        
        // Ejecutar al cargar la página
        toggleMedicalFields();
    }
});
</script>
@endsection