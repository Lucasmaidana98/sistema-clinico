<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Reporte de Pacientes
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
                                <h3 class="text-lg font-medium text-gray-900">Reporte de Pacientes</h3>
                                <p class="text-gray-600">Listado completo de pacientes con filtros avanzados</p>
                            </div>
                            <div class="mt-4 sm:mt-0 flex space-x-3">
                                <a href="{{ route('reports.index') }}" 
                                   class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    Volver a Reportes
                                </a>
                                <form method="GET" action="{{ route('reports.patients-pdf') }}" class="inline">
                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                    <input type="hidden" name="status" value="{{ request('status') }}">
                                    <input type="hidden" name="gender" value="{{ request('gender') }}">
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
                        <form method="GET" action="{{ route('reports.patients') }}" class="space-y-4">
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
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="Nombre, email o identificación...">
                                </div>

                                <!-- Status Filter -->
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                                        Estado
                                    </label>
                                    <select name="status" 
                                            id="status"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">Todos los estados</option>
                                        <option value="Activo" {{ request('status') === 'Activo' ? 'selected' : '' }}>Activo</option>
                                        <option value="Inactivo" {{ request('status') === 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                                    </select>
                                </div>

                                <!-- Gender Filter -->
                                <div>
                                    <label for="gender" class="block text-sm font-medium text-gray-700 mb-1">
                                        Género
                                    </label>
                                    <select name="gender" 
                                            id="gender"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">Todos los géneros</option>
                                        <option value="Masculino" {{ request('gender') === 'Masculino' ? 'selected' : '' }}>Masculino</option>
                                        <option value="Femenino" {{ request('gender') === 'Femenino' ? 'selected' : '' }}>Femenino</option>
                                        <option value="Otro" {{ request('gender') === 'Otro' ? 'selected' : '' }}>Otro</option>
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
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
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
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>

                                <!-- Submit Buttons -->
                                <div class="flex items-end space-x-2">
                                    <button type="submit" 
                                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                                        <i class="fas fa-search mr-2"></i>
                                        Filtrar
                                    </button>
                                    <a href="{{ route('reports.patients') }}" 
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
                                    Se encontraron {{ $patients->total() }} pacientes
                                    @if(request()->hasAny(['search', 'status', 'gender', 'date_from', 'date_to']))
                                        que coinciden con los filtros aplicados
                                    @endif
                                </p>
                            </div>
                            <div class="text-sm text-gray-500">
                                Mostrando {{ $patients->firstItem() ?? 0 }} - {{ $patients->lastItem() ?? 0 }} de {{ $patients->total() }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Patients Table -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="overflow-x-auto">
                        <table class="w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Paciente
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Identificación
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Contacto
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Edad/Género
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Estado
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Fecha Registro
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($patients as $patient)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                    <i class="fas fa-user text-blue-600"></i>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $patient->first_name }} {{ $patient->last_name }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    ID: {{ $patient->id }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $patient->identification_type }}</div>
                                        <div class="text-sm font-medium text-gray-500">{{ $patient->identification_number }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $patient->email }}</div>
                                        <div class="text-sm text-gray-500">{{ $patient->phone }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $patient->age ?? 'N/A' }} años</div>
                                        <div class="text-sm text-gray-500">{{ $patient->gender }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $patient->status === 'Activo' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $patient->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div>{{ $patient->created_at->format('d/m/Y') }}</div>
                                        <div class="text-xs text-gray-400">{{ $patient->created_at->format('H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('patients.show', $patient) }}" 
                                               class="text-blue-600 hover:text-blue-900" 
                                               title="Ver detalles">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('patients.edit', $patient) }}" 
                                               class="text-green-600 hover:text-green-900" 
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
                                            <i class="fas fa-search text-4xl text-gray-300 mb-4"></i>
                                            <h3 class="text-lg font-medium text-gray-900 mb-2">No se encontraron pacientes</h3>
                                            <p class="text-gray-500">
                                                @if(request()->hasAny(['search', 'status', 'gender', 'date_from', 'date_to']))
                                                    Intenta ajustar los filtros para encontrar más resultados.
                                                @else
                                                    No hay pacientes registrados en el sistema.
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
                @if($patients->hasPages())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        {{ $patients->appends(request()->query())->links() }}
                    </div>
                </div>
                @endif
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