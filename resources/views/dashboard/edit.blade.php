<x-app-layout>
    @can('editar_mascotas')
        <x-slot name="header">
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Editar Información de la Mascota') }}
                </h2>
                <a href="{{ route('pets.show', $pet->id) }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
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
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <form action="{{ route('pets.update', $pet->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="nombre" :value="__('Nombre')" />
                                    <x-text-input id="nombre" name="nombre" type="text" class="mt-1 block w-full" 
                                                 :value="old('nombre', $pet->nombre)" required autofocus />
                                    <x-input-error class="mt-2" :messages="$errors->get('nombre')" />
                </div>

                                <div>
                                    <x-input-label for="especie" :value="__('Especie')" />
                                    <x-text-input id="especie" name="especie" type="text" class="mt-1 block w-full" 
                                                 :value="old('especie', $pet->especie)" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('especie')" />
                </div>

                                <div>
                                    <x-input-label for="raza" :value="__('Raza')" />
                                    <x-text-input id="raza" name="raza" type="text" class="mt-1 block w-full" 
                                                 :value="old('raza', $pet->raza)" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('raza')" />
                </div>

                                <div>
                                    <x-input-label :value="__('Edad')" />
                                    <div class="grid grid-cols-2 gap-4 mt-1">
                                        <div>
                                            <x-input-label for="edad_anios" :value="__('Años')" class="text-sm" />
                                            <x-text-input id="edad_anios" name="edad_anios" type="number" 
                                                         class="block w-full" :value="old('edad_anios', $pet->edad_anios)" min="0" />
                                        </div>
                                        <div>
                                            <x-input-label for="edad_meses" :value="__('Meses')" class="text-sm" />
                                            <x-text-input id="edad_meses" name="edad_meses" type="number" 
                                                         class="block w-full" :value="old('edad_meses', $pet->edad_meses)" min="0" max="11" />
                                        </div>
                                    </div>
                                    <x-input-error class="mt-2" :messages="$errors->get('edad_anios')" />
                                    <x-input-error class="mt-2" :messages="$errors->get('edad_meses')" />
                </div>

                                <div>
                                    <x-input-label for="sexo" :value="__('Sexo')" />
                                    <select id="sexo" name="sexo" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                        <option value="Macho" {{ old('sexo', $pet->sexo) == 'Macho' ? 'selected' : '' }}>Macho</option>
                        <option value="Hembra" {{ old('sexo', $pet->sexo) == 'Hembra' ? 'selected' : '' }}>Hembra</option>
                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('sexo')" />
                </div>

                                <div>
                                    <x-input-label for="vaccine_file" :value="__('Archivo de Vacunas (PDF)')" />
                                    <input type="file" id="vaccine_file" name="vaccine_file" 
                                           class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400
                                                  file:mr-4 file:py-2 file:px-4
                                                  file:rounded-md file:border-0
                                                  file:text-sm file:font-semibold
                                                  file:bg-gray-50 dark:file:bg-gray-700
                                                  file:text-gray-700 dark:file:text-gray-200
                                                  hover:file:bg-gray-100 dark:hover:file:bg-gray-600" />
                    @if ($pet->vaccine_file)
                                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                            Vacuna actual: 
                                            <a href="{{ asset('storage/' . $pet->vaccine_file) }}" 
                                               target="_blank" 
                                               class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                                Ver PDF
                                            </a>
                                        </p>
                    @endif
                                    <x-input-error class="mt-2" :messages="$errors->get('vaccine_file')" />
                                </div>
                </div>

                            <div class="flex items-center justify-end mt-6 space-x-4">
                                <x-primary-button>
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    {{ __('Actualizar Mascota') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
                </div>
        </div>
    @else
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4" role="alert">
            <p>No tienes permiso para editar esta mascota.</p>
                </div>
            </div>
        </div>
    @endcan
</x-app-layout>
