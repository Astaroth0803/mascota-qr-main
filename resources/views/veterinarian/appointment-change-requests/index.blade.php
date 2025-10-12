@extends('layouts.standard')

@section('title', 'Solicitudes de Cambio de Citas')

@php
    $title = 'Solicitudes de Cambio de Citas';
    $subtitle = 'Gestiona las solicitudes de cambio de fecha de tus clientes';
@endphp

@section('main-content')
<div>
    <div class="flex items-center justify-end mb-6">
        <div class="flex items-center space-x-3">
            <a href="{{ route('dashboard.veterinario') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-200 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-200 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span class="hidden sm:inline">Volver al Dashboard</span>
                <span class="sm:hidden">Volver</span>
            </a>
        </div>
    </div>

            <!-- Solicitudes Pendientes -->
            @if($pendingRequests->count() > 0)
                <div class="mb-8">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 bg-yellow-100 rounded-2xl flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900">Solicitudes Pendientes</h2>
                                <p class="text-sm text-gray-600">{{ $pendingRequests->count() }} solicitudes requieren tu atención</p>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            @foreach($pendingRequests as $request)
                                <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-5 hover:shadow-md transition-all duration-200">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center space-x-3 mb-3">
                                                <h3 class="text-base font-semibold text-gray-900 truncate">
                                                    {{ $request->appointment->pet->nombre }} - {{ $request->client->name }}
                                                </h3>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    Pendiente
                                                </span>
                                            </div>
                                            
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                                                <div class="bg-white rounded-xl p-3 border border-gray-100">
                                                    <div class="flex items-center mb-1">
                                                        <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                        </svg>
                                                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Fecha Actual</span>
                                                    </div>
                                                    <p class="text-sm font-semibold text-gray-900">{{ $request->appointment->date->format('d/m/Y') }}</p>
                                                    <p class="text-xs text-gray-600">{{ $request->appointment->time ? $request->appointment->time->format('H:i') : 'Sin hora' }}</p>
                                                </div>
                                                
                                                <div class="bg-blue-50 rounded-xl p-3 border border-blue-200">
                                                    <div class="flex items-center mb-1">
                                                        <svg class="w-4 h-4 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                        </svg>
                                                        <span class="text-xs font-medium text-blue-600 uppercase tracking-wide">Nueva Fecha</span>
                                                    </div>
                                                    <p class="text-sm font-semibold text-blue-900">{{ $request->requested_date->format('d/m/Y') }}</p>
                                                    <p class="text-xs text-blue-700">{{ $request->requested_time ? \Carbon\Carbon::parse($request->requested_time)->format('H:i') : 'Sin hora' }}</p>
                                                </div>
                                            </div>
                                            
                                            @if($request->reason)
                                                <div class="bg-gray-50 rounded-xl p-3 mb-3">
                                                    <div class="flex items-center mb-1">
                                                        <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                                        </svg>
                                                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Razón del Cambio</span>
                                                    </div>
                                                    <p class="text-sm text-gray-700">{{ $request->reason }}</p>
                                                </div>
                                            @endif
                                            
                                            <div class="flex items-center text-xs text-gray-500">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Solicitud enviada: {{ $request->created_at->format('d/m/Y H:i') }}
                                            </div>
                                        </div>
                                        
                                        <div class="flex-shrink-0">
                                            <a href="{{ route('dashboard.veterinario.appointment-change-requests.show', $request->id) }}" 
                                               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                Ver Detalles
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">¡Todo al día!</h3>
                        <p class="text-sm text-gray-600 mb-6">No tienes solicitudes de cambio de citas pendientes. Todas han sido procesadas.</p>
                        <a href="{{ route('dashboard.veterinario') }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                            </svg>
                            Ir al Dashboard
                        </a>
                    </div>
                </div>
            @endif

            <!-- Historial de Solicitudes -->
            @if($allRequests->count() > 0)
                <div>
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 bg-gray-100 rounded-2xl flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900">Historial de Solicitudes</h2>
                                <p class="text-sm text-gray-600">Todas las solicitudes de cambio de citas</p>
                            </div>
                        </div>
                        
                        <div class="space-y-3">
                            @foreach($allRequests as $request)
                                <div class="bg-gray-50 border border-gray-200 rounded-2xl p-4 hover:shadow-sm transition-all duration-200">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center space-x-3 mb-2">
                                                <h3 class="text-sm font-semibold text-gray-900 truncate">
                                                    {{ $request->appointment->pet->nombre }} - {{ $request->client->name }}
                                                </h3>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    @if($request->status === 'approved') bg-green-100 text-green-800
                                                    @elseif($request->status === 'rejected') bg-red-100 text-red-800
                                                    @else bg-yellow-100 text-yellow-800 @endif">
                                                    @if($request->status === 'approved') Aprobada
                                                    @elseif($request->status === 'rejected') Rechazada
                                                    @else Pendiente @endif
                                                </span>
                                            </div>
                                            
                                            <div class="flex items-center text-sm text-gray-600 mb-2">
                                                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <span class="font-medium">{{ $request->appointment->date->format('d/m/Y') }} {{ $request->appointment->time ? $request->appointment->time->format('H:i') : '' }}</span>
                                                <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                                </svg>
                                                <span class="font-medium">{{ $request->requested_date->format('d/m/Y') }} {{ $request->requested_time ? \Carbon\Carbon::parse($request->requested_time)->format('H:i') : '' }}</span>
                                            </div>
                                            
                                            @if($request->vet_notes)
                                                <div class="bg-white rounded-xl p-3 mb-2">
                                                    <div class="flex items-center mb-1">
                                                        <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                                        </svg>
                                                        <span class="text-xs font-medium text-gray-500 uppercase tracking-wide">Notas del Veterinario</span>
                                                    </div>
                                                    <p class="text-sm text-gray-700">{{ $request->vet_notes }}</p>
                                                </div>
                                            @endif
                                            
                                            <div class="flex items-center text-xs text-gray-500">
                                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                {{ $request->created_at->format('d/m/Y H:i') }}
                                                @if($request->vet_response_at)
                                                    <span class="mx-2">•</span>
                                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    Respondido: {{ $request->vet_response_at->format('d/m/Y H:i') }}
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="flex-shrink-0">
                                            <a href="{{ route('dashboard.veterinario.appointment-change-requests.show', $request->id) }}" 
                                               class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-200 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-50 transition-colors">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                Ver
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Paginación -->
                        <div class="mt-6">
                            {{ $allRequests->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
