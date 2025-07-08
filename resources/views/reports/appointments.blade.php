<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Reporte de Citas Médicas
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="space-y-6">
                
                <!-- Header -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Reporte de Citas Médicas</h3>
                                <p class="text-gray-600">Seguimiento completo de citas programadas con filtros avanzados</p>
                            </div>
                            <div class="mt-4 sm:mt-0 flex space-x-3">
                                <a href="{{ route('reports.index') }}" 
                                   class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    Volver a Reportes
                                </a>
                                <form method="GET" action="{{ route('reports.appointments-pdf') }}" class="inline">
                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                    <input type="hidden" name="status" value="{{ request('status') }}">
                                    <input type="hidden" name="doctor_id" value="{{ request('doctor_id') }}">
                                    <input type="hidden" name="date_from" value="{{ request('date_from') }}">
                                    <input type="hidden" name="date_to" value="{{ request('date_to') }}">
                                    <button type="submit" 
                                            class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                                        <i class="fas fa-file-pdf mr-2"></i>
                                        Exportar PDF
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Filtros de Búsqueda</h4>
                        <form method="GET" action="{{ route('reports.appointments') }}" class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <!-- Search -->
                                <div>
                                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">
                                        Buscar Paciente
                                    </label>
                                    <input type="text" 
                                           name="search" 
                                           id="search"
                                           value="{{ request('search') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                           placeholder="Nombre del paciente...">
                                </div>

                                <!-- Status Filter -->
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                                        Estado de la Cita
                                    </label>
                                    <select name="status" 
                                            id="status"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                        <option value="">Todos los estados</option>
                                        <option value="Programada" {{ request('status') === 'Programada' ? 'selected' : '' }}>Programada</option>
                                        <option value="Confirmada" {{ request('status') === 'Confirmada' ? 'selected' : '' }}>Confirmada</option>
                                        <option value="Completada" {{ request('status') === 'Completada' ? 'selected' : '' }}>Completada</option>
                                        <option value="Cancelada" {{ request('status') === 'Cancelada' ? 'selected' : '' }}>Cancelada</option>
                                        <option value="No Asistió" {{ request('status') === 'No Asistió' ? 'selected' : '' }}>No Asistió</option>
                                    </select>
                                </div>

                                <!-- Doctor Filter -->
                                <div>
                                    <label for="doctor_id" class="block text-sm font-medium text-gray-700 mb-1">
                                        Doctor
                                    </label>
                                    <select name="doctor_id" 
                                            id="doctor_id"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                        <option value="">Todos los doctores</option>
                                        @foreach($doctors as $doctor)
                                        <option value="{{ $doctor->id }}" {{ request('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                            Dr. {{ $doctor->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Date From -->
                                <div>
                                    <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">
                                        Fecha Desde
                                    </label>
                                    <input type="date" 
                                           name="date_from" 
                                           id="date_from"
                                           value="{{ request('date_from') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                </div>

                                <!-- Date To -->
                                <div>
                                    <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">
                                        Fecha Hasta
                                    </label>
                                    <input type="date" 
                                           name="date_to" 
                                           id="date_to"
                                           value="{{ request('date_to') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                </div>

                                <!-- Submit Buttons -->
                                <div class="flex items-end space-x-2">
                                    <button type="submit" 
                                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                                        <i class="fas fa-search mr-2"></i>
                                        Filtrar
                                    </button>
                                    <a href="{{ route('reports.appointments') }}" 
                                       class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                                        <i class="fas fa-times mr-2"></i>
                                        Limpiar
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Results Summary -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="text-lg font-medium text-gray-900">
                                    Resultados de la Búsqueda
                                </h4>
                                <p class="text-gray-600">
                                    Se encontraron {{ $appointments->total() }} citas médicas
                                    @if(request()->hasAny(['search', 'status', 'doctor_id', 'date_from', 'date_to']))
                                        que coinciden con los filtros aplicados
                                    @endif
                                </p>
                            </div>
                            <div class="text-sm text-gray-500">
                                Mostrando {{ $appointments->firstItem() ?? 0 }} - {{ $appointments->lastItem() ?? 0 }} de {{ $appointments->total() }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Appointments Table -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="overflow-x-auto">
                        <table class="w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Paciente
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Doctor
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Fecha y Hora
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Motivo
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Estado
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Notas
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($appointments as $appointment)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                                                    <i class="fas fa-user text-green-600"></i>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $appointment->patient->identification_number }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            Dr. {{ $appointment->doctor->name }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $appointment->doctor->email }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('H:i') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 max-w-xs truncate">
                                            {{ $appointment->reason ?? 'Sin especificar' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @switch($appointment->status)
                                                @case('Programada')
                                                    bg-yellow-100 text-yellow-800
                                                    @break
                                                @case('Confirmada')
                                                    bg-blue-100 text-blue-800
                                                    @break
                                                @case('Completada')
                                                    bg-green-100 text-green-800
                                                    @break
                                                @case('Cancelada')
                                                    bg-red-100 text-red-800
                                                    @break
                                                @case('No Asistió')
                                                    bg-gray-100 text-gray-800
                                                    @break
                                                @default
                                                    bg-gray-100 text-gray-800
                                            @endswitch">
                                            {{ $appointment->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 max-w-xs truncate">
                                            {{ $appointment->notes ?? 'Sin notas' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('appointments.show', $appointment) }}" 
                                               class="text-green-600 hover:text-green-900" 
                                               title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('appointments.edit', $appointment) }}" 
                                               class="text-blue-600 hover:text-blue-900" 
                                               title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center py-8">
                                            <i class="fas fa-calendar-times text-4xl text-gray-300 mb-4"></i>
                                            <h3 class="text-lg font-medium text-gray-900 mb-2">No se encontraron citas</h3>
                                            <p class="text-gray-500">
                                                @if(request()->hasAny(['search', 'status', 'doctor_id', 'date_from', 'date_to']))
                                                    Intenta ajustar los filtros para encontrar más resultados.
                                                @else
                                                    No hay citas programadas en el sistema.
                                                @endif
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                @if($appointments->hasPages())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        {{ $appointments->appends(request()->query())->links() }}
                    </div>
                </div>
                @endif

                <!-- Quick Statistics -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Estadísticas Rápidas</h4>
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                            @php
                                $statusCounts = $appointments->groupBy('status')->map->count();
                            @endphp
                            <div class="text-center">
                                <div class="text-2xl font-bold text-yellow-600">{{ $statusCounts['Programada'] ?? 0 }}</div>
                                <div class="text-sm text-gray-600">Programadas</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-blue-600">{{ $statusCounts['Confirmada'] ?? 0 }}</div>
                                <div class="text-sm text-gray-600">Confirmadas</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-green-600">{{ $statusCounts['Completada'] ?? 0 }}</div>
                                <div class="text-sm text-gray-600">Completadas</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-red-600">{{ $statusCounts['Cancelada'] ?? 0 }}</div>
                                <div class="text-sm text-gray-600">Canceladas</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-gray-600">{{ $statusCounts['No Asistió'] ?? 0 }}</div>
                                <div class="text-sm text-gray-600">No Asistieron</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Auto-submit form when date fields change
        document.addEventListener('DOMContentLoaded', function() {
            const dateInputs = document.querySelectorAll('input[type="date"]');
            dateInputs.forEach(function(input) {
                input.addEventListener('change', function() {
                    // Optional: Auto-submit on date change
                    // document.querySelector('form').submit();
                });
            });
        });
    </script>
    @endpush
</x-app-layout>