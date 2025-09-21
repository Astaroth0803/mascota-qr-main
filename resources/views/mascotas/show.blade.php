<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
            <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                {{ __('Detalles de Mascota') }}
            </h2>
        </div>
    </x-slot>

    {{-- Incluir el sidebar como componente --}}
    <x-sidebar-menu />

    {{-- Contenido principal con margen responsive para el sidebar --}}
    <div class="lg:ml-64 transition-all duration-300 ease-in-out">
        <div class="min-h-screen bg-gray-50">
            <div class="p-4 sm:p-6 lg:p-8">
                {{-- Título de la vista --}}
                <div class="mb-4 sm:mb-6 text-center">
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-800">{{ $pet->nombre }}</h1>
                </div>

                {{-- Banner con gradiente y avatar --}}
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg shadow-lg p-4 sm:p-6 mb-4 sm:mb-6 relative overflow-hidden">
                    <div class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-4">
                        <div class="relative group">
                            <div class="w-20 h-20 sm:w-24 sm:h-24 lg:w-[100px] lg:h-[100px] bg-white rounded-full shadow-lg flex items-center justify-center overflow-hidden">
                                @if($pet->profile_image)
                                    <img src="{{ Storage::url($pet->profile_image) }}" alt="{{ $pet->nombre }}" class="w-full h-full object-cover">
                                @else
                                    <svg class="w-12 h-12 sm:w-16 sm:h-16 lg:w-[80px] lg:h-[80px] text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                @endif
                            </div>
                            <form action="{{ route('dashboard.cliente.mascotas.update-image', $pet->id) }}" method="POST" enctype="multipart/form-data" class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                @csrf
                                @method('PUT')
                                <label for="profile_image" class="cursor-pointer bg-black bg-opacity-50 rounded-full p-1 sm:p-2">
                                    <svg class="w-4 h-4 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <input type="file" name="profile_image" id="profile_image" class="hidden" accept="image/*" onchange="this.form.submit()">
                                </label>
                            </form>
                        </div>
                        <div class="text-white text-center sm:text-left">
                            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold">{{ $pet->nombre }}</h1>
                            <p class="text-sm sm:text-base lg:text-lg opacity-90">{{ $pet->especie }}</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                    {{-- Información de la Mascota --}}
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-4 sm:p-6">
                            <h2 class="text-lg sm:text-xl font-semibold text-gray-800 mb-3 sm:mb-4 flex items-center">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span class="hidden sm:inline">Información de la Mascota</span>
                                <span class="sm:hidden">Información</span>
                            </h2>
                            <div class="space-y-3 sm:space-y-4">
                                <div class="bg-gray-50 rounded-lg p-3 sm:p-4 hover:shadow-md transition-shadow">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-500 mr-2 sm:mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-xs sm:text-sm text-gray-500">Raza</p>
                                            <p class="font-medium text-gray-900 truncate">{{ $pet->raza }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-gray-50 rounded-lg p-3 sm:p-4 hover:shadow-md transition-shadow">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-500 mr-2 sm:mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-xs sm:text-sm text-gray-500">Edad</p>
                                            <p class="font-medium text-gray-900">
                                                {{ $pet->edad_anios }} años y {{ $pet->edad_meses }} meses
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-gray-50 rounded-lg p-3 sm:p-4 hover:shadow-md transition-shadow">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-purple-500 mr-2 sm:mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-xs sm:text-sm text-gray-500">Sexo</p>
                                            <p class="font-medium text-gray-900">{{ $pet->sexo }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Información del Dueño --}}
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-4 sm:p-6">
                            <h2 class="text-lg sm:text-xl font-semibold text-gray-800 mb-3 sm:mb-4 flex items-center">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span class="hidden sm:inline">Información del Dueño</span>
                                <span class="sm:hidden">Dueño</span>
                            </h2>
                            <div class="space-y-3 sm:space-y-4">
                                <div class="bg-gray-50 rounded-lg p-3 sm:p-4 hover:shadow-md transition-shadow">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-indigo-500 mr-2 sm:mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-xs sm:text-sm text-gray-500">Nombre</p>
                                            <p class="font-medium text-gray-900 truncate">{{ $pet->user->name }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-gray-50 rounded-lg p-3 sm:p-4 hover:shadow-md transition-shadow">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-indigo-500 mr-2 sm:mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-xs sm:text-sm text-gray-500">Correo</p>
                                            <p class="font-medium text-gray-900 truncate">{{ $pet->user->email }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-gray-50 rounded-lg p-3 sm:p-4 hover:shadow-md transition-shadow">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-indigo-500 mr-2 sm:mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-xs sm:text-sm text-gray-500">Teléfono</p>
                                            <p class="font-medium text-gray-900 truncate">{{ $pet->telefono_owner }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Botones de Acción --}}
                <div class="mt-4 sm:mt-6 flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-4">
                    <a href="{{ route('dashboard.cliente.mascotas.edit', $pet->id) }}" 
                       class="inline-flex items-center justify-center px-3 sm:px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-300 disabled:opacity-25 transition">
                        <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <span class="hidden sm:inline">Editar Mascota</span>
                        <span class="sm:hidden">Editar</span>
                    </a>
                    <a href="{{ route('dashboard.cliente.index') }}" 
                       class="inline-flex items-center justify-center px-3 sm:px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 active:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                        <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <span class="hidden sm:inline">Volver</span>
                        <span class="sm:hidden">Volver</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 