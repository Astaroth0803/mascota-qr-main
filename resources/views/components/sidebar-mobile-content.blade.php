{{-- Contenido del sidebar móvil --}}
@props(['active' => 'dashboard', 'pendingRequests' => 0])

<nav class="space-y-2 px-4">
    <!-- Dashboard -->
    <a href="{{ route('dashboard') }}"
        @class([
             'group flex items-center rounded-lg px-3 py-3 text-sm font-medium transition-all duration-200',
             'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 border-l-4 border-blue-500 shadow-sm' => $active === 'dashboard',
             'text-gray-600 dark:text-white hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:text-blue-600 dark:hover:text-blue-300 hover:border-l-4 hover:border-blue-300' => $active !== 'dashboard'
        ])>
        <svg class="mr-3 h-5 w-5"
             @class([
                 'text-blue-600 dark:text-blue-400' => $active === 'dashboard',
                 'text-gray-500 dark:text-gray-300 group-hover:text-blue-500 dark:group-hover:text-blue-400' => $active !== 'dashboard'
             ])
             fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
        </svg>
        <span class="truncate">Dashboard</span>
    </a>

    @if(auth()->user()->hasRole('administrador') || auth()->user()->hasRole('super_admin'))
        <!-- Menú desplegable de Usuarios -->
        <div x-data="{ open: {{ $active === 'usuarios' ? 'true' : 'false' }} }" class="space-y-2">
            <button @click="open = !open" 
                    class="group flex w-full items-center justify-between rounded-lg px-3 py-3 text-sm font-medium text-gray-600 hover:bg-green-50 hover:text-green-600 hover:border-l-4 hover:border-green-300 transition-all duration-200">
                <div class="flex items-center">
                    <svg class="mr-3 h-5 w-5 text-gray-500 group-hover:text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span class="truncate">Usuarios</span>
                </div>
                <svg class="h-4 w-4 text-gray-400 transition-transform duration-200" 
                     :class="{ 'rotate-180': open }" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="open" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 transform scale-100"
                 x-transition:leave-end="opacity-0 transform scale-95"
                 class="space-y-1 pl-8">
                <a href="{{ route('dashboard.usuarios') }}"
                   class="group flex items-center rounded-lg px-3 py-2 text-sm font-medium {{ $active === 'usuarios' ? 'bg-green-100 text-green-700' : 'text-gray-500 hover:bg-green-50 hover:text-green-600' }} transition-colors duration-200">
                    <svg class="mr-2 h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <span class="truncate">Lista de Usuarios</span>
                </a>
                <a href="{{ route('usuarios.create') }}"
                   class="group flex items-center rounded-lg px-3 py-2 text-sm font-medium text-gray-500 hover:bg-green-50 hover:text-green-600 transition-colors duration-200">
                    <svg class="mr-2 h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    <span class="truncate">Nuevo Usuario</span>
                </a>
            </div>
        </div>

        <!-- Dropdown de Solicitudes -->
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" 
                    @class([
                         'group flex items-center justify-between rounded-lg px-3 py-3 text-sm font-medium transition-all duration-200 w-full',
                         'bg-orange-100 text-orange-700 border-l-4 border-orange-500 shadow-sm' => in_array($active, ['solicitudes', 'pet-requests']),
                         'text-gray-600 hover:bg-orange-50 hover:text-orange-600 hover:border-l-4 hover:border-orange-300' => !in_array($active, ['solicitudes', 'pet-requests'])
                    ])>
                <div class="flex items-center">
                    <svg class="mr-3 h-5 w-5"
                         @class([
                             'text-orange-600' => in_array($active, ['solicitudes', 'pet-requests']),
                             'text-gray-500 group-hover:text-orange-500' => !in_array($active, ['solicitudes', 'pet-requests'])
                         ])
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <span class="truncate">Solicitudes</span>
                    @if($pendingRequests > 0)
                        <span class="ml-2 inline-flex h-5 w-5 items-center justify-center rounded-full bg-orange-500 text-xs font-medium text-white shadow-sm">
                            {{ $pendingRequests }}
                        </span>
                    @endif
                </div>
                <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            
            <div x-show="open" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 transform scale-100"
                 x-transition:leave-end="opacity-0 transform scale-95"
                 class="ml-4 mt-1 space-y-1">
                <a href="{{ route('dashboard.solicitudes') }}" 
                   @class([
                        'block px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200',
                        'bg-orange-50 text-orange-600' => $active === 'solicitudes',
                        'text-gray-500 hover:bg-orange-50 hover:text-orange-600' => $active !== 'solicitudes'
                   ])>
                    <span class="truncate">Solicitudes de Usuarios</span>
                </a>
                <a href="{{ route('pet-requests.index') }}" 
                   @class([
                        'block px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200',
                        'bg-blue-50 text-blue-600' => $active === 'pet-requests',
                        'text-gray-500 hover:bg-blue-50 hover:text-blue-600' => $active !== 'pet-requests'
                   ])>
                    <span class="truncate">Solicitudes de Mascotas</span>
                </a>
            </div>
        </div>

        <!-- Generador de QR -->
        <a href="{{ route('qr.generator') }}"
            @class([
                 'group flex items-center rounded-lg px-3 py-3 text-sm font-medium transition-all duration-200',
                 'bg-purple-100 text-purple-700 border-l-4 border-purple-500 shadow-sm' => $active === 'qr-generator',
                 'text-gray-600 hover:bg-purple-50 hover:text-purple-600 hover:border-l-4 hover:border-purple-300' => $active !== 'qr-generator'
            ])>
            <svg class="mr-3 h-5 w-5"
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
    
    @if(auth()->user()->hasRole('veterinario'))
        <!-- Menú directo de Veterinario -->
        <a href="{{ route('dashboard.veterinario.calendario.index') }}"
           @class([
                'group flex items-center rounded-lg px-3 py-3 text-sm font-medium transition-all duration-200',
                'bg-blue-100 text-blue-700 border-l-4 border-blue-500 shadow-sm' => $active === 'calendario',
                'text-gray-600 hover:bg-blue-50 hover:text-blue-600 hover:border-l-4 hover:border-blue-300' => $active !== 'calendario'
           ])>
            <svg class="mr-3 h-5 w-5"
                 @class([
                     'text-blue-600' => $active === 'calendario',
                     'text-gray-500 group-hover:text-blue-500' => $active !== 'calendario'
                 ])
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <span class="truncate">Mi Calendario</span>
        </a>
        
        <a href="{{ route('dashboard.veterinario.calendario.today') }}"
           @class([
                'group flex items-center rounded-lg px-3 py-3 text-sm font-medium transition-all duration-200',
                'bg-yellow-100 text-yellow-700 border-l-4 border-yellow-500 shadow-sm' => $active === 'citas-hoy',
                'text-gray-600 hover:bg-yellow-50 hover:text-yellow-600 hover:border-l-4 hover:border-yellow-300' => $active !== 'citas-hoy'
           ])>
            <svg class="mr-3 h-5 w-5"
                 @class([
                     'text-yellow-600' => $active === 'citas-hoy',
                     'text-gray-500 group-hover:text-yellow-500' => $active !== 'citas-hoy'
                 ])
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="truncate">Citas de Hoy</span>
        </a>
        
        <a href="{{ route('dashboard.veterinario.solicitudes.index') }}"
           @class([
                'group flex items-center rounded-lg px-3 py-3 text-sm font-medium transition-all duration-200',
                'bg-red-100 text-red-700 border-l-4 border-red-500 shadow-sm' => $active === 'solicitudes-citas',
                'text-gray-600 hover:bg-red-50 hover:text-red-600 hover:border-l-4 hover:border-red-300' => $active !== 'solicitudes-citas'
           ])>
            <svg class="mr-3 h-5 w-5"
                 @class([
                     'text-red-600' => $active === 'solicitudes-citas',
                     'text-gray-500 group-hover:text-red-500' => $active !== 'solicitudes-citas'
                 ])
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <span class="truncate">Solicitudes de Citas</span>
        </a>
        
        
    @endif
    
    @if(auth()->user()->hasRole('cliente_qr'))
        <!-- Menú directo de Veterinarios -->
        <a href="{{ route('dashboard.cliente.veterinarios.index') }}"
           class="group flex items-center rounded-lg px-3 py-3 text-sm font-medium {{ $active === 'veterinarios' ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 border-l-4 border-green-300' : 'text-gray-600 dark:text-white hover:bg-green-50 dark:hover:bg-green-900/20 hover:text-green-600 dark:hover:text-green-300 hover:border-l-4 hover:border-green-300' }} transition-all duration-200">
            <svg class="mr-3 h-5 w-5 text-gray-500 dark:text-gray-300 group-hover:text-green-500 dark:group-hover:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <span class="truncate">Veterinarios Disponibles</span>
        </a>
        <a href="{{ route('dashboard.cliente.veterinarios.mis-veterinarios') }}"
           class="group flex items-center rounded-lg px-3 py-3 text-sm font-medium {{ $active === 'mis-veterinarios' ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 border-l-4 border-green-300' : 'text-gray-600 dark:text-white hover:bg-green-50 dark:hover:bg-green-900/20 hover:text-green-600 dark:hover:text-green-300 hover:border-l-4 hover:border-green-300' }} transition-all duration-200">
            <svg class="mr-3 h-5 w-5 text-gray-500 dark:text-gray-300 group-hover:text-green-500 dark:group-hover:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="truncate">Mis Veterinarios</span>
        </a>
    @endif
    
    <!-- Enlace de perfil -->
    <a href="{{ route('profile.edit') }}" class="group flex items-center rounded-lg px-3 py-3 text-sm font-medium text-gray-600 dark:text-white hover:bg-purple-50 dark:hover:bg-purple-900/20 hover:text-purple-700 dark:hover:text-purple-300 transition-colors duration-200">
        <svg class="mr-3 h-5 w-5 text-gray-500 dark:text-gray-300 group-hover:text-purple-500 dark:group-hover:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
        <span class="truncate">Perfil</span>
    </a>
    
    <!-- Botón de logout -->
    <div class="px-3 py-3 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700 -mx-4">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center px-3 py-2 text-sm font-medium text-gray-600 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-600 hover:text-gray-900 dark:hover:text-white rounded-lg transition-colors duration-200">
                <svg class="mr-3 h-5 w-5 text-gray-500 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span class="truncate">Cerrar sesión</span>
            </button>
        </form>
    </div>
</nav>

