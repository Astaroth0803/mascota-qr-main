@extends('layouts.dashboard')

@section('title', 'Editar Cita - Cliente')

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
                            <h1 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900">Editar Cita</h1>
                            <p class="text-sm text-gray-600 mt-1">Modifica la información de la cita médica</p>
                        </div>
                        
                        <!-- Botones de acción -->
                        <div class="flex items-center gap-3">
                            <a href="{{ route('dashboard.cliente.calendario.show', $appointment->id) }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Volver al Detalle
                            </a>
                            <a href="{{ route('dashboard.cliente.calendario.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-500 text-white text-sm font-medium rounded-lg hover:bg-gray-600 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Ver Calendario
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
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-medium text-gray-900">Editando cita para {{ $appointment->pet->nombre }}</h2>
                            <p class="text-sm text-gray-500">{{ $appointment->pet->especie }} - {{ $appointment->pet->raza }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Información de la cita actual -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <h3 class="text-sm font-medium text-blue-800 mb-2">Información de la Cita Actual</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="font-medium text-blue-700">Mascota:</span>
                            <span class="text-blue-600">{{ $appointment->pet->nombre }} - {{ $appointment->pet->especie }}</span>
                        </div>
                        <div>
                            <span class="font-medium text-blue-700">Tipo:</span>
                            <span class="text-blue-600">{{ $appointmentTypes[$appointment->record_type] ?? $appointment->record_type }}</span>
                        </div>
                        <div>
                            <span class="font-medium text-blue-700">Fecha actual:</span>
                            <span class="text-blue-600">{{ $appointment->date->format('d/m/Y') }}</span>
                        </div>
                        <div>
                            <span class="font-medium text-blue-700">Hora actual:</span>
                            <span class="text-blue-600">{{ $appointment->time ? $appointment->time->format('H:i') : 'No especificada' }}</span>
                        </div>
                    </div>
                </div>

                @if($appointment->pendingChangeRequests()->exists())
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">Solicitud Pendiente</h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <p>Ya tienes una solicitud de cambio de fecha pendiente para esta cita. Espera la respuesta de tu veterinario.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <form action="{{ route('dashboard.cliente.calendario.update', $appointment->id) }}" method="POST" class="p-6 space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">Solicitud de Cambio de Fecha</h3>
                                    <div class="mt-2 text-sm text-yellow-700">
                                        <p>Como cliente, solo puedes solicitar cambios de fecha y hora. Tu veterinario debe aprobar el cambio antes de que se aplique.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                        <!-- Nueva Fecha y Hora Solicitada -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="requested_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nueva Fecha Solicitada <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="requested_date" id="requested_date" required
                                       value="{{ old('requested_date') }}"
                                       min="{{ now()->format('Y-m-d') }}"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('requested_date') border-red-300 @enderror">
                                @error('requested_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="requested_time" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nueva Hora Solicitada <span class="text-red-500">*</span>
                                </label>
                                <input type="time" name="requested_time" id="requested_time" required
                                       value="{{ old('requested_time') }}"
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('requested_time') border-red-300 @enderror">
                                @error('requested_time')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Razón del Cambio -->
                        <div>
                            <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">
                                Razón del Cambio de Fecha
                            </label>
                            <textarea name="reason" id="reason" rows="3"
                                      placeholder="Explica brevemente por qué necesitas cambiar la fecha de la cita..."
                                      class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('reason') border-red-300 @enderror">{{ old('reason') }}</textarea>
                            @error('reason')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    
                        <!-- Botones de acción -->
                        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                            <a href="{{ route('dashboard.cliente.calendario.show', $appointment->id) }}" 
                               class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Cancelar
                            </a>
                            <button type="submit" 
                                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Solicitar Cambio de Fecha
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
