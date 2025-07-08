<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Panel de Reportes
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="space-y-6">
                
                <!-- Header Section -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Sistema de Reportes Médicos</h3>
                                <p class="text-gray-600">Genera y visualiza reportes completos del sistema clínico</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <i class="fas fa-chart-bar text-2xl text-blue-600"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Total Patients -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                        <i class="fas fa-users text-white text-sm"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-500">Total Pacientes</div>
                                    <div class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_patients']) }}</div>
                                </div>
                            </div>
                            <div class="mt-4">
                                <div class="text-sm text-gray-600">
                                    <span class="text-green-600 font-medium">{{ number_format($stats['active_patients']) }}</span> activos
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Appointments -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                        <i class="fas fa-calendar-check text-white text-sm"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-500">Total Citas</div>
                                    <div class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_appointments']) }}</div>
                                </div>
                            </div>
                            <div class="mt-4">
                                <div class="text-sm text-gray-600">
                                    <span class="text-blue-600 font-medium">{{ number_format($stats['appointments_today']) }}</span> hoy
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Medical Records -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                                        <i class="fas fa-file-medical text-white text-sm"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-500">Historiales Médicos</div>
                                    <div class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_medical_records']) }}</div>
                                </div>
                            </div>
                            <div class="mt-4">
                                <div class="text-sm text-gray-600">
                                    <span class="text-purple-600 font-medium">{{ number_format($stats['appointments_this_month']) }}</span> citas este mes
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Access Reports -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-6">Reportes Disponibles</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            
                            <!-- Patients Report -->
                            <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-users text-blue-600 text-xl"></i>
                                    </div>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('reports.patients') }}" 
                                           class="inline-flex items-center px-3 py-1 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                                            <i class="fas fa-eye mr-1"></i>
                                            Ver
                                        </a>
                                        <a href="{{ route('reports.patients-pdf') }}" 
                                           class="inline-flex items-center px-3 py-1 text-sm bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                                            <i class="fas fa-file-pdf mr-1"></i>
                                            PDF
                                        </a>
                                    </div>
                                </div>
                                <h4 class="text-lg font-medium text-gray-900 mb-2">Reporte de Pacientes</h4>
                                <p class="text-gray-600 text-sm">Listado completo de pacientes con filtros por estado, género y fecha de registro.</p>
                                <div class="mt-4 text-sm text-gray-500">
                                    <span class="font-medium">{{ number_format($stats['total_patients']) }}</span> pacientes registrados
                                </div>
                            </div>

                            <!-- Appointments Report -->
                            <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-calendar-check text-green-600 text-xl"></i>
                                    </div>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('reports.appointments') }}" 
                                           class="inline-flex items-center px-3 py-1 text-sm bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                                            <i class="fas fa-eye mr-1"></i>
                                            Ver
                                        </a>
                                        <a href="{{ route('reports.appointments-pdf') }}" 
                                           class="inline-flex items-center px-3 py-1 text-sm bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                                            <i class="fas fa-file-pdf mr-1"></i>
                                            PDF
                                        </a>
                                    </div>
                                </div>
                                <h4 class="text-lg font-medium text-gray-900 mb-2">Reporte de Citas</h4>
                                <p class="text-gray-600 text-sm">Seguimiento de citas médicas con filtros por estado, doctor y rango de fechas.</p>
                                <div class="mt-4 text-sm text-gray-500">
                                    <span class="font-medium">{{ number_format($stats['total_appointments']) }}</span> citas programadas
                                </div>
                            </div>

                            <!-- Medical Records Report -->
                            <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-file-medical text-purple-600 text-xl"></i>
                                    </div>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('reports.medical-records') }}" 
                                           class="inline-flex items-center px-3 py-1 text-sm bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors">
                                            <i class="fas fa-eye mr-1"></i>
                                            Ver
                                        </a>
                                        <a href="{{ route('reports.medical-records-pdf') }}" 
                                           class="inline-flex items-center px-3 py-1 text-sm bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                                            <i class="fas fa-file-pdf mr-1"></i>
                                            PDF
                                        </a>
                                    </div>
                                </div>
                                <h4 class="text-lg font-medium text-gray-900 mb-2">Historial Médico</h4>
                                <p class="text-gray-600 text-sm">Reportes de historiales médicos con búsqueda por diagnóstico y doctor.</p>
                                <div class="mt-4 text-sm text-gray-500">
                                    <span class="font-medium">{{ number_format($stats['total_medical_records']) }}</span> registros médicos
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-6">Estadísticas Rápidas</h3>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-blue-600">{{ number_format($stats['appointments_today']) }}</div>
                                <div class="text-sm text-gray-600">Citas Hoy</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-green-600">{{ number_format($stats['appointments_this_month']) }}</div>
                                <div class="text-sm text-gray-600">Citas Este Mes</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-purple-600">{{ number_format($stats['active_patients']) }}</div>
                                <div class="text-sm text-gray-600">Pacientes Activos</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-orange-600">{{ number_format($stats['total_medical_records']) }}</div>
                                <div class="text-sm text-gray-600">Registros Médicos</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>