<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMedicalRecordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('medical-records.create');
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
            'appointment_id' => 'nullable|exists:appointments,id',
            'visit_date' => 'required|date|before_or_equal:today',
            'diagnosis' => 'required|string|max:1000',
            'symptoms' => 'required|string|max:1000',
            'treatment' => 'required|string|max:1000',
            'prescription' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:2000',
            'weight' => 'nullable|numeric|min:0|max:999.99',
            'height' => 'nullable|numeric|min:0|max:999.99',
            'blood_pressure_systolic' => 'nullable|integer|min:50|max:300',
            'blood_pressure_diastolic' => 'nullable|integer|min:30|max:200',
            'temperature' => 'nullable|numeric|min:30|max:50',
            'heart_rate' => 'nullable|integer|min:30|max:300',
            'follow_up_instructions' => 'nullable|string|max:1000',
            'next_visit_date' => 'nullable|date|after:visit_date',
        ];
    }

    public function messages()
    {
        return [
            'patient_id.required' => 'El paciente es obligatorio.',
            'patient_id.exists' => 'El paciente seleccionado no existe.',
            'doctor_id.required' => 'El médico es obligatorio.',
            'doctor_id.exists' => 'El médico seleccionado no existe.',
            'appointment_id.exists' => 'La cita seleccionada no existe.',
            'visit_date.required' => 'La fecha de visita es obligatoria.',
            'visit_date.date' => 'La fecha de visita debe ser una fecha válida.',
            'visit_date.before_or_equal' => 'La fecha de visita no puede ser posterior a hoy.',
            'diagnosis.required' => 'El diagnóstico es obligatorio.',
            'diagnosis.max' => 'El diagnóstico no puede tener más de 1000 caracteres.',
            'symptoms.required' => 'Los síntomas son obligatorios.',
            'symptoms.max' => 'Los síntomas no pueden tener más de 1000 caracteres.',
            'treatment.required' => 'El tratamiento es obligatorio.',
            'treatment.max' => 'El tratamiento no puede tener más de 1000 caracteres.',
            'prescription.max' => 'La prescripción no puede tener más de 1000 caracteres.',
            'notes.max' => 'Las notas no pueden tener más de 2000 caracteres.',
            'weight.numeric' => 'El peso debe ser un número válido.',
            'weight.min' => 'El peso no puede ser negativo.',
            'weight.max' => 'El peso no puede ser mayor a 999.99 kg.',
            'height.numeric' => 'La altura debe ser un número válido.',
            'height.min' => 'La altura no puede ser negativa.',
            'height.max' => 'La altura no puede ser mayor a 999.99 cm.',
            'blood_pressure_systolic.integer' => 'La presión sistólica debe ser un número entero.',
            'blood_pressure_systolic.min' => 'La presión sistólica mínima es 50 mmHg.',
            'blood_pressure_systolic.max' => 'La presión sistólica máxima es 300 mmHg.',
            'blood_pressure_diastolic.integer' => 'La presión diastólica debe ser un número entero.',
            'blood_pressure_diastolic.min' => 'La presión diastólica mínima es 30 mmHg.',
            'blood_pressure_diastolic.max' => 'La presión diastólica máxima es 200 mmHg.',
            'temperature.numeric' => 'La temperatura debe ser un número válido.',
            'temperature.min' => 'La temperatura mínima es 30°C.',
            'temperature.max' => 'La temperatura máxima es 50°C.',
            'heart_rate.integer' => 'La frecuencia cardíaca debe ser un número entero.',
            'heart_rate.min' => 'La frecuencia cardíaca mínima es 30 bpm.',
            'heart_rate.max' => 'La frecuencia cardíaca máxima es 300 bpm.',
            'follow_up_instructions.max' => 'Las instrucciones de seguimiento no pueden tener más de 1000 caracteres.',
            'next_visit_date.date' => 'La fecha de próxima visita debe ser una fecha válida.',
            'next_visit_date.after' => 'La fecha de próxima visita debe ser posterior a la fecha de visita.',
        ];
    }
}
