<x-app-layout>
    @can('ver_mascotas')
        <x-slot name="header">
            <h2>{{ __('Detalles de la Mascota') }}</h2>
        </x-slot>

        <div class="bg-white p-4 rounded shadow-md">
            <div class="mb-4">
                <h3 class="text-xl font-bold">{{ $pet->nombre }}</h3>
                <p class="text-gray-500">{{ $pet->especie }} - {{ $pet->raza }}</p>
            </div>

            <div class="mb-4">
                <x-input-label :value="__('Edad')" />
                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                    @if($pet->edad_anios || $pet->edad_meses)
                        @if($pet->edad_anios)
                            {{ $pet->edad_anios }} año{{ $pet->edad_anios > 1 ? 's' : '' }}
                        @endif
                        @if($pet->edad_meses)
                            @if($pet->edad_anios) y @endif
                            {{ $pet->edad_meses }} mes{{ $pet->edad_meses > 1 ? 'es' : '' }}
                        @endif
                    @else
                        No especificada
                    @endif
                </p>
            </div>

            <div class="mb-4">
                <strong>Sexo:</strong> {{ $pet->sexo }}
            </div>

            @if ($pet->vaccine_file)
                <div class="mb-4">
                    <strong>Vacunas:</strong>
                    <a href="{{ asset('storage/' . $pet->vaccine_file) }}" target="_blank" class="text-blue-500 hover:underline">Ver PDF</a>
                </div>
            @else
                <div class="mb-4">
                    <strong>Vacunas:</strong> <span class="text-gray-500 italic">No adjuntado</span>
                </div>
            @endif

            <div class="mb-4">
                <a href="{{ route('dashboard.cliente') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">Volver al Dashboard</a>
            </div>
        </div>
    @else
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4" role="alert">
            <p>No tienes permiso para ver los detalles de esta mascota.</p>
        </div>
    @endcan
</x-app-layout>
