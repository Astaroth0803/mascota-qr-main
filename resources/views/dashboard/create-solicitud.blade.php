<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Crear Nueva Solicitud') }}
            </h2>
            <a href="{{ route('dashboard.solicitudes') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Volver
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('solicitudes.store') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Datos del dueño -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Datos del Dueño</h3>
                                
                                <div class="space-y-2">
                                    <label for="nombre_owner" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Nombre
                                    </label>
                                    <input type="text" name="nombre_owner" id="nombre_owner" value="{{ old('nombre_owner') }}" 
                                           class="w-full p-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nombre_owner') border-red-500 @enderror"
                                           placeholder="Nombre del dueño">
                                    @error('nombre_owner')
                                        <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="space-y-2">
                                    <label for="apellido_owner" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Apellido
                                    </label>
                                    <input type="text" name="apellido_owner" id="apellido_owner" value="{{ old('apellido_owner') }}" 
                                           class="w-full p-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('apellido_owner') border-red-500 @enderror"
                                           placeholder="Apellido del dueño">
                                    @error('apellido_owner')
                                        <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="space-y-2">
                                    <label for="correo_owner" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Correo Electrónico
                                    </label>
                                    <input type="email" name="correo_owner" id="correo_owner" value="{{ old('correo_owner') }}" 
                                           class="w-full p-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('correo_owner') border-red-500 @enderror"
                                           placeholder="correo@ejemplo.com">
                                    @error('correo_owner')
                                        <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="space-y-2">
                                    <label for="telefono_owner" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Teléfono
                                    </label>
                                    <input type="text" name="telefono_owner" id="telefono_owner" value="{{ old('telefono_owner') }}" 
                                           class="w-full p-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('telefono_owner') border-red-500 @enderror"
                                           placeholder="Número de teléfono">
                                    @error('telefono_owner')
                                        <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Datos de la mascota -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Datos de la Mascota</h3>
                                
                                <div class="space-y-2">
                                    <label for="nombre" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Nombre de la Mascota
                                    </label>
                                    <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}" 
                                           class="w-full p-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('nombre') border-red-500 @enderror"
                                           placeholder="Nombre de la mascota">
                                    @error('nombre')
                                        <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="space-y-2">
                                    <label for="especie" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Especie
                                    </label>
                                    <input type="text" name="especie" id="especie" value="{{ old('especie') }}" 
                                           class="w-full p-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('especie') border-red-500 @enderror"
                                           placeholder="Especie de la mascota">
                                    @error('especie')
                                        <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="space-y-2">
                                    <label for="raza" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Raza
                                    </label>
                                    <input type="text" name="raza" id="raza" value="{{ old('raza') }}" 
                                           class="w-full p-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('raza') border-red-500 @enderror"
                                           placeholder="Raza de la mascota">
                                    @error('raza')
                                        <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="space-y-2">
                                    <label for="edad" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Edad
                                    </label>
                                    <input type="text" name="edad" id="edad" value="{{ old('edad') }}" 
                                           class="w-full p-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('edad') border-red-500 @enderror"
                                           placeholder="Edad de la mascota">
                                    @error('edad')
                                        <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="space-y-2">
                                    <label for="sexo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Sexo
                                    </label>
                                    <select name="sexo" id="sexo" 
                                            class="w-full p-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('sexo') border-red-500 @enderror">
                                        <option value="">Seleccione el sexo</option>
                                        <option value="Macho" {{ old('sexo') == 'Macho' ? 'selected' : '' }}>Macho</option>
                                        <option value="Hembra" {{ old('sexo') == 'Hembra' ? 'selected' : '' }}>Hembra</option>
                                    </select>
                                    @error('sexo')
                                        <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- ID de pago Yappy -->
                        <div class="space-y-2">
                            <label for="id_pago_yappy" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                ID de Pago Yappy
                            </label>
                            <input type="text" name="id_pago_yappy" id="id_pago_yappy" value="{{ old('id_pago_yappy') }}" 
                                   class="w-full p-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('id_pago_yappy') border-red-500 @enderror"
                                   placeholder="ID del pago realizado en Yappy">
                            @error('id_pago_yappy')
                                <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Crear Solicitud
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 