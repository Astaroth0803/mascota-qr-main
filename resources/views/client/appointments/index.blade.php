@extends('layouts.dashboard')

@section('title', 'Mis Citas')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Mis Citas</h1>
        <a href="{{ route('dashboard.cliente.calendario.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
            Nueva Cita
        </a>
    </div>

    @if($appointments->count() > 0)
        <div class="grid gap-4">
            @foreach($appointments as $appointment)
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 
                    @if($appointment->status === 'completed') border-green-500 
                    @elseif($appointment->status === 'pending') border-yellow-500 
                    @elseif($appointment->status === 'cancelled') border-red-500 
                    @else border-blue-500 @endif">
                    
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900">
                                {{ $appointment->pet->nombre }}
                            </h3>
                            <p class="text-gray-600">
                                <strong>Veterinario:</strong> {{ $appointment->veterinarian->name }}
                            </p>
                            <p class="text-gray-600">
                                <strong>Fecha:</strong> {{ $appointment->scheduled_datetime->format('d/m/Y H:i') }}
                            </p>
                            <p class="text-gray-600">
                                <strong>Tipo:</strong> {{ $appointment->type }}
                            </p>
                            @if($appointment->notes)
                                <p class="text-gray-600 mt-2">
                                    <strong>Notas:</strong> {{ $appointment->notes }}
                                </p>
                            @endif
                        </div>
                        
                        <div class="flex space-x-2">
                            <a href="{{ route('dashboard.cliente.calendario.show', $appointment->id) }}" 
                               class="bg-blue-100 hover:bg-blue-200 text-blue-800 px-3 py-1 rounded text-sm">
                                Ver
                            </a>
                            @if($appointment->status === 'pending')
                                <a href="{{ route('dashboard.cliente.calendario.edit', $appointment->id) }}" 
                                   class="bg-yellow-100 hover:bg-yellow-200 text-yellow-800 px-3 py-1 rounded text-sm">
                                    Editar
                                </a>
                            @endif
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($appointment->status === 'completed') bg-green-100 text-green-800
                            @elseif($appointment->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($appointment->status === 'cancelled') bg-red-100 text-red-800
                            @else bg-blue-100 text-blue-800 @endif">
                            {{ ucfirst($appointment->status) }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="mt-6">
            {{ $appointments->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <div class="text-gray-400 mb-4">
                <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No tienes citas programadas</h3>
            <p class="text-gray-500 mb-4">Comienza creando tu primera cita.</p>
            <a href="{{ route('dashboard.cliente.calendario.create') }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                Crear Primera Cita
            </a>
        </div>
    @endif
</div>
@endsection
