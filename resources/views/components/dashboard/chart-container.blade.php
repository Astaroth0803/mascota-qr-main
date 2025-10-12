@props([
    'title' => '',
    'description' => '',
    'height' => 'h-64'
])

<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
    <div class="mb-4">
        <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ $title }}</h3>
        @if($description)
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $description }}</p>
        @endif
    </div>
    <div class="{{ $height }} flex items-center justify-center">
        {{ $slot }}
    </div>
</div>
