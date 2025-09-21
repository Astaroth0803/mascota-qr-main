<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @extends('layout')
    @section('title', 'Mascota QR | Iniciar Sesión')
</head>
<body>
    <header>
        <x-main-nav></x-main-nav>
    </header>
 
    <main>
        @section('main-content')
        <!-- Hero Section -->
        <div class="relative min-h-[40vh] bg-gradient-to-r from-blue-600 to-black flex items-center justify-center pt-32">
            <div class="absolute inset-0 bg-black/50"></div>
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">
                    Iniciar Sesión
                </h1>
                <p class="text-xl text-gray-200 max-w-3xl mx-auto">
                    Accede a tu cuenta para gestionar tus mascotas
                </p>
            </div>
        </div>

        <!-- Login Form -->
        <div class="py-16 bg-white">
            <div class="max-w-md mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-8">
                        <form method="POST" action="{{ route('login') }}" class="space-y-6">
                            @csrf
                            
                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Correo Electrónico
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                        </svg>
                                    </div>
                                    <input id="email" name="email" type="email" required 
                                        class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="tu@email.com">
                                </div>
                            </div>

                            <!-- Password -->
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                    Contraseña
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                    </div>
                                    <input id="password" name="password" type="password" required 
                                        class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="••••••••">
                                </div>
                            </div>

                            <!-- Remember Me -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <input id="remember_me" name="remember" type="checkbox" 
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <label for="remember_me" class="ml-2 block text-sm text-gray-700">
                                        Recordarme
                                    </label>
                                </div>
                                <div class="text-sm">
                                    <a href="#" class="font-medium text-blue-600 hover:text-blue-500">
                                        ¿Olvidaste tu contraseña?
                                    </a>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div>
                                <button type="submit" 
                                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Iniciar Sesión
                                </button>
                            </div>
                        </form>

                        <!-- Register Link -->
                        <div class="mt-6 text-center">
                            <p class="text-sm text-gray-600">
                                ¿No tienes una cuenta?
                                <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-500">
                                    Regístrate aquí
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endsection
    </main>
</body>
</html> 