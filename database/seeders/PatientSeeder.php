<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Patient;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 30 patients with varied demographics
        
        // Create 15 adult patients (18-64 years)
        Patient::factory()->count(15)->create();
        
        // Create 8 elderly patients (65+ years)
        Patient::factory()->elderly()->count(8)->create();
        
        // Create 7 pediatric patients (0-17 years)
        Patient::factory()->pediatric()->count(7)->create();
        
        // Create a few specific test patients with known data
        Patient::factory()->create([
            'first_name' => 'Juan Carlos',
            'last_name' => 'Pérez González',
            'email' => 'juan.perez@email.com',
            'phone' => '+34 600 123 456',
            'identification_number' => '12345678A',
            'identification_type' => 'DNI',
            'birth_date' => '1985-03-15',
            'gender' => 'M',
            'address' => 'Calle Mayor 123, 28001 Madrid',
            'emergency_contact_name' => 'María Pérez',
            'emergency_contact_phone' => '+34 600 654 321',
            'blood_type' => 'A+',
            'allergies' => 'Penicilina',
            'medical_history' => 'Hipertensión arterial controlada',
            'status' => 'Activo',
        ]);
        
        Patient::factory()->create([
            'first_name' => 'Ana María',
            'last_name' => 'López Martínez',
            'email' => 'ana.lopez@email.com',
            'phone' => '+34 600 789 012',
            'identification_number' => '87654321B',
            'identification_type' => 'DNI',
            'birth_date' => '1992-07-22',
            'gender' => 'F',
            'address' => 'Avenida de la Constitución 45, 41001 Sevilla',
            'emergency_contact_name' => 'Carlos López',
            'emergency_contact_phone' => '+34 600 210 987',
            'blood_type' => 'O-',
            'allergies' => 'Frutos secos, Ácaros del polvo',
            'medical_history' => 'Asma bronquial, Rinitis alérgica',
            'status' => 'Activo',
        ]);
        
        Patient::factory()->create([
            'first_name' => 'Carmen',
            'last_name' => 'Rodríguez Fernández',
            'email' => 'carmen.rodriguez@email.com',
            'phone' => '+34 600 345 678',
            'identification_number' => '11223344C',
            'identification_type' => 'DNI',
            'birth_date' => '1955-12-08',
            'gender' => 'F',
            'address' => 'Plaza del Carmen 7, 08002 Barcelona',
            'emergency_contact_name' => 'Isabel Rodríguez',
            'emergency_contact_phone' => '+34 600 876 543',
            'blood_type' => 'B+',
            'allergies' => null,
            'medical_history' => 'Diabetes mellitus tipo 2, Osteoporosis',
            'status' => 'Activo',
        ]);
        
        $this->command->info('Created ' . Patient::count() . ' patients successfully!');
    }
}
