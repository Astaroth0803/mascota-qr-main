@extends('layouts.standard')

@section('title', 'Editar Perfil')

@php
    $title = 'Editar Perfil';
    $subtitle = 'Gestiona tu información personal y configuración de cuenta';
@endphp

@section('main-content')
<div class="space-y-6">
    <div class="max-w-4xl mx-auto">
        <div class="space-y-4 sm:space-y-6">
            <!-- Información del Perfil -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 sm:h-6 sm:w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-base sm:text-lg font-medium text-gray-900 dark:text-white">Información del Perfil</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Actualiza tu información personal y dirección de email</p>
                        </div>
                    </div>
                </div>
                    <div class="px-4 sm:px-6 py-4 sm:py-6">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

            <!-- Actualizar Contraseña -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 sm:h-6 sm:w-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-base sm:text-lg font-medium text-gray-900 dark:text-white">Actualizar Contraseña</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Mantén tu cuenta segura con una contraseña fuerte</p>
                        </div>
                    </div>
                </div>
                    <div class="px-4 sm:px-6 py-4 sm:py-6">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

            <!-- Eliminar Cuenta -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-red-200 dark:border-red-800 overflow-hidden">
                <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-900/20">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 sm:h-6 sm:w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 15.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-base sm:text-lg font-medium text-red-900 dark:text-red-300">Eliminar Cuenta</h3>
                            <p class="text-sm text-red-600 dark:text-red-400">Elimina permanentemente tu cuenta y todos sus datos</p>
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
@endsection