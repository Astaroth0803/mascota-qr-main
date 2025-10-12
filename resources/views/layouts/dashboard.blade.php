{{-- resources/views/layouts/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="es" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard</title>
    <!-- Puedes agregar tus hojas de estilo y scripts aquí -->
    @vite('resources/css/app.css')  <!-- Para vincular tu archivo CSS -->
    @vite(['resources/css/main.css'])
    @vite(['resources/js/app.js'])  <!-- Para cargar el NotificationManager -->
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    
</head>

<body class="bg-gray-50 dark:bg-gray-900" data-user-id="{{ auth()->id() }}" data-user-role="{{ auth()->user()->getRoleNames()->first() }}">
<x-app-layout>
        <header>
            <!-- Aquí puedes agregar un navbar si lo deseas -->
        </header>
        <x-sidebar-menu />
            @yield('content') <!-- Esta sección se sobrescribirá por las vistas hijas -->
            
        </main>
    </div>
    @stack('scripts') <!-- Puedes agregar scripts específicos aquí -->
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.0.0/dist/flowbite.min.js">
    </script>
</x-app-layout>
</body>
</html>
