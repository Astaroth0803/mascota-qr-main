<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
            <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                {{ __('Historial Médico') }}
            </h2>
        </div>
    </x-slot>

    {{-- Incluir el sidebar como componente --}}
    <x-sidebar-menu />

    {{-- Contenido principal con margen responsive para el sidebar --}}
    <div class="lg:ml-64 transition-all duration-300 ease-in-out">
        <div class="min-h-screen bg-gray-50">
            <div class="p-4 sm:p-6 lg:p-8">
                <div class="mb-4 sm:mb-6">
                    <a href="{{ route('dashboard.cliente.mascotas.show', $pet->id) }}"
                       class="flex items-center text-sm text-blue-600 hover:text-blue-800">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <span class="hidden sm:inline">Volver a detalles de {{ $pet->nombre }}</span>
                        <span class="sm:hidden">Volver</span>
                    </a>
                </div>

                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    {{-- Header de la mascota --}}
                    <div class="px-4 sm:px-6 py-4 sm:py-6 bg-gradient-to-r from-orange-500 to-orange-600">
                        <div class="flex flex-col sm:flex-row items-center">
                            <div class="flex-shrink-0 bg-white p-2 rounded-full mb-3 sm:mb-0">
                                @if($pet->profile_image)
                                    <img src="{{ Storage::url($pet->profile_image) }}"
                                         alt="{{ $pet->nombre }}"
                                         class="w-10 h-10 sm:w-12 sm:h-12 rounded-full object-cover">
                                @else
                                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-orange-100 flex items-center justify-center">
                                        <svg class="w-6 h-6 sm:w-8 sm:h-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="text-center sm:text-left sm:ml-4 text-white">
                                <h2 class="text-xl sm:text-2xl font-bold">{{ $pet->nombre }}</h2>
                                <p class="text-sm sm:text-base text-orange-100">{{ $pet->especie }} - {{ $pet->raza }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Pestañas de navegación --}}
                    <div class="border-b border-gray-200">
                        <nav class="-mb-px flex space-x-4 sm:space-x-8 px-4 sm:px-6 overflow-x-auto" aria-label="Tabs">
                            <button
                                class="historyTab border-orange-500 text-orange-600 whitespace-nowrap py-3 sm:py-4 px-1 border-b-2 font-medium text-sm active"
                                data-tab="historyTab">
                                <span class="hidden sm:inline">Historial Médico</span>
                                <span class="sm:hidden">Historial</span>
                            </button>
                            <button
                                class="addRecordTab border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-3 sm:py-4 px-1 border-b-2 font-medium text-sm"
                                data-tab="addRecordTab">
                                <span class="hidden sm:inline">Agregar Registro</span>
                                <span class="sm:hidden">Agregar</span>
                            </button>
                        </nav>
                    </div>

                    {{-- Contenido: Historial Médico --}}
                    <div id="historyTab" class="px-4 sm:px-6 py-4 sm:py-6">
                        @if($pet->vaccinationRecords->isEmpty())
                            <div class="text-center py-6 sm:py-8">
                                <svg class="mx-auto h-10 w-10 sm:h-12 sm:w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <h3 class="mt-2 text-sm sm:text-base font-medium text-gray-900">No hay registros médicos</h3>
                                <p class="mt-1 text-xs sm:text-sm text-gray-500">Comienza agregando el primer registro médico de tu mascota.</p>
                                <div class="mt-4 sm:mt-6">
                                    <button type="button"
                                            class="showAddRecord inline-flex items-center px-3 sm:px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                        <svg class="-ml-1 mr-2 h-4 w-4 sm:h-5 sm:w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                        <span class="hidden sm:inline">Agregar registro médico</span>
                                        <span class="sm:hidden">Agregar registro</span>
                                    </button>
                                </div>
                            </div>
                        @else
                            <div class="space-y-4 sm:space-y-8">
                                @foreach($pet->vaccinationRecords->sortByDesc('date') as $record)
                                    <div class="bg-gray-50 rounded-lg p-3 sm:p-4 border border-gray-200 hover:shadow-md transition-shadow duration-200">
                                        <div class="flex flex-col lg:flex-row lg:justify-between lg:items-start space-y-3 lg:space-y-0">
                                            <div class="flex-1">
                                                <div class="flex items-start">
                                                    {{-- Icono según tipo de registro --}}
                                                    @if($record->record_type == 'vacuna')
                                                        <span class="flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-green-100 text-green-500 mr-3 flex-shrink-0">
                                                            <svg class="w-4 h-4 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                                            </svg>
                                                        </span>
                                                        <h3 class="text-base sm:text-lg font-semibold text-gray-900">Vacuna: {{ $record->vaccine_name }}</h3>
                                                    @elseif($record->record_type == 'checkeo')
                                                        <span class="flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-blue-100 text-blue-500 mr-3 flex-shrink-0">
                                                            <svg class="w-4 h-4 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                                            </svg>
                                                        </span>
                                                        <h3 class="text-base sm:text-lg font-semibold text-gray-900">Cita de control</h3>
                                                    @elseif($record->record_type == 'peluqueria')
                                                        <span class="flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-purple-100 text-purple-500 mr-3 flex-shrink-0">
                                                            <svg class="w-4 h-4 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                                            </svg>
                                                        </span>
                                                        <h3 class="text-base sm:text-lg font-semibold text-gray-900">Peluquería/Estética</h3>
                                                    @elseif($record->record_type == 'operacion')
                                                        <span class="flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-red-100 text-red-500 mr-3 flex-shrink-0">
                                                            <svg class="w-4 h-4 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                        </span>
                                                        <h3 class="text-base sm:text-lg font-semibold text-gray-900">Operación/Cirugía</h3>
                                                    @else
                                                        <span class="flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-gray-100 text-gray-500 mr-3 flex-shrink-0">
                                                            <svg class="w-4 h-4 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                            </svg>
                                                        </span>
                                                        <h3 class="text-base sm:text-lg font-semibold text-gray-900">Registro médico</h3>
                                                    @endif
                                                </div>
                                                <div class="mt-2 sm:mt-3 space-y-1">
                                                    <p class="text-xs sm:text-sm text-gray-500">
                                                        Fecha: {{ $record->date->format('d/m/Y') }} a las {{ \Carbon\Carbon::parse($record->time)->format('H:i') }}
                                                    </p>
                                                    @if($record->vet_name)
                                                        <p class="text-xs sm:text-sm text-gray-500">Veterinario: {{ $record->vet_name }}</p>
                                                    @endif
                                                    @if($record->location)
                                                        <p class="text-xs sm:text-sm text-gray-500">Lugar: {{ $record->location }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            @if($record->document_path)
                                                <div class="flex-shrink-0">
                                                    <a href="{{ Storage::url($record->document_path) }}" target="_blank" class="inline-flex items-center px-2 sm:px-3 py-1 sm:py-2 border border-transparent text-xs sm:text-sm leading-5 font-medium rounded-md text-white bg-orange-600 hover:bg-orange-500 focus:outline-none focus:border-orange-700 focus:shadow-outline-orange active:bg-orange-700 transition ease-in-out duration-150">
                                                        <svg class="-ml-1 mr-1 sm:mr-2 h-3 w-3 sm:h-4 sm:w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122" />
                                                        </svg>
                                                        <span class="hidden sm:inline">Ver documento</span>
                                                        <span class="sm:hidden">Documento</span>
                                                    </a>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="mt-4 sm:mt-6 space-y-3">
                                            {{-- Campos específicos según tipo --}}
                                            @if($record->record_type == 'vacuna')
                                                @if($record->next_date)
                                                    <div class="bg-yellow-50 border border-yellow-200 rounded-md p-3">
                                                        <div class="flex">
                                                            <div class="flex-shrink-0">
                                                                <svg class="h-4 w-4 sm:h-5 sm:w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                </svg>
                                                            </div>
                                                            <div class="ml-3">
                                                                <h3 class="text-xs sm:text-sm font-medium text-yellow-800">Próxima vacunación</h3>
                                                                <div class="mt-1 sm:mt-2 text-xs sm:text-sm text-yellow-700">
                                                                    <p>{{ $record->next_date->format('d/m/Y') }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif

                                            @if($record->diagnosis)
                                                <div class="rounded-md bg-white p-3 border border-gray-200">
                                                    <h4 class="text-xs sm:text-sm font-medium text-gray-900">Diagnóstico:</h4>
                                                    <p class="mt-1 text-xs sm:text-sm text-gray-600">{{ $record->diagnosis }}</p>
                                                </div>
                                            @endif

                                            @if($record->treatment)
                                                <div class="rounded-md bg-white p-3 border border-gray-200">
                                                    <h4 class="text-xs sm:text-sm font-medium text-gray-900">Tratamiento:</h4>
                                                    <p class="mt-1 text-xs sm:text-sm text-gray-600">{{ $record->treatment }}</p>
                                                </div>
                                            @endif

                                            @if($record->observations)
                                                <div class="rounded-md bg-white p-3 border border-gray-200">
                                                    <h4 class="text-xs sm:text-sm font-medium text-gray-900">Observaciones:</h4>
                                                    <p class="mt-1 text-xs sm:text-sm text-gray-600">{{ $record->observations }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Contenido: Formulario para agregar registro --}}
                    <div id="addRecordTab" class="px-4 sm:px-6 py-4 sm:py-6 hidden">
                        <form action="{{ route('dashboard.cliente.mascotas.vaccination-records.store', $pet->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4 sm:space-y-6">
                            @csrf

                            {{-- Tipo de registro --}}
                            <div>
                                <label for="record_type" class="block text-sm font-medium text-gray-700">Tipo de registro</label>
                                <select id="record_type" name="record_type" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm rounded-md">
                                    @php
                                        // Definir los tipos de registro directamente en la vista si no están disponibles
                                        if (!isset($recordTypes)) {
                                            $recordTypes = [
                                                'vacuna' => 'Vacunación',
                                                'checkeo' => 'Cita de control',
                                                'peluqueria' => 'Peluquería/Estética',
                                                'operacion' => 'Operación/Cirugía'
                                            ];
                                        }
                                    @endphp
                                    @foreach($recordTypes as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Campos comunes para todos los tipos --}}
                            <div class="grid grid-cols-1 gap-y-4 sm:gap-y-6 gap-x-4 sm:grid-cols-6">
                                <div class="sm:col-span-3">
                                    <label for="date" class="block text-sm font-medium text-gray-700">Fecha <span class="text-red-500">*</span></label>
                                    <input type="date" name="date" id="date" value="{{ date('Y-m-d') }}" required class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="time" class="block text-sm font-medium text-gray-700">Hora <span class="text-red-500">*</span></label>
                                    <input type="time" name="time" id="time" value="{{ date('H:i') }}" required class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('time')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="vet_name" class="block text-sm font-medium text-gray-700">Nombre del veterinario <span class="text-red-500">*</span></label>
                                    <input type="text" name="vet_name" id="vet_name" required maxlength="255" class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('vet_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="sm:col-span-3">
                                    <label for="location" class="block text-sm font-medium text-gray-700">Lugar <span class="text-red-500">*</span></label>
                                    <input type="text" name="location" id="location" required maxlength="255" class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('location')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Campos dinámicos según el tipo de registro --}}
                            <div id="dynamic-fields">
                                {{-- Se cargarán dinámicamente según el tipo seleccionado --}}
                                {{-- Campos para vacuna (predeterminados) --}}
                                <div id="vaccine-fields" class="space-y-4 sm:space-y-6">
                                    <div>
                                        <label for="vaccine_name" class="block text-sm font-medium text-gray-700">Nombre de la vacuna <span class="text-red-500">*</span></label>
                                        <input type="text" name="vaccine_name" id="vaccine_name" required maxlength="255" class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        @error('vaccine_name')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="next_date" class="block text-sm font-medium text-gray-700">Fecha de próxima vacunación</label>
                                        <input type="date" name="next_date" id="next_date" min="{{ date('Y-m-d') }}" class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        @error('next_date')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Observaciones (común para todos) --}}
                            <div>
                                <label for="observations" class="block text-sm font-medium text-gray-700">Observaciones</label>
                                <textarea name="observations" id="observations" rows="3" class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                                @error('observations')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Documento o evidencia --}}
                            <div>
                                <label for="document" class="block text-sm font-medium text-gray-700">Documento o evidencia (opcional)</label>
                                <div class="mt-1 flex justify-center px-4 sm:px-6 pt-4 sm:pt-5 pb-4 sm:pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-8 w-8 sm:h-12 sm:w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex flex-col sm:flex-row text-sm text-gray-600">
                                            <label for="document" class="relative cursor-pointer bg-white rounded-md font-medium text-orange-600 hover:text-orange-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-orange-500">
                                                <span>Subir un archivo</span>
                                                <input id="document" name="document" type="file" accept=".pdf,.jpg,.jpeg,.png" class="sr-only">
                                            </label>
                                            <p class="mt-1 sm:mt-0 sm:pl-1">o arrastrar y soltar</p>
                                        </div>
                                        <p class="text-xs text-gray-500">PDF, JPG, JPEG o PNG hasta 10MB</p>
                                    </div>
                                </div>
                                @error('document')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                    <span class="hidden sm:inline">Guardar registro</span>
                                    <span class="sm:hidden">Guardar</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Script para manejar las pestañas
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('[data-tab]');
            const contents = document.querySelectorAll('#historyTab, #addRecordTab');
            const showAddRecordButton = document.querySelector('.showAddRecord');
            const recordTypeSelect = document.getElementById('record_type');
            const dynamicFields = document.getElementById('dynamic-fields');

            // Función para cambiar las pestañas
            function switchTab(tabId) {
                tabs.forEach(tab => {
                    if (tab.dataset.tab === tabId) {
                        tab.classList.add('border-orange-500', 'text-orange-600');
                        tab.classList.remove('border-transparent', 'text-gray-500');
                    } else {
                        tab.classList.remove('border-orange-500', 'text-orange-600');
                        tab.classList.add('border-transparent', 'text-gray-500');
                    }
                });

                contents.forEach(content => {
                    if (content.id === tabId) {
                        content.classList.remove('hidden');
                    } else {
                        content.classList.add('hidden');
                    }
                });
            }

            // Evento para cambiar entre pestañas
            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    switchTab(tab.dataset.tab);
                });
            });

            // Evento para el botón de mostrar formulario
            if (showAddRecordButton) {
                showAddRecordButton.addEventListener('click', () => {
                    switchTab('addRecordTab');
                });
            }

            // Función para actualizar los campos dinámicos según el tipo de registro
            function updateDynamicFields(recordType) {
                let fieldsHTML = '';

                switch(recordType) {
                    case 'vacuna':
                        fieldsHTML = `
                            <div id="vaccine-fields" class="space-y-4 sm:space-y-6">
                                <div>
                                    <label for="vaccine_name" class="block text-sm font-medium text-gray-700">Nombre de la vacuna <span class="text-red-500">*</span></label>
                                    <input type="text" name="vaccine_name" id="vaccine_name" required maxlength="255" class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('vaccine_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="next_date" class="block text-sm font-medium text-gray-700">Fecha de próxima vacunación</label>
                                    <input type="date" name="next_date" id="next_date" min="{{ date('Y-m-d') }}" class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('next_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        `;
                        break;

                    case 'checkeo':
                        fieldsHTML = `
                            <div id="checkup-fields" class="space-y-4 sm:space-y-6">
                                <div>
                                    <label for="diagnosis" class="block text-sm font-medium text-gray-700">Diagnóstico</label>
                                    <textarea name="diagnosis" id="diagnosis" rows="3" class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                                    @error('diagnosis')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="treatment" class="block text-sm font-medium text-gray-700">Tratamiento prescrito</label>
                                    <textarea name="treatment" id="treatment" rows="3" class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                                    @error('treatment')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="next_date" class="block text-sm font-medium text-gray-700">Fecha de próxima cita</label>
                                    <input type="date" name="next_date" id="next_date" min="{{ date('Y-m-d') }}" class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('next_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        `;
                        break;

                    case 'peluqueria':
                        fieldsHTML = `
                            <div id="grooming-fields" class="space-y-4 sm:space-y-6">
                                <div>
                                    <label for="observations" class="block text-sm font-medium text-gray-700">Detalles del servicio <span class="text-red-500">*</span></label>
                                    <textarea name="observations" id="observations" rows="3" required class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                                    @error('observations')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="next_date" class="block text-sm font-medium text-gray-700">Fecha del próximo servicio</label>
                                    <input type="date" name="next_date" id="next_date" min="{{ date('Y-m-d') }}" class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('next_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        `;
                        break;

                    case 'operacion':
                        fieldsHTML = `
                            <div id="surgery-fields" class="space-y-4 sm:space-y-6">
                                <div>
                                    <label for="diagnosis" class="block text-sm font-medium text-gray-700">Diagnóstico/Motivo de la cirugía <span class="text-red-500">*</span></label>
                                    <textarea name="diagnosis" id="diagnosis" rows="3" required class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                                    @error('diagnosis')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="treatment" class="block text-sm font-medium text-gray-700">Procedimiento realizado y cuidados posteriores <span class="text-red-500">*</span></label>
                                    <textarea name="treatment" id="treatment" rows="3" required class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                                    @error('treatment')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="next_date" class="block text-sm font-medium text-gray-700">Fecha de control post-operatorio</label>
                                    <input type="date" name="next_date" id="next_date" min="{{ date('Y-m-d') }}" class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('next_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        `;
                        break;
                }

                dynamicFields.innerHTML = fieldsHTML;
            }

            // Inicializar campos dinámicos con el tipo predeterminado
            updateDynamicFields(recordTypeSelect.value);

            // Cambiar campos cuando cambie el tipo de registro
            recordTypeSelect.addEventListener('change', function() {
                updateDynamicFields(this.value);
            });

            // Validación adicional para fechas futuras
            document.addEventListener('change', function(e) {
                if (e.target.name === 'next_date') {
                    const selectedDate = new Date(e.target.value);
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);
                    
                    if (selectedDate < today) {
                        alert('La fecha de próxima cita debe ser posterior a hoy.');
                        e.target.value = '';
                    }
                }
            });

            // Validación del formulario antes de enviar
            document.querySelector('form').addEventListener('submit', function(e) {
                const recordType = recordTypeSelect.value;
                let isValid = true;
                let errorMessage = '';

                // Validar campos requeridos según el tipo
                switch(recordType) {
                    case 'vacuna':
                        const vaccineName = document.getElementById('vaccine_name');
                        if (!vaccineName.value.trim()) {
                            isValid = false;
                            errorMessage = 'El nombre de la vacuna es obligatorio.';
                        }
                        break;
                    case 'peluqueria':
                        const observations = document.getElementById('observations');
                        if (!observations.value.trim()) {
                            isValid = false;
                            errorMessage = 'Los detalles del servicio son obligatorios.';
                        }
                        break;
                    case 'operacion':
                        const diagnosis = document.getElementById('diagnosis');
                        const treatment = document.getElementById('treatment');
                        if (!diagnosis.value.trim()) {
                            isValid = false;
                            errorMessage = 'El diagnóstico es obligatorio.';
                        } else if (!treatment.value.trim()) {
                            isValid = false;
                            errorMessage = 'El tratamiento es obligatorio.';
                        }
                        break;
                }

                if (!isValid) {
                    e.preventDefault();
                    alert(errorMessage);
                }
            });
        });
    </script>
</x-app-layout>
