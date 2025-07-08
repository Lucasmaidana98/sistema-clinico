<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Dashboard del Sistema Clínico
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    Bienvenido, {{ Auth::user()->name }}
                </p>
            </div>
            <div class="text-sm text-gray-600">
                {{ now()->format('l, F j, Y') }}
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Patients -->
                <div class="medical-card rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 mr-4">
                            <i class="fas fa-users text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-semibold text-gray-800">
                                {{ $totalPatients ?? 0 }}
                            </p>
                            <p class="text-sm text-gray-600">Pacientes Totales</p>
                        </div>
                    </div>
                </div>

                <!-- Today's Appointments -->
                <div class="medical-card rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 mr-4">
                            <i class="fas fa-calendar-check text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-semibold text-gray-800">
                                {{ $todaysAppointments ?? 0 }}
                            </p>
                            <p class="text-sm text-gray-600">Citas Hoy</p>
                        </div>
                    </div>
                </div>

                <!-- Pending Appointments -->
                <div class="medical-card rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-100 mr-4">
                            <i class="fas fa-clock text-yellow-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-semibold text-gray-800">
                                {{ $pendingAppointments ?? 0 }}
                            </p>
                            <p class="text-sm text-gray-600">Citas Pendientes</p>
                        </div>
                    </div>
                </div>

                <!-- Medical Records -->
                <div class="medical-card rounded-lg shadow-md p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-purple-100 mr-4">
                            <i class="fas fa-file-medical text-purple-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-semibold text-gray-800">
                                {{ $medicalRecords ?? 0 }}
                            </p>
                            <p class="text-sm text-gray-600">Registros Médicos</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <div class="lg:col-span-2">
                    <div class="medical-card rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">
                            <i class="fas fa-bolt text-blue-600 mr-2"></i>
                            Acciones Rápidas
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <a href="{{ route('patients.create') }}" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                                <i class="fas fa-user-plus text-blue-600 text-2xl mr-3"></i>
                                <div>
                                    <p class="font-medium text-gray-800">Nuevo Paciente</p>
                                    <p class="text-sm text-gray-600">Registrar paciente</p>
                                </div>
                            </a>
                            <a href="{{ route('appointments.create') }}" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                                <i class="fas fa-calendar-plus text-green-600 text-2xl mr-3"></i>
                                <div>
                                    <p class="font-medium text-gray-800">Nueva Cita</p>
                                    <p class="text-sm text-gray-600">Agendar cita</p>
                                </div>
                            </a>
                            <a href="{{ route('medical-records.create') }}" class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                                <i class="fas fa-file-medical-alt text-purple-600 text-2xl mr-3"></i>
                                <div>
                                    <p class="font-medium text-gray-800">Nuevo Registro</p>
                                    <p class="text-sm text-gray-600">Crear historial médico</p>
                                </div>
                            </a>
                            <a href="{{ route('patients.index') }}" class="flex items-center p-4 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors">
                                <i class="fas fa-search text-indigo-600 text-2xl mr-3"></i>
                                <div>
                                    <p class="font-medium text-gray-800">Buscar Paciente</p>
                                    <p class="text-sm text-gray-600">Buscar y ver pacientes</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="medical-card rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        <i class="fas fa-history text-gray-600 mr-2"></i>
                        Actividad Reciente
                    </h3>
                    <div class="space-y-3">
                        @if(isset($recentAppointments) && count($recentAppointments) > 0)
                            @foreach($recentAppointments as $appointment)
                            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                <div class="flex-shrink-0 w-2 h-2 bg-blue-500 rounded-full mr-3"></div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-800">
                                        Cita con {{ $appointment->patient->full_name }}
                                    </p>
                                    <p class="text-xs text-gray-600">
                                        {{ $appointment->appointment_date }}
                                    </p>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-calendar-times text-gray-400 text-2xl mb-2"></i>
                                <p class="text-sm text-gray-500">No hay actividad reciente</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Today's Schedule -->
            <div class="medical-card rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-calendar-day text-blue-600 mr-2"></i>
                    Cronograma de Hoy
                </h3>
                @if(isset($todaysSchedule) && count($todaysSchedule) > 0)
                    <div class="space-y-3">
                        @foreach($todaysSchedule as $appointment)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                                    <i class="fas fa-user-md text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $appointment->patient->full_name }}</p>
                                    <p class="text-sm text-gray-600">{{ $appointment->reason }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-800">
                                    {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('H:i') }}
                                </p>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($appointment->status === 'confirmada') bg-green-100 text-green-800
                                    @elseif($appointment->status === 'pendiente') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-calendar-times text-gray-400 text-3xl mb-3"></i>
                        <p class="text-gray-500">No hay citas programadas para hoy</p>
                        <a href="{{ route('appointments.create') }}" class="mt-3 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i>
                            Agendar Cita
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
