<x-app-layout>
    <x-slot name="header">
        <div class="transition-all duration-300 ease-in-out lg:ml-64">
            <div class="bg-white overflow-hidden shadow rounded-lg p-4 sm:p-6 hover:shadow-lg transition-shadow duration-300 mb-8 mx-2 sm:mx-4 lg:mx-8">
                <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
                    <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                        {{ __('Dashboard - Buky Pet Admin') }}
                    </h2>
                </div>
            </div>
        </div>
    </x-slot>

    {{-- Incluir el sidebar como componente --}}
    <x-sidebar-menu :active="'dashboard'" :pendingRequests="$solicitudCount ?? 0" />

    {{-- Contenido principal --}}
    <div class="lg:ml-64 transition-all duration-300 ease-in-out">
        <div class="min-h-screen bg-gray-50">
            <div class="p-4 sm:p-6 lg:p-8 mt-4">
                {{-- Tarjetas de estadísticas --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-6">
                    {{-- Tarjeta de Usuarios --}}
                    <div class="bg-white overflow-hidden shadow rounded-lg p-4 sm:p-5 hover:shadow-lg transition-shadow duration-300">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-users text-blue-500 text-xl sm:text-2xl"></i>
                            </div>
                            <div class="ml-3 sm:ml-5 w-0 flex-1 min-w-0">
                                <dl>
                                    <dt class="text-xs sm:text-sm font-medium text-gray-500 truncate">Usuarios</dt>
                                    <dd class="flex items-baseline">
                                        <div class="text-lg sm:text-2xl font-semibold text-gray-900">{{ $userCount ?? 0 }}</div>
                                        <div class="ml-2 flex items-baseline text-xs sm:text-sm font-semibold text-green-600">
                                            <svg class="self-center flex-shrink-0 mr-1 h-3 w-3 sm:h-5 sm:w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                            </svg>
                                            <span class="sr-only">Increased by</span>
                                            0%
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>

                    {{-- Tarjeta de Mascotas --}}
                    <div class="bg-white overflow-hidden shadow rounded-lg p-4 sm:p-5 hover:shadow-lg transition-shadow duration-300">
                         <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-paw text-green-500 text-xl sm:text-2xl"></i>
                            </div>
                            <div class="ml-3 sm:ml-5 w-0 flex-1 min-w-0">
                                <dl>
                                    <dt class="text-xs sm:text-sm font-medium text-gray-500 truncate">Mascotas</dt>
                                    <dd class="flex items-baseline">
                                        <div class="text-lg sm:text-2xl font-semibold text-gray-900">{{ count($pets) }}</div>
                                        <div class="ml-2 flex items-baseline text-xs sm:text-sm font-semibold text-green-600">
                                            <svg class="self-center flex-shrink-0 mr-1 h-3 w-3 sm:h-5 sm:w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                            </svg>
                                            <span class="sr-only">Increased by</span>
                                            0%
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>

                    {{-- Tarjeta de Solicitudes Pendientes --}}
                    <div class="bg-white overflow-hidden shadow rounded-lg p-4 sm:p-5 hover:shadow-lg transition-shadow duration-300 sm:col-span-2 lg:col-span-1">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-clock text-yellow-500 text-xl sm:text-2xl"></i>
                            </div>
                            <div class="ml-3 sm:ml-5 w-0 flex-1 min-w-0">
                                <dl>
                                    <dt class="text-xs sm:text-sm font-medium text-gray-500 truncate">Solicitudes Pendientes</dt>
                                    <dd class="flex items-baseline">
                                        <div class="text-lg sm:text-2xl font-semibold text-gray-900">{{ $solicitudCount ?? 0 }}</div>
                                         <div class="ml-2 flex items-baseline text-xs sm:text-sm font-semibold text-green-600">
                                            <svg class="self-center flex-shrink-0 mr-1 h-3 w-3 sm:h-5 sm:w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                            </svg>
                                            <span class="sr-only">Increased by</span>
                                            0%
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Sección de Gráficos --}}
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 sm:gap-6 mb-6">
                    {{-- Gráfico de Actividad de Usuarios --}}
                    <div class="bg-white overflow-hidden shadow rounded-lg p-4 sm:p-6 hover:shadow-lg transition-shadow duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-base sm:text-lg font-medium text-gray-900">Actividad de Usuarios</h3>
                            <div class="flex space-x-2">
                                <button class="text-xs sm:text-sm text-gray-500 hover:text-gray-700">0 días</button>
                                <button class="text-xs sm:text-sm text-blue-600 font-medium">0 días</button>
                                <button class="text-xs sm:text-sm text-gray-500 hover:text-gray-700">0 días</button>
                            </div>
                        </div>
                        <div class="h-48 sm:h-64 lg:h-72">
                            <canvas id="user-activity-chart"></canvas>
                        </div>
                    </div>

                    {{-- Gráfico de Distribución de Mascotas --}}
                    <div class="bg-white overflow-hidden shadow rounded-lg p-4 sm:p-6 hover:shadow-lg transition-shadow duration-300">
                         <div class="flex items-center justify-between mb-4">
                            <h3 class="text-base sm:text-lg font-medium text-gray-900">Distribución de Mascotas</h3>
                            <div class="text-xs sm:text-sm text-gray-500">
                                Total: {{ count($pets) }}
                            </div>
                        </div>
                        <div class="h-48 sm:h-64 lg:h-72">
                            <canvas id="pet-distribution-chart"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Sección de Acciones Rápidas --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6">
                    <a href="{{ route('dashboard.usuarios') }}" class="bg-white p-4 sm:p-6 rounded-lg shadow hover:shadow-lg transition-all duration-300 group">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition-colors duration-300">
                                    <i class="fas fa-users text-blue-600 text-sm sm:text-base"></i>
                                </div>
                            </div>
                            <div class="ml-3 sm:ml-4">
                                <p class="text-sm sm:text-base font-medium text-gray-900 group-hover:text-blue-600 transition-colors duration-300">Gestionar Usuarios</p>
                                <p class="text-xs sm:text-sm text-gray-500">Ver y administrar usuarios</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('dashboard.solicitudes') }}" class="bg-white p-4 sm:p-6 rounded-lg shadow hover:shadow-lg transition-all duration-300 group">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-orange-100 rounded-lg flex items-center justify-center group-hover:bg-orange-200 transition-colors duration-300">
                                    <i class="fas fa-clipboard-list text-orange-600 text-sm sm:text-base"></i>
                                </div>
                            </div>
                            <div class="ml-3 sm:ml-4">
                                <p class="text-sm sm:text-base font-medium text-gray-900 group-hover:text-orange-600 transition-colors duration-300">Solicitudes</p>
                                <p class="text-xs sm:text-sm text-gray-500">Revisar solicitudes pendientes</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('usuarios.create') }}" class="bg-white p-4 sm:p-6 rounded-lg shadow hover:shadow-lg transition-all duration-300 group">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-200 transition-colors duration-300">
                                    <i class="fas fa-user-plus text-green-600 text-sm sm:text-base"></i>
                                </div>
                            </div>
                            <div class="ml-3 sm:ml-4">
                                <p class="text-sm sm:text-base font-medium text-gray-900 group-hover:text-green-600 transition-colors duration-300">Nuevo Usuario</p>
                                <p class="text-xs sm:text-sm text-gray-500">Crear cuenta de usuario</p>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('qr.generator') }}" class="bg-white p-4 sm:p-6 rounded-lg shadow hover:shadow-lg transition-all duration-300 group">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-purple-100 rounded-lg flex items-center justify-center group-hover:bg-purple-200 transition-colors duration-300">
                                    <i class="fas fa-qrcode text-purple-600 text-sm sm:text-base"></i>
                                </div>
                            </div>
                            <div class="ml-3 sm:ml-4">
                                <p class="text-sm sm:text-base font-medium text-gray-900 group-hover:text-purple-600 transition-colors duration-300">Generador QR</p>
                                <p class="text-xs sm:text-sm text-gray-500">Crear códigos QR para mascotas</p>
                            </div>
                        </div>
                    </a>
                </div>

                {{-- Sección de Actividad Reciente --}}
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 sm:px-6 py-4 sm:py-6 border-b border-gray-200">
                        <h3 class="text-base sm:text-lg font-medium text-gray-900">Actividad Reciente</h3>
                    </div>
                    <div class="divide-y divide-gray-200">
                        <div class="px-4 sm:px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user-plus text-green-600 text-sm"></i>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">Nuevo usuario registrado</p>
                                    <p class="text-xs text-gray-500">Hace 0 horas</p>
                                </div>
                            </div>
                        </div>
                        <div class="px-4 sm:px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-paw text-blue-600 text-sm"></i>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">Nueva mascota registrada</p>
                                    <p class="text-xs text-gray-500">Hace 0 horas</p>
                                </div>
                            </div>
                        </div>
                        <div class="px-4 sm:px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-clipboard-check text-orange-600 text-sm"></i>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">Solicitud aprobada</p>
                                    <p class="text-xs text-gray-500">Hace 0 horas</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Datos de actividad de usuarios (pasados desde el controlador)
        const userDataForChart = @json($userDataForChart);

        // Datos de distribución de mascotas (pasados desde el controlador)
        const petDistributionData = @json($petDistributionData);

        // Configuración responsive para Chart.js
        Chart.defaults.responsive = true;
        Chart.defaults.maintainAspectRatio = false;

        // Función para obtener el tamaño de fuente según el dispositivo
        function getFontSize() {
            return window.innerWidth < 640 ? 10 : window.innerWidth < 1024 ? 11 : 12;
        }

        // Gráfico de Actividad de Usuarios
        const userActivityCtx = document.getElementById('user-activity-chart').getContext('2d');
        new Chart(userActivityCtx, {
            type: 'line',
            data: {
                labels: ['Día 1', 'Día 2', 'Día 3', 'Día 4', 'Día 5', 'Día 6', 'Día 7'],
                datasets: [{
                    label: 'Usuarios Activos',
                    data: userDataForChart,
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: 'rgb(59, 130, 246)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            font: {
                                size: getFontSize()
                            },
                            color: '#6B7280'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: getFontSize()
                            },
                            color: '#6B7280'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: {
                                size: getFontSize() + 1
                            },
                            color: '#374151',
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: 'rgba(59, 130, 246, 0.5)',
                        borderWidth: 1,
                        cornerRadius: 8,
                        displayColors: false
                    }
                }
            }
        });

        // Gráfico de Distribución de Mascotas
        const petDistributionCtx = document.getElementById('pet-distribution-chart').getContext('2d');
        new Chart(petDistributionCtx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(petDistributionData),
                datasets: [{
                    label: 'Distribución de Mascotas',
                    data: Object.values(petDistributionData),
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(245, 158, 11, 0.8)'
                    ],
                    borderColor: [
                        'rgba(59, 130, 246, 1)',
                        'rgba(16, 185, 129, 1)',
                        'rgba(245, 158, 11, 1)'
                    ],
                    borderWidth: 2,
                    hoverOffset: 4
                }]
            },
             options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '60%',
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            font: {
                                size: getFontSize()
                            },
                            color: '#374151',
                            padding: window.innerWidth < 640 ? 10 : 15,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: 'rgba(59, 130, 246, 0.5)',
                        borderWidth: 1,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(1);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });

        // Ajustar gráficos en cambio de tamaño de ventana
        window.addEventListener('resize', function() {
            // Los gráficos se ajustarán automáticamente gracias a responsive: true
            // Pero podemos forzar un redibujado si es necesario
            setTimeout(() => {
                window.dispatchEvent(new Event('resize'));
            }, 100);
        });
    </script>
</x-app-layout>
