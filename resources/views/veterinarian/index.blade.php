@extends('layouts.standard')

@section('title', 'Dashboard Veterinario')

@php
    $title = 'Dashboard Veterinario';
    $subtitle = 'Bienvenido, ' . auth()->user()->name;
@endphp

@section('main-content')
<div class="space-y-8">
                

                <!-- Tarjetas de Estadísticas -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Mascotas -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            </div>
                            <span class="text-xs font-medium text-red-600 bg-red-50 px-2 py-1 rounded-full">
                                
                            </span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">TOTAL MASCOTAS ATENDIDAS </p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total_mascotas'] }}</p>
                    </div>
                </div>

                <!-- Citas Hoy -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <span class="text-xs font-medium text-green-600 bg-green-50 px-2 py-1 rounded-full">
                                Mantiene 0.00% en 1 día
                            </span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">CITAS HOY</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['citas_hoy'] }}</p>
                        </div>
                    </div>


                    <!-- Citas Pendientes -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span class="text-xs font-medium text-orange-600 bg-orange-50 px-2 py-1 rounded-full">
                                Pendientes de revisión
                            </span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">CITAS PENDIENTES</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['citas_pendientes'] }}</p>
                        </div>
                    </div>
                </div>

                <!-- Gráfico Principal -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-xl font-semibold text-gray-900">Citas Mensuales</h2>
                            <div class="flex items-center space-x-2">
                                <select id="chartFilter" class="text-sm border border-gray-300 rounded-lg px-3 py-1 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="day">Últimos 7 Días</option>
                                    <option value="specific">Día Específico</option>
                                    <option value="week">Por Semana</option>
                                    <option value="month">Por Mes</option>
                                </select>
                                <input type="date" id="specificDate" class="text-sm border border-gray-300 rounded-lg px-3 py-1 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" style="display: none;" value="{{ now()->toDateString() }}">
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                <span class="text-sm text-gray-600">Finalizadas</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                <span class="text-sm text-gray-600">Rechazadas</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="w-3 h-3 bg-orange-500 rounded-full"></div>
                                <span class="text-sm text-gray-600">Canceladas</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Gráfico real con Chart.js -->
                    <div class="h-64">
                        <canvas id="appointmentsChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Próximas Citas -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Próximas Citas
                        </h2>
                        <a href="{{ route('dashboard.veterinario.calendario.index') }}" 
                           class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                            Ver todas
                        </a>
                    </div>
                </div>
                
                    <div class="p-6">
                        <div class="space-y-4">
                        @if(isset($upcomingAppointments) && $upcomingAppointments->count() > 0)
                            @foreach($upcomingAppointments->take(3) as $appointmentRequest)
                                    <div class="flex items-center p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mr-4">
                                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-sm font-semibold text-gray-900 truncate">{{ $appointmentRequest->pet->nombre }}</h3>
                                        <p class="text-xs text-gray-600">{{ $appointmentRequest->scheduled_datetime ? $appointmentRequest->scheduled_datetime->format('d/m/Y H:i') : 'Sin fecha programada' }}</p>
                                        <p class="text-xs text-gray-500">{{ $appointmentRequest->getAppointmentTypeLabelAttribute() }}</p>
                                    </div>
                                    <div class="ml-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-sm text-gray-500 mt-2">No hay citas próximas</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>


            </div>
        </div>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Datos del gráfico (estos vendrán del controlador)
    const chartData = {
        day: {
            labels: {!! json_encode($dailyChartData['labels'] ?? []) !!},
            completed: {!! json_encode($dailyChartData['completed'] ?? []) !!},
            rejected: {!! json_encode($dailyChartData['rejected'] ?? []) !!},
            cancelled: {!! json_encode($dailyChartData['cancelled'] ?? []) !!}
        },
        specific: {
            labels: {!! json_encode($specificDayData['labels'] ?? []) !!},
            completed: {!! json_encode($specificDayData['completed'] ?? []) !!},
            rejected: {!! json_encode($specificDayData['rejected'] ?? []) !!},
            cancelled: {!! json_encode($specificDayData['cancelled'] ?? []) !!}
        },
        week: {
            labels: {!! json_encode($weeklyChartData['labels'] ?? []) !!},
            completed: {!! json_encode($weeklyChartData['completed'] ?? []) !!},
            rejected: {!! json_encode($weeklyChartData['rejected'] ?? []) !!},
            cancelled: {!! json_encode($weeklyChartData['cancelled'] ?? []) !!}
        },
        month: {
            labels: {!! json_encode($monthlyChartData['labels'] ?? []) !!},
            completed: {!! json_encode($monthlyChartData['completed'] ?? []) !!},
            rejected: {!! json_encode($monthlyChartData['rejected'] ?? []) !!},
            cancelled: {!! json_encode($monthlyChartData['cancelled'] ?? []) !!}
        }
    };

    // Configuración del gráfico
    const ctx = document.getElementById('appointmentsChart').getContext('2d');
    let chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.day.labels,
            datasets: [
                {
                    label: 'Finalizadas',
                    data: chartData.day.completed,
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0.4,
                    fill: false
                },
                {
                    label: 'Rechazadas',
                    data: chartData.day.rejected,
                    borderColor: 'rgb(239, 68, 68)',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    tension: 0.4,
                    fill: false
                },
                {
                    label: 'Canceladas',
                    data: chartData.day.cancelled,
                    borderColor: 'rgb(249, 115, 22)',
                    backgroundColor: 'rgba(249, 115, 22, 0.1)',
                    tension: 0.4,
                    fill: false
                }
            ]
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
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });

    // Función para actualizar el gráfico
    function updateChart(filter) {
        const data = chartData[filter];
        chart.data.labels = data.labels;
        chart.data.datasets[0].data = data.completed;
        chart.data.datasets[1].data = data.rejected;
        chart.data.datasets[2].data = data.cancelled;
        chart.update();
    }

    // Event listener para el filtro
    document.getElementById('chartFilter').addEventListener('change', function() {
        const specificDateInput = document.getElementById('specificDate');
        
        if (this.value === 'specific') {
            specificDateInput.style.display = 'block';
        } else {
            specificDateInput.style.display = 'none';
        }
        
        updateChart(this.value);
    });

    // Event listener para el cambio de fecha específica
    document.getElementById('specificDate').addEventListener('change', function() {
        if (document.getElementById('chartFilter').value === 'specific') {
            // Hacer petición AJAX para obtener datos del día específico
            fetch(`/dashboard/veterinario/chart-data/${this.value}`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                chartData.specific = data;
                updateChart('specific');
            })
            .catch(error => {
                console.error('Error:', error);
                updateChart('specific');
            });
        }
    });
});
</script>

@endsection
