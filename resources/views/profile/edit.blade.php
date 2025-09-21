<x-app-layout>
    <x-sidebar-menu :active="'profile'" />

    <x-slot name="header">
        <div class="transition-all duration-300 ease-in-out lg:ml-64">
            <div class="bg-white overflow-hidden shadow rounded-lg p-4 sm:p-6 hover:shadow-lg transition-shadow duration-300 mb-8 mx-2 sm:mx-4 lg:mx-8">
                <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
                    <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                        {{ __('Dashboard - Pet Management') }} - {{ Auth::user()->name }}
                    </h2>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="pt-20 lg:pt-12 lg:ml-64 transition-all duration-300 ease-in-out min-h-screen bg-gray-50">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>