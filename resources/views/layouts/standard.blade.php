@extends('layouts.dashboard')

@section('title', $title ?? 'Dashboard')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700 lg:ml-64">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="py-3 lg:py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <!-- Botón de Menú (Solo móviles) -->
                        <button onclick="toggleSidebar()" class="lg:hidden p-2 text-gray-400 dark:text-gray-300 hover:text-gray-600 dark:hover:text-gray-100 transition-colors" title="Menú">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                        
                        <div class="flex-1 min-w-0">
                            <h1 class="text-lg lg:text-xl font-bold text-gray-900 dark:text-white">{{ $title ?? 'Dashboard' }}</h1>
                            @if(isset($subtitle))
                            <p class="text-sm lg:text-base text-gray-600 dark:text-gray-300 mt-1">{{ $subtitle }}</p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                        <!-- Botón de Modo Oscuro/Claro -->
                        <button onclick="toggleDarkMode()" class="p-2 text-gray-400 dark:text-gray-300 hover:text-gray-600 dark:hover:text-gray-100 transition-colors" title="Modo Oscuro/Claro">
                            <svg id="dark-mode-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </button>
                        
                        <!-- Botón de Notificaciones -->
                        <div class="relative">
                            <button onclick="toggleNotifications()" class="p-2 text-gray-400 dark:text-gray-300 hover:text-gray-600 dark:hover:text-gray-100 transition-colors relative" title="Notificaciones">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 3c-2.2 0-4 1.8-4 4v1.2C7.2 9.1 6 10.9 6 13v2l-1 1v1h14v-1l-1-1v-2c0-2.1-1.2-3.9-2.9-4.8V7c0-2.2-1.8-4-4-4z"
                                          fill="none" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round" stroke-linecap="round"/>
                                    <path d="M9.5 18a2.5 2.5 0 0 0 5 0" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">0</span>
                            </button>
                            
                            <!-- Dropdown de Notificaciones -->
                            <div id="notifications-dropdown" class="hidden absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50">
                                <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Notificaciones</h3>
                                        <span id="notification-count" class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-xs font-medium px-2.5 py-0.5 rounded-full">0</span>
                                    </div>
                                </div>
                                <div id="notifications-content" class="max-h-64 overflow-y-auto">
                                    <div class="text-center py-8">
                                        <svg class="mx-auto h-8 w-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5-5-5h5v-5a7.5 7.5 0 1 0-15 0v5h5l-5 5-5-5h5v-5a7.5 7.5 0 1 1 15 0v5z"></path>
                                        </svg>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Cargando notificaciones...</p>
                                    </div>
                                </div>
                                <div class="p-3 border-t border-gray-200 dark:border-gray-700">
                                    <a href="{{ auth()->user()->hasRole('veterinario') ? route('notifications.index') : route('dashboard.cliente.notifications.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300">Ver todas las notificaciones</a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Botón de Perfil -->
                        <div class="relative">
                            <button onclick="toggleProfile()" class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center hover:bg-blue-600 transition-colors" title="Perfil">
                                <span class="text-white text-sm font-medium">{{ substr(auth()->user()->name, 0, 1) }}</span>
                            </button>
                            
                            <!-- Dropdown de Perfil -->
                            <div id="profile-dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50">
                                <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</p>
                                </div>
                                <div class="py-1">
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Mi Perfil</a>
                                </div>
                                <div class="py-1 border-t border-gray-200 dark:border-gray-700">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20">
                                            Cerrar Sesión
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar Móvil (Overlay) -->
    <div id="mobile-sidebar" class="fixed inset-0 z-50 lg:hidden hidden">
        <!-- Overlay -->
        <div class="fixed inset-0 bg-black bg-opacity-50" onclick="toggleSidebar()"></div>
        
        <!-- Sidebar -->
        <div class="fixed inset-y-0 left-0 w-64 bg-white dark:bg-gray-800 shadow-xl transform transition-transform duration-300 ease-in-out -translate-x-full" id="sidebar-content">
            <div class="flex flex-col h-full">
                <!-- Header del Sidebar -->
                <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
                    <span class="text-lg font-bold text-gray-800 dark:text-white">Buky World</span>
                    <button onclick="toggleSidebar()" class="p-2 text-gray-400 dark:text-gray-300 hover:text-gray-600 dark:hover:text-gray-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- Contenido del Sidebar -->
                <div class="flex-1 overflow-y-auto py-4">
                    <x-sidebar-mobile-content />
                </div>
            </div>
        </div>
    </div>

    <!-- Contenido Principal -->
    <div class="lg:ml-64">
        <div class="px-2 sm:px-4 lg:px-6 xl:px-8 py-4 lg:py-6">
            @yield('main-content')
        </div>
    </div>
