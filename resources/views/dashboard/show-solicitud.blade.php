<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalles de la Solicitud') }}
            </h2>
            <a href="{{ route('dashboard.solicitudes') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <i class="fas fa-arrow-left mr-2"></i>
                Volver a Solicitudes
            </a>
        </div>
    </x-slot>

    {{-- Incluir el sidebar funcional (si aplica a esta vista, ajusta active si es necesario) --}}
    {{-- <x-sidebar-menu :active="'solicitudes'" /> --}}

    <div class="py-6 ml-64" id="main-content"> {{-- Ajusta ml-64 si el sidebar no está incluido --}}
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Información de la Solicitud #{{ $solicitud->id }}</h3>

                <div class="space-y-4">
                    <div>
                        <p class="text-sm font-medium text-gray-700">Nombre del Dueño:</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $solicitud->nombre_owner }} {{ $solicitud->apellido_owner }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-700">Correo Electrónico:</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $solicitud->correo_owner }}</p>
                    </div>
                     <div>
                        <p class="text-sm font-medium text-gray-700">Teléfono del Dueño:</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $solicitud->telefono_owner ?? 'N/A' }}</p>
                    </div>
                    
                    <hr class="my-4">

                    <div>
                        <p class="text-sm font-medium text-gray-700">Nombre de la Mascota:</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $solicitud->nombre }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-700">Especie:</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $solicitud->especie }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-700">Raza:</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $solicitud->raza }}</p> {{-- Muestra la raza final --}}
                    </div>
                    <div>
                         <p class="text-sm font-medium text-gray-700">Edad:</p>
                         <p class="mt-1 text-sm text-gray-900">
                             @if($solicitud->edad_anios || $solicitud->edad_meses)
                                @if($solicitud->edad_anios)
                                     {{ $solicitud->edad_anios }} año{{ $solicitud->edad_anios > 1 ? 's' : '' }}
                                 @endif
                                 @if($solicitud->edad_meses)
                                     @if($solicitud->edad_anios) y @endif
                                     {{ $solicitud->edad_meses }} mes{{ $solicitud->edad_meses > 1 ? 'es' : '' }}
                                 @endif
                             @else
                                 No especificada
                             @endif
                         </p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-700">Sexo:</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $solicitud->sexo }}</p>
                    </div>

                    <hr class="my-4">

                    <div>
                        <p class="text-sm font-medium text-gray-700">ID de Pago Yappy:</p>
                        <p class="mt-1 text-sm text-gray-900">{{ $solicitud->id_pago_yappy ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-700">Estado:</p>
                        <p class="mt-1 text-sm text-gray-900">{{ ucfirst($solicitud->status ?? 'pendiente') }}</p>
                    </div>
                </div>

                {{-- Puedes añadir botones de acción aquí si son relevantes en esta vista --}}
                {{-- Por ejemplo: Aceptar/Rechazar si el estado es pendiente --}}
                 <div class="flex justify-end space-x-2 mt-6">
                      @if($solicitud->status == 'pending')
                         {{-- Botón Aceptar --}}
                         <form action="{{ route('solicitudes.accept', $solicitud->id) }}" method="POST" class="inline-block">
                             @csrf
                             @method('PATCH')
                             <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-xs flex items-center">
                                 <i class="fas fa-check-circle mr-1"></i> Aceptar Solicitud
                             </button>
                         </form>
                         {{-- Botón Rechazar --}}
                         <form action="{{ route('solicitudes.reject', $solicitud->id) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de rechazar esta solicitud?');">
                             @csrf
                             @method('DELETE')
                             <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-xs flex items-center">
                                 <i class="fas fa-times-circle mr-1"></i> Rechazar Solicitud
                             </button>
                         </form>
                      @endif
                 </div>
            </div>
        </div>
    </div>
</x-app-layout> 