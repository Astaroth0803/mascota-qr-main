@extends('layouts.standard')

@section('title', 'Código QR - ' . $pet->nombre)

@php
    $title = 'Código QR';
    $subtitle = $pet->nombre . ' • ' . $pet->especie . ' ' . $pet->raza;
@endphp

@section('main-content')
<div class="space-y-6">
            
            <!-- Columna Derecha - Código QR -->
            <div class="lg:col-span-2">

                <!-- Código QR -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-6">
                    <div class="p-6">
                        <div class="text-center mb-6">
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">Código QR</h3>
                            <p class="text-gray-600">Escanea este código para acceder al perfil público de {{ $pet->nombre }}</p>
                        </div>
                        
                        <!-- QR Code Image -->
                        <div class="flex justify-center mb-8">
                            <div class="bg-white p-6 rounded-2xl shadow-lg border-2 border-gray-200">
                                <img src="{{ $qrImageUrl }}" alt="Código QR de {{ $pet->nombre }}" class="w-64 h-64 rounded-xl">
                            </div>
                        </div>

                        <!-- QR Information -->
                        <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl p-6 mb-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <p class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                                        </svg>
                                        Código QR
                                    </p>
                                    <div class="bg-white px-4 py-3 rounded-xl border border-gray-200">
                                        <p class="text-sm font-mono text-gray-900">{{ $pet->qr_code }}</p>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                        </svg>
                                        URL Pública
                                    </p>
                                    <div class="bg-white px-4 py-3 rounded-xl border border-gray-200">
                                        <p class="text-sm font-mono text-gray-900 break-all">{{ $publicUrl }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Acciones -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <button onclick="downloadQR()" class="inline-flex items-center justify-center px-6 py-4 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Descargar QR
                            </button>
                            <button onclick="printQR()" class="inline-flex items-center justify-center px-6 py-4 bg-green-600 text-white text-sm font-semibold rounded-xl hover:bg-green-700 transition-all duration-300 shadow-lg hover:shadow-xl">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                </svg>
                                Imprimir QR
                            </button>
                        </div>
                        
                        <!-- Sección de Compartir -->
                        <div class="mt-6 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl p-6 border border-indigo-200">
                            <div class="flex items-center mb-4">
                                <div class="w-8 h-8 bg-indigo-500 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                                    </svg>
                                </div>
                                <h4 class="text-lg font-semibold text-gray-800">Compartir Perfil</h4>
                            </div>
                            <p class="text-sm text-gray-600 mb-4">Comparte el perfil de tu mascota con veterinarios, familiares o cuidadores. Los dispositivos nativos ofrecen opciones como copiar URL, enviar por WhatsApp, email, etc.</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <button onclick="shareQR()" class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-black text-sm font-semibold rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 shadow-lg">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                                    </svg>
                                    Compartir Perfil
                                </button>
                                <a href="{{ $publicUrl }}" target="_blank" class="w-full inline-flex items-center justify-center px-4 py-3 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition-all duration-300 shadow-lg">
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
@endsection
