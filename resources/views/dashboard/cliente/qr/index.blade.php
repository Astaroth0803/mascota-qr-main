<x-app-layout>
    <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-4 lg:py-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <h1 class="text-xl lg:text-2xl xl:text-3xl font-bold text-gray-900">Gestor de Códigos QR</h1>
                        <p class="text-sm lg:text-base text-gray-600 mt-1">Gestiona los códigos QR de tus mascotas</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 lg:gap-3">
                        <a href="{{ route('dashboard.cliente.index') }}" 
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

    <!-- Contenido principal -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
        
        <!-- Estadísticas -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 lg:gap-6 mb-6 lg:mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Mascotas</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total_pets'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Con QR</p>
                        <p class="text-2xl font-bold text-green-600">{{ $stats['pets_with_qr'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Sin QR</p>
                        <p class="text-2xl font-bold text-red-600">{{ $stats['pets_without_qr'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mensaje informativo -->
        <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Información sobre Códigos QR</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <p>Los códigos QR son generados únicamente por el administrador del sistema. Aquí puedes ver y gestionar los códigos QR que ya han sido generados para tus mascotas.</p>
                        <p class="mt-1">Si necesitas generar un código QR para una mascota que no lo tiene, contacta al administrador.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Buscar Mascota</label>
                    <input type="text" id="searchPet" placeholder="Nombre de la mascota..." 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
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

        <!-- Tabla de mascotas -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Mis Mascotas</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mascota</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Especie/Raza</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado QR</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="petsTableBody" class="bg-white divide-y divide-gray-200">
                        @foreach($pets as $pet)
                        <tr class="pet-row" data-pet-id="{{ $pet->id }}" data-pet-name="{{ $pet->nombre }}" data-has-qr="{{ $pet->qr_code ? 'true' : 'false' }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($pet->profile_image)
                                        <img class="h-10 w-10 rounded-full object-cover" src="{{ asset('storage/' . $pet->profile_image) }}" alt="{{ $pet->nombre }}">
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $pet->nombre }}</div>
                                        <div class="text-sm text-gray-500">ID: {{ $pet->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $pet->especie }}</div>
                                <div class="text-sm text-gray-500">{{ $pet->raza }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($pet->qr_code)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Con QR
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                        </svg>
                                        Sin QR
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    @if($pet->qr_code)
                                        <button onclick="showQRModal('{{ $pet->nombre }}', '{{ $pet->qr_code }}', '{{ route('public.pet.qr', $pet->qr_code) }}')" 
                                                class="text-blue-600 hover:text-blue-900">
                                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                                            </svg>
                                            Ver QR
                                        </button>
                                        <a href="{{ route('public.pet.qr', $pet->qr_code) }}" target="_blank" 
                                           class="text-green-600 hover:text-green-900">
                                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                            </svg>
                                            Ver Perfil
                                        </a>
                                        <a href="{{ route('dashboard.cliente.qr.show', $pet->id) }}" 
                                           class="text-purple-600 hover:text-purple-900">
                                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            Detalles
                                        </a>
                                    @else
                                        <span class="text-gray-500 text-sm">
                                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Contacta al administrador
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal de código QR -->
    <div id="qrModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 lg:w-1/3 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900" id="qrModalTitle">Código QR</h3>
                    <button onclick="closeQRModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
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
                    <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2 justify-center">
                        <button onclick="downloadQR()" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Descargar QR
                        </button>
                        <button onclick="printQR()" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                            Imprimir QR
                        </button>
                        <button onclick="copyQRUrl()" class="bg-purple-500 text-white px-4 py-2 rounded-lg hover:bg-purple-600 transition-colors">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                            Copiar URL
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
            const qrStatus = document.getElementById('filterQR').value;
            const rows = document.querySelectorAll('.pet-row');

            rows.forEach(row => {
                const petName = row.dataset.petName.toLowerCase();
                const hasQR = row.dataset.hasQr;

                let showRow = true;

                if (searchTerm && !petName.includes(searchTerm)) {
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
            fetch(`{{ route('dashboard.cliente.qr.generate-single') }}`, {
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
            fetch('{{ route("dashboard.cliente.qr.generate-multiple") }}', {
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

        // Regenerar QR
        function regenerateQR(petId) {
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
                    alert('Código QR regenerado exitosamente para ' + data.pet_name);
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
    </div>
</x-app-layout>
