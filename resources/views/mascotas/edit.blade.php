<x-app-layout>
    <x-sidebar-menu />
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Título de la vista -->
            <div class="mb-6 text-center">
                <h1 class="text-4xl font-bold text-gray-800">EDITAR MASCOTA</h1>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('dashboard.cliente.mascotas.update', $pet->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Imagen de Perfil y Formulario -->
                        <div class="flex flex-col md:flex-row gap-8">
                            <!-- Imagen de Perfil -->
                            <div class="md:w-1/4">
                                <div class="relative group">
                                    <div class="w-32 h-32 bg-white rounded-full shadow-lg flex items-center justify-center overflow-hidden">
                                        @if($pet->profile_image)
                                            <img src="{{ Storage::url($pet->profile_image) }}" alt="{{ $pet->nombre }}" class="w-full h-full object-cover">
                                        @else
                                            <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                <p class="mt-2 text-sm text-gray-500">Haz clic en la imagen para cambiarla</p>
                            </div>

                            <!-- Información Básica -->
                            <div class="md:w-3/4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Nombre -->
                                    <div>
                                        <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                                        <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $pet->nombre) }}" 
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        @error('nombre')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Especie -->
                                    <div>
                                        <label for="especie" class="block text-sm font-medium text-gray-700">Especie</label>
                                        <select name="especie" id="especie" 
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            <option value="Perro" {{ old('especie', $pet->especie) == 'Perro' ? 'selected' : '' }}>Perro</option>
                                            <option value="Gato" {{ old('especie', $pet->especie) == 'Gato' ? 'selected' : '' }}>Gato</option>
                                            <option value="Otro" {{ old('especie', $pet->especie) == 'Otro' ? 'selected' : '' }}>Otro</option>
                                        </select>
                                        @error('especie')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Raza -->
                                    <div>
                                        <label for="raza" class="block text-sm font-medium text-gray-700">Raza</label>
                                        <input type="text" name="raza" id="raza" value="{{ old('raza', $pet->raza) }}" 
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        @error('raza')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Sexo -->
                                    <div>
                                        <label for="sexo" class="block text-sm font-medium text-gray-700">Sexo</label>
                                        <select name="sexo" id="sexo" 
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            <option value="Macho" {{ old('sexo', $pet->sexo) == 'Macho' ? 'selected' : '' }}>Macho</option>
                                            <option value="Hembra" {{ old('sexo', $pet->sexo) == 'Hembra' ? 'selected' : '' }}>Hembra</option>
                                        </select>
                                        @error('sexo')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Edad (Años) -->
                                    <div>
                                        <label for="edad_anios" class="block text-sm font-medium text-gray-700">Edad (Años)</label>
                                        <input type="number" name="edad_anios" id="edad_anios" value="{{ old('edad_anios', $pet->edad_anios) }}" min="0" max="30"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        @error('edad_anios')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Edad (Meses) -->
                                    <div>
                                        <label for="edad_meses" class="block text-sm font-medium text-gray-700">Edad (Meses)</label>
                                        <input type="number" name="edad_meses" id="edad_meses" value="{{ old('edad_meses', $pet->edad_meses) }}" min="0" max="11"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        @error('edad_meses')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Documentos -->
                        <div class="mt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Documentos</h3>
                            
                            <!-- Certificado de Vacunas -->
                            <div class="space-y-4">
                                <div>
                                    <label for="vaccine_file" class="block text-sm font-medium text-gray-700">Certificado de Vacunas</label>
                                    <div class="mt-1 flex items-center">
                                        @if($pet->vaccine_file)
                                            <div class="flex items-center space-x-2">
                                                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span class="text-sm text-gray-500">Archivo actual: {{ basename($pet->vaccine_file) }}</span>
                                            </div>
                                        @endif
                                        <input type="file" name="vaccine_file" id="vaccine_file" accept=".pdf,.doc,.docx"
                                               class="ml-4 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    </div>
                                    @error('vaccine_file')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="mt-6 flex justify-end space-x-4">
                            <a href="{{ route('dashboard.cliente.mascotas.show', $pet->id) }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 active:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Cancelar
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-300 disabled:opacity-25 transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 