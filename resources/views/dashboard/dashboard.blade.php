{{-- resources/views/dashboard.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard - Pet Management') }}
            </h2>
            <a href="{{ route('dashboard.cliente.registrar.mascota') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Agregar Nueva Mascota
            </a>
        </div>
    </x-slot>
            
    <x-sidebar />   
</x-app-layout>
<x-footer /> 
