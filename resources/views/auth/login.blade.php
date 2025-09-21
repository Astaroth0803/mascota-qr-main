<x-guest-layout>
    <!-- Return Button -->
    <div class="absolute top-4 left-4 z-10">
        <a href="{{ url()->previous() }}" class="text-white hover:text-gray-900 dark:text-white dark:hover:text-gray-100">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
        </a>
    </div>

    <!-- Main Container -->
    <div class="min-h-screen flex flex-col md:flex-row">
        <!-- Left Section (Full width on mobile, 2/3 on desktop) -->
        <div class="w-full md:w-2/3 bg-gradient-to-r from-blue-600 to-black flex items-center justify-center p-8">
            <!-- Add any content here (e.g., an image, text, or illustration) -->
            <div class="text-center text-white">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Bienvenido a Buky World</h1>
                <p class="text-lg md:text-xl">Lorem Ipsum.</p>
            </div>
        </div>

        <!-- Right Section (Full width on mobile, 1/3 on desktop) -->
        <div class="w-full md:w-1/3 bg-white md:bg-transparent flex items-center justify-center p-8 md:p-4">
            <!-- Login Form Container -->
            <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-md md:shadow-none">
                <!-- Logo -->
                <div class="mb-8 text-center">
                    <img src="https://i.postimg.cc/9Fs7Jxfy/Mesa-de-trabajo-2.png" alt="Buky World Logo" class="h-16 mx-auto">
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" class="w-full">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="mt-4">
                        <x-input-label for="password" :value="__('Password')" />
                        <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me -->
                    <div class="block mt-4">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-blue-600 shadow-sm focus:ring-blue-500 dark:focus:ring-blue-600 dark:focus:ring-offset-gray-800" name="remember">
                            <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
                        </label>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        @if (Route::has('password.request'))
                            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                                {{ __('Forgot your password?') }}
                            </a>
                        @endif

                        <x-primary-button class="ms-3 bg-blue-600 hover:bg-blue-700">
                            {{ __('Log in') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>