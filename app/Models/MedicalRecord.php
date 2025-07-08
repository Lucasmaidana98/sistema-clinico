<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'appointment_id',
        'visit_date',
        'diagnosis',
        'symptoms',
        'treatment',
        'prescription',
        'notes',
        'weight',
        'height',
        'blood_pressure_systolic',
        'blood_pressure_diastolic',
        'temperature',
        'heart_rate',
        'follow_up_instructions',
        'next_visit_date',
    ];

    protected function casts(): array
    {
        return [
            'visit_date' => 'date',
            'next_visit_date' => 'date',
            'weight' => 'decimal:2',
            'height' => 'decimal:2',
            'temperature' => 'decimal:1',
        ];
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function getBloodPressureAttribute()
    {
        if ($this->blood_pressure_systolic && $this->blood_pressure_diastolic) {
            return $this->blood_pressure_systolic . '/' . $this->blood_pressure_diastolic;
        }
        return null;
    }

    public function getBmiAttribute()
    {
        if ($this->weight && $this->height) {
            return round($this->weight / (($this->height / 100) ** 2), 2);
        }
        return null;
    }
}
