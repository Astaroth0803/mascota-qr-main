<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <!-- Header -->
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                                <svg class="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Detalles de Solicitud de Cambio
                            </h1>
                            <p class="text-sm text-gray-600 mt-1">Revisa y aprueba o rechaza la solicitud de cambio de fecha</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('dashboard.veterinario.appointment-change-requests.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Volver a Solicitudes
                            </a>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <!-- Estado de la Solicitud -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between p-4 rounded-lg
                            @if($changeRequest->status === 'pending') bg-yellow-50 border border-yellow-200
                            @elseif($changeRequest->status === 'approved') bg-green-50 border border-green-200
                            @else bg-red-50 border border-red-200 @endif">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2 
                                    @if($changeRequest->status === 'pending') text-yellow-500
                                    @elseif($changeRequest->status === 'approved') text-green-500
                                    @else text-red-500 @endif" fill="currentColor" viewBox="0 0 20 20">
                                    @if($changeRequest->status === 'pending')
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    @elseif($changeRequest->status === 'approved')
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    @else
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    @endif
                                </svg>
                                <span class="text-sm font-medium
                                    @if($changeRequest->status === 'pending') text-yellow-800
                                    @elseif($changeRequest->status === 'approved') text-green-800
                                    @else text-red-800 @endif">
                                    @if($changeRequest->status === 'pending') Solicitud Pendiente
                                    @elseif($changeRequest->status === 'approved') Solicitud Aprobada
                                    @else Solicitud Rechazada @endif
                                </span>
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $changeRequest->created_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                    </div>

                    <!-- Información de la Cita -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                        <!-- Información Actual -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Cita Actual
                            </h3>
                            <div class="space-y-2 text-sm">
                                <div><span class="font-medium text-gray-700">Mascota:</span> <span class="text-gray-900">{{ $changeRequest->appointment->pet->nombre }}</span></div>
                                <div><span class="font-medium text-gray-700">Cliente:</span> <span class="text-gray-900">{{ $changeRequest->client->name }}</span></div>
                                <div><span class="font-medium text-gray-700">Tipo:</span> <span class="text-gray-900">{{ $changeRequest->appointment->record_type }}</span></div>
                                <div><span class="font-medium text-gray-700">Fecha:</span> <span class="text-gray-900">{{ $changeRequest->appointment->date->format('d/m/Y') }}</span></div>
                                <div><span class="font-medium text-gray-700">Hora:</span> <span class="text-gray-900">{{ $changeRequest->appointment->time ? $changeRequest->appointment->time->format('H:i') : 'No especificada' }}</span></div>
                                @if($changeRequest->appointment->location)
                                    <div><span class="font-medium text-gray-700">Ubicación:</span> <span class="text-gray-900">{{ $changeRequest->appointment->location }}</span></div>
                                @endif
                            </div>
                        </div>

                        <!-- Nueva Fecha Solicitada -->
                        <div class="bg-blue-50 rounded-lg p-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-3 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Nueva Fecha Solicitada
                            </h3>
                            <div class="space-y-2 text-sm">
                                <div><span class="font-medium text-gray-700">Fecha:</span> <span class="text-blue-600 font-medium">{{ $changeRequest->requested_date->format('d/m/Y') }}</span></div>
                                <div><span class="font-medium text-gray-700">Hora:</span> <span class="text-blue-600 font-medium">{{ $changeRequest->requested_time ? \Carbon\Carbon::parse($changeRequest->requested_time)->format('H:i') : 'No especificada' }}</span></div>
                                @if($changeRequest->reason)
                                    <div class="mt-3">
                                        <span class="font-medium text-gray-700">Razón del cambio:</span>
                                        <p class="text-gray-600 mt-1 p-2 bg-white rounded border">{{ $changeRequest->reason }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Acciones (solo si está pendiente) -->
                    @if($changeRequest->status === 'pending')
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Acciones</h3>
                            
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <!-- Aprobar -->
                                <form action="{{ route('dashboard.veterinario.appointment-change-requests.approve', $changeRequest->id) }}" method="POST">
                                    @csrf
                                    <div class="border border-green-200 rounded-lg p-4 bg-green-50">
                                        <h4 class="text-sm font-medium text-green-800 mb-2">Aprobar Solicitud</h4>
                                        <p class="text-sm text-green-700 mb-3">La cita se actualizará con la nueva fecha y hora solicitada.</p>
                                        
                                        <div class="mb-3">
                                            <label for="approve_notes" class="block text-sm font-medium text-gray-700 mb-1">
                                                Notas (opcional)
                                            </label>
                                            <textarea name="vet_notes" id="approve_notes" rows="2" 
                                                      placeholder="Agrega comentarios sobre la aprobación..."
                                                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 text-sm"></textarea>
                                        </div>
                                        
                                        <button type="submit" 
                                                class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            Aprobar Solicitud
                                        </button>
                                    </div>
                                </form>

                                <!-- Rechazar -->
                                <form action="{{ route('dashboard.veterinario.appointment-change-requests.reject', $changeRequest->id) }}" method="POST">
                                    @csrf
                                    <div class="border border-red-200 rounded-lg p-4 bg-red-50">
                                        <h4 class="text-sm font-medium text-red-800 mb-2">Rechazar Solicitud</h4>
                                        <p class="text-sm text-red-700 mb-3">La cita mantendrá su fecha y hora original.</p>
                                        
                                        <div class="mb-3">
                                            <label for="reject_notes" class="block text-sm font-medium text-gray-700 mb-1">
                                                Motivo del rechazo <span class="text-red-500">*</span>
                                            </label>
                                            <textarea name="vet_notes" id="reject_notes" rows="2" required
                                                      placeholder="Explica por qué rechazas esta solicitud..."
                                                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 text-sm @error('vet_notes') border-red-300 @enderror"></textarea>
                                            @error('vet_notes')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
                                        <button type="submit" 
                                                class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            Rechazar Solicitud
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @else
                        <!-- Información de la respuesta -->
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Respuesta del Veterinario</h3>
                            
                            <div class="space-y-3">
                                <div class="flex items-center">
                                    <span class="font-medium text-gray-700 w-24">Estado:</span>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                        @if($changeRequest->status === 'approved') bg-green-100 text-green-800
                                        @else bg-red-100 text-red-800 @endif">
                                        @if($changeRequest->status === 'approved') Aprobada
                                        @else Rechazada @endif
                                    </span>
                                </div>
                                
                                @if($changeRequest->vet_response_at)
                                    <div class="flex items-center">
                                        <span class="font-medium text-gray-700 w-24">Respondido:</span>
                                        <span class="text-gray-900">{{ $changeRequest->vet_response_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                @endif
                                
                                @if($changeRequest->vet_notes)
                                    <div>
                                        <span class="font-medium text-gray-700">Notas:</span>
                                        <p class="text-gray-600 mt-1 p-3 bg-white rounded border">{{ $changeRequest->vet_notes }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

