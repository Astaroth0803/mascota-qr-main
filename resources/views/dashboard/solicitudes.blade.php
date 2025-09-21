<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestionar Solicitudes') }}
            </h2>
            {{-- Botón para crear nueva solicitud (si aplica) --}}
            {{-- <a href="{{ route('solicitudes.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-plus mr-2"></i> Nueva Solicitud
            </a> --}}
        </div>
    </x-slot>

    {{-- Incluir el sidebar funcional --}}
    <x-sidebar-menu :active="'solicitudes'" :pendingRequests="$solicitudes->total() ?? 0" /> {{-- Usar el total de la paginación --}}

    <div class="py-6 ml-64" id="main-content"> {{-- Añadir margen izquierdo para el sidebar --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
                    <p class="font-bold">Éxito</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded" role="alert">
                    <p class="font-bold">Error</p>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                {{-- <h3 class="text-lg font-medium text-gray-900 mb-4">Filtros de Solicitudes</h3> --}}

                <!-- Formulario de filtrado con estilos de la imagen -->
                <form action="{{ route('dashboard.solicitudes') }}" method="GET" class="mb-6 flex flex-col md:flex-row items-center md:space-x-4 space-y-4 md:space-y-0">
                    <div class="relative flex-grow w-full md:w-auto">
                        <input type="text" name="search" class="w-full border border-gray-300 rounded-md py-2 px-4 pl-10 focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Buscar por nombre de mascota o dueño" value="{{ request('search') }}">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </div>
                    <div class="w-full md:w-auto">
                         <select id="status" name="status" class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Todos los estados</option>
                            <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Verificadas</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendientes</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rechazadas</option>
                         </select>
                    </div>
                    <button type="submit" class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md shadow-sm flex items-center justify-center">
                        <i class="fas fa-filter mr-2"></i> Filtrar
                    </button>
                    <a href="{{ route('dashboard.solicitudes') }}" class="w-full md:w-auto bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-md shadow-sm inline-flex items-center justify-center text-center">
                       <i class="fas fa-sync-alt mr-2"></i> Limpiar
                    </a>
                </form>

                <div class="mt-8">
                    {{-- <h3 class="text-lg font-medium text-gray-900 mb-4">Listado de Solicitudes</h3> --}}
                    
                    {{-- Reemplazar la tabla por una cuadrícula de tarjetas --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @if(count($solicitudes) > 0)
                            @foreach ($solicitudes as $solicitud)
                                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 p-6 border border-gray-200">
                                    <div class="flex items-center justify-between mb-4">
                                        {{-- Avatar o iniciales --}}
                                        <div class="flex-shrink-0 w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-xl font-bold text-blue-800">
                                            {{ strtoupper(substr($solicitud->nombre_owner, 0, 1)) }}{{ strtoupper(substr($solicitud->apellido_owner, 0, 1)) ?? '' }}
                                        </div>
                                        {{-- Estado de la solicitud --}}
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                            @if($solicitud->status == 'verified') bg-green-100 text-green-800
                                            @elseif($solicitud->status == 'pending') bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ ucfirst($solicitud->status ?? 'pendiente') }} {{-- Mostrar 'pendiente' si el estado es null --}}
                                        </span>
                                    </div>
                                    
                                    {{-- Información del dueño y mascota --}}
                                    <div class="mb-4">
                                        <p class="text-lg font-semibold text-gray-800">{{ $solicitud->nombre_owner }} {{ $solicitud->apellido_owner }}</p>
                                        <p class="text-sm text-gray-600">{{ $solicitud->correo_owner }}</p>
                                        <p class="text-md font-medium text-gray-700 mt-2">Mascota: {{ $solicitud->nombre }} ({{ $solicitud->especie }} - {{ $solicitud->raza }})</p>
                                        <p class="text-sm text-gray-600 mt-1">
                                            Edad:
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
                                        <p class="text-sm text-gray-600 mt-1">Sexo: {{ $solicitud->sexo }}</p>
                                    </div>

                                     {{-- ID de Pago --}}
                                    <div class="mb-4 text-sm text-gray-700">
                                        <strong>ID Pago Yappy:</strong> {{ $solicitud->id_pago_yappy ?? 'N/A' }}
                                    </div>

                                    {{-- Botones de Acción --}}
                                    <div class="flex justify-end space-x-2 mt-6">
                                   
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
                                     
                                        {{-- Botón Ver Detalles --}}
                                        <a href="{{ route('solicitudes.show', $solicitud->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-xs flex items-center">
                                             <i class="fas fa-eye mr-1"></i> Ver
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="md:col-span-3 text-center py-8">
                                <p class="text-gray-500 text-lg">No hay solicitudes encontradas.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Paginación con estilos mejorados -->
                    @if($solicitudes->hasPages())
                        <div class="mt-6">
                            {{ $solicitudes->links() }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
