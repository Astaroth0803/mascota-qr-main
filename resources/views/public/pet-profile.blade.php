<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pet->nombre }} - Perfil de Mascota</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .pet-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .info-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Header -->
    <div class="pet-card text-white py-6">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                        <i class="fas fa-paw text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold">{{ $pet->nombre }}</h1>
                        <p class="text-lg opacity-90">{{ $pet->especie }} - {{ $pet->raza }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm opacity-75">Código QR: {{ $pet->qr_code }}</p>
                    <p class="text-xs opacity-60">Perfil público</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Información Principal -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Información Básica -->
                <div class="info-card rounded-xl p-6 shadow-lg">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                        Información Básica
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-calendar-alt text-gray-500"></i>
                            <div>
                                <p class="text-sm text-gray-600">Edad</p>
                                <p class="font-semibold">{{ $pet->edad_anios }} años, {{ $pet->edad_meses }} meses</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-venus-mars text-gray-500"></i>
                            <div>
                                <p class="text-sm text-gray-600">Sexo</p>
                                <p class="font-semibold">{{ $pet->sexo }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-paw text-gray-500"></i>
                            <div>
                                <p class="text-sm text-gray-600">Especie</p>
                                <p class="font-semibold">{{ $pet->especie }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-tag text-gray-500"></i>
                            <div>
                                <p class="text-sm text-gray-600">Raza</p>
                                <p class="font-semibold">{{ $pet->raza }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información del Dueño -->
                <div class="info-card rounded-xl p-6 shadow-lg">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-user text-green-500 mr-2"></i>
                        Información del Dueño
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-user text-gray-500"></i>
                            <div>
                                <p class="text-sm text-gray-600">Nombre</p>
                                <p class="font-semibold">{{ $pet->nombre_owner }} {{ $pet->apellido_owner }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-phone text-gray-500"></i>
                            <div>
                                <p class="text-sm text-gray-600">Teléfono</p>
                                <p class="font-semibold">{{ $pet->telefono_owner ?? 'No disponible' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-envelope text-gray-500"></i>
                            <div>
                                <p class="text-sm text-gray-600">Email</p>
                                <p class="font-semibold">{{ $pet->correo_owner ?? 'No disponible' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Historial Médico Reciente -->
                @if($pet->vaccinationRecords->count() > 0)
                <div class="info-card rounded-xl p-6 shadow-lg">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-heartbeat text-red-500 mr-2"></i>
                        Historial Médico Reciente
                    </h2>
                    <div class="space-y-4">
                        @foreach($pet->vaccinationRecords as $record)
                        <div class="border-l-4 border-blue-500 pl-4 py-2">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="font-semibold text-gray-800">
                                        @switch($record->record_type)
                                            @case('vacuna')
                                                <i class="fas fa-syringe text-blue-500 mr-1"></i>Vacunación
                                                @break
                                            @case('checkeo')
                                                <i class="fas fa-stethoscope text-green-500 mr-1"></i>Control Médico
                                                @break
                                            @case('peluqueria')
                                                <i class="fas fa-cut text-purple-500 mr-1"></i>Peluquería
                                                @break
                                            @case('operacion')
                                                <i class="fas fa-procedures text-red-500 mr-1"></i>Operación
                                                @break
                                        @endswitch
                                    </h3>
                                    @if($record->vaccine_name)
                                        <p class="text-sm text-gray-600">{{ $record->vaccine_name }}</p>
                                    @endif
                                    @if($record->diagnosis)
                                        <p class="text-sm text-gray-600">{{ $record->diagnosis }}</p>
                                    @endif
                                    @if($record->observations)
                                        <p class="text-sm text-gray-600">{{ $record->observations }}</p>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($record->date)->format('d/m/Y') }}</p>
                                    @if($record->vet_name)
                                        <p class="text-xs text-gray-400">{{ $record->vet_name }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Foto de la Mascota -->
                <div class="info-card rounded-xl p-6 shadow-lg text-center">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Foto de {{ $pet->nombre }}</h3>
                    @if($pet->profile_image)
                        <img src="{{ asset('storage/' . $pet->profile_image) }}" 
                             alt="{{ $pet->nombre }}" 
                             class="w-48 h-48 object-cover rounded-full mx-auto shadow-lg">
                    @else
                        <div class="w-48 h-48 bg-gray-200 rounded-full mx-auto flex items-center justify-center">
                            <i class="fas fa-paw text-6xl text-gray-400"></i>
                        </div>
                    @endif
                </div>

                <!-- Código QR -->
                <div class="info-card rounded-xl p-6 shadow-lg text-center">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Código QR</h3>
                    <div class="flex justify-center mb-4">
                        <img src="{{ app('App\Services\QRCodeService')->generateQRImageUrl(route('public.pet.qr', $pet->qr_code), 200) }}" 
                             alt="Código QR de {{ $pet->nombre }}" 
                             class="border-2 border-gray-300 rounded-lg">
                    </div>
                    <p class="text-sm text-gray-600">Escanea este código para acceder al perfil</p>
                </div>

                <!-- Información de Contacto -->
                <div class="info-card rounded-xl p-6 shadow-lg">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">¿Encontraste esta mascota?</h3>
                    <div class="space-y-3">
                        <p class="text-sm text-gray-600">Si encontraste a {{ $pet->nombre }} perdida, contacta a su dueño:</p>
                        @if($pet->telefono_owner)
                        <a href="tel:{{ $pet->telefono_owner }}" 
                           class="block w-full bg-green-500 text-white py-2 px-4 rounded-lg text-center hover:bg-green-600 transition-colors">
                            <i class="fas fa-phone mr-2"></i>Llamar al Dueño
                        </a>
                        @endif
                        @if($pet->correo_owner)
                        <a href="mailto:{{ $pet->correo_owner }}" 
                           class="block w-full bg-blue-500 text-white py-2 px-4 rounded-lg text-center hover:bg-blue-600 transition-colors">
                            <i class="fas fa-envelope mr-2"></i>Enviar Email
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="container mx-auto px-4 text-center">
            <p class="text-sm opacity-75">Perfil generado por Buky Pet QR</p>
            <p class="text-xs opacity-50 mt-2">Última actualización: {{ $pet->updated_at->format('d/m/Y H:i') }}</p>
        </div>
    </footer>
</body>
</html>

