@props(['message', 'actionLabel' => null, 'actionUrl' => null])

<div class="flex flex-col items-center justify-center p-12 text-center">
    <div class="flex items-center justify-center w-16 h-16 mb-6 bg-gray-100 rounded-full">
        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
        </svg>
    </div>

    <p class="mb-4 text-lg font-medium text-gray-600">
        {{ $message }}
    </p>

    @if($actionLabel && $actionUrl)
        <a href="{{ $actionUrl }}" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            {{ $actionLabel }}
        </a>
    @endif
</div>
