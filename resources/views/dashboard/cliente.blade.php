<x-app-layout>

    {{-- Incluir el sidebar como componente --}}
    <x-sidebar-menu />

    {{-- Header responsive igual que administrador --}}
    <x-slot name="header">
        <div class="transition-all duration-300 ease-in-out lg:ml-64">
            <div class="bg-white overflow-hidden shadow rounded-lg p-4 sm:p-6 hover:shadow-lg transition-shadow duration-300 mb-1 mx-2 sm:mx-4 lg:mx-8">
                <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
                    <div class="text-center sm:text-left">
                        <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                            Mis Mascotas
                        </h2>
                        <p class="mt-1 text-sm sm:text-base text-gray-600">Administra y visualiza la información de tus mascotas</p>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    {{-- Contenido principal responsive para el sidebar --}}
    <div class="pt-1 lg:pt-12 lg:ml-64 transition-all duration-300 ease-in-out min-h-screen bg-gray-50">
        <div class="p-4 sm:p-6 lg:p-8">
            {{-- Header Section (ya está en el slot, así que lo quitamos de aquí) --}}

            @if($pets->isEmpty())
                <div class="rounded-xl shadow-sm p-6 sm:p-8 bg-gray-50">
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-12 h-12 sm:w-16 sm:h-16 rounded-full bg-orange-100 mb-4">
                            <svg class="w-6 h-6 sm:w-8 sm:h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">No tienes mascotas registradas</h3>
                        <p class="text-sm sm:text-base text-gray-600 mb-6">Comienza registrando tu primera mascota para mantener su información segura.</p>
                        <a href="{{ route('dashboard.cliente.registrar.mascota') }}"
                           class="inline-flex items-center px-4 sm:px-6 py-2 sm:py-3 border border-transparent text-sm font-medium rounded-full shadow-sm text-white bg-orange-500 hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-all duration-200">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Registrar Mascota
                        </a>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                    @foreach($pets as $pet)
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200 flex flex-col hover:shadow-xl transition-shadow duration-300">
                            {{-- Header de la tarjeta --}}
                            <div class="flex items-center p-4 sm:p-6 bg-gray-50 border-b border-gray-200">
                                <div class="flex-shrink-0 mr-3 sm:mr-4">
                                    <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-full overflow-hidden bg-gray-100 flex items-center justify-center">
                                        @if($pet->profile_image)
                                            <img src="{{ Storage::url($pet->profile_image) }}"
                                                 alt="{{ $pet->nombre }}"
                                                 class="w-full h-full object-cover">
                                        @else
                                            <svg class="w-6 h-6 sm:w-10 sm:h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        @endif
                                    </div>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h3 class="text-lg sm:text-xl font-semibold text-gray-900 truncate">{{ $pet->nombre }}</h3>
                                    <p class="text-xs sm:text-sm text-gray-600 truncate">{{ $pet->especie }} - {{ $pet->raza }}</p>
                                </div>
                            </div>

                            {{-- Contenido de la tarjeta --}}
                            <div class="p-4 sm:p-6 space-y-4 sm:space-y-6 flex-grow">
                                {{-- Datos de la mascota --}}
                                <div class="border border-gray-200 rounded-lg p-3 sm:p-4">
                                    <h4 class="text-sm sm:text-lg font-semibold text-gray-800 mb-2 sm:mb-3 flex items-center">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-orange-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                        <span class="hidden sm:inline">Datos de {{ $pet->nombre }}</span>
                                        <span class="sm:hidden">Datos</span>
                                    </h4>
                                    <div class="text-gray-700 space-y-1 sm:space-y-2">
                                        <p class="text-xs sm:text-sm"><span class="font-medium">Raza:</span> {{ $pet->raza }}</p>
                                        <p class="text-xs sm:text-sm"><span class="font-medium">Edad:</span> {{ $pet->edad_anios }} año{{ $pet->edad_anios == 1 ? '' : 's' }} y {{ $pet->edad_meses }} mes{{ $pet->edad_meses == 1 ? '' : 'es' }}</p>
                                        <p class="text-xs sm:text-sm"><span class="font-medium">Sexo:</span> {{ $pet->sexo }}</p>
                                    </div>
                                </div>

                                {{-- Historial Médico --}}
                                <div class="border border-gray-200 rounded-lg p-3 sm:p-4">
                                    <h4 class="text-sm sm:text-lg font-semibold text-gray-800 mb-2 sm:mb-3 flex items-center">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        <span class="hidden sm:inline">Historial Médico</span>
                                        <span class="sm:hidden">Médico</span>
                                    </h4>
                                    @if($pet->vaccine_file)
                                         <div class="flex items-center justify-between">
                                             <span class="text-xs sm:text-sm text-gray-700">Certificado adjuntado</span>
                                         </div>
                                    @else
                                        @if($pet->vaccinationRecords->count() > 0)
                                             <span class="text-xs sm:text-sm text-gray-700">{{ $pet->vaccinationRecords->count() }} registros</span>
                                        @else
                                            <span class="text-xs sm:text-sm text-gray-500">Sin registros</span>
                                        @endif
                                    @endif

                                    <div class="mt-3 sm:mt-4 text-right">
                                         <a href="{{ route('dashboard.cliente.mascotas.vaccination-history', $pet->id) }}"
                                            class="inline-flex items-center px-3 sm:px-4 py-1.5 sm:py-2 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-600 focus:outline-none focus:border-green-700 focus:ring focus:ring-green-300 disabled:opacity-25 transition">
                                             <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7H9m1.5-4H9m3 4H9m6 4H9" /></svg>
                                            <span class="hidden sm:inline">Ver Historial</span>
                                            <span class="sm:hidden">Historial</span>
                                         </a>
                                    </div>
                                </div>

                                {{-- Próxima Cita --}}
                                @if(isset($pet->next_appointment))
                                <div class="border border-gray-200 rounded-lg p-3 sm:p-4">
                                    <h4 class="text-sm sm:text-lg font-semibold text-gray-800 mb-2 sm:mb-3 flex items-center">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                        <span class="hidden sm:inline">Próxima Cita</span>
                                        <span class="sm:hidden">Cita</span>
                                    </h4>
                                    <p class="text-xs sm:text-sm text-gray-700 mb-2 sm:mb-3">{{ $pet->next_appointment->fecha }} a las {{ $pet->next_appointment->hora }}</p>
                                    <a href="{{ route('dashboard.cliente.citas.show', $pet->next_appointment->id) }}" 
                                       class="inline-flex items-center px-3 sm:px-4 py-1.5 sm:py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600 transition">
                                        <span class="hidden sm:inline">Ver Detalles</span>
                                        <span class="sm:hidden">Detalles</span>
                                    </a>
                                </div>
                                @endif
                            </div>

                            {{-- Botones de acción --}}
                            <div class="p-4 sm:p-6 bg-gray-50 border-t border-gray-200">
                                <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                                    <a href="{{ route('dashboard.cliente.mascotas.edit', $pet->id) }}"
                                       class="flex-1 sm:flex-none inline-flex items-center justify-center px-3 sm:px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-300 disabled:opacity-25 transition">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                        <span class="hidden sm:inline">Editar</span>
                                        <span class="sm:hidden">Editar</span>
                                    </a>
                                    <a href="{{ route('dashboard.cliente.mascotas.show', $pet->id) }}"
                                       class="flex-1 sm:flex-none inline-flex items-center justify-center px-3 sm:px-4 py-2 bg-orange-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-600 active:bg-orange-700 focus:outline-none focus:border-orange-700 focus:ring focus:ring-orange-300 disabled:opacity-25 transition">
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                        <span class="hidden sm:inline">Ver Detalles</span>
                                        <span class="sm:hidden">Detalles</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
