<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAppointmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('appointments.update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date',
            'appointment_type' => 'required|in:Consulta General,Consulta Especializada,Control,Emergencia,Seguimiento',
            'status' => 'required|in:Programada,Confirmada,En Progreso,Completada,Cancelada,No Presentado',
            'reason' => 'required|string|max:500',
            'notes' => 'nullable|string|max:1000',
            'duration_minutes' => 'required|integer|min:15|max:480',
            'cost' => 'required|numeric|min:0|max:999999.99',
        ];
    }

    public function messages()
    {
        return [
            'patient_id.required' => 'El paciente es obligatorio.',
            'patient_id.exists' => 'El paciente seleccionado no existe.',
            'doctor_id.required' => 'El médico es obligatorio.',
            'doctor_id.exists' => 'El médico seleccionado no existe.',
            'appointment_date.required' => 'La fecha de la cita es obligatoria.',
            'appointment_date.date' => 'La fecha de la cita debe ser una fecha válida.',
            'appointment_type.required' => 'El tipo de cita es obligatorio.',
            'appointment_type.in' => 'El tipo de cita debe ser: Consulta General, Consulta Especializada, Control, Emergencia o Seguimiento.',
            'status.required' => 'El estado es obligatorio.',
            'status.in' => 'El estado debe ser: Programada, Confirmada, En Progreso, Completada, Cancelada o No Presentado.',
            'reason.required' => 'El motivo de la cita es obligatorio.',
            'reason.max' => 'El motivo de la cita no puede tener más de 500 caracteres.',
            'notes.max' => 'Las notas no pueden tener más de 1000 caracteres.',
            'duration_minutes.required' => 'La duración es obligatoria.',
            'duration_minutes.integer' => 'La duración debe ser un número entero.',
            'duration_minutes.min' => 'La duración mínima es de 15 minutos.',
            'duration_minutes.max' => 'La duración máxima es de 480 minutos (8 horas).',
            'cost.required' => 'El costo es obligatorio.',
            'cost.numeric' => 'El costo debe ser un número válido.',
            'cost.min' => 'El costo no puede ser negativo.',
            'cost.max' => 'El costo no puede ser mayor a 999,999.99.',
        ];
    }
}
