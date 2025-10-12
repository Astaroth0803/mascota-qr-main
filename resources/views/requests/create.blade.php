<x-app-layout>
    @can('registrar_mascotas')
        <x-slot name="header">
        <div class="flex flex-col sm:flex-inline items-start sm:items-center justify-between gap-6">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Registrar Nueva Mascota') }}
                </h2>
                <a href="{{ route('dashboard.cliente.index') }}" 
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
                        <form action="{{ route('pets.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="nombre" :value="__('Nombre')" />
                                    <x-text-input id="nombre" name="nombre" type="text" class="mt-1 block w-full" 
                                                 :value="old('nombre')" required autofocus />
                                    <x-input-error class="mt-2" :messages="$errors->get('nombre')" />
            </div>

                                <div>
                                    <x-input-label for="especie" :value="__('Especie')" />
                                    <select id="especie" name="especie" 
                                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                            required
                                            onchange="actualizarRazas()">
                                        <option value="">Seleccione una especie</option>
                                        <option value="Perro" {{ old('especie') == 'Perro' ? 'selected' : '' }}>Perro</option>
                                        <option value="Gato" {{ old('especie') == 'Gato' ? 'selected' : '' }}>Gato</option>
                                        <option value="Conejo" {{ old('especie') == 'Conejo' ? 'selected' : '' }}>Conejo</option>
                                        <option value="Ave" {{ old('especie') == 'Ave' ? 'selected' : '' }}>Ave</option>
                                        <option value="Hamster" {{ old('especie') == 'Hamster' ? 'selected' : '' }}>Hamster</option>
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('especie')" />
            </div>

                                <div>
                                    <x-input-label for="raza" :value="__('Raza')" />
                                    <select id="raza" name="raza" 
                                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                            required>
                                        <option value="">Seleccione una raza</option>
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('raza')" />
            </div>

                                <div>
                                    <x-input-label :value="__('Edad')" />
                                    <div class="grid grid-cols-2 gap-4 mt-1">
                                        <div>
                                            <x-input-label for="edad_anios" :value="__('Años')" class="text-sm" />
                                            <x-text-input id="edad_anios" name="edad_anios" type="number" 
                                                         class="block w-full" :value="old('edad_anios')" min="0" />
                                        </div>
                                        <div>
                                            <x-input-label for="edad_meses" :value="__('Meses')" class="text-sm" />
                                            <x-text-input id="edad_meses" name="edad_meses" type="number" 
                                                         class="block w-full" :value="old('edad_meses')" min="0" max="11" />
                                        </div>
                                    </div>
                                    <x-input-error class="mt-2" :messages="$errors->get('edad_anios')" />
                                    <x-input-error class="mt-2" :messages="$errors->get('edad_meses')" />
            </div>

                                <div>
                                    <x-input-label for="sexo" :value="__('Sexo')" />
                                    <select id="sexo" name="sexo" 
                                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                            required>
                                        <option value="">Seleccione el sexo</option>
                                        <option value="Macho" {{ old('sexo') == 'Macho' ? 'selected' : '' }}>Macho</option>
                                        <option value="Hembra" {{ old('sexo') == 'Hembra' ? 'selected' : '' }}>Hembra</option>
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
                                    <x-input-error class="mt-2" :messages="$errors->get('vaccine_file')" />
                                </div>
            </div>

                            <div class="flex items-center justify-end mt-6 space-x-4">
                                <x-primary-button>
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    {{ __('Registrar Mascota') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @push('scripts')
        <script>
            const razasPorEspecie = {
                'Perro': @json(App\Data\RazasPorEspecie::getRazas('Perro')),
                'Gato': @json(App\Data\RazasPorEspecie::getRazas('Gato')),
                'Conejo': @json(App\Data\RazasPorEspecie::getRazas('Conejo')),
                'Ave': @json(App\Data\RazasPorEspecie::getRazas('Ave')),
                'Hamster': @json(App\Data\RazasPorEspecie::getRazas('Hamster'))
            };

            function actualizarRazas() {
                const especie = document.getElementById('especie').value;
                const razaSelect = document.getElementById('raza');
                razaSelect.innerHTML = '<option value="">Seleccione una raza</option>';

                if (especie && razasPorEspecie[especie]) {
                    razasPorEspecie[especie].forEach(raza => {
                        const option = document.createElement('option');
                        option.value = raza;
                        option.textContent = raza;
                        razaSelect.appendChild(option);
                    });
                }
            }

            document.addEventListener('DOMContentLoaded', function() {
                const especie = document.getElementById('especie').value;
                if (especie) {
                    actualizarRazas();
                    // Restaurar la raza seleccionada anteriormente
                    const razaAnterior = @json(old('raza'));
                    if (razaAnterior) {
                        document.getElementById('raza').value = razaAnterior;
                    }
                }
            });
        </script>
        @endpush
    @else
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4" role="alert">
                    <p>No tienes permiso para registrar mascotas.</p>
                </div>
            </div>
        </div>
    @endcan
</x-app-layout>
