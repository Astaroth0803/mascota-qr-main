@props(['title', 'value', 'change', 'icon', 'color' => 'blue'])

<div class="bg-white rounded-lg shadow-lg p-6">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-xl font-semibold mb-2">{{ $title }}</h3>
            <p class="text-3xl font-bold">{{ $value }}</p>
            @if(isset($change))
                <p class="text-sm {{ $change >= 0 ? 'text-green-600' : 'text-red-600' }}">
                    {{ $change >= 0 ? '↑' : '↓' }} {{ abs($change) }}%
                </p>
            @endif
        </div>
        <div class="p-3 rounded-full bg-{{ $color }}-100">
            <svg class="w-6 h-6 text-{{ $color }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                {{ $icon }}
            </svg>
        </div>
    </div>
    <div class="mt-4" style="height: 60px;">
        <canvas id="miniChart-{{ Str::slug($title) }}"></canvas>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('miniChart-{{ Str::slug($title) }}').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
            datasets: [{
                data: [12, 19, 3, 5, 2, 3],
                borderColor: '#{{ $color }}',
                tension: 0.1,
                fill: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    display: false
                },
                x: {
                    display: false
                }
            }
        }
    });
});
</script>
@endpush 