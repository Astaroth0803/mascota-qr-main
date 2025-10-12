@extends('layouts.standard')

@section('title', 'Editar Mascota')

@php
    $title = 'Editar Mascota';
    $subtitle = 'Modifica la información de ' . $pet->nombre;
@endphp

@section('main-content')
<div class="w-full">
    <!-- Header Principal -->
    <div class="mb-6">
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Editar Mascota</h1>
        <p class="text-gray-600 mt-1">Modifica la información de {{ $pet->nombre }}</p>
    </div>
    
    <!-- Botones de acción -->
    <div class="flex flex-col sm:flex-row gap-3 mb-6">
        <a href="{{ route('dashboard.cliente.index', $pet) }}" 
           class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
            <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span>Volver</span>
        </a>
    </div>

    <!-- Contenido principal -->
    <div class="w-full">
        <div class="px-2 sm:px-4 lg:px-6 xl:px-8 py-4 lg:py-6">
            <div class="max-w-4xl mx-auto">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-gray-200 bg-gray-50">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-lg font-medium text-gray-900">Información de la Mascota</h3>
                                <p class="text-sm text-gray-500">Actualiza los datos de tu mascota</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 sm:p-6">
                    <form action="{{ route('dashboard.cliente.mascotas.update', $pet) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Imagen de Perfil y Formulario -->
                        <div class="flex flex-col lg:flex-row gap-6 lg:gap-8">
                            <!-- Imagen de Perfil -->
                            <div class="lg:w-1/4 flex justify-center lg:justify-start">
                                <div class="relative group">
                                    <div class="w-32 h-32 bg-gray-100 rounded-full shadow-lg flex items-center justify-center overflow-hidden border-4 border-white">
                                        @if($pet->profile_image)
                                            <img src="{{ Storage::url($pet->profile_image) }}" alt="{{ $pet->nombre }}" class="w-full h-full object-cover">
                                        @else
                                            <svg class="w-20 h-20 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        @endif
                                    </div>
                                    <label for="profile_image" class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer bg-black bg-opacity-50 rounded-full">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <input type="file" name="profile_image" id="profile_image" class="hidden" accept="image/*">
                                    </label>
                                </div>
                                <p class="mt-3 text-sm text-gray-500 text-center lg:text-left">Haz clic en la imagen para cambiarla</p>
                            </div>

                            <!-- Información Básica -->
                            <div class="lg:w-3/4">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                                    <!-- Nombre -->
                                    <div>
                                        <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">Nombre</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                            </div>
                                            <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $pet->nombre) }}" 
                                                   class="pl-10 w-full p-3 border border-gray-300 rounded-lg bg-white text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nombre') border-red-500 @enderror text-sm sm:text-base">
                                        </div>
                                        @error('nombre')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Especie -->
                                    <div>
                                        <label for="especie" class="block text-sm font-medium text-gray-700 mb-2">Especie</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                </svg>
                                            </div>
                                            <select name="especie" id="especie" 
                                                    class="pl-10 w-full p-3 border border-gray-300 rounded-lg bg-white text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('especie') border-red-500 @enderror text-sm sm:text-base">
                                                <option value="Perro" {{ old('especie', $pet->especie) == 'Perro' ? 'selected' : '' }}>Perro</option>
                                                <option value="Gato" {{ old('especie', $pet->especie) == 'Gato' ? 'selected' : '' }}>Gato</option>
                                                <option value="Otro" {{ old('especie', $pet->especie) == 'Otro' ? 'selected' : '' }}>Otro</option>
                                            </select>
                                        </div>
                                        @error('especie')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Raza -->
                                    <div>
                                        <label for="raza" class="block text-sm font-medium text-gray-700 mb-2">Raza</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                                </svg>
                                            </div>
                                            <input type="text" name="raza" id="raza" value="{{ old('raza', $pet->raza) }}" 
                                                   class="pl-10 w-full p-3 border border-gray-300 rounded-lg bg-white text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('raza') border-red-500 @enderror text-sm sm:text-base">
                                        </div>
                                        @error('raza')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Sexo -->
                                    <div>
                                        <label for="sexo" class="block text-sm font-medium text-gray-700 mb-2">Sexo</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                                </svg>
                                            </div>
                                            <select name="sexo" id="sexo" 
                                                    class="pl-10 w-full p-3 border border-gray-300 rounded-lg bg-white text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('sexo') border-red-500 @enderror text-sm sm:text-base">
                                                <option value="Macho" {{ old('sexo', $pet->sexo) == 'Macho' ? 'selected' : '' }}>Macho</option>
                                                <option value="Hembra" {{ old('sexo', $pet->sexo) == 'Hembra' ? 'selected' : '' }}>Hembra</option>
                                            </select>
                                        </div>
                                        @error('sexo')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Edad (Años) -->
                                    <div>
                                        <label for="edad_anios" class="block text-sm font-medium text-gray-700 mb-2">Edad (Años)</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                            <input type="number" name="edad_anios" id="edad_anios" value="{{ old('edad_anios', $pet->edad_anios) }}" min="0" max="30"
                                                   class="pl-10 w-full p-3 border border-gray-300 rounded-lg bg-white text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('edad_anios') border-red-500 @enderror text-sm sm:text-base">
                                        </div>
                                        @error('edad_anios')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Edad (Meses) -->
                                    <div>
                                        <label for="edad_meses" class="block text-sm font-medium text-gray-700 mb-2">Edad (Meses)</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                            <input type="number" name="edad_meses" id="edad_meses" value="{{ old('edad_meses', $pet->edad_meses) }}" min="0" max="11"
                                                   class="pl-10 w-full p-3 border border-gray-300 rounded-lg bg-white text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('edad_meses') border-red-500 @enderror text-sm sm:text-base">
                                        </div>
                                        @error('edad_meses')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Documentos -->
                        <div class="mt-8">
                            <div class="bg-gray-50 rounded-lg p-4 sm:p-6">
                                <div class="flex items-center mb-4">
                                    <div class="flex-shrink-0">
                                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-lg font-medium text-gray-900">Documentos</h3>
                                        <p class="text-sm text-gray-500">Sube el certificado de vacunas de tu mascota</p>
                                    </div>
                                </div>
                                
                                <!-- Certificado de Vacunas -->
                                <div>
                                    <label for="vaccine_file" class="block text-sm font-medium text-gray-700 mb-2">Certificado de Vacunas</label>
                                    <div class="mt-1">
                                        @if($pet->vaccine_file)
                                            <div class="flex items-center space-x-2 mb-3 p-3 bg-green-50 border border-green-200 rounded-lg">
                                                <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span class="text-sm text-green-700 font-medium">Archivo actual: {{ basename($pet->vaccine_file) }}</span>
                                            </div>
                                        @endif
                                        <div class="relative">
                                            <input type="file" name="vaccine_file" id="vaccine_file" accept=".pdf,.doc,.docx"
                                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-lg p-3">
                                        </div>
                                    </div>
                                    @error('vaccine_file')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <div class="flex flex-col sm:flex-row items-center justify-end gap-3 sm:gap-4">
                                <a href="{{ route('dashboard.cliente.index') }}"
                                   class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
                                    <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                    </svg>
                                    <span>Cancelar</span>
                                </a>
                                <button type="submit" 
                                        class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                    <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Guardar Cambios</span>
                                </button>
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 