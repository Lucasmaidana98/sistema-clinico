@extends('layouts.app')

@section('title', 'Nuevo Historial Médico - Sistema Clínico')
@section('page-title', 'Nuevo Historial Médico')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Nuevo Historial Médico</h1>
                    <p class="text-gray-600">Registra la información médica del paciente</p>
                </div>
                <a href="{{ route('medical-records.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('medical-records.store') }}" class="p-6 space-y-6">
            @csrf
            
            <!-- Patient and Doctor Information -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Información General</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
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
                            <option value="{{ $doctor->id }}" {{ old('doctor_id', auth()->id()) == $doctor->id ? 'selected' : '' }}>
                                Dr. {{ $doctor->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('doctor_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="visit_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Fecha de Visita <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               id="visit_date" 
                               name="visit_date" 
                               value="{{ old('visit_date', date('Y-m-d')) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('visit_date') border-red-500 @enderror"
                               required>
                        @error('visit_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Medical Information -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Información Médica</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="symptoms" class="block text-sm font-medium text-gray-700 mb-2">
                            Síntomas
                        </label>
                        <textarea id="symptoms" 
                                  name="symptoms" 
                                  rows="4" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('symptoms') border-red-500 @enderror"
                                  placeholder="Describe los síntomas presentados por el paciente">{{ old('symptoms') }}</textarea>
                        @error('symptoms')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="diagnosis" class="block text-sm font-medium text-gray-700 mb-2">
                            Diagnóstico
                        </label>
                        <textarea id="diagnosis" 
                                  name="diagnosis" 
                                  rows="4" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('diagnosis') border-red-500 @enderror"
                                  placeholder="Ingresa el diagnóstico médico">{{ old('diagnosis') }}</textarea>
                        @error('diagnosis')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="treatment" class="block text-sm font-medium text-gray-700 mb-2">
                            Tratamiento
                        </label>
                        <textarea id="treatment" 
                                  name="treatment" 
                                  rows="4" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('treatment') border-red-500 @enderror"
                                  placeholder="Describe el tratamiento recomendado">{{ old('treatment') }}</textarea>
                        @error('treatment')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="prescription" class="block text-sm font-medium text-gray-700 mb-2">
                            Prescripción
                        </label>
                        <textarea id="prescription" 
                                  name="prescription" 
                                  rows="4" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('prescription') border-red-500 @enderror"
                                  placeholder="Medicamentos y dosificación">{{ old('prescription') }}</textarea>
                        @error('prescription')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Vital Signs -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Signos Vitales</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div>
                        <label for="weight" class="block text-sm font-medium text-gray-700 mb-2">
                            Peso (kg)
                        </label>
                        <input type="number" 
                               id="weight" 
                               name="weight" 
                               value="{{ old('weight') }}" 
                               step="0.01"
                               min="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('weight') border-red-500 @enderror"
                               placeholder="70.5">
                        @error('weight')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="height" class="block text-sm font-medium text-gray-700 mb-2">
                            Altura (cm)
                        </label>
                        <input type="number" 
                               id="height" 
                               name="height" 
                               value="{{ old('height') }}" 
                               step="0.01"
                               min="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('height') border-red-500 @enderror"
                               placeholder="170">
                        @error('height')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="temperature" class="block text-sm font-medium text-gray-700 mb-2">
                            Temperatura (°C)
                        </label>
                        <input type="number" 
                               id="temperature" 
                               name="temperature" 
                               value="{{ old('temperature') }}" 
                               step="0.1"
                               min="30"
                               max="45"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('temperature') border-red-500 @enderror"
                               placeholder="36.5">
                        @error('temperature')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="heart_rate" class="block text-sm font-medium text-gray-700 mb-2">
                            Frecuencia Cardíaca
                        </label>
                        <input type="number" 
                               id="heart_rate" 
                               name="heart_rate" 
                               value="{{ old('heart_rate') }}" 
                               min="30"
                               max="200"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('heart_rate') border-red-500 @enderror"
                               placeholder="70">
                        @error('heart_rate')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="blood_pressure_systolic" class="block text-sm font-medium text-gray-700 mb-2">
                            Presión Sistólica
                        </label>
                        <input type="number" 
                               id="blood_pressure_systolic" 
                               name="blood_pressure_systolic" 
                               value="{{ old('blood_pressure_systolic') }}" 
                               min="60"
                               max="250"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('blood_pressure_systolic') border-red-500 @enderror"
                               placeholder="120">
                        @error('blood_pressure_systolic')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="blood_pressure_diastolic" class="block text-sm font-medium text-gray-700 mb-2">
                            Presión Diastólica
                        </label>
                        <input type="number" 
                               id="blood_pressure_diastolic" 
                               name="blood_pressure_diastolic" 
                               value="{{ old('blood_pressure_diastolic') }}" 
                               min="40"
                               max="150"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('blood_pressure_diastolic') border-red-500 @enderror"
                               placeholder="80">
                        @error('blood_pressure_diastolic')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Información Adicional</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                            Notas Adicionales
                        </label>
                        <textarea id="notes" 
                                  name="notes" 
                                  rows="4" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('notes') border-red-500 @enderror"
                                  placeholder="Observaciones adicionales">{{ old('notes') }}</textarea>
                        @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="follow_up_instructions" class="block text-sm font-medium text-gray-700 mb-2">
                            Instrucciones de Seguimiento
                        </label>
                        <textarea id="follow_up_instructions" 
                                  name="follow_up_instructions" 
                                  rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('follow_up_instructions') border-red-500 @enderror"
                                  placeholder="Instrucciones para el seguimiento del paciente">{{ old('follow_up_instructions') }}</textarea>
                        @error('follow_up_instructions')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="next_visit_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Próxima Visita
                        </label>
                        <input type="date" 
                               id="next_visit_date" 
                               name="next_visit_date" 
                               value="{{ old('next_visit_date') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('next_visit_date') border-red-500 @enderror">
                        @error('next_visit_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('medical-records.index') }}" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors">
                    Guardar Historial
                </button>
            </div>
        </form>
    </div>
</div>
@endsection