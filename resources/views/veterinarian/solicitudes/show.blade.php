<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <!-- Header -->
                <div class="bg-gradient-to-r from-green-50 to-green-100 px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                                <svg class="w-6 h-6 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Detalles de la Solicitud
                            </h1>
                            <p class="text-sm text-gray-600 mt-1">Información completa de la solicitud de veterinario</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('dashboard.veterinario.solicitudes.index') }}" 
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
                    <!-- Mensajes de éxito/error -->
                    @if(session('success'))
                        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-green-800 font-medium">{{ session('success') }}</span>
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-red-800 font-medium">{{ session('error') }}</span>
                            </div>
                        </div>
                    @endif

                    <!-- Información de la Solicitud -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Información del Cliente y Mascota -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Información del Cliente
                            </h3>
                            
                            <div class="space-y-3">
                                <div class="flex items-center">
                                    <span class="text-sm font-medium text-gray-700 w-24">Cliente:</span>
                                    <span class="text-sm text-gray-900">{{ $solicitud->mascota->user->name }}</span>
                                </div>
                                
                                <div class="flex items-center">
                                    <span class="text-sm font-medium text-gray-700 w-24">Email:</span>
                                    <span class="text-sm text-gray-900">{{ $solicitud->mascota->user->email }}</span>
                                </div>
                                
                                <div class="flex items-center">
                                    <span class="text-sm font-medium text-gray-700 w-24">Mascota:</span>
                                    <span class="text-sm text-gray-900">{{ $solicitud->mascota->nombre }}</span>
                                </div>
                                
                                <div class="flex items-center">
                                    <span class="text-sm font-medium text-gray-700 w-24">Especie:</span>
                                    <span class="text-sm text-gray-900">{{ $solicitud->mascota->especie }}</span>
                                </div>
                                
                                <div class="flex items-center">
                                    <span class="text-sm font-medium text-gray-700 w-24">Raza:</span>
                                    <span class="text-sm text-gray-900">{{ $solicitud->mascota->raza }}</span>
                                </div>
                                
                                <div class="flex items-center">
                                    <span class="text-sm font-medium text-gray-700 w-24">Edad:</span>
                                    <span class="text-sm text-gray-900">{{ $solicitud->mascota->edad }} años</span>
                                </div>
                            </div>
                        </div>

                        <!-- Información de la Solicitud -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Detalles de la Solicitud
                            </h3>
                            
                            <div class="space-y-3">
                                <div class="flex items-center">
                                    <span class="text-sm font-medium text-gray-700 w-24">Estado:</span>
                                    @if($solicitud->activo)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                            </svg>
                                            Pendiente
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                            Procesada
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="flex items-center">
                                    <span class="text-sm font-medium text-gray-700 w-24">Tipo:</span>
                                    <span class="text-sm text-gray-900">{{ $solicitud->tipo_asignacion_nombre }}</span>
                                </div>
                                
                                <div class="flex items-center">
                                    <span class="text-sm font-medium text-gray-700 w-24">Fecha:</span>
                                    <span class="text-sm text-gray-900">{{ $solicitud->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                
                                @if($solicitud->notas)
                                    <div class="mt-4">
                                        <span class="text-sm font-medium text-gray-700 block mb-2">Notas:</span>
                                        <div class="text-sm text-gray-900 bg-white p-3 rounded border">
                                            {{ $solicitud->notas }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Acciones -->
                    @if($solicitud->activo)
                        <div class="mt-8 bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-yellow-800 mb-4">
                                ¿Qué acción deseas tomar?
                            </h3>
                            
                            <div class="flex flex-col sm:flex-row gap-4">
                                <form action="{{ route('dashboard.veterinario.solicitudes.aceptar', $solicitud->id) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" 
                                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Aceptar Solicitud
                                    </button>
                                </form>
                                
                                <form action="{{ route('dashboard.veterinario.solicitudes.rechazar', $solicitud->id) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" 
                                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors"
                                            onclick="return confirm('¿Estás seguro de que deseas rechazar esta solicitud?')">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        Rechazar Solicitud
                                    </button>
                                </form>
                            </div>
                            
                            <p class="text-sm text-yellow-700 mt-4">
                                <strong>Nota:</strong> Al aceptar o rechazar esta solicitud, se notificará automáticamente al cliente sobre tu decisión.
                            </p>
                        </div>
                    @else
                        <div class="mt-8 bg-green-50 border border-green-200 rounded-lg p-6">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <h3 class="text-lg font-semibold text-green-800">
                                    Solicitud Procesada
                                </h3>
                            </div>
                            <p class="text-sm text-green-700 mt-2">
                                Esta solicitud ya ha sido procesada. El cliente ha sido notificado sobre tu decisión.
                            </p>
                        </div>
                    @endif

                    <!-- Enlaces adicionales -->
                    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="{{ route('dashboard.veterinario.solicitudes.index') }}" 
                           class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-sm font-medium text-gray-900">Todas las Solicitudes</h3>
                                    <p class="text-sm text-gray-500">Ver todas las solicitudes</p>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('dashboard.veterinario.notificaciones.index') }}" 
                           class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.828 7l2.586 2.586a2 2 0 002.828 0L12.828 7H4.828z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-sm font-medium text-gray-900">Notificaciones</h3>
                                    <p class="text-sm text-gray-500">Ver notificaciones</p>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('dashboard.veterinario.calendario.index') }}" 
                           class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="text-sm font-medium text-gray-900">Mi Calendario</h3>
                                    <p class="text-sm text-gray-500">Ver calendario de citas</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
