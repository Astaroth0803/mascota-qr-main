<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Depuración de Registros Médicos
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Información de Depuración</h3>

                <div class="bg-gray-100 p-4 rounded mb-6">
                    <h4 class="font-medium mb-2">Información de la Mascota</h4>
                    <p><strong>ID:</strong> {{ $pet->id }}</p>
                    <p><strong>Nombre:</strong> {{ $pet->nombre }}</p>
                    <p><strong>Usuario ID:</strong> {{ $pet->user_id }}</p>
                </div>

                <div class="bg-gray-100 p-4 rounded mb-6">
                    <h4 class="font-medium mb-2">Consulta SQL Directa</h4>
                    <p><strong>Total de registros:</strong> {{ $sqlRecords->count() }}</p>

                    @if($sqlRecords->count() > 0)
                        <div class="mt-4 overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-300">
                                <thead>
                                    <tr>
                                        <th class="py-2 px-4 border-b">ID</th>
                                        <th class="py-2 px-4 border-b">Tipo</th>
                                        <th class="py-2 px-4 border-b">Fecha</th>
                                        <th class="py-2 px-4 border-b">Detalles</th>
                                        <th class="py-2 px-4 border-b">Creado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sqlRecords as $record)
                                        <tr>
                                            <td class="py-2 px-4 border-b">{{ $record->id }}</td>
                                            <td class="py-2 px-4 border-b">{{ $record->record_type }}</td>
                                            <td class="py-2 px-4 border-b">{{ $record->date }}</td>
                                            <td class="py-2 px-4 border-b">
                                                @if($record->record_type == 'vacuna')
                                                    Vacuna: {{ $record->vaccine_name }}
                                                @elseif(in_array($record->record_type, ['checkeo', 'operacion']))
                                                    Diag: {{ Str::limit($record->diagnosis, 30) }}
                                                @else
                                                    Obs: {{ Str::limit($record->observations, 30) }}
                                                @endif
                                            </td>
                                            <td class="py-2 px-4 border-b">{{ $record->created_at }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-red-600 mt-2">No se encontraron registros con consulta SQL directa.</p>
                    @endif
                </div>

                <div class="bg-gray-100 p-4 rounded mb-6">
                    <h4 class="font-medium mb-2">Registros de Relación Eloquent</h4>
                    <p><strong>Total de registros:</strong> {{ $pet->vaccinationRecords->count() }}</p>

                    @if($pet->vaccinationRecords->count() > 0)
                        <div class="mt-4 overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-300">
                                <thead>
                                    <tr>
                                        <th class="py-2 px-4 border-b">ID</th>
                                        <th class="py-2 px-4 border-b">Tipo</th>
                                        <th class="py-2 px-4 border-b">Fecha</th>
                                        <th class="py-2 px-4 border-b">Detalles</th>
                                        <th class="py-2 px-4 border-b">Creado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pet->vaccinationRecords as $record)
                                        <tr>
                                            <td class="py-2 px-4 border-b">{{ $record->id }}</td>
                                            <td class="py-2 px-4 border-b">{{ $record->record_type }}</td>
                                            <td class="py-2 px-4 border-b">{{ $record->date }}</td>
                                            <td class="py-2 px-4 border-b">
                                                @if($record->record_type == 'vacuna')
                                                    Vacuna: {{ $record->vaccine_name }}
                                                @elseif(in_array($record->record_type, ['checkeo', 'operacion']))
                                                    Diag: {{ Str::limit($record->diagnosis, 30) }}
                                                @else
                                                    Obs: {{ Str::limit($record->observations, 30) }}
                                                @endif
                                            </td>
                                            <td class="py-2 px-4 border-b">{{ $record->created_at }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-red-600 mt-2">No se encontraron registros en la relación Eloquent.</p>
                    @endif
                </div>

                <div class="mt-6 flex space-x-4">
                    <a href="{{ route('dashboard.cliente.mascotas.vaccination-history', $pet) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                        Volver al Historial
                    </a>

                    <a href="{{ route('dashboard.cliente.mascotas.debug-refresh', $pet) }}" class="inline-flex items-center px-4 py-2 bg-orange-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-600 active:bg-orange-700 focus:outline-none focus:border-orange-700 focus:ring focus:ring-orange-300 disabled:opacity-25 transition">
                        Refrescar Caché
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
