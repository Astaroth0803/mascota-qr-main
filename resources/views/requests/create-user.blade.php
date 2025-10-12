@extends('layouts.standard')

@section('title', 'Crear Usuario')

@php
    $title = 'Crear Usuario';
    $subtitle = 'Registra un nuevo usuario en el sistema';
@endphp

@section('main-content')
<div class="w-full">

    <!-- Contenido principal -->
    <div class="w-full">
        <div class="px-2 sm:px-4 lg:px-6 xl:px-8 py-4 lg:py-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-4 sm:p-6 border border-gray-200 dark:border-gray-700">
                <form action="{{ route('usuarios.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                        <!-- Nombre -->
                        <div class="space-y-2">
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Nombre
                            </label>
                            <div class="relative">
                                <input type="text" name="name" id="name" value="{{ old('name') }}" 
                                       class="w-full p-2 sm:p-3 pl-12 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror text-sm sm:text-base"
                                       placeholder="Nombre completo">
                                <svg class="w-5 h-5 absolute left-3 top-2.5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            @error('name')
                                <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="space-y-2">
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Email
                            </label>
                            <div class="relative">
                                <input type="email" name="email" id="email" value="{{ old('email') }}"
                                       class="w-full p-2 sm:p-3 pl-12 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror text-sm sm:text-base"
                                       placeholder="correo@ejemplo.com">
                                <svg class="w-5 h-5 absolute left-3 top-2.5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            @error('email')
                                <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Contraseña -->
                        <div class="space-y-2">
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Contraseña
                            </label>
                            <div class="relative">
                                <input type="password" name="password" id="password"
                                       class="w-full p-2 sm:p-3 pl-12 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror text-sm sm:text-base"
                                       placeholder="••••••••">
                                <svg class="w-5 h-5 absolute left-3 top-2.5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            @error('password')
                                <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirmar Contraseña -->
                        <div class="space-y-2">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Confirmar Contraseña
                            </label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                       class="w-full p-2 sm:p-3 pl-12 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base"
                                       placeholder="••••••••">
                                <svg class="w-5 h-5 absolute left-3 top-2.5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            @error('password_confirmation')
                                <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Tipo de Veterinario -->
                    <div class="space-y-2">
                        <label for="tipo_veterinario" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Tipo de Veterinario <span class="text-gray-500">(Solo para veterinarios)</span>
                        </label>
                        <select name="tipo_veterinario" id="tipo_veterinario" 
                                class="w-full p-2 sm:p-3 border border-gray-300 rounded-lg bg-white text-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('tipo_veterinario') border-red-500 @enderror text-sm sm:text-base">
                            <option value="">Seleccionar tipo</option>
                            @foreach(App\Models\User::getTiposVeterinarios() as $key => $value)
                                <option value="{{ $key }}" {{ old('tipo_veterinario') == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                        @error('tipo_veterinario')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Roles -->
                    <div class="space-y-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Rol <span class="text-gray-500">(Opcional)</span>
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($roles as $role)
                                <div class="flex items-center p-4 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors duration-200">
                                    <input type="radio" name="role" value="{{ $role->name }}" id="role_{{ $role->id }}"
                                           class="w-4 h-4 text-blue-600 bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2"
                                           {{ old('role') == $role->name ? 'checked' : '' }}>
                                    <label for="role_{{ $role->id }}" class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">
                                        {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @error('role')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('dashboard.usuarios') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <i class="fas fa-times mr-2"></i>
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-user-plus mr-2"></i>
                            Crear Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const radioButtons = document.querySelectorAll('input[type="radio"]');
        if (radioButtons && radioButtons.length > 0) {
            radioButtons.forEach(radio => {
                radio.addEventListener('change', function () {
                    // Opcional: Si quieres deseleccionar un radio button haciendo clic nuevamente
                    // Esto no es comportamiento estándar de radio buttons, pero a veces se desea.
                    // Si implementas lógica compleja aquí, asegúrate de manejar bien el estado.
                    // El comportamiento estándar es que al seleccionar uno, los demás se deseleccionan automáticamente.
                    // No necesitamos código extra para la deselección automática entre radios.

                    // Si necesitas hacer algo visualmente diferente al seleccionar (ej. cambiar color del contenedor)
                    // puedes iterar y actualizar clases aquí.
                     radioButtons.forEach(rb => {
                         rb.closest('div').classList.remove('bg-blue-200'); // Ejemplo: quitar highlight anterior
                     });
                     if(this.checked) {
                         this.closest('div').classList.add('bg-blue-200'); // Ejemplo: añadir highlight al seleccionado
                     }
                });
            });
        }
    });
</script> 