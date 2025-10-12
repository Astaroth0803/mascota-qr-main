<x-app-layout>
    <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200 pt-16 lg:pt-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-4 lg:py-6">
                <!-- Breadcrumb -->
                <nav class="flex items-center space-x-2 text-sm text-gray-500 mb-4">
                    <a href="{{ route('dashboard.cliente.index') }}" class="hover:text-gray-700 transition-colors">Dashboard</a>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                    <a href="{{ route('dashboard.cliente.qr.index') }}" class="hover:text-gray-700 transition-colors">Códigos QR</a>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                    <span class="text-gray-900 font-medium">{{ $pet->nombre }}</span>
                </nav>
                
                <!-- Título Principal -->
                <div>
                    <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Código QR</h1>
                    <p class="text-gray-600 mt-1">{{ $pet->nombre }} • {{ $pet->especie }} {{ $pet->raza }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenido principal -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
        
        <!-- Banner de la Mascota -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-6">
            <div class="p-6">
                <div class="flex items-center space-x-6">
                    <!-- Avatar -->
                    <div class="flex-shrink-0">
                        @if($pet->profile_image)
                            <img class="w-20 h-20 md:w-24 md:h-24 rounded-2xl object-cover shadow-lg" src="{{ asset('storage/' . $pet->profile_image) }}" alt="{{ $pet->nombre }}">
                        @else
                            <div class="w-20 h-20 md:w-24 md:h-24 bg-gradient-to-br from-blue-100 to-purple-100 rounded-2xl flex items-center justify-center shadow-lg">
                                <svg class="w-10 h-10 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Información -->
                    <div class="flex-1 min-w-0">
                        <div class="mb-4">
                            <h2 class="text-xl md:text-2xl font-bold text-gray-900 mb-1">{{ $pet->nombre }}</h2>
                            <p class="text-gray-600">{{ $pet->especie }} • {{ $pet->raza }}</p>
                        </div>
                        
                        <!-- Información rápida -->
                        <div class="flex flex-wrap gap-3">
                            <div class="flex items-center bg-gray-50 rounded-xl px-3 py-2 border border-gray-200">
                                <svg class="w-4 h-4 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="font-medium text-gray-700">
                                    @if($pet->edad_anios && $pet->edad_anios > 0)
                                        {{ $pet->edad_anios }}a
                                    @endif
                                    @if($pet->edad_meses && $pet->edad_meses > 0)
                                        {{ $pet->edad_meses }}m
                                    @endif
                                    @if(!$pet->edad_anios && !$pet->edad_meses)
                                        Sin edad
                                    @endif
                                </span>
                            </div>
                            
                            <div class="flex items-center bg-gray-50 rounded-xl px-3 py-2 border border-gray-200">
                                <svg class="w-4 h-4 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span class="font-medium text-gray-700">{{ ucfirst($pet->sexo) }}</span>
                            </div>
                            
                            @if($pet->peso)
                                <div class="flex items-center bg-gray-50 rounded-xl px-3 py-2 border border-gray-200">
                                    <svg class="w-4 h-4 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16l3-1m-3 1l-3-1" />
                                    </svg>
                                    <span class="font-medium text-gray-700">{{ $pet->peso }} kg</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Código QR -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-6">
            <div class="p-6">
                <div class="text-center">
                    <div class="mb-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Código QR</h3>
                        <p class="text-gray-600">Escanea este código para acceder al perfil público de {{ $pet->nombre }}</p>
                    </div>
                    
                    <!-- QR Code Image -->
                    <div class="flex justify-center mb-8">
                        <div class="bg-white p-4 rounded-2xl shadow-lg border border-gray-200">
                            <img src="{{ $qrImageUrl }}" alt="Código QR de {{ $pet->nombre }}" class="w-64 h-64 rounded-xl">
                        </div>
                    </div>

                    <!-- QR Information -->
                    <div class="bg-gray-50 rounded-xl p-6 mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm font-medium text-gray-500 mb-2">Código QR</p>
                                <div class="bg-white px-4 py-3 rounded-xl border border-gray-200">
                                    <p class="text-sm font-mono text-gray-900">{{ $pet->qr_code }}</p>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 mb-2">URL Pública</p>
                                <div class="bg-white px-4 py-3 rounded-xl border border-gray-200">
                                    <p class="text-sm font-mono text-gray-900 break-all">{{ $publicUrl }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Acciones -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                        <button onclick="downloadQR()" class="inline-flex items-center justify-center px-4 py-3 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Descargar QR
                        </button>
                        <button onclick="printQR()" class="inline-flex items-center justify-center px-4 py-3 bg-green-600 text-white text-sm font-medium rounded-xl hover:bg-green-700 transition-colors shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                            Imprimir QR
                        </button>
                        <button onclick="copyQRUrl()" class="inline-flex items-center justify-center px-4 py-3 bg-purple-600 text-white text-sm font-medium rounded-xl hover:bg-purple-700 transition-colors shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                            Copiar URL
                        </button>
                        <a href="{{ $publicUrl }}" target="_blank" class="inline-flex items-center justify-center px-4 py-3 bg-indigo-600 text-white text-sm font-medium rounded-xl hover:bg-indigo-700 transition-colors shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                            Ver Perfil Público
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Instrucciones de uso -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-2xl p-6">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-blue-900 mb-3">¿Cómo usar este código QR?</h3>
                    <div class="space-y-3">
                        <div class="flex items-start">
                            <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                <span class="text-xs font-bold text-blue-600">1</span>
                            </div>
                            <p class="ml-3 text-sm text-blue-800">Imprime este código QR y colócalo en la placa de identificación de tu mascota</p>
                        </div>
                        <div class="flex items-start">
                            <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                <span class="text-xs font-bold text-blue-600">2</span>
                            </div>
                            <p class="ml-3 text-sm text-blue-800">Cualquier persona puede escanear el código para ver el perfil público de {{ $pet->nombre }}</p>
                        </div>
                        <div class="flex items-start">
                            <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                <span class="text-xs font-bold text-blue-600">3</span>
                            </div>
                            <p class="ml-3 text-sm text-blue-800">El código QR contiene información de contacto para casos de emergencia</p>
                        </div>
                        <div class="flex items-start">
                            <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5">
                                <span class="text-xs font-bold text-blue-600">4</span>
                            </div>
                            <p class="ml-3 text-sm text-blue-800">Si necesitas regenerar el código QR, contacta al administrador del sistema</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Variables globales
        const qrUrl = '{{ $publicUrl }}';
        const qrCode = '{{ $pet->qr_code }}';
        const petId = {{ $pet->id }};

        // Descargar código QR
        function downloadQR() {
            const qrImageUrl = '{{ $qrImageUrl }}';
            const link = document.createElement('a');
            link.download = `qr-{{ $pet->nombre }}-${qrCode}.png`;
            link.href = qrImageUrl;
            link.click();
        }

        // Imprimir código QR
        function printQR() {
            const qrImageUrl = '{{ $qrImageUrl }}';
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                    <head>
                        <title>Imprimir Código QR - {{ $pet->nombre }}</title>
                        <style>
                            body { 
                                font-family: Arial, sans-serif; 
                                text-align: center; 
                                padding: 20px;
                            }
                            .qr-container { 
                                margin: 20px 0; 
                            }
                            .qr-info { 
                                margin: 20px 0; 
                                font-size: 14px;
                                text-align: left;
                                max-width: 400px;
                                margin-left: auto;
                                margin-right: auto;
                            }
                            .qr-code { 
                                font-family: monospace; 
                                background: #f0f0f0; 
                                padding: 5px; 
                                border-radius: 3px;
                                word-break: break-all;
                            }
                            .pet-info {
                                margin-bottom: 20px;
                                padding: 15px;
                                background: #f8f9fa;
                                border-radius: 8px;
                            }
                        </style>
                    </head>
                    <body>
                        <div class="pet-info">
                            <h2>{{ $pet->nombre }}</h2>
                            <p><strong>Especie:</strong> {{ $pet->especie }}</p>
                            <p><strong>Raza:</strong> {{ $pet->raza }}</p>
                        </div>
                        
                        <h2>Código QR</h2>
                        <div class="qr-container">
                            <img src="${qrImageUrl}" alt="Código QR" style="max-width: 300px;">
                        </div>
                        
                        <div class="qr-info">
                            <p><strong>Código:</strong> <span class="qr-code">${qrCode}</span></p>
                            <p><strong>URL:</strong> <span class="qr-code">${qrUrl}</span></p>
                        </div>
                        
                        <p><em>Escanea este código QR para acceder al perfil de {{ $pet->nombre }}</em></p>
                    </body>
                </html>
            `);
            printWindow.document.close();
            printWindow.print();
        }

        // Copiar URL del QR
        function copyQRUrl() {
            navigator.clipboard.writeText(qrUrl).then(function() {
                alert('URL copiada al portapapeles');
            }, function(err) {
                console.error('Error al copiar: ', err);
                alert('Error al copiar la URL');
            });
        }

        // Regenerar código QR
        function regenerateQR() {
            if (!confirm('¿Estás seguro de que quieres regenerar el código QR? El código anterior dejará de funcionar.')) {
                return;
            }

            fetch(`{{ route('dashboard.cliente.qr.regenerate', '') }}/${petId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Código QR regenerado exitosamente');
                    location.reload();
                } else {
                    alert('Error al regenerar el código QR: ' + (data.message || 'Error desconocido'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al regenerar el código QR');
            });
        }
    </script>
    </div>
</x-app-layout>
