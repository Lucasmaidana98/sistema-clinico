<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $appointmentTypes = [
            'Consulta general',
            'Revisión',
            'Seguimiento',
            'Consulta especializada',
            'Urgencia',
            'Chequeo médico',
            'Consulta preventiva',
            'Vacunación',
            'Análisis de resultados',
            'Consulta pediátrica',
            'Control prenatal',
            'Rehabilitación',
            'Dermatología',
            'Cardiología',
            'Neurología',
            'Traumatología',
            'Ginecología',
            'Oftalmología',
            'Otorrinolaringología',
            'Psiquiatría'
        ];
        
        $reasons = [
            'Dolor de cabeza persistente',
            'Revisión rutinaria',
            'Dolor abdominal',
            'Fiebre y malestar general',
            'Control de presión arterial',
            'Revisión de medicación',
            'Dolor de espalda',
            'Tos persistente',
            'Mareos y vértigo',
            'Dolor en articulaciones',
            'Problemas digestivos',
            'Fatiga y cansancio',
            'Dolor en el pecho',
            'Seguimiento post-operatorio',
            'Vacunación anual',
            'Examen físico general',
            'Problemas respiratorios',
            'Dolores musculares',
            'Consulta por resultados de análisis',
            'Seguimiento de tratamiento',
            'Renovación de recetas',
            'Chequeo preventivo',
            'Dolor de garganta',
            'Problemas de la piel',
            'Control de diabetes',
            'Seguimiento de hipertensión',
            'Dolor menstrual',
            'Problemas de sueño',
            'Ansiedad y estrés',
            'Revisión ocular'
        ];
        
        $statuses = ['Programada', 'Confirmada', 'Completada', 'Cancelada', 'No asistió'];
        $durations = [15, 30, 45, 60, 90]; // minutes
        
        // Generate appointment date within last 3 months or next 2 months
        $appointmentDate = $this->faker->dateTimeBetween('-3 months', '+2 months');
        
        return [
            'patient_id' => Patient::factory(),
            'doctor_id' => User::whereHas('roles', function ($query) {
                $query->where('name', 'Doctor');
            })->inRandomOrder()->first()?->id ?? User::factory()->create()->assignRole('Doctor')->id,
            'appointment_date' => $appointmentDate,
            'appointment_type' => $this->faker->randomElement($appointmentTypes),
            'status' => $this->faker->randomElement($statuses),
            'reason' => $this->faker->randomElement($reasons),
            'notes' => $this->faker->optional(0.3)->sentence(10),
            'duration_minutes' => $this->faker->randomElement($durations),
            'cost' => $this->faker->randomFloat(2, 30, 150), // Between 30 and 150 euros
        ];
    }
    
    /**
     * Define a state for upcoming appointments.
     */
    public function upcoming(): static
    {
        return $this->state(fn (array $attributes) => [
            'appointment_date' => $this->faker->dateTimeBetween('now', '+2 months'),
            'status' => $this->faker->randomElement(['Programada', 'Confirmada']),
        ]);
    }
    
    /**
     * Define a state for completed appointments.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'appointment_date' => $this->faker->dateTimeBetween('-3 months', '-1 day'),
            'status' => 'Completada',
            'notes' => $this->faker->sentence(15),
        ]);
    }
    
    /**
     * Define a state for cancelled appointments.
     */
    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Cancelada',
            'notes' => $this->faker->randomElement([
                'Cancelada por el paciente',
                'Cancelada por enfermedad del doctor',
                'Reagendada por solicitud del paciente',
                'Cancelada por emergencia médica',
                'Cancelada por motivos familiares'
            ]),
        ]);
    }
    
    /**
     * Define a state for today's appointments.
     */
    public function today(): static
    {
        return $this->state(fn (array $attributes) => [
            'appointment_date' => now()->addHours($this->faker->numberBetween(1, 8)),
            'status' => $this->faker->randomElement(['Programada', 'Confirmada']),
        ]);
    }
    
    /**
     * Define a state for emergency appointments.
     */
    public function emergency(): static
    {
        return $this->state(fn (array $attributes) => [
            'appointment_type' => 'Urgencia',
            'reason' => $this->faker->randomElement([
                'Dolor de pecho agudo',
                'Dificultad para respirar',
                'Fiebre alta',
                'Dolor abdominal severo',
                'Lesión traumática',
                'Sangrado abundante',
                'Mareos intensos',
                'Reacción alérgica'
            ]),
            'duration_minutes' => $this->faker->randomElement([30, 45, 60]),
            'cost' => $this->faker->randomFloat(2, 80, 200),
        ]);
    }
}