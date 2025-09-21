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

    <div class="py-6 ml-64" id="main-content"> {{-- Añadir ml-64 para el sidebar --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Formulario de filtrado con estilos de la imagen -->
            <form action="{{ route('dashboard.usuarios') }}" method="GET" class="mb-6 flex flex-col md:flex-row items-center md:space-x-4 space-y-4 md:space-y-0 bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                <div class="relative flex-grow w-full md:w-auto">
                    <input type="text" name="search" class="w-full border border-gray-300 rounded-md py-2 px-4 pl-10 focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Buscar por nombre o email" value="{{ request('search') }}">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>
                <div class="w-full md:w-auto">
                     <select name="role" class="w-full border border-gray-300 rounded-md shadow-sm p-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Todos los roles</option>
                        <option value="administrador" {{ request('role') == 'administrador' ? 'selected' : '' }}>Administrador</option>
                        <option value="cliente_qr" {{ request('role') == 'cliente_qr' ? 'selected' : '' }}>Clientes</option>
                    </select>
                </div>
                <button type="submit" class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md shadow-sm flex items-center justify-center">
                   <i class="fas fa-filter mr-2"></i> Filtrar
                </button>
                 <a href="{{ route('dashboard.usuarios') }}" class="w-full md:w-auto bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-md shadow-sm inline-flex items-center justify-center text-center">
                   <i class="fas fa-sync-alt mr-2"></i> Limpiar
                </a>
            </form>

            <!-- Tarjetas de usuarios -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @if(count($usuarios) > 0)
                    @foreach ($usuarios as $usuario)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 p-6 border border-gray-200">
                            <div class="flex items-center mb-4">
                                {{-- Avatar o iniciales --}}
                                <div class="flex-shrink-0 w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-xl font-bold text-blue-800">
                                    {{ strtoupper(substr($usuario->name, 0, 1)) }}
                                </div>
                                <div class="ml-4">
                                    <p class="text-lg font-semibold text-gray-800">{{ $usuario->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $usuario->email }}</p>
                                </div>
                                {{-- Rol del usuario --}}
                                <div class="ml-auto">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full 
                                        @if($usuario->getRoleNames()->first() == 'administrador' || $usuario->getRoleNames()->first() == 'super_admin') bg-purple-100 text-purple-800
                                        @elseif($usuario->getRoleNames()->first() == 'cliente_qr') bg-green-100 text-green-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($usuario->getRoleNames()->first() ?? 'Sin rol') }}
                                    </span>
                                </div>
                            </div>

                            {{-- Botones de Acción --}}
                            <div class="flex justify-end space-x-2 mt-4">
                                {{-- Botón Ver Mascotas --}}
                                <a href="{{ route('usuarios.mascotas', $usuario->id) }}" class="bg-purple-500 hover:bg-purple-600 text-white font-bold py-2 px-4 rounded text-xs flex items-center">
                                     <i class="fas fa-paw mr-1"></i> Ver Mascotas
                                </a>
                                {{-- Botón Cambiar Clave --}}
                                <a href="{{ route('usuarios.editPassword', $usuario->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded text-xs flex items-center">
                                     <i class="fas fa-key mr-1"></i> Cambiar Clave
                                </a>
                                {{-- Botón Eliminar --}}
                                <form action="{{ route('usuarios.destroy', $usuario->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este usuario?')" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded text-xs flex items-center">
                                         <i class="fas fa-trash mr-1"></i> Eliminar
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
