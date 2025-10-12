<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pet->nombre }} - Perfil de Mascota | BUKY WORLD</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }
        
        .buky-header {
            background: linear-gradient(135deg, #6366F1 0%, #8B5CF6 50%, #A855F7 100%);
            box-shadow: 0 4px 20px rgba(99, 102, 241, 0.3);
        }
        
        .toggle-active {
            background: linear-gradient(135deg, #10B981 0%, #059669 100%);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
            transform: translateY(-1px);
        }
        
        .toggle-inactive {
            background: linear-gradient(135deg, #6B7280 0%, #4B5563 100%);
            box-shadow: 0 2px 8px rgba(107, 114, 128, 0.2);
        }
        
        .toggle-inactive:hover {
            background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        
        .card-shadow {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .info-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .contact-button {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .contact-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }
        
        .table-header {
            background: linear-gradient(135deg, #F8FAFC 0%, #F1F5F9 100%);
        }
        
        .table-row:hover {
            background: linear-gradient(135deg, #F8FAFC 0%, #F1F5F9 100%);
        }
        
        .pet-avatar {
            border: 4px solid rgba(255, 255, 255, 0.9);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
        }
        
        .section-title {
            background: linear-gradient(135deg, #1F2937 0%, #374151 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .info-label {
            color: #6B7280;
            font-weight: 500;
            letter-spacing: 0.025em;
        }
        
        .info-value {
            color: #1F2937;
            font-weight: 600;
        }
        
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .badge-vaccine {
            background: linear-gradient(135deg, #10B981 0%, #059669 100%);
            color: white;
        }
        
        .badge-medicine {
            background: linear-gradient(135deg, #3B82F6 0%, #2563EB 100%);
            color: white;
        }
        
        .badge-grooming {
            background: linear-gradient(135deg, #8B5CF6 0%, #7C3AED 100%);
            color: white;
        }
        
        .badge-surgery {
            background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
            color: white;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header Púrpura -->
    <div class="buky-header text-white py-6">
        <div class="container mx-auto px-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-6">
                    <div class="w-16 h-16 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                        <i class="fas fa-paw text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold tracking-tight">BUKY WORLD</h1>
                        <p class="text-xl font-medium opacity-90 mt-1">{{ $pet->nombre }} - {{ $pet->raza }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="bg-white bg-opacity-20 rounded-xl px-4 py-2 backdrop-blur-sm">
                        <p class="text-sm font-semibold">PET ID: {{ $pet->id }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-6">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Sidebar Izquierdo - Información de la Mascota -->
            <div class="lg:col-span-1">
                <div class="info-card card-shadow rounded-2xl p-8">
                    <h2 class="section-title text-2xl font-bold mb-6">INFORMACION DE LA MASCOTA</h2>
                    
                    <!-- Foto de la Mascota -->
                    <div class="text-center mb-8">
                        @if($pet->profile_image)
                            <img src="{{ asset('storage/' . $pet->profile_image) }}" 
                                 alt="{{ $pet->nombre }}" 
                                 class="w-36 h-36 object-cover rounded-full mx-auto pet-avatar">
                        @else
                            <div class="w-36 h-36 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full mx-auto flex items-center justify-center pet-avatar">
                                <i class="fas fa-paw text-5xl text-gray-400"></i>
                            </div>
                        @endif
                    </div>

                    <!-- Información Básica -->
                    <div class="space-y-4 mb-8">
                        <div class="bg-gray-50 rounded-xl p-4">
                            <label class="info-label text-sm font-semibold uppercase tracking-wide">EDAD</label>
                            <p class="info-value text-lg mt-1">
                                @if($pet->edad_anios && $pet->edad_meses)
                                    {{ $pet->edad_anios }}a {{ $pet->edad_meses }}m
                                @elseif($pet->edad_anios)
                                    {{ $pet->edad_anios }}a
                                @elseif($pet->edad_meses)
                                    {{ $pet->edad_meses }}m
                                @else
                                    No especificada
                                @endif
                            </p>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-4">
                            <label class="info-label text-sm font-semibold uppercase tracking-wide">SEXO</label>
                            <p class="info-value text-lg mt-1">{{ ucfirst($pet->sexo) }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-4">
                            <label class="info-label text-sm font-semibold uppercase tracking-wide">ESPECIE</label>
                            <p class="info-value text-lg mt-1">{{ ucfirst($pet->especie) }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-4">
                            <label class="info-label text-sm font-semibold uppercase tracking-wide">RAZA</label>
                            <p class="info-value text-lg mt-1">{{ ucfirst($pet->raza) }}</p>
                        </div>
                    </div>

                    <!-- Contactar al Dueño -->
                    <div>
                        <h3 class="section-title text-xl font-bold mb-6">CONTACTAR AL DUEÑO</h3>
                        <div class="space-y-4">
                            @if($pet->telefono_owner)
                            <a href="tel:{{ $pet->telefono_owner }}" 
                               class="contact-button block w-full bg-gradient-to-r from-green-500 to-green-600 text-white py-4 px-6 rounded-xl text-center font-semibold">
                                <i class="fas fa-phone mr-3"></i>BOTON DE LLAMADA
                            </a>
                            @endif
                            @if($pet->correo_owner)
                            <a href="mailto:{{ $pet->correo_owner }}" 
                               class="contact-button block w-full bg-gradient-to-r from-blue-500 to-blue-600 text-white py-4 px-6 rounded-xl text-center font-semibold">
                                <i class="fas fa-envelope mr-3"></i>BOTON DE CORREO
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Área Principal - Historial Médico -->
            <div class="lg:col-span-3">
                <div class="info-card card-shadow rounded-2xl p-8">
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="section-title text-3xl font-bold">HISTORIAL MEDICO RECIENTE</h2>
                    </div>

                    <!-- Toggle de Selección -->
                    <div class="flex mb-8 rounded-xl overflow-hidden shadow-lg">
                        <button id="vaccinesToggle" 
                                class="toggle-active text-white px-8 py-4 font-semibold text-lg transition-all duration-300 flex-1">
                            <i class="fas fa-syringe mr-2"></i>VACUNAS
                        </button>
                        <button id="medicineToggle" 
                                class="toggle-inactive text-white px-8 py-4 font-semibold text-lg transition-all duration-300 flex-1">
                            <i class="fas fa-stethoscope mr-2"></i>MEDICINA GENERAL
                        </button>
                    </div>

                    <!-- Contenido de Vacunas -->
                    <div id="vaccinesContent" class="space-y-6">
                        <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-6 border border-green-200">
                            <div class="flex items-center mb-4">
                                <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-syringe text-white"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-800">VACUNAS RECIENTES</h3>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead>
                                        <tr class="table-header">
                                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 uppercase tracking-wide">Nombre de Vacuna</th>
                                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 uppercase tracking-wide">Laboratorio</th>
                                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 uppercase tracking-wide">Lote</th>
                                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 uppercase tracking-wide">Fecha de Aplicación</th>
                                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 uppercase tracking-wide">Fecha de Expedición</th>
                                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 uppercase tracking-wide">Fecha de Caducidad</th>
                                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 uppercase tracking-wide">Veterinario</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($pet->vaccinationRecords->whereIn('record_type', ['vacuna', 'vacunacion']) as $record)
                                        <tr class="table-row border-b border-gray-100">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center">
                                                    <span class="status-badge badge-vaccine mr-2">Vacuna</span>
                                                    <div>
                                                        <div class="text-sm font-semibold text-gray-800">{{ $record->vaccine_name ?? $record->nombre_comercial ?? 'N/A' }}</div>
                                                        @if($record->nombre_tecnico)
                                                            <div class="text-xs text-gray-500">{{ $record->nombre_tecnico }}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-sm font-medium text-gray-700">{{ $record->laboratorio ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 text-sm font-medium text-gray-700">{{ $record->lote ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 text-sm font-medium text-gray-700">
                                                @if($record->fecha_aplicacion)
                                                    {{ \Carbon\Carbon::parse($record->fecha_aplicacion)->format('d/m/Y') }}
                                                @elseif($record->date)
                                                    {{ \Carbon\Carbon::parse($record->date)->format('d/m/Y') }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm font-medium text-gray-700">
                                                @if($record->fecha_expedicion)
                                                    {{ \Carbon\Carbon::parse($record->fecha_expedicion)->format('d/m/Y') }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm font-medium text-gray-700">
                                                @if($record->fecha_caducidad)
                                                    {{ \Carbon\Carbon::parse($record->fecha_caducidad)->format('d/m/Y') }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm font-medium text-gray-700">{{ $record->vet_name ?? 'N/A' }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="px-6 py-12 text-center">
                                                <div class="flex flex-col items-center">
                                                    <i class="fas fa-syringe text-4xl text-gray-300 mb-4"></i>
                                                    <p class="text-gray-500 font-medium">No hay registros de vacunas</p>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Contenido de Medicina General -->
                    <div id="medicineContent" class="space-y-6 hidden">
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border border-blue-200">
                            <div class="flex items-center mb-4">
                                <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-stethoscope text-white"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-800">MEDICINA GENERAL</h3>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead>
                                        <tr class="table-header">
                                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 uppercase tracking-wide">Tipo de Cita</th>
                                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 uppercase tracking-wide">Fecha</th>
                                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 uppercase tracking-wide">Observaciones</th>
                                            <th class="px-6 py-4 text-left text-sm font-bold text-gray-700 uppercase tracking-wide">Hora</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($pet->vaccinationRecords->whereNotIn('record_type', ['vacuna', 'vacunacion']) as $record)
                                        <tr class="table-row border-b border-gray-100">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center">
                                                    @switch($record->record_type)
                                                        @case('peluqueria')
                                                            <span class="status-badge badge-grooming mr-2">Peluquería</span>
                                                            <i class="fas fa-cut text-purple-500 mr-2"></i>
                                                            @break
                                                        @case('checkeo')
                                                            <span class="status-badge badge-medicine mr-2">Control</span>
                                                            <i class="fas fa-stethoscope text-green-500 mr-2"></i>
                                                            @break
                                                        @case('operacion')
                                                            <span class="status-badge badge-surgery mr-2">Operación</span>
                                                            <i class="fas fa-procedures text-red-500 mr-2"></i>
                                                            @break
                                                        @default
                                                            <span class="status-badge badge-medicine mr-2">{{ ucfirst($record->record_type) }}</span>
                                                    @endswitch
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-sm font-medium text-gray-700">{{ \Carbon\Carbon::parse($record->date)->format('d/m/Y') }}</td>
                                            <td class="px-6 py-4 text-sm font-medium text-gray-700">{{ $record->observations ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 text-sm font-medium text-gray-700">{{ \Carbon\Carbon::parse($record->date)->format('H:i') }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-12 text-center">
                                                <div class="flex flex-col items-center">
                                                    <i class="fas fa-stethoscope text-4xl text-gray-300 mb-4"></i>
                                                    <p class="text-gray-500 font-medium">No hay registros de medicina general</p>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gradient-to-r from-gray-800 to-gray-900 text-white py-8 mt-12">
        <div class="container mx-auto px-6 text-center">
            <div class="flex items-center justify-center mb-4">
                <div class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-paw text-lg"></i>
                </div>
                <p class="text-lg font-semibold">Perfil generado por BUKY WORLD</p>
            </div>
            <p class="text-sm opacity-75">Última actualización: {{ $pet->updated_at->format('d/m/Y H:i') }}</p>
        </div>
    </footer>

    <script>
        // Toggle functionality
        document.getElementById('vaccinesToggle').addEventListener('click', function() {
            document.getElementById('vaccinesContent').classList.remove('hidden');
            document.getElementById('medicineContent').classList.add('hidden');
            
            document.getElementById('vaccinesToggle').classList.remove('toggle-inactive');
            document.getElementById('vaccinesToggle').classList.add('toggle-active');
            document.getElementById('medicineToggle').classList.remove('toggle-active');
            document.getElementById('medicineToggle').classList.add('toggle-inactive');
        });

        document.getElementById('medicineToggle').addEventListener('click', function() {
            document.getElementById('medicineContent').classList.remove('hidden');
            document.getElementById('vaccinesContent').classList.add('hidden');
            
            document.getElementById('medicineToggle').classList.remove('toggle-inactive');
            document.getElementById('medicineToggle').classList.add('toggle-active');
            document.getElementById('vaccinesToggle').classList.remove('toggle-active');
            document.getElementById('vaccinesToggle').classList.add('toggle-inactive');
        });
    </script>
</body>
</html>