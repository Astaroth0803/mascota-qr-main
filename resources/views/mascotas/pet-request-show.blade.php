@extends('layouts.dashboard')

@section('title', 'Detalles de Solicitud de Mascota')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Detalles de Solicitud</h1>
                        <p class="text-gray-600 mt-1">Información completa de la solicitud de mascota</p>
                    </div>
                    <a href="{{ route('pet-requests.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Volver a Solicitudes
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <!-- Header de la solicitud -->
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <h2 class="text-xl font-semibold text-gray-900">{{ $petRequest->nombre }}</h2>
                        <span class="ml-3 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if($petRequest->isPending()) bg-yellow-100 text-yellow-800
                            @elseif($petRequest->isApproved()) bg-green-100 text-green-800
                            @else bg-red-100 text-red-800 @endif">
                            @if($petRequest->isPending())
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                                Pendiente
                            @elseif($petRequest->isApproved())
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Aprobada
                            @else
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                                Rechazada
                            @endif
                        </span>
                    </div>
                    <div class="text-sm text-gray-500">
                        <span class="font-medium">Solicitado:</span> {{ $petRequest->created_at->format('d/m/Y H:i') }}
                    </div>
                </div>
            </div>

            <!-- Información de la mascota -->
            <div class="px-6 py-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Información de la Mascota</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                        <p class="text-sm text-gray-900">{{ $petRequest->nombre }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Especie</label>
                        <p class="text-sm text-gray-900">{{ $petRequest->especie }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Raza</label>
                        <p class="text-sm text-gray-900">{{ $petRequest->raza }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Edad</label>
                        <p class="text-sm text-gray-900">{{ $petRequest->edad_anios }} años, {{ $petRequest->edad_meses }} meses</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sexo</label>
                        <p class="text-sm text-gray-900">{{ $petRequest->sexo }}</p>
                    </div>
                    @if($petRequest->payment_id)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">ID de Pago</label>
                            <p class="text-sm text-gray-900 font-mono">{{ $petRequest->payment_id }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Información del usuario -->
            <div class="px-6 py-6 bg-gray-50 border-t border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Información del Solicitante</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                        <p class="text-sm text-gray-900">{{ $petRequest->user->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <p class="text-sm text-gray-900">{{ $petRequest->user->email }}</p>
                    </div>
                </div>
            </div>

            <!-- Información de revisión -->
            @if($petRequest->reviewed_at)
                <div class="px-6 py-6 border-t border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Información de Revisión</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Revisado por</label>
                            <p class="text-sm text-gray-900">{{ $petRequest->reviewer->name ?? 'Sistema' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de revisión</label>
                            <p class="text-sm text-gray-900">{{ $petRequest->reviewed_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    @if($petRequest->isRejected() && $petRequest->rejection_reason)
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Motivo del rechazo</label>
                            <div class="mt-2 p-4 bg-red-50 border border-red-200 rounded-lg">
                                <p class="text-sm text-red-800">{{ $petRequest->rejection_reason }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Acciones para administradores -->
            @if(Auth::user()->hasAnyRole(['administrador', 'super_admin']) && $petRequest->isPending())
                <div class="px-6 py-6 bg-blue-50 border-t border-blue-200">
                    <h3 class="text-lg font-medium text-blue-900 mb-4">Acciones de Administración</h3>
                    <div class="flex space-x-4">
                        <form action="{{ route('pet-requests.approve', $petRequest) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Aprobar Solicitud
                            </button>
                        </form>
                        
                        <button onclick="openRejectModal()" 
                                class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Rechazar Solicitud
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal para Rechazar Solicitud -->
@if(Auth::user()->hasAnyRole(['administrador', 'super_admin']) && $petRequest->isPending())
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Rechazar Solicitud</h3>
            <form action="{{ route('pet-requests.reject', $petRequest) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">
                        Motivo del rechazo <span class="text-red-500">*</span>
                    </label>
                    <textarea name="rejection_reason" id="rejection_reason" rows="4" 
                              class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                              placeholder="Explica por qué se rechaza esta solicitud..."
                              required></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeRejectModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-400">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700">
                        Rechazar Solicitud
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openRejectModal() {
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('rejection_reason').value = '';
}
</script>
@endif
@endsection
