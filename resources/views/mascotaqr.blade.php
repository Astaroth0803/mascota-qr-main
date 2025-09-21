<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @extends('layout')
    @section('title', 'Buky World | Registro de Mascota')
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body class="bg-gray-50">
    <header class="H_header">
        <x-main-nav></x-main-nav>
    </header>
 
    <main class="H_main max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Hero Section -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Registro de Mascota</h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Registra a tu mascota y mantén su información médica segura en la nube. Accede a su historial desde cualquier lugar.
            </p>
        </div>

        <!-- Features Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                <div class="text-blue-600 mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold mb-2">Información Segura</h3>
                <p class="text-gray-600">Toda la información de tu mascota está protegida y accesible solo para ti.</p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                <div class="text-blue-600 mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold mb-2">Acceso 24/7</h3>
                <p class="text-gray-600">Accede al historial médico de tu mascota en cualquier momento y lugar.</p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                <div class="text-blue-600 mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold mb-2">Historial Completo</h3>
                <p class="text-gray-600">Mantén un registro detallado de vacunas, tratamientos y visitas al veterinario.</p>
            </div>
        </div>

        <!-- Form Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-8">
            <div class="max-w-2xl mx-auto">
                <h2 class="text-2xl font-semibold mb-6">Información de la Mascota</h2>
                
                <form action="{{ route('solicitudes.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <!-- Información de la Mascota -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre de la Mascota</label>
                            <input type="text" name="nombre" id="nombre" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                value="{{ old('nombre') }}">
                            @error('nombre')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="especie" class="block text-sm font-medium text-gray-700">Especie</label>
                            <select name="especie" id="especie" required onchange="actualizarRazas()"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Seleccione una especie</option>
                                <option value="Perro" {{ old('especie') == 'Perro' ? 'selected' : '' }}>Perro</option>
                                <option value="Gato" {{ old('especie') == 'Gato' ? 'selected' : '' }}>Gato</option>
                            </select>
                            @error('especie')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="raza" class="block text-sm font-medium text-gray-700">Raza</label>
                            <select name="raza" id="raza" required onchange="mostrarOtraRaza()"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Primero seleccione una especie</option>
                            </select>
                            @error('raza')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="otra_raza_container" class="hidden">
                            <label for="otra_raza" class="block text-sm font-medium text-gray-700">Especifique la raza</label>
                            <input type="text" name="otra_raza" id="otra_raza"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                placeholder="Ingrese la raza de su mascota" value="{{ old('otra_raza') }}">
                            @error('otra_raza')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="edad" class="block text-sm font-medium text-gray-700">Edad</label>
                            <div class="grid grid-cols-2 gap-4 mt-1">
                                <div>
                                    <label for="edad_anios" class="block text-xs text-gray-500">Años</label>
                                    <input type="number" name="edad_anios" id="edad_anios" min="0" max="30"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        placeholder="0" value="{{ old('edad_anios') }}">
                                    @error('edad_anios')
                                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="edad_meses" class="block text-xs text-gray-500">Meses</label>
                                    <input type="number" name="edad_meses" id="edad_meses" min="0" max="11"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        placeholder="0" value="{{ old('edad_meses') }}">
                                    @error('edad_meses')
                                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Ingrese al menos uno de los dos campos</p>
                        </div>

                        <div>
                            <label for="sexo" class="block text-sm font-medium text-gray-700">Sexo</label>
                            <select name="sexo" id="sexo" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Seleccione el sexo</option>
                                <option value="Macho" {{ old('sexo') == 'Macho' ? 'selected' : '' }}>Macho</option>
                                <option value="Hembra" {{ old('sexo') == 'Hembra' ? 'selected' : '' }}>Hembra</option>
                            </select>
                            @error('sexo')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Información del Dueño -->
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Información del Dueño</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="nombre_owner" class="block text-sm font-medium text-gray-700">Nombre</label>
                                <input type="text" name="nombre_owner" id="nombre_owner" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    value="{{ old('nombre_owner') }}">
                                @error('nombre_owner')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="apellido_owner" class="block text-sm font-medium text-gray-700">Apellido</label>
                                <input type="text" name="apellido_owner" id="apellido_owner" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    value="{{ old('apellido_owner') }}">
                                @error('apellido_owner')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="telefono_owner" class="block text-sm font-medium text-gray-700">Teléfono</label>
                                <input type="tel" name="telefono_owner" id="telefono_owner" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    value="{{ old('telefono_owner') }}">
                                @error('telefono_owner')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="correo_owner" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                                <input type="email" name="correo_owner" id="correo_owner" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    value="{{ old('correo_owner') }}">
                                @error('correo_owner')
                                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Información de Pago -->
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Información de Pago</h3>
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        Para procesar tu solicitud, necesitamos el ID de tu pago con Yappy. 
                                        Realiza el pago al número +507 0000-0000 y proporciona el ID en el siguiente campo.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="id_pago_yappy" class="block text-sm font-medium text-gray-700">ID de Pago Yappy</label>
                            <input type="text" name="id_pago_yappy" id="id_pago_yappy" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                value="{{ old('id_pago_yappy') }}">
                            @error('id_pago_yappy')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" 
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Registrar Mascota
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <footer class="bg-white border-t border-gray-200 mt-12">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <p class="text-center text-gray-500 text-sm">
                &copy; {{ date('Y') }} Buky World. Todos los derechos reservados.
            </p>
        </div>
    </footer>

    <script>
        const razasPorEspecie = {
            'Perro': [
                'Labrador Retriever',
                'Pastor Alemán',
                'Golden Retriever',
                'Bulldog Francés',
                'Poodle',
                'Chihuahua',
                'Bulldog Inglés',
                'Rottweiler',
                'Yorkshire Terrier',
                'Boxer',
                'Doberman',
                'Shih Tzu',
                'Beagle',
                'Pug',
                'Husky Siberiano',
                'Otro'
            ],
            'Gato': [
                'Siamés',
                'Persa',
                'Maine Coon',
                'Ragdoll',
                'Bengalí',
                'Sphynx',
                'Abisinio',
                'Británico de Pelo Corto',
                'Ruso Azul',
                'Birmano',
                'Otro'
            ]
        };

        function actualizarRazas() {
            const especieSelect = document.getElementById('especie');
            const razaSelect = document.getElementById('raza');
            const especieSeleccionada = especieSelect.value;

            // Limpiar opciones actuales
            razaSelect.innerHTML = '';
            // Ocultar el campo de otra raza
            document.getElementById('otra_raza_container').classList.add('hidden');
            document.getElementById('otra_raza').required = false;

            if (especieSeleccionada) {
                // Agregar las razas correspondientes a la especie seleccionada
                razasPorEspecie[especieSeleccionada].forEach(raza => {
                    const option = document.createElement('option');
                    option.value = raza;
                    option.textContent = raza;
                    razaSelect.appendChild(option);
                });
            } else {
                // Si no hay especie seleccionada, mostrar mensaje
                const option = document.createElement('option');
                option.value = '';
                option.textContent = 'Primero seleccione una especie';
                razaSelect.appendChild(option);
            }
        }

        function mostrarOtraRaza() {
            const razaSelect = document.getElementById('raza');
            const otraRazaContainer = document.getElementById('otra_raza_container');
            const otraRazaInput = document.getElementById('otra_raza');

            if (razaSelect.value === 'Otro') {
                otraRazaContainer.classList.remove('hidden');
                otraRazaInput.required = true;
            } else {
                otraRazaContainer.classList.add('hidden');
                otraRazaInput.required = false;
                otraRazaInput.value = ''; // Limpiar el valor cuando se oculta
            }
        }

        // Validación del formulario
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                const razaSelect = document.getElementById('raza');
                const otraRazaInput = document.getElementById('otra_raza');
                const edadAnios = document.getElementById('edad_anios').value;
                const edadMeses = document.getElementById('edad_meses').value;
                
                // Validar raza
                if (razaSelect.value === 'Otro' && !otraRazaInput.value.trim()) {
                    e.preventDefault();
                    alert('Por favor, especifique la raza de su mascota');
                    return;
                }

                // Validar edad
                if (!edadAnios && !edadMeses) {
                    e.preventDefault();
                    alert('Por favor, ingrese al menos la edad en años o meses');
                    return;
                }

                // Validar que no se excedan los límites
                if (edadAnios > 30) {
                    e.preventDefault();
                    alert('La edad en años no puede ser mayor a 30');
                    return;
                }

                if (edadMeses > 11) {
                    e.preventDefault();
                    alert('La edad en meses no puede ser mayor a 11');
                    return;
                }
            });
        }
    </script>
</body>
</html>