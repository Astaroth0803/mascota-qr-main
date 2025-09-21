<x-app-layout>
    <x-slot name="header">
    <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestión de Usuarios') }}
            </h2>
            <a href="{{ route('usuarios.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md shadow-sm inline-flex items-center justify-center">
                <i class="fas fa-plus mr-2"></i> Nuevo Usuario
            </a>
        </div>
    </x-slot>

    {{-- Incluir el sidebar funcional --}}
    <x-sidebar-menu :active="'usuarios'" /> {{-- Marcar Usuarios como activo --}}
    
@if (session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded" role="alert">
        <p class="font-bold">Error</p>
        <p>{{ session('error') }}</p>
    </div>
@endif

    <div class="py-4 sm:py-6 lg:ml-64" id="main-content"> {{-- Añadir ml-64 para el sidebar --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Formulario de filtrado con estilos de la imagen -->
            <form action="{{ route('dashboard.usuarios') }}" method="GET" class="mb-4 sm:mb-6 flex flex-col sm:flex-row items-stretch sm:items-center gap-3 sm:gap-4 bg-white p-3 sm:p-4 rounded-lg shadow-sm border border-gray-200">
                <div class="relative flex-grow w-full sm:w-auto">
                    <input type="text" name="search" class="w-full border border-gray-300 rounded-md py-2 px-4 pl-10 focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base" placeholder="Buscar por nombre o email" value="{{ request('search') }}">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>
                <div class="w-full sm:w-auto">
                     <select name="role" class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm sm:text-base">
                        <option value="">Todos los roles</option>
                        <option value="super_admin" {{ request('role') == 'super_admin' ? 'selected' : '' }}>Administrador</option>
                        <option value="cliente_qr" {{ request('role') == 'cliente_qr' ? 'selected' : '' }}>Clientes</option>
                    </select>
                </div>
                <button type="submit" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-3 sm:px-4 rounded-md shadow-sm flex items-center justify-center text-sm sm:text-base">
                   <i class="fas fa-filter mr-1 sm:mr-2"></i> <span class="hidden sm:inline">Filtrar</span><span class="sm:hidden">Filtrar</span>
                </button>
                 <a href="{{ route('dashboard.usuarios') }}" class="w-full sm:w-auto bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-3 sm:px-4 rounded-md shadow-sm inline-flex items-center justify-center text-center text-sm sm:text-base">
                   <i class="fas fa-sync-alt mr-1 sm:mr-2"></i> <span class="hidden sm:inline">Limpiar</span><span class="sm:hidden">Limpiar</span>
                </a>
            </form>

            <!-- Tarjetas de usuarios -->
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-6">
                @if(count($usuarios) > 0)
                    @foreach ($usuarios as $usuario)
                        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 p-4 border border-gray-200 h-full flex flex-col">
                            {{-- Header con avatar y rol --}}
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center min-w-0 flex-1">
                                    {{-- Avatar o iniciales --}}
                                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-sm font-bold text-blue-800">
                                        {{ strtoupper(substr($usuario->name, 0, 1)) }}
                                    </div>
                                    <div class="ml-3 min-w-0 flex-1">
                                        <p class="text-sm font-semibold text-gray-800 truncate">{{ $usuario->name }}</p>
                                        <p class="text-xs text-gray-600 truncate">{{ $usuario->email }}</p>
                                    </div>
                                </div>
                                {{-- Rol del usuario --}}
                                <div class="flex-shrink-0 ml-2">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                        @if($usuario->getRoleNames()->first() == 'administrador' || $usuario->getRoleNames()->first() == 'super_admin') bg-purple-100 text-purple-800
                                        @elseif($usuario->getRoleNames()->first() == 'cliente_qr') bg-green-100 text-green-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($usuario->getRoleNames()->first() ?? 'Sin rol') }}
                                    </span>
                                </div>
                            </div>

                            {{-- Botones de Acción --}}
                            <div class="flex flex-wrap gap-2 mt-auto pt-3">
                                {{-- Botón Ver Mascotas --}}
                                <a href="{{ route('usuarios.mascotas', $usuario->id) }}" 
                                   class="inline-flex items-center px-2.5 py-1.5 bg-purple-500 hover:bg-purple-600 text-white text-xs font-medium rounded-md transition-colors duration-200">
                                    <i class="fas fa-paw mr-1.5 text-xs"></i>
                                    <span class="hidden sm:inline">Ver Mascotas</span>
                                    <span class="sm:hidden">Mascotas</span>
                                </a>
                                
                                {{-- Botón Cambiar Clave --}}
                                <a href="{{ route('usuarios.editPassword', $usuario->id) }}" 
                                   class="inline-flex items-center px-2.5 py-1.5 bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-medium rounded-md transition-colors duration-200">
                                    <i class="fas fa-key mr-1.5 text-xs"></i>
                                    <span class="hidden sm:inline">Cambiar Clave</span>
                                    <span class="sm:hidden">Clave</span>
                                </a>
                                
                                {{-- Botón Eliminar --}}
                                <form action="{{ route('usuarios.destroy', $usuario->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este usuario?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center px-2.5 py-1.5 bg-red-500 hover:bg-red-600 text-white text-xs font-medium rounded-md transition-colors duration-200">
                                        <i class="fas fa-trash mr-1.5 text-xs"></i>
                                        <span class="hidden sm:inline">Eliminar</span>
                                        <span class="sm:hidden">Eliminar</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="md:col-span-3 text-center py-8">
                        <p class="text-gray-500 text-lg">No hay usuarios encontrados.</p>
                    </div>
                @endif
            </div>

            <!-- Paginación -->
            @if($usuarios->hasPages())
                <div class="mt-6">
                    {{ $usuarios->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