</div>

<script>
// Funciones JavaScript para el header

// Función para toggle del sidebar móvil
function toggleSidebar() {
    const sidebar = document.getElementById('mobile-sidebar');
    const sidebarContent = document.getElementById('sidebar-content');
    
    if (sidebar.classList.contains('hidden')) {
        sidebar.classList.remove('hidden');
        // Pequeño delay para la animación
        setTimeout(() => {
            sidebarContent.classList.remove('-translate-x-full');
        }, 10);
    } else {
        sidebarContent.classList.add('-translate-x-full');
        setTimeout(() => {
            sidebar.classList.add('hidden');
        }, 300);
    }
}




function toggleDarkMode() {
    const html = document.documentElement;
    const icon = document.getElementById('dark-mode-icon');
    
    console.log('Toggle dark mode clicked'); // Debug
    
    if (html.classList.contains('dark')) {
        html.classList.remove('dark');
        localStorage.setItem('darkMode', 'false');
        updateDarkModeIcon(false);
        console.log('Dark mode disabled'); // Debug
    } else {
        html.classList.add('dark');
        localStorage.setItem('darkMode', 'true');
        updateDarkModeIcon(true);
        console.log('Dark mode enabled'); // Debug
    }
}

function updateDarkModeIcon(isDark) {
    const icon = document.getElementById('dark-mode-icon');
    if (isDark) {
        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>';
    } else {
        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>';
    }
}

function toggleNotifications() {
    const dropdown = document.getElementById('notifications-dropdown');
    const profileDropdown = document.getElementById('profile-dropdown');
    
    if (profileDropdown && !profileDropdown.classList.contains('hidden')) {
        profileDropdown.classList.add('hidden');
    }
    
    if (dropdown.classList.contains('hidden')) {
        // Cargar notificaciones cuando se abre el dropdown
        if (window.notificationManager) {
            window.notificationManager.loadNotifications();
        }
    }
    
    dropdown.classList.toggle('hidden');
}

function toggleProfile() {
    const dropdown = document.getElementById('profile-dropdown');
    const notificationsDropdown = document.getElementById('notifications-dropdown');
    
    if (notificationsDropdown && !notificationsDropdown.classList.contains('hidden')) {
        notificationsDropdown.classList.add('hidden');
    }
    
    dropdown.classList.toggle('hidden');
}

// Cerrar dropdowns al hacer clic fuera
document.addEventListener('click', function(event) {
    const notificationsDropdown = document.getElementById('notifications-dropdown');
    const profileDropdown = document.getElementById('profile-dropdown');
    
    if (!event.target.closest('[onclick="toggleNotifications()"]') && notificationsDropdown && !notificationsDropdown.classList.contains('hidden')) {
        notificationsDropdown.classList.add('hidden');
    }
    
    if (!event.target.closest('[onclick="toggleProfile()"]') && profileDropdown && !profileDropdown.classList.contains('hidden')) {
        profileDropdown.classList.add('hidden');
    }
});

// Cargar preferencia de modo oscuro
document.addEventListener('DOMContentLoaded', function() {
    const darkMode = localStorage.getItem('darkMode');
    console.log('Initial dark mode:', darkMode); // Debug
    
    if (darkMode === 'true') {
        document.documentElement.classList.add('dark');
        updateDarkModeIcon(true);
        console.log('Dark mode loaded from localStorage'); // Debug
    } else {
        document.documentElement.classList.remove('dark');
        updateDarkModeIcon(false);
        console.log('Light mode loaded'); // Debug
    }
});
</script>
@endsection
