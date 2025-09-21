@extends('layouts.dashboard')

@section('title', 'Generador de Códigos QR')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="lg:ml-64">
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="py-4 lg:py-6">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <div class="flex-1 min-w-0">
                            <h1 class="text-xl lg:text-2xl xl:text-3xl font-bold text-gray-900">Generador de Códigos QR</h1>
                            <p class="text-sm lg:text-base text-gray-600 mt-1">Genera códigos QR para las mascotas registradas</p>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-2 lg:gap-3">
                            <a href="{{ route('dashboard.administrador') }}" 
                               class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
                                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                <span>Volver al Dashboard</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenido principal -->
    <div class="lg:ml-64">
        <div class="px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
                {{-- Botones de acción --}}
                <div class="mb-6 flex flex-col sm:flex-row gap-4">
                    <button id="selectAllBtn" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors">
                        <i class="fas fa-check-square mr-2"></i>Seleccionar Todas
                    </button>
                    <button id="deselectAllBtn" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                        <i class="fas fa-square mr-2"></i>Deseleccionar Todas
                    </button>
                    <button id="generateSelectedBtn" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors">
                        <i class="fas fa-qrcode mr-2"></i>Generar QR Seleccionadas
                    </button>
                </div>

                {{-- Filtros --}}
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Buscar Mascota</label>
                            <input type="text" id="searchPet" placeholder="Nombre de la mascota..." 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Filtrar por Usuario</label>
                            <select id="filterUser" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Todos los usuarios</option>
                                @foreach($pets->pluck('user.name', 'user.id')->unique() as $userId => $userName)
                                    <option value="{{ $userId }}">{{ $userName }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Estado QR</label>
                            <select id="filterQR" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Todos</option>
                                <option value="with_qr">Con QR</option>
                                <option value="without_qr">Sin QR</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Tabla de mascotas --}}
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Mascotas Registradas</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <input type="checkbox" id="selectAllCheckbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mascota</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dueño</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Especie/Raza</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado QR</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="petsTableBody" class="bg-white divide-y divide-gray-200">
                                @foreach($pets as $pet)
                                <tr class="pet-row" data-pet-id="{{ $pet->id }}" data-pet-name="{{ $pet->nombre }}" data-user-id="{{ $pet->user_id }}" data-has-qr="{{ $pet->qr_code ? 'true' : 'false' }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="checkbox" class="pet-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500" value="{{ $pet->id }}">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if($pet->profile_image)
                                                <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . $pet->profile_image) }}" alt="{{ $pet->nombre }}">
                                            @else
                                                <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                                    <i class="fas fa-paw text-gray-400"></i>
                                                </div>
                                            @endif
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $pet->nombre }}</div>
                                                <div class="text-sm text-gray-500">ID: {{ $pet->id }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $pet->user->name ?? 'N/A' }}</div>
                                        <div class="text-sm text-gray-500">{{ $pet->user->email ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $pet->especie }}</div>
                                        <div class="text-sm text-gray-500">{{ $pet->raza }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($pet->qr_code)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i>Con QR
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-times-circle mr-1"></i>Sin QR
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            @if($pet->qr_code)
                                                <button onclick="showQRModal('{{ $pet->nombre }}', '{{ $pet->qr_code }}', '{{ route('public.pet.qr', $pet->qr_code) }}')" 
                                                        class="text-blue-600 hover:text-blue-900">
                                                    <i class="fas fa-qrcode"></i> Ver QR
                                                </button>
                                                <a href="{{ route('public.pet.qr', $pet->qr_code) }}" target="_blank" 
                                                   class="text-green-600 hover:text-green-900">
                                                    <i class="fas fa-external-link-alt"></i> Ver Perfil
                                                </a>
                                            @else
                                                <button onclick="generateSingleQR({{ $pet->id }})" 
                                                        class="text-green-600 hover:text-green-900">
                                                    <i class="fas fa-qrcode"></i> Generar QR
                                                </button>
                                            @endif
                                            <a href="{{ route('usuarios.mascotas', $pet->user_id) }}" 
                                               class="text-purple-600 hover:text-purple-900">
                                                <i class="fas fa-user"></i> Ver Usuario
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de código QR -->
    <div id="qrModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 lg:w-1/3 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900" id="qrModalTitle">Código QR</h3>
                    <button onclick="closeQRModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="text-center">
                    <div id="qrCodeContainer" class="flex justify-center mb-4">
                        <!-- El código QR se generará aquí -->
                    </div>
                    <div class="mb-4">
                        <p class="text-sm text-gray-600 mb-2">Código: <span id="qrCodeText" class="font-mono text-xs bg-gray-100 px-2 py-1 rounded"></span></p>
                        <p class="text-sm text-gray-600">URL: <span id="qrUrlText" class="font-mono text-xs bg-gray-100 px-2 py-1 rounded break-all"></span></p>
                    </div>
                    <div class="flex space-x-2 justify-center">
                        <button onclick="downloadQR()" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors">
                            <i class="fas fa-download mr-2"></i>Descargar QR
                        </button>
                        <button onclick="printQR()" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors">
                            <i class="fas fa-print mr-2"></i>Imprimir QR
                        </button>
                        <button onclick="copyQRUrl()" class="bg-purple-500 text-white px-4 py-2 rounded-lg hover:bg-purple-600 transition-colors">
                            <i class="fas fa-copy mr-2"></i>Copiar URL
                        </button>
                    </div>
                </div>
                <div class="mt-6 flex justify-end">
                    <button onclick="closeQRModal()" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Variables globales para el modal QR
        let currentQRUrl = '';
        let currentQRCode = '';

        // Funcionalidad de selección
        document.getElementById('selectAllCheckbox').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.pet-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        document.getElementById('selectAllBtn').addEventListener('click', function() {
            const checkboxes = document.querySelectorAll('.pet-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = true;
            });
            document.getElementById('selectAllCheckbox').checked = true;
        });

        document.getElementById('deselectAllBtn').addEventListener('click', function() {
            const checkboxes = document.querySelectorAll('.pet-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            document.getElementById('selectAllCheckbox').checked = false;
        });

        // Filtros
        function filterPets() {
            const searchTerm = document.getElementById('searchPet').value.toLowerCase();
            const userId = document.getElementById('filterUser').value;
            const qrStatus = document.getElementById('filterQR').value;
            const rows = document.querySelectorAll('.pet-row');

            rows.forEach(row => {
                const petName = row.dataset.petName.toLowerCase();
                const rowUserId = row.dataset.userId;
                const hasQR = row.dataset.hasQr;

                let showRow = true;

                if (searchTerm && !petName.includes(searchTerm)) {
                    showRow = false;
                }

                if (userId && rowUserId !== userId) {
                    showRow = false;
                }

                if (qrStatus === 'with_qr' && hasQR !== 'true') {
                    showRow = false;
                }

                if (qrStatus === 'without_qr' && hasQR !== 'false') {
                    showRow = false;
                }

                row.style.display = showRow ? '' : 'none';
            });
        }

        document.getElementById('searchPet').addEventListener('input', filterPets);
        document.getElementById('filterUser').addEventListener('change', filterPets);
        document.getElementById('filterQR').addEventListener('change', filterPets);

        // Generar QR para múltiples mascotas
        document.getElementById('generateSelectedBtn').addEventListener('click', function() {
            const selectedPets = Array.from(document.querySelectorAll('.pet-checkbox:checked')).map(cb => cb.value);
            
            if (selectedPets.length === 0) {
                alert('Por favor selecciona al menos una mascota.');
                return;
            }

            generateMultipleQR(selectedPets);
        });

        // Generar QR para una sola mascota
        function generateSingleQR(petId) {
            fetch(`/dashboard/administrador/qr/generate-single`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    pet_id: petId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showResults([{
                        pet_id: petId,
                        pet_name: data.pet_name || 'Mascota',
                        qr_code: data.qr_code,
                        public_url: data.public_url,
                        qr_image_url: data.qr_image_url
                    }]);
                    // Recargar la página para actualizar el estado
                    setTimeout(() => location.reload(), 2000);
                } else {
                    alert('Error al generar el código QR: ' + (data.message || 'Error desconocido'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al generar el código QR');
            });
        }

        // Generar QR para múltiples mascotas
        function generateMultipleQR(petIds) {
            fetch('/dashboard/administrador/qr/generate-multiple', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    pet_ids: petIds
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showResults(data.results);
                    // Recargar la página para actualizar el estado
                    setTimeout(() => location.reload(), 2000);
                } else {
                    alert('Error al generar los códigos QR: ' + (data.message || 'Error desconocido'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al generar los códigos QR');
            });
        }

        // Mostrar modal de código QR
        function showQRModal(petName, qrCode, qrUrl) {
            document.getElementById('qrModalTitle').textContent = `Código QR - ${petName}`;
            document.getElementById('qrCodeText').textContent = qrCode;
            document.getElementById('qrUrlText').textContent = qrUrl;
            currentQRUrl = qrUrl;
            currentQRCode = qrCode;

            // Generar código QR usando la API
            const qrImageUrl = `https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=${encodeURIComponent(qrUrl)}`;
            document.getElementById('qrCodeContainer').innerHTML = `<img src="${qrImageUrl}" alt="Código QR" class="border-2 border-gray-300 rounded-lg">`;

            // Mostrar modal
            document.getElementById('qrModal').classList.remove('hidden');
        }

        // Cerrar modal QR
        function closeQRModal() {
            document.getElementById('qrModal').classList.add('hidden');
            currentQRUrl = '';
            currentQRCode = '';
        }

        // Descargar código QR
        function downloadQR() {
            if (currentQRUrl) {
                const qrImageUrl = `https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=${encodeURIComponent(currentQRUrl)}`;
                const link = document.createElement('a');
                link.download = `qr-${currentQRCode}.png`;
                link.href = qrImageUrl;
                link.click();
            }
        }

        // Imprimir código QR
        function printQR() {
            if (currentQRUrl) {
                const qrImageUrl = `https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=${encodeURIComponent(currentQRUrl)}`;
                const printWindow = window.open('', '_blank');
                printWindow.document.write(`
                    <html>
                        <head>
                            <title>Imprimir Código QR</title>
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
                                }
                                .qr-code { 
                                    font-family: monospace; 
                                    background: #f0f0f0; 
                                    padding: 5px; 
                                    border-radius: 3px;
                                }
                            </style>
                        </head>
                        <body>
                            <h2>Código QR - ${document.getElementById('qrModalTitle').textContent.replace('Código QR - ', '')}</h2>
                            <div class="qr-container">
                                <img src="${qrImageUrl}" alt="Código QR" style="max-width: 300px;">
                            </div>
                            <div class="qr-info">
                                <p><strong>Código:</strong> <span class="qr-code">${currentQRCode}</span></p>
                                <p><strong>URL:</strong> <span class="qr-code">${currentQRUrl}</span></p>
                            </div>
                            <p><em>Escanea este código QR para acceder al perfil de la mascota</em></p>
                        </body>
                    </html>
                `);
                printWindow.document.close();
                printWindow.print();
            }
        }

        // Copiar URL del QR
        function copyQRUrl() {
            copyToClipboard(currentQRUrl);
        }

        // Mostrar resultados
        function showResults(results) {
            let message = 'Códigos QR generados correctamente:\n\n';
            results.forEach(result => {
                message += `• ${result.pet_name}: ${result.qr_code}\n`;
            });
            alert(message);
        }

        // Copiar al portapapeles
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                alert('URL copiada al portapapeles');
            }, function(err) {
                console.error('Error al copiar: ', err);
            });
        }
    </script>
@endsection

