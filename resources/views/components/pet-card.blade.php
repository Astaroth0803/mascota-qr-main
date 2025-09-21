@props(['pet'])

<div class="overflow-hidden bg-white rounded-lg shadow-sm">
    <div class="relative">
        @if($pet->profile_image)
            <img src="{{ Storage::url($pet->profile_image) }}"
                 alt="{{ $pet->nombre }}"
                 class="object-cover w-full h-48">
        @else
            <div class="flex items-center justify-center w-full h-48 bg-gray-100">
                <x-icon-pet class="w-20 h-20 text-gray-400" />
            </div>
        @endif
    </div>

    <div class="p-4">
        <h3 class="text-lg font-medium text-gray-900">
            {{ $pet->nombre }}
        </h3>

        <dl class="mt-2 text-sm text-gray-500">
            <div class="flex justify-between">
                <dt>Especie:</dt>
                <dd>{{ $pet->especie }}</dd>
            </div>
            <div class="flex justify-between mt-1">
                <dt>Raza:</dt>
                <dd>{{ $pet->raza }}</dd>
            </div>
            <div class="flex justify-between mt-1">
                <dt>Edad:</dt>
                <dd>{{ $pet->edad }}</dd>
            </div>
        </dl>

        <div class="flex justify-end mt-4 space-x-2">
            <x-button-link href="{{ route('dashboard.cliente.mascotas.show', $pet->id) }}" size="sm">
                Ver Detalles
            </x-button-link>
        </div>
    </div>
</div>
