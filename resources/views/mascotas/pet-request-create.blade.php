@extends('layouts.dashboard')

@section('title', 'Solicitar Nueva Mascota')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Solicitar Nueva Mascota</h1>
                        <p class="text-gray-600 mt-1">Completa la información de tu mascota para solicitar su registro</p>
                    </div>
                    <a href="{{ route('dashboard.cliente.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Volver al Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Información del proceso -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-8">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Proceso de Solicitud</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <ul class="list-disc list-inside space-y-1">
                            <li>Completa la información básica de tu mascota</li>
                            <li>Opcionalmente, proporciona un ID de pago para verificación</li>
                            <li>Un administrador revisará tu solicitud</li>
                            <li>Recibirás una notificación cuando sea aprobada o rechazada</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulario -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Información de la Mascota</h3>
                <p class="text-sm text-gray-500">Proporciona los datos básicos de tu mascota</p>
            </div>
            
            <form action="{{ route('dashboard.cliente.pet-requests.store') }}" method="POST" class="p-6 space-y-6">
                @csrf
                
                <!-- Información Básica -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nombre -->
                    <div class="md:col-span-2">
                        <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                            Nombre de la Mascota <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}" 
                                   class="pl-10 w-full p-3 border border-gray-300 rounded-lg bg-white text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nombre') border-red-500 @enderror"
                                   placeholder="Ej: Max, Luna, Rocky">
                        </div>
                        @error('nombre')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Especie -->
                    <div>
                        <label for="especie" class="block text-sm font-medium text-gray-700 mb-2">
                            Especie <span class="text-red-500">*</span>
                        </label>
                        <select name="especie" id="especie" 
                                class="w-full p-3 border border-gray-300 rounded-lg bg-white text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('especie') border-red-500 @enderror">
                            <option value="">Selecciona una especie</option>
                            <option value="Perro" {{ old('especie') == 'Perro' ? 'selected' : '' }}>Perro</option>
                            <option value="Gato" {{ old('especie') == 'Gato' ? 'selected' : '' }}>Gato</option>
                            <option value="Conejo" {{ old('especie') == 'Conejo' ? 'selected' : '' }}>Conejo</option>
                            <option value="Ave" {{ old('especie') == 'Ave' ? 'selected' : '' }}>Ave</option>
                            <option value="Otro" {{ old('especie') == 'Otro' ? 'selected' : '' }}>Otro</option>
                        </select>
                        @error('especie')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Raza -->
                    <div>
                        <label for="raza" class="block text-sm font-medium text-gray-700 mb-2">
                            Raza <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="raza" id="raza" value="{{ old('raza') }}" 
                               class="w-full p-3 border border-gray-300 rounded-lg bg-white text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('raza') border-red-500 @enderror"
                               placeholder="Ej: Labrador, Persa, Angora">
                        @error('raza')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Edad -->
                    <div>
                        <label for="edad_anios" class="block text-sm font-medium text-gray-700 mb-2">
                            Edad (años) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="edad_anios" id="edad_anios" value="{{ old('edad_anios', 0) }}" 
                               min="0" max="30"
                               class="w-full p-3 border border-gray-300 rounded-lg bg-white text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('edad_anios') border-red-500 @enderror">
                        @error('edad_anios')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="edad_meses" class="block text-sm font-medium text-gray-700 mb-2">
                            Edad (meses) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="edad_meses" id="edad_meses" value="{{ old('edad_meses', 0) }}" 
                               min="0" max="11"
                               class="w-full p-3 border border-gray-300 rounded-lg bg-white text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('edad_meses') border-red-500 @enderror">
                        @error('edad_meses')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sexo -->
                    <div>
                        <label for="sexo" class="block text-sm font-medium text-gray-700 mb-2">
                            Sexo <span class="text-red-500">*</span>
                        </label>
                        <select name="sexo" id="sexo" 
                                class="w-full p-3 border border-gray-300 rounded-lg bg-white text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('sexo') border-red-500 @enderror">
                            <option value="">Selecciona el sexo</option>
                            <option value="Macho" {{ old('sexo') == 'Macho' ? 'selected' : '' }}>Macho</option>
                            <option value="Hembra" {{ old('sexo') == 'Hembra' ? 'selected' : '' }}>Hembra</option>
                        </select>
                        @error('sexo')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Información de Pago (Opcional) -->
                <div class="border-t border-gray-200 pt-6">
                    <h4 class="text-lg font-medium text-gray-900 mb-4">Información de Pago (Opcional)</h4>
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">Verificación de Pago</h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <p>Si ya realizaste el pago, proporciona el ID de la transacción para acelerar el proceso de aprobación.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="payment_id" class="block text-sm font-medium text-gray-700 mb-2">
                            ID de Pago/Transacción
                        </label>
                        <input type="text" name="payment_id" id="payment_id" value="{{ old('payment_id') }}" 
                               class="w-full p-3 border border-gray-300 rounded-lg bg-white text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('payment_id') border-red-500 @enderror"
                               placeholder="Ej: TXN123456789">
                        <p class="mt-2 text-sm text-gray-500">Deja este campo vacío si aún no has realizado el pago</p>
                        @error('payment_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('dashboard.cliente.index') }}" 
                       class="px-6 py-3 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        Enviar Solicitud
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
