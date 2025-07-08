@extends('layouts.app')

@section('title', 'Nueva Cita - Sistema Clínico')
@section('page-title', 'Nueva Cita')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Nueva Cita</h1>
                    <p class="text-gray-600">Programa una nueva cita médica</p>
                </div>
                <a href="{{ route('appointments.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('appointments.store') }}" class="p-6 space-y-6">
            @csrf
            
            <!-- Appointment Information -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Información de la Cita</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="patient_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Paciente <span class="text-red-500">*</span>
                        </label>
                        <select id="patient_id" 
                                name="patient_id" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('patient_id') border-red-500 @enderror"
                                required>
                            <option value="">Selecciona un paciente</option>
                            @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" {{ old('patient_id', request('patient_id')) == $patient->id ? 'selected' : '' }}>
                                {{ $patient->full_name }} - {{ $patient->identification_number }}
                            </option>
                            @endforeach
                        </select>
                        @error('patient_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="doctor_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Doctor <span class="text-red-500">*</span>
                        </label>
                        <select id="doctor_id" 
                                name="doctor_id" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('doctor_id') border-red-500 @enderror"
                                required>
                            <option value="">Selecciona un doctor</option>
                            @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                Dr. {{ $doctor->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('doctor_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="appointment_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Fecha <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               id="appointment_date" 
                               name="appointment_date" 
                               value="{{ old('appointment_date') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('appointment_date') border-red-500 @enderror"
                               min="{{ date('Y-m-d') }}"
                               required>
                        @error('appointment_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="appointment_time" class="block text-sm font-medium text-gray-700 mb-2">
                            Hora <span class="text-red-500">*</span>
                        </label>
                        <input type="time" 
                               id="appointment_time" 
                               name="appointment_time" 
                               value="{{ old('appointment_time') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('appointment_time') border-red-500 @enderror"
                               required>
                        @error('appointment_time')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="appointment_type" class="block text-sm font-medium text-gray-700 mb-2">
                            Tipo de Cita <span class="text-red-500">*</span>
                        </label>
                        <select id="appointment_type" 
                                name="appointment_type" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('appointment_type') border-red-500 @enderror"
                                required>
                            <option value="">Selecciona el tipo</option>
                            <option value="consulta_general" {{ old('appointment_type') === 'consulta_general' ? 'selected' : '' }}>Consulta General</option>
                            <option value="control" {{ old('appointment_type') === 'control' ? 'selected' : '' }}>Control</option>
                            <option value="emergencia" {{ old('appointment_type') === 'emergencia' ? 'selected' : '' }}>Emergencia</option>
                            <option value="revision" {{ old('appointment_type') === 'revision' ? 'selected' : '' }}>Revisión</option>
                            <option value="especialista" {{ old('appointment_type') === 'especialista' ? 'selected' : '' }}>Especialista</option>
                        </select>
                        @error('appointment_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="duration_minutes" class="block text-sm font-medium text-gray-700 mb-2">
                            Duración (minutos) <span class="text-red-500">*</span>
                        </label>
                        <select id="duration_minutes" 
                                name="duration_minutes" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('duration_minutes') border-red-500 @enderror"
                                required>
                            <option value="">Selecciona la duración</option>
                            <option value="15" {{ old('duration_minutes') == '15' ? 'selected' : '' }}>15 minutos</option>
                            <option value="30" {{ old('duration_minutes', '30') == '30' ? 'selected' : '' }}>30 minutos</option>
                            <option value="45" {{ old('duration_minutes') == '45' ? 'selected' : '' }}>45 minutos</option>
                            <option value="60" {{ old('duration_minutes') == '60' ? 'selected' : '' }}>1 hora</option>
                            <option value="90" {{ old('duration_minutes') == '90' ? 'selected' : '' }}>1 hora 30 minutos</option>
                            <option value="120" {{ old('duration_minutes') == '120' ? 'selected' : '' }}>2 horas</option>
                        </select>
                        @error('duration_minutes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Estado <span class="text-red-500">*</span>
                        </label>
                        <select id="status" 
                                name="status" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('status') border-red-500 @enderror"
                                required>
                            <option value="pendiente" {{ old('status', 'pendiente') === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="confirmada" {{ old('status') === 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                        </select>
                        @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="cost" class="block text-sm font-medium text-gray-700 mb-2">
                            Costo
                        </label>
                        <input type="number" 
                               id="cost" 
                               name="cost" 
                               value="{{ old('cost') }}" 
                               step="0.01"
                               min="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('cost') border-red-500 @enderror"
                               placeholder="0.00">
                        @error('cost')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">
                            Motivo de la Cita
                        </label>
                        <textarea id="reason" 
                                  name="reason" 
                                  rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('reason') border-red-500 @enderror"
                                  placeholder="Describe el motivo de la cita">{{ old('reason') }}</textarea>
                        @error('reason')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                            Notas Adicionales
                        </label>
                        <textarea id="notes" 
                                  name="notes" 
                                  rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('notes') border-red-500 @enderror"
                                  placeholder="Notas adicionales sobre la cita">{{ old('notes') }}</textarea>
                        @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('appointments.index') }}" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                    Guardar Cita
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Combine date and time fields when form is submitted
    document.querySelector('form').addEventListener('submit', function(e) {
        const date = document.getElementById('appointment_date').value;
        const time = document.getElementById('appointment_time').value;
        
        if (date && time) {
            const datetime = date + ' ' + time;
            
            // Create hidden field for combined datetime
            const hiddenField = document.createElement('input');
            hiddenField.type = 'hidden';
            hiddenField.name = 'appointment_datetime';
            hiddenField.value = datetime;
            
            this.appendChild(hiddenField);
        }
    });
</script>
@endpush
@endsection