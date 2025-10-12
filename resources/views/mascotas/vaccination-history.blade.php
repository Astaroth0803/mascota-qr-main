<x-app-layout>
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <div class="bg-white shadow-sm border-b border-gray-200 pt-16 lg:pt-0">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="py-4 lg:py-6">
                    <!-- Breadcrumb -->
                    <nav class="flex items-center space-x-2 text-sm text-gray-500 mb-4">
                        <a href="{{ route('dashboard.cliente.index') }}" class="hover:text-gray-700 transition-colors">Dashboard</a>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                        <a href="{{ route('dashboard.cliente.mascotas.show', $pet) }}" class="hover:text-gray-700 transition-colors">{{ $pet->nombre }}</a>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                        <span class="text-gray-900 font-medium">Historial Médico</span>
                    </nav>
                    
                    <!-- Título Principal -->
                    <div>
                        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Historial Médico</h1>
                        <p class="text-gray-600 mt-1">{{ $pet->nombre }} • {{ $pet->especie }} {{ $pet->raza }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenido principal -->
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
            
            <!-- Historial Médico -->
            @if($pet->vaccinationRecords->isEmpty())
                <!-- Estado vacío -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No hay registros médicos</h3>
                        <p class="text-gray-600 mb-6">Los registros médicos aparecerán aquí cuando un veterinario los agregue durante las citas.</p>
                        <a href="{{ route('dashboard.cliente.mascotas.show', $pet) }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Volver a detalles
                        </a>
                    </div>
                </div>
            @else
                <!-- Lista de registros -->
                <div class="space-y-4">
                    @foreach($pet->vaccinationRecords->sortByDesc('date') as $record)
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow duration-200">
                            <div class="flex items-start space-x-4">
                                <!-- Icono del tipo de registro -->
                                <div class="flex-shrink-0">
                                    @if($record->record_type == 'vacuna')
                                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                            </svg>
                                        </div>
                                    @elseif($record->record_type == 'checkeo')
                                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                            </svg>
                                        </div>
                                    @elseif($record->record_type == 'peluqueria')
                                        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H6l-2-2H5a2 2 0 00-2 2z" />
                                            </svg>
                                        </div>
                                    @elseif($record->record_type == 'operacion')
                                        <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                    @else
                                        <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Contenido del registro -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                                @if($record->record_type == 'vacuna')
                                                    Vacuna: {{ $record->vaccine_name }}
                                                @elseif($record->record_type == 'checkeo')
                                                    Cita de control
                                                @elseif($record->record_type == 'peluqueria')
                                                    Peluquería/Estética
                                                @elseif($record->record_type == 'operacion')
                                                    Operación/Cirugía
                                                @else
                                                    Registro médico
                                                @endif
                                            </h3>
                                            
                                            <!-- Información básica -->
                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                                <div class="flex items-center text-sm text-gray-600">
                                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    {{ $record->date->format('d/m/Y') }}@if($record->time) - {{ \Carbon\Carbon::parse($record->time)->format('H:i') }}@endif
                                                </div>
                                                @if($record->vet_name)
                                                    <div class="flex items-center text-sm text-gray-600">
                                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                        </svg>
                                                        {{ $record->vet_name }}
                                                    </div>
                                                @endif
                                                @if($record->location)
                                                    <div class="flex items-center text-sm text-gray-600">
                                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        </svg>
                                                        {{ $record->location }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        @if($record->document_path)
                                            <div class="flex-shrink-0 ml-4">
                                                <a href="{{ Storage::url($record->document_path) }}" target="_blank" 
                                                   class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                    Ver documento
                                                </a>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Detalles específicos del registro -->
                                    <div class="space-y-4">
                                        <!-- Próxima cita para vacunas -->
                                        @if($record->record_type == 'vacuna' && $record->next_date)
                                            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0">
                                                        <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    </div>
                                                    <div class="ml-3">
                                                        <h4 class="text-sm font-medium text-yellow-800">Próxima vacunación</h4>
                                                        <p class="text-sm text-yellow-700">
                                                            {{ $record->next_date instanceof \Carbon\Carbon ? $record->next_date->format('d/m/Y') : \Carbon\Carbon::parse($record->next_date)->format('d/m/Y') }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Diagnóstico -->
                                        @if($record->diagnosis)
                                            <div class="bg-gray-50 rounded-xl p-4">
                                                <h4 class="text-sm font-medium text-gray-900 mb-2">Diagnóstico</h4>
                                                <p class="text-sm text-gray-600">{{ $record->diagnosis }}</p>
                                            </div>
                                        @endif

                                        <!-- Tratamiento -->
                                        @if($record->treatment)
                                            <div class="bg-gray-50 rounded-xl p-4">
                                                <h4 class="text-sm font-medium text-gray-900 mb-2">Tratamiento</h4>
                                                <p class="text-sm text-gray-600">{{ $record->treatment }}</p>
                                            </div>
                                        @endif

                                        <!-- Observaciones -->
                                        @if($record->observations)
                                            <div class="bg-gray-50 rounded-xl p-4">
                                                <h4 class="text-sm font-medium text-gray-900 mb-2">Observaciones</h4>
                                                <p class="text-sm text-gray-600">{{ $record->observations }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>