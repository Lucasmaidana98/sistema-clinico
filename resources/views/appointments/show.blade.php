@extends('layouts.app')

@section('title', 'Detalles de la Cita - Sistema Clínico')
@section('page-title', 'Detalles de la Cita')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center">
                    <i class="fas fa-calendar-alt text-white text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Cita Médica</h1>
                    <p class="text-gray-600">{{ $appointment->appointment_date->format('d/m/Y H:i') }}</p>
                    <div class="flex items-center space-x-2 mt-1">
                        <span class="px-2 py-1 text-xs font-medium rounded-full 
                            @if($appointment->status === 'confirmada') bg-green-100 text-green-800
                            @elseif($appointment->status === 'pendiente') bg-yellow-100 text-yellow-800
                            @elseif($appointment->status === 'cancelada') bg-red-100 text-red-800
                            @elseif($appointment->status === 'completada') bg-blue-100 text-blue-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($appointment->status) }}
                        </span>
                        <span class="text-sm text-gray-500">{{ $appointment->duration_minutes }} minutos</span>
                    </div>
                </div>
            </div>
            <div class="flex space-x-2 mt-4 md:mt-0">
                @if($appointment->status === 'confirmada')
                <a href="{{ route('medical-records.create', ['appointment_id' => $appointment->id]) }}" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                    <i class="fas fa-file-medical mr-2"></i>
                    Crear Historial
                </a>
                @endif
                <a href="{{ route('appointments.edit', $appointment) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-edit mr-2"></i>
                    Editar
                </a>
                <a href="{{ route('appointments.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver
                </a>
            </div>
        </div>
    </div>

    <!-- Appointment Information -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Patient Information -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Información del Paciente</h3>
            <div class="space-y-3">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-blue-600"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">{{ $appointment->patient->full_name }}</p>
                        <p class="text-sm text-gray-500">{{ $appointment->patient->identification_type }}: {{ $appointment->patient->identification_number }}</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Edad</label>
                        <p class="text-gray-900">{{ $appointment->patient->age }} años</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Género</label>
                        <p class="text-gray-900 capitalize">{{ $appointment->patient->gender ?? 'No especificado' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Teléfono</label>
                        <p class="text-gray-900">{{ $appointment->patient->phone ?? 'No especificado' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Email</label>
                        <p class="text-gray-900">{{ $appointment->patient->email ?? 'No especificado' }}</p>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('patients.show', $appointment->patient) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                        Ver perfil completo del paciente
                    </a>
                </div>
            </div>
        </div>

        <!-- Doctor and Appointment Details -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Detalles de la Cita</h3>
            <div class="space-y-3">
                <div>
                    <label class="text-sm font-medium text-gray-500">Doctor</label>
                    <p class="text-gray-900">Dr. {{ $appointment->doctor->name }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Fecha y Hora</label>
                    <p class="text-gray-900">{{ $appointment->appointment_date->format('l, d \de F \de Y \a \l\a\s H:i') }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Tipo de Cita</label>
                    <p class="text-gray-900 capitalize">{{ str_replace('_', ' ', $appointment->appointment_type) }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Duración</label>
                    <p class="text-gray-900">{{ $appointment->duration_minutes }} minutos</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Estado</label>
                    <p class="text-gray-900">
                        <span class="px-2 py-1 text-xs font-medium rounded-full 
                            @if($appointment->status === 'confirmada') bg-green-100 text-green-800
                            @elseif($appointment->status === 'pendiente') bg-yellow-100 text-yellow-800
                            @elseif($appointment->status === 'cancelada') bg-red-100 text-red-800
                            @elseif($appointment->status === 'completada') bg-blue-100 text-blue-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($appointment->status) }}
                        </span>
                    </p>
                </div>
                @if($appointment->cost)
                <div>
                    <label class="text-sm font-medium text-gray-500">Costo</label>
                    <p class="text-gray-900">${{ number_format($appointment->cost, 2) }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Reason and Notes -->
    @if($appointment->reason || $appointment->notes)
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Motivo y Notas</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @if($appointment->reason)
            <div>
                <label class="text-sm font-medium text-gray-500">Motivo de la Cita</label>
                <p class="text-gray-900 mt-1">{{ $appointment->reason }}</p>
            </div>
            @endif
            @if($appointment->notes)
            <div>
                <label class="text-sm font-medium text-gray-500">Notas Adicionales</label>
                <p class="text-gray-900 mt-1">{{ $appointment->notes }}</p>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Medical Record -->
    @if($appointment->medicalRecord)
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-900">Historial Médico de la Cita</h3>
            <a href="{{ route('medical-records.show', $appointment->medicalRecord) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                Ver historial completo
            </a>
        </div>
        <div class="bg-gray-50 rounded-lg p-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-500">Diagnóstico</label>
                    <p class="text-gray-900">{{ $appointment->medicalRecord->diagnosis ?: 'Sin diagnóstico especificado' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Síntomas</label>
                    <p class="text-gray-900">{{ $appointment->medicalRecord->symptoms ?: 'Sin síntomas especificados' }}</p>
                </div>
            </div>
            @if($appointment->medicalRecord->treatment)
            <div class="mt-4">
                <label class="text-sm font-medium text-gray-500">Tratamiento</label>
                <p class="text-gray-900">{{ $appointment->medicalRecord->treatment }}</p>
            </div>
            @endif
        </div>
    </div>
    @else
    @if($appointment->status === 'confirmada' || $appointment->status === 'completada')
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="text-center py-8 text-gray-500">
            <i class="fas fa-file-medical-alt text-4xl mb-4"></i>
            <p class="mb-4">No se ha creado un historial médico para esta cita</p>
            <a href="{{ route('medical-records.create', ['appointment_id' => $appointment->id]) }}" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                <i class="fas fa-plus mr-2"></i>
                Crear historial médico
            </a>
        </div>
    </div>
    @endif
    @endif
</div>
@endsection