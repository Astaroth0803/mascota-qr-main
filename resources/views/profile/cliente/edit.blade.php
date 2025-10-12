<x-app-layout>
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <div class="bg-white shadow-sm border-b border-gray-200 pt-16 lg:pt-0">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="py-4 lg:py-6">
                    <!-- Breadcrumb -->
                    <nav class="flex items-center space-x-2 text-sm text-gray-500 mb-4">
                        <a href="{{ route('dashboard') }}" class="hover:text-gray-700 transition-colors">Dashboard</a>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                        <span class="text-gray-900 font-medium">Mi Perfil</span>
                    </nav>
                    
                    <!-- Título Principal -->
                    <div>
                        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Mi Perfil</h1>
                        <p class="text-gray-600 mt-1">Administra tu información personal</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenido principal -->
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
            
            <!-- Formulario de Perfil -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
                <div class="p-6">
                    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
                        @csrf
                        @method('patch')

                        <!-- Información Personal -->
                        <div class="border-b border-gray-200 pb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Información Personal</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Nombre -->
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Nombre completo
                                    </label>
                                    <input type="text" name="name" id="name" 
                                           value="{{ old('name', $user->name) }}"
                                           class="block w-full px-4 py-3 border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-300 @enderror">
                                    @error('name')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                        Correo electrónico
                                    </label>
                                    <input type="email" name="email" id="email" 
                                           value="{{ old('email', $user->email) }}"
                                           class="block w-full px-4 py-3 border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-300 @enderror">
                                    @error('email')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Información de la Cuenta -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Información de la Cuenta</h3>
                            
                            <div class="bg-gray-50 rounded-xl p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                                    <div>
                                        <span class="font-medium text-gray-700">Miembro desde:</span>
                                        <span class="text-gray-600 ml-2">{{ $user->created_at->format('d/m/Y') }}</span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-700">Email verificado:</span>
                                        <span class="text-gray-600 ml-2">
                                            @if($user->email_verified_at)
                                                <span class="inline-flex items-center text-green-600">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                    Verificado
                                                </span>
                                            @else
                                                <span class="text-red-600">No verificado</span>
                                            @endif
                                        </span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-700">Mascotas registradas:</span>
                                        <span class="text-gray-600 ml-2">{{ $user->pets()->count() }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="flex flex-col sm:flex-row gap-3 pt-6">
                            <button type="submit" 
                                    class="flex-1 sm:flex-none inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Guardar cambios
                            </button>
                            
                            <a href="{{ route('dashboard') }}" 
                               class="flex-1 sm:flex-none inline-flex items-center justify-center px-6 py-3 bg-gray-100 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Cancelar
                            </a>
                        </div>

                        @if (session('status') === 'profile-updated')
                            <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-xl">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-green-800">Perfil actualizado correctamente</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Información Adicional -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-2xl p-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-blue-900 mb-2">Información importante</h3>
                        <div class="space-y-2 text-sm text-blue-800">
                            <p>• Tu información personal se mantiene segura y privada</p>
                            <p>• Si cambias tu email, deberás verificarlo nuevamente</p>
                            <p>• Los cambios se reflejan inmediatamente en tu cuenta</p>
                            <p>• Tu información se usa para contactarte sobre tus mascotas</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
