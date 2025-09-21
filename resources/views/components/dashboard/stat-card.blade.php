@props(['title', 'value', 'icon', 'class' => ''])

<div class="p-6 bg-white rounded-lg shadow-sm">
    <div class="flex items-center">
        <div class="flex-shrink-0 p-3 {{ $class }}">
            <x-dynamic-component :component="'icon-' . $icon" class="w-6 h-6" />
        </div>
        <div class="ml-4">
            <h4 class="text-sm font-medium text-gray-500">
                {{ $title }}
            </h4>
            <p class="text-2xl font-semibold text-gray-900">
                {{ $value }}
            </p>
        </div>
    </div>
</div>
