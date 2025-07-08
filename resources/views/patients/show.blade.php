@extends('layouts.app')

@section('title', 'Perfil del Paciente - Sistema Clínico')
@section('page-title', 'Perfil del Paciente')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center">
                    <i class="fas fa-user text-white text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $patient->full_name }}</h1>
                    <p class="text-gray-600">{{ $patient->identification_type }}: {{ $patient->identification_number }}</p>
                    <div class="flex items-center space-x-2 mt-1">
                        <span class="px-2 py-1 text-xs font-medium rounded-full 
                            @if($patient->status === 'activo') bg-green-100 text-green-800 
                            @else bg-red-100 text-red-800 
                            @endif">
                            {{ ucfirst($patient->status) }}
                        </span>
                        <span class="text-sm text-gray-500">{{ $patient->age }} años</span>
                    </div>
                </div>
            </div>
            <div class="flex space-x-2 mt-4 md:mt-0">
                <a href="{{ route('appointments.create', ['patient_id' => $patient->id]) }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-calendar-plus mr-2"></i>
                    Nueva Cita
                </a>
                <a href="{{ route('patients.edit', $patient) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-edit mr-2"></i>
                    Editar
                </a>
                <a href="{{ route('patients.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver
                </a>
            </div>
        </div>
    </div>

    <!-- Patient Information -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Personal Information -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Información Personal</h3>
            <div class="space-y-3">
                <div>
                    <label class="text-sm font-medium text-gray-500">Nombre Completo</label>
                    <p class="text-gray-900">{{ $patient->full_name }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Identificación</label>
                    <p class="text-gray-900">{{ $patient->identification_type }}: {{ $patient->identification_number }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Fecha de Nacimiento</label>
                    <p class="text-gray-900">{{ $patient->birth_date?->format('d/m/Y') ?? 'No especificada' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Edad</label>
                    <p class="text-gray-900">{{ $patient->age }} años</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Género</label>
                    <p class="text-gray-900 capitalize">{{ $patient->gender ?? 'No especificado' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Estado</label>
                    <p class="text-gray-900">
                        <span class="px-2 py-1 text-xs font-medium rounded-full 
                            @if($patient->status === 'activo') bg-green-100 text-green-800 
                            @else bg-red-100 text-red-800 
                            @endif">
                            {{ ucfirst($patient->status) }}
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Información de Contacto</h3>
            <div class="space-y-3">
                <div>
                    <label class="text-sm font-medium text-gray-500">Correo Electrónico</label>
                    <p class="text-gray-900">{{ $patient->email ?: 'No especificado' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Teléfono</label>
                    <p class="text-gray-900">{{ $patient->phone ?: 'No especificado' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Dirección</label>
                    <p class="text-gray-900">{{ $patient->address ?: 'No especificada' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Contacto de Emergencia</label>
                    <p class="text-gray-900">{{ $patient->emergency_contact_name ?: 'No especificado' }}</p>
                    @if($patient->emergency_contact_phone)
                    <p class="text-sm text-gray-600">{{ $patient->emergency_contact_phone }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Medical Information -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Información Médica</h3>
            <div class="space-y-3">
                <div>
                    <label class="text-sm font-medium text-gray-500">Tipo de Sangre</label>
                    <p class="text-gray-900">{{ $patient->blood_type ?: 'No especificado' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Alergias</label>
                    <p class="text-gray-900">{{ $patient->allergies ?: 'Ninguna conocida' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Historial Médico</label>
                    <p class="text-gray-900 text-sm">{{ $patient->medical_history ?: 'Sin historial previo' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Appointments -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">Citas Recientes</h3>
                <a href="{{ route('appointments.create', ['patient_id' => $patient->id]) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                    Nueva Cita
                </a>
            </div>
        </div>
        <div class="p-6">
            @if($patient->appointments->count() > 0)
                <div class="space-y-4">
                    @foreach($patient->appointments->take(5) as $appointment)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-calendar-alt text-blue-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $appointment->appointment_date->format('d/m/Y H:i') }}</p>
                                <p class="text-sm text-gray-600">{{ $appointment->appointment_type }}</p>
                                <p class="text-sm text-gray-600">Dr. {{ $appointment->doctor->name }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="px-2 py-1 text-xs font-medium rounded-full 
                                @if($appointment->status === 'confirmada') bg-green-100 text-green-800
                                @elseif($appointment->status === 'pendiente') bg-yellow-100 text-yellow-800
                                @elseif($appointment->status === 'cancelada') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($appointment->status) }}
                            </span>
                            <div class="mt-1">
                                <a href="{{ route('appointments.show', $appointment) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                    Ver detalles
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @if($patient->appointments->count() > 5)
                <div class="mt-4 text-center">
                    <a href="{{ route('appointments.index', ['patient_id' => $patient->id]) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                        Ver todas las citas ({{ $patient->appointments->count() }})
                    </a>
                </div>
                @endif
            @else
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-calendar-times text-4xl mb-4"></i>
                    <p>No hay citas registradas para este paciente</p>
                    <a href="{{ route('appointments.create', ['patient_id' => $patient->id]) }}" class="inline-flex items-center mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        Programar primera cita
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Medical Records -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">Historiales Médicos</h3>
                <a href="{{ route('medical-records.create', ['patient_id' => $patient->id]) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                    Nuevo Historial
                </a>
            </div>
        </div>
        <div class="p-6">
            @if($patient->medicalRecords->count() > 0)
                <div class="space-y-4">
                    @foreach($patient->medicalRecords->take(5) as $record)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-file-medical text-purple-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $record->visit_date->format('d/m/Y') }}</p>
                                <p class="text-sm text-gray-600">{{ $record->diagnosis ?: 'Sin diagnóstico específico' }}</p>
                                <p class="text-sm text-gray-600">Dr. {{ $record->doctor->name }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <a href="{{ route('medical-records.show', $record) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                Ver historial
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
                @if($patient->medicalRecords->count() > 5)
                <div class="mt-4 text-center">
                    <a href="{{ route('medical-records.index', ['patient_id' => $patient->id]) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                        Ver todos los historiales ({{ $patient->medicalRecords->count() }})
                    </a>
                </div>
                @endif
            @else
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-file-medical-alt text-4xl mb-4"></i>
                    <p>No hay historiales médicos para este paciente</p>
                    <a href="{{ route('medical-records.create', ['patient_id' => $patient->id]) }}" class="inline-flex items-center mt-4 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        Crear primer historial
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection