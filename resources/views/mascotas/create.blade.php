<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Registrar Nueva Mascota') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('dashboard.cliente.mascotas.store') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <div>
                            <x-input-label for="nombre" :value="__('Nombre de la Mascota')" />
                            <x-text-input id="nombre" name="nombre" type="text" class="mt-1 block w-full" required value="{{ old('nombre') }}" />
                            <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="especie" :value="__('Especie')" />
                            <x-text-input id="especie" name="especie" type="text" class="mt-1 block w-full" required value="{{ old('especie') }}" />
                            <x-input-error :messages="$errors->get('especie')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="raza" :value="__('Raza')" />
                            <x-text-input id="raza" name="raza" type="text" class="mt-1 block w-full" required value="{{ old('raza') }}" />
                            <x-input-error :messages="$errors->get('raza')" class="mt-2" />
                        </div>

                        <div class="flex space-x-4">
                            <div class="flex-1">
                                <x-input-label for="edad_anios" :value="__('Edad (Años)')" />
                                <x-text-input id="edad_anios" name="edad_anios" type="number" class="mt-1 block w-full" value="{{ old('edad_anios') }}" min="0" max="30"/>
                                <x-input-error :messages="$errors->get('edad_anios')" class="mt-2" />
                            </div>
                            <div class="flex-1">
                                <x-input-label for="edad_meses" :value="__('Edad (Meses)')" />
                                <x-text-input id="edad_meses" name="edad_meses" type="number" class="mt-1 block w-full" value="{{ old('edad_meses') }}" min="0" max="11"/>
                                <x-input-error :messages="$errors->get('edad_meses')" class="mt-2" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="sexo" :value="__('Sexo')" />
                            <select id="sexo" name="sexo" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Seleccionar sexo</option>
                                <option value="Macho" {{ old('sexo') == 'Macho' ? 'selected' : '' }}>Macho</option>
                                <option value="Hembra" {{ old('sexo') == 'Hembra' ? 'selected' : '' }}>Hembra</option>
                            </select>
                            <x-input-error :messages="$errors->get('sexo')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="profile_image" :value="__('Foto de la Mascota (Opcional)')" />
                            <input type="file" id="profile_image" name="profile_image" class="mt-1 block w-full" accept="image/*">
                            <x-input-error :messages="$errors->get('profile_image')" class="mt-2" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Enviar Solicitud de Mascota') }}</x-primary-button>
                            <a href="{{ route('dashboard.cliente.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                {{ __('Cancelar') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 