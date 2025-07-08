<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePatientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('patients.update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $patientId = $this->route('patient');
        
        return [
            'first_name' => 'required|string|max:255|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'last_name' => 'required|string|max:255|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'email' => 'required|email|unique:patients,email,' . $patientId,
            'phone' => 'required|string|regex:/^[0-9\+\-\s\(\)]+$/|min:7|max:20',
            'identification_number' => 'required|string|unique:patients,identification_number,' . $patientId . '|max:20',
            'identification_type' => 'required|in:DNI,Pasaporte,Carnet Extranjería',
            'birth_date' => 'required|date|before:today|after:1900-01-01',
            'gender' => 'required|in:M,F,Otro',
            'address' => 'required|string|max:500',
            'emergency_contact_name' => 'required|string|max:255|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'emergency_contact_phone' => 'required|string|regex:/^[0-9\+\-\s\(\)]+$/|min:7|max:20',
            'blood_type' => 'nullable|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'allergies' => 'nullable|string|max:1000',
            'medical_history' => 'nullable|string|max:2000',
            'status' => 'required|in:Activo,Inactivo',
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => 'El nombre es obligatorio.',
            'first_name.regex' => 'El nombre solo puede contener letras y espacios.',
            'last_name.required' => 'El apellido es obligatorio.',
            'last_name.regex' => 'El apellido solo puede contener letras y espacios.',
            'email.required' => 'El email es obligatorio.',
            'email.email' => 'El email debe tener un formato válido.',
            'email.unique' => 'Este email ya está registrado.',
            'phone.required' => 'El teléfono es obligatorio.',
            'phone.regex' => 'El teléfono debe tener un formato válido.',
            'phone.min' => 'El teléfono debe tener al menos 7 caracteres.',
            'phone.max' => 'El teléfono no puede tener más de 20 caracteres.',
            'identification_number.required' => 'El número de identificación es obligatorio.',
            'identification_number.unique' => 'Este número de identificación ya está registrado.',
            'identification_number.max' => 'El número de identificación no puede tener más de 20 caracteres.',
            'identification_type.required' => 'El tipo de identificación es obligatorio.',
            'identification_type.in' => 'El tipo de identificación debe ser: DNI, Pasaporte o Carnet Extranjería.',
            'birth_date.required' => 'La fecha de nacimiento es obligatoria.',
            'birth_date.date' => 'La fecha de nacimiento debe ser una fecha válida.',
            'birth_date.before' => 'La fecha de nacimiento debe ser anterior a hoy.',
            'birth_date.after' => 'La fecha de nacimiento no puede ser anterior a 1900.',
            'gender.required' => 'El género es obligatorio.',
            'gender.in' => 'El género debe ser: M, F u Otro.',
            'address.required' => 'La dirección es obligatoria.',
            'address.max' => 'La dirección no puede tener más de 500 caracteres.',
            'emergency_contact_name.required' => 'El nombre del contacto de emergencia es obligatorio.',
            'emergency_contact_name.regex' => 'El nombre del contacto de emergencia solo puede contener letras y espacios.',
            'emergency_contact_phone.required' => 'El teléfono del contacto de emergencia es obligatorio.',
            'emergency_contact_phone.regex' => 'El teléfono del contacto de emergencia debe tener un formato válido.',
            'emergency_contact_phone.min' => 'El teléfono del contacto de emergencia debe tener al menos 7 caracteres.',
            'emergency_contact_phone.max' => 'El teléfono del contacto de emergencia no puede tener más de 20 caracteres.',
            'blood_type.in' => 'El tipo de sangre debe ser: A+, A-, B+, B-, AB+, AB-, O+ u O-.',
            'allergies.max' => 'Las alergias no pueden tener más de 1000 caracteres.',
            'medical_history.max' => 'El historial médico no puede tener más de 2000 caracteres.',
            'status.required' => 'El estado es obligatorio.',
            'status.in' => 'El estado debe ser: Activo o Inactivo.',
        ];
    }
}
