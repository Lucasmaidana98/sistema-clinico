@extends('layouts.app')

@section('title', 'Editar Paciente - Sistema Clínico')
@section('page-title', 'Editar Paciente')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Editar Paciente</h1>
                    <p class="text-gray-600">Actualiza la información de {{ $patient->full_name }}</p>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('patients.show', $patient) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-eye mr-2"></i>
                        Ver Perfil
                    </a>
                    <a href="{{ route('patients.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Volver
                    </a>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('patients.update', $patient) }}" class="p-6 space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Personal Information -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Información Personal</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nombre <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="first_name" 
                               name="first_name" 
                               value="{{ old('first_name', $patient->first_name) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('first_name') border-red-500 @enderror"
                               placeholder="Ingresa el nombre"
                               required>
                        @error('first_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Apellido <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="last_name" 
                               name="last_name" 
                               value="{{ old('last_name', $patient->last_name) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('last_name') border-red-500 @enderror"
                               placeholder="Ingresa el apellido"
                               required>
                        @error('last_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="identification_type" class="block text-sm font-medium text-gray-700 mb-2">
                            Tipo de Identificación <span class="text-red-500">*</span>
                        </label>
                        <select id="identification_type" 
                                name="identification_type" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('identification_type') border-red-500 @enderror"
                                required>
                            <option value="">Selecciona un tipo</option>
                            <option value="cedula" {{ old('identification_type', $patient->identification_type) === 'cedula' ? 'selected' : '' }}>Cédula</option>
                            <option value="pasaporte" {{ old('identification_type', $patient->identification_type) === 'pasaporte' ? 'selected' : '' }}>Pasaporte</option>
                            <option value="tarjeta_identidad" {{ old('identification_type', $patient->identification_type) === 'tarjeta_identidad' ? 'selected' : '' }}>Tarjeta de Identidad</option>
                        </select>
                        @error('identification_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="identification_number" class="block text-sm font-medium text-gray-700 mb-2">
                            Número de Identificación <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="identification_number" 
                               name="identification_number" 
                               value="{{ old('identification_number', $patient->identification_number) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('identification_number') border-red-500 @enderror"
                               placeholder="Ingresa el número"
                               required>
                        @error('identification_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Fecha de Nacimiento <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               id="birth_date" 
                               name="birth_date" 
                               value="{{ old('birth_date', $patient->birth_date?->format('Y-m-d')) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('birth_date') border-red-500 @enderror"
                               required>
                        @error('birth_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">
                            Género <span class="text-red-500">*</span>
                        </label>
                        <select id="gender" 
                                name="gender" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('gender') border-red-500 @enderror"
                                required>
                            <option value="">Selecciona un género</option>
                            <option value="masculino" {{ old('gender', $patient->gender) === 'masculino' ? 'selected' : '' }}>Masculino</option>
                            <option value="femenino" {{ old('gender', $patient->gender) === 'femenino' ? 'selected' : '' }}>Femenino</option>
                            <option value="otro" {{ old('gender', $patient->gender) === 'otro' ? 'selected' : '' }}>Otro</option>
                        </select>
                        @error('gender')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Información de Contacto</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Correo Electrónico
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email', $patient->email) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror"
                               placeholder="correo@ejemplo.com">
                        @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                            Teléfono
                        </label>
                        <input type="tel" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone', $patient->phone) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('phone') border-red-500 @enderror"
                               placeholder="123-456-7890">
                        @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                            Dirección
                        </label>
                        <textarea id="address" 
                                  name="address" 
                                  rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('address') border-red-500 @enderror"
                                  placeholder="Ingresa la dirección completa">{{ old('address', $patient->address) }}</textarea>
                        @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Emergency Contact -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Contacto de Emergencia</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="emergency_contact_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nombre del Contacto
                        </label>
                        <input type="text" 
                               id="emergency_contact_name" 
                               name="emergency_contact_name" 
                               value="{{ old('emergency_contact_name', $patient->emergency_contact_name) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('emergency_contact_name') border-red-500 @enderror"
                               placeholder="Nombre del contacto">
                        @error('emergency_contact_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="emergency_contact_phone" class="block text-sm font-medium text-gray-700 mb-2">
                            Teléfono del Contacto
                        </label>
                        <input type="tel" 
                               id="emergency_contact_phone" 
                               name="emergency_contact_phone" 
                               value="{{ old('emergency_contact_phone', $patient->emergency_contact_phone) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('emergency_contact_phone') border-red-500 @enderror"
                               placeholder="123-456-7890">
                        @error('emergency_contact_phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Medical Information -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Información Médica</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="blood_type" class="block text-sm font-medium text-gray-700 mb-2">
                            Tipo de Sangre
                        </label>
                        <select id="blood_type" 
                                name="blood_type" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('blood_type') border-red-500 @enderror">
                            <option value="">Selecciona el tipo</option>
                            <option value="A+" {{ old('blood_type', $patient->blood_type) === 'A+' ? 'selected' : '' }}>A+</option>
                            <option value="A-" {{ old('blood_type', $patient->blood_type) === 'A-' ? 'selected' : '' }}>A-</option>
                            <option value="B+" {{ old('blood_type', $patient->blood_type) === 'B+' ? 'selected' : '' }}>B+</option>
                            <option value="B-" {{ old('blood_type', $patient->blood_type) === 'B-' ? 'selected' : '' }}>B-</option>
                            <option value="AB+" {{ old('blood_type', $patient->blood_type) === 'AB+' ? 'selected' : '' }}>AB+</option>
                            <option value="AB-" {{ old('blood_type', $patient->blood_type) === 'AB-' ? 'selected' : '' }}>AB-</option>
                            <option value="O+" {{ old('blood_type', $patient->blood_type) === 'O+' ? 'selected' : '' }}>O+</option>
                            <option value="O-" {{ old('blood_type', $patient->blood_type) === 'O-' ? 'selected' : '' }}>O-</option>
                        </select>
                        @error('blood_type')
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
                            <option value="activo" {{ old('status', $patient->status) === 'activo' ? 'selected' : '' }}>Activo</option>
                            <option value="inactivo" {{ old('status', $patient->status) === 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                        @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="allergies" class="block text-sm font-medium text-gray-700 mb-2">
                            Alergias
                        </label>
                        <textarea id="allergies" 
                                  name="allergies" 
                                  rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('allergies') border-red-500 @enderror"
                                  placeholder="Describe las alergias conocidas">{{ old('allergies', $patient->allergies) }}</textarea>
                        @error('allergies')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="medical_history" class="block text-sm font-medium text-gray-700 mb-2">
                            Historial Médico
                        </label>
                        <textarea id="medical_history" 
                                  name="medical_history" 
                                  rows="4" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('medical_history') border-red-500 @enderror"
                                  placeholder="Describe el historial médico relevante">{{ old('medical_history', $patient->medical_history) }}</textarea>
                        @error('medical_history')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('patients.show', $patient) }}" class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                    Actualizar Paciente
                </button>
            </div>
        </form>
    </div>
</div>
@endsection