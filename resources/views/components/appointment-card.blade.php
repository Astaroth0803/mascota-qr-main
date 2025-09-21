@props(['appointment'])

<div class="p-4 mb-4 border border-gray-200 rounded-lg bg-gray-50">
    <div class="flex items-center justify-between">
        <div>
            <h4 class="text-sm font-medium text-gray-900">{{ $appointment['title'] }}</h4>
            <p class="text-sm text-gray-500">{{ $appointment['description'] }}</p>
        </div>
        <div class="text-right">
            <div class="text-sm font-medium text-gray-900">{{ $appointment['date'] }}</div>
            <div class="text-sm text-gray-500">{{ $appointment['time'] }}</div>
        </div>
    </div>
    @if(isset($appointment['pet_id']))
        <div class="mt-2 text-sm">
            <span class="font-medium text-gray-500">Mascota:</span>
            <a href="{{ route('dashboard.cliente.mascotas.show', $appointment['pet_id']) }}" class="ml-1 text-blue-600 hover:text-blue-800">
                {{ $appointment['pet_name'] }}
            </a>
        </div>
    @endif
</div>
