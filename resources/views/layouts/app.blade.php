<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@auth{{ Auth::user()->hasAnyRole(['administrador', 'super_admin']) ? 'Buky World | Admin' : (Auth::user()->hasRole('cliente_qr') ? 'Buky World | Cliente' : 'Buky World') }}@else{{ config('app.name', default: 'Buky World') }}@endauth</title>
        @vite(['resources/css/main.css'])
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <script src="https://unpkg.com/@tailwindcss/browser@4"></script>

        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen dark:bg-gray-050">

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white light:bg-gray-800 shadow mt-16 lg:mt-0">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset
            <!-- Page Content -->
            <x-sidebar-menu />
            <main>
                {{ $slot }}
            </main>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/flowbite@3.0.0/dist/flowbite.min.js"></script>

        {{-- Required for pushed scripts from components --}}
        @stack('scripts')
    </body>
</html>
