{{-- Incluir el sidebar funcional --}}
    @props(['active' => 'dashboard', 'pendingRequests' => 0])

    {{-- Navbar para móviles --}}
    <nav x-data="{ open: false }" class="lg:hidden fixed top-0 left-0 right-0 z-40 bg-white border-b border-gray-200 shadow-sm">
        <div class="flex items-center justify-between px-4 py-3">
            <div class="flex items-center space-x-3">
                
                <span class="text-lg font-bold text-gray-800">Buky World</span>
            </div>
            <div class="flex items-center space-x-2">
                <!-- Botón hamburger -->
                <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition">
                    <svg x-show="!open" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg x-show="open" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <!-- Botón logout -->
                <form method="POST" action="{{ route('logout') }}" class="flex-shrink-0">
                    @csrf
                    <button type="submit" class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 hover:text-gray-900 rounded-lg transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span class="hidden sm:inline">Cerrar sesión</span>
                        <span class="sm:hidden">Salir</span>
                    </button>
                </form>
            </div>
        </div>
        <!-- Menú desplegable -->
        <div x-show="open" x-transition class="px-4 pb-3">
            <nav class="space-y-2">
                <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ $active === 'dashboard' ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-blue-50 hover:text-blue-600' }}">Dashboard</a>
                @if(auth()->user()->hasRole('administrador') || auth()->user()->hasRole('super_admin'))
                    <a href="{{ route('dashboard.usuarios') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ $active === 'usuarios' ? 'bg-green-100 text-green-700' : 'text-gray-600 hover:bg-green-50 hover:text-green-600' }}">Usuarios</a>
                    <a href="{{ route('usuarios.create') }}" class="block px-3 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-green-50 hover:text-green-600">Nuevo Usuario</a>
                    <a href="{{ route('dashboard.solicitudes') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ $active === 'solicitudes' ? 'bg-orange-100 text-orange-700' : 'text-gray-600 hover:bg-orange-50 hover:text-orange-600' }}">Solicitudes @if($pendingRequests > 0)<span class="ml-2 inline-flex h-5 w-5 items-center justify-center rounded-full bg-orange-500 text-xs font-medium text-white">{{ $pendingRequests }}</span>@endif</a>
                    <a href="{{ route('qr.generator') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ $active === 'qr-generator' ? 'bg-purple-100 text-purple-700' : 'text-gray-600 hover:bg-purple-50 hover:text-purple-600' }}">Generador QR</a>
                @endif
                <a href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-purple-50 hover:text-purple-700">Perfil</a>
            </nav>
        </div>
    </nav>

    {{-- Sidebar para desktop --}}
    <aside class="hidden lg:block fixed top-0 left-0 z-40 h-screen w-64 bg-white border-r border-gray-200 shadow-lg">
        <!-- Logo -->
        <div class="flex h-16 items-center border-b border-gray-200 px-4 bg-gradient-to-r from-blue-50 to-indigo-50">
            <div class="flex items-center space-x-3">
                <span class="text-lg sm:text-xl font-bold text-gray-800">Buky World</span>
            </div>
        </div>

        <!-- Enlaces principales -->
        <div class="flex flex-col h-full bg-gray-50">
            <div class="flex-1 py-4 overflow-y-auto">
                <nav class="space-y-2 px-4">
                    <!-- Dashboard -->
                    <a href="{{ route('dashboard') }}"
                        @class([
                             'group flex items-center rounded-lg px-3 sm:px-4 py-3 text-sm font-medium transition-all duration-200',
                             'bg-blue-100 text-blue-700 border-l-4 border-blue-500 shadow-sm' => $active === 'dashboard',
                             'text-gray-600 hover:bg-blue-50 hover:text-blue-600 hover:border-l-4 hover:border-blue-300' => $active !== 'dashboard'
                        ])>
                        <svg class="mr-3 h-4 w-4 sm:h-5 sm:w-5"
                             @class([
                                 'text-blue-600' => $active === 'dashboard',
                                 'text-gray-500 group-hover:text-blue-500' => $active !== 'dashboard'
                             ])
                             fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span class="truncate">Dashboard</span>
                    </a>

                    @if(auth()->user()->hasRole('administrador') || auth()->user()->hasRole('super_admin'))
                        <!-- Menú desplegable de Usuarios -->
                        <div class="space-y-2">
                            <div class="group flex w-full items-center rounded-lg px-3 sm:px-4 py-3 text-sm font-medium text-gray-600 hover:bg-green-50 hover:text-green-600 hover:border-l-4 hover:border-green-300 transition-all duration-200">
                                <svg class="mr-3 h-4 w-4 sm:h-5 sm:w-5 text-gray-500 group-hover:text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <span class="flex-1 truncate">Usuarios</span>
                            </div>
                            <div class="space-y-1 pl-8 sm:pl-11">
                                <a href="{{ route('dashboard.usuarios') }}"
                                   class="group flex items-center rounded-lg px-3 sm:px-4 py-2 text-sm font-medium text-gray-500 hover:bg-green-50 hover:text-green-600 transition-colors duration-200">
                                    <span class="truncate">Lista de Usuarios</span>
                                </a>
                                <a href="{{ route('usuarios.create') }}"
                                   class="group flex items-center rounded-lg px-3 sm:px-4 py-2 text-sm font-medium text-gray-500 hover:bg-green-50 hover:text-green-600 transition-colors duration-200">
                                    <span class="truncate">Nuevo Usuario</span>
                                </a>
                            </div>
                        </div>

                        <!-- Solicitudes -->
                        <a href="{{ route('dashboard.solicitudes') }}"
                            @class([
                                 'group flex items-center rounded-lg px-3 sm:px-4 py-3 text-sm font-medium transition-all duration-200',
                                 'bg-orange-100 text-orange-700 border-l-4 border-orange-500 shadow-sm' => $active === 'solicitudes',
                                 'text-gray-600 hover:bg-orange-50 hover:text-orange-600 hover:border-l-4 hover:border-orange-300' => $active !== 'solicitudes'
                            ])>
                            <svg class="mr-3 h-4 w-4 sm:h-5 sm:w-5"
                                 @class([
                                     'text-orange-600' => $active === 'solicitudes',
                                     'text-gray-500 group-hover:text-orange-500' => $active !== 'solicitudes'
                                 ])
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <span class="truncate">Solicitudes</span>
                            @if($pendingRequests > 0)
                                <span class="ml-auto inline-flex h-5 w-5 items-center justify-center rounded-full bg-orange-500 text-xs font-medium text-white shadow-sm">
                                    {{ $pendingRequests }}
                                </span>
                            @endif
                        </a>

                        <!-- Generador de QR -->
                        <a href="{{ route('qr.generator') }}"
                            @class([
                                 'group flex items-center rounded-lg px-3 sm:px-4 py-3 text-sm font-medium transition-all duration-200',
                                 'bg-purple-100 text-purple-700 border-l-4 border-purple-500 shadow-sm' => $active === 'qr-generator',
                                 'text-gray-600 hover:bg-purple-50 hover:text-purple-600 hover:border-l-4 hover:border-purple-300' => $active !== 'qr-generator'
                            ])>
                            <svg class="mr-3 h-4 w-4 sm:h-5 sm:w-5"
                                 @class([
                                     'text-purple-600' => $active === 'qr-generator',
                                     'text-gray-500 group-hover:text-purple-500' => $active !== 'qr-generator'
                                 ])
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 4v1m6 11h2m-6 0h-2v4m0-4v4m0-4h4m-4 0H6m12-4V9a2 2 0 00-2-2H8a2 2 0 00-2 2v2m12 0v2a2 2 0 01-2 2H8a2 2 0 01-2-2v-2m12 0H6" />
                            </svg>
                            <span class="truncate">Generador QR</span>
                        </a>
                    @endif
                    <!-- Enlace de perfil -->
                    <a href="{{ route('profile.edit') }}" class="group flex items-center rounded-lg px-3 sm:px-4 py-3 text-sm font-medium text-gray-600 hover:bg-purple-50 hover:text-purple-700 transition-colors duration-200">
                        <svg class="mr-3 h-4 w-4 sm:h-5 sm:w-5 text-gray-500 group-hover:text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="truncate">Perfil</span>
                    </a>
                                <!-- Botón de logout fijo en la parte inferior -->
            <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 hover:text-gray-900 rounded-lg transition-colors duration-200">
                        <svg class="mr-3 h-4 w-4 sm:h-5 sm:w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span class="truncate">Cerrar sesión</span>
                    </button>
                </form>
            </div>
        </div>
                </nav>
            </div>

    </aside>