<x-app-layout>
    <x-sidebar-menu :active="'profile'" />

    <x-slot name="header">
        <div class="py-3 sm:py-4 lg:py-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex-1 min-w-0">
                    <h1 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900">Editar Perfil</h1>
                    <p class="text-sm sm:text-base text-gray-600 mt-1">Gestiona tu información personal y configuración de cuenta</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                    <a href="{{ route('dashboard') }}" 
                       class="inline-flex items-center justify-center px-3 sm:px-4 py-2 bg-gray-600 text-white text-xs sm:text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
                        <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1 sm:mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        <span class="hidden sm:inline">Volver al Dashboard</span>
                        <span class="sm:hidden">Volver</span>
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6 lg:ml-64" id="main-content">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="space-y-4 sm:space-y-6">
                <!-- Información del Perfil -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-gray-200 bg-gray-50">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 sm:h-6 sm:w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-base sm:text-lg font-medium text-gray-900">Información del Perfil</h3>
                                <p class="text-sm text-gray-500">Actualiza tu información personal y dirección de email</p>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 sm:px-6 py-4 sm:py-6">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <!-- Actualizar Contraseña -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-gray-200 bg-gray-50">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 sm:h-6 sm:w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-base sm:text-lg font-medium text-gray-900">Actualizar Contraseña</h3>
                                <p class="text-sm text-gray-500">Mantén tu cuenta segura con una contraseña fuerte</p>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 sm:px-6 py-4 sm:py-6">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <!-- Eliminar Cuenta -->
                <div class="bg-white rounded-lg shadow-sm border border-red-200 overflow-hidden">
                    <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-red-200 bg-red-50">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 sm:h-6 sm:w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 15.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-base sm:text-lg font-medium text-red-900">Eliminar Cuenta</h3>
                                <p class="text-sm text-red-600">Elimina permanentemente tu cuenta y todos sus datos</p>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 sm:px-6 py-4 sm:py-6">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>