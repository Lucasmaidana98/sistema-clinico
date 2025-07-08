<?php

namespace Database\Factories;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Patient>
 */
class PatientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gender = $this->faker->randomElement(['M', 'F']);
        
        // Spanish names arrays
        $maleNames = [
            'Carlos', 'José', 'Antonio', 'Francisco', 'Manuel', 'David', 'Juan', 'Miguel',
            'Alejandro', 'Daniel', 'Rafael', 'Pedro', 'Ángel', 'Sergio', 'Alberto',
            'Fernando', 'Diego', 'Raúl', 'Andrés', 'Adrián', 'Gonzalo', 'Pablo',
            'Ignacio', 'Rubén', 'Javier', 'Jesús', 'Víctor', 'Eduardo', 'Roberto',
            'Enrique', 'Guillermo', 'Ramón', 'Emilio', 'Arturo', 'Óscar', 'Rodrigo'
        ];
        
        $femaleNames = [
            'María', 'Carmen', 'Ana', 'Isabel', 'Pilar', 'Dolores', 'Teresa', 'Rosa',
            'Cristina', 'Elena', 'Patricia', 'Laura', 'Marta', 'Lucía', 'Beatriz',
            'Raquel', 'Mónica', 'Silvia', 'Alicia', 'Rocío', 'Nuria', 'Esther',
            'Inmaculada', 'Amparo', 'Concepción', 'Esperanza', 'Remedios', 'Josefa',
            'Francisca', 'Antonia', 'Manuela', 'Encarnación', 'Rosario', 'Soledad',
            'Asunción', 'Montserrat', 'Milagros', 'Nieves', 'Consuelo', 'Purificación'
        ];
        
        $lastNames = [
            'García', 'Rodríguez', 'González', 'Fernández', 'López', 'Martínez', 'Sánchez',
            'Pérez', 'Gómez', 'Martín', 'Jiménez', 'Ruiz', 'Hernández', 'Díaz',
            'Moreno', 'Muñoz', 'Álvarez', 'Romero', 'Alonso', 'Gutiérrez', 'Navarro',
            'Torres', 'Domínguez', 'Vázquez', 'Ramos', 'Gil', 'Ramírez', 'Serrano',
            'Blanco', 'Suárez', 'Molina', 'Morales', 'Ortega', 'Delgado', 'Castro',
            'Ortiz', 'Rubio', 'Marín', 'Sanz', 'Iglesias', 'Medina', 'Garrido',
            'Cortés', 'Castillo', 'Santos', 'Lozano', 'Guerrero', 'Cano', 'Prieto',
            'Méndez', 'Cruz', 'Herrera', 'Peña', 'Flores', 'Cabrera', 'Campos',
            'Vega', 'Fuentes', 'Carrasco', 'Reyes', 'Contreras', 'Pascual', 'Herrero'
        ];
        
        $firstName = $gender === 'M' ? $this->faker->randomElement($maleNames) : $this->faker->randomElement($femaleNames);
        $lastName = $this->faker->randomElement($lastNames) . ' ' . $this->faker->randomElement($lastNames);
        
        // Spanish medical conditions
        $commonConditions = [
            'Hipertensión arterial', 'Diabetes mellitus tipo 2', 'Hiperlipidemia',
            'Artritis', 'Asma bronquial', 'Gastritis crónica', 'Migraña',
            'Osteoporosis', 'Fibromialgia', 'Síndrome del intestino irritable',
            'Hipotiroidismo', 'Reflujo gastroesofágico', 'Ansiedad generalizada',
            'Depresión leve', 'Cervicalgia', 'Lumbalgia crónica', 'Rinitis alérgica',
            'Dermatitis atópica', 'Síndrome del túnel carpiano', 'Varices',
            'Colesterol alto', 'Triglicéridos elevados', 'Úlcera péptica',
            'Bronquitis crónica', 'Sinusitis recurrente', 'Cefalea tensional',
            'Artritis reumatoide', 'Psoriasis', 'Eczema', 'Urticaria crónica'
        ];
        
        $allergies = [
            'Penicilina', 'Aspirina', 'Ibuprofeno', 'Ácaros del polvo', 'Polen',
            'Frutos secos', 'Mariscos', 'Látex', 'Huevos', 'Leche',
            'Gluten', 'Soja', 'Pescado', 'Antibióticos', 'Contraste yodado',
            'Níquel', 'Perfumes', 'Detergentes', 'Pelo de animales', 'Picaduras de insectos'
        ];
        
        $bloodTypes = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        
        return [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $this->faker->unique()->email(),
            'phone' => $this->faker->phoneNumber(),
            'identification_number' => $this->faker->unique()->numerify('########') . strtoupper($this->faker->randomLetter()),
            'identification_type' => $this->faker->randomElement(['DNI', 'Pasaporte', 'Carnet Extranjería']),
            'birth_date' => $this->faker->dateTimeBetween('-85 years', '-18 years')->format('Y-m-d'),
            'gender' => $gender,
            'address' => $this->faker->address(),
            'emergency_contact_name' => $this->faker->name(),
            'emergency_contact_phone' => $this->faker->phoneNumber(),
            'blood_type' => $this->faker->randomElement($bloodTypes),
            'allergies' => $this->faker->optional(0.3)->randomElement($allergies),
            'medical_history' => $this->faker->optional(0.4)->randomElement($commonConditions),
            'status' => $this->faker->randomElement(['Activo', 'Inactivo']),
        ];
    }
    
    /**
     * Define a state for elderly patients.
     */
    public function elderly(): static
    {
        return $this->state(fn (array $attributes) => [
            'birth_date' => $this->faker->dateTimeBetween('-85 years', '-65 years')->format('Y-m-d'),
            'medical_history' => $this->faker->randomElement([
                'Hipertensión arterial, Diabetes mellitus tipo 2',
                'Artritis, Osteoporosis',
                'Cardiopatía isquémica, Hiperlipidemia',
                'EPOC, Bronquitis crónica',
                'Demencia senil, Parkinson',
                'Cataratas, Glaucoma'
            ]),
        ]);
    }
    
    /**
     * Define a state for pediatric patients.
     */
    public function pediatric(): static
    {
        return $this->state(fn (array $attributes) => [
            'birth_date' => $this->faker->dateTimeBetween('-17 years', '-1 years')->format('Y-m-d'),
            'medical_history' => $this->faker->optional(0.2)->randomElement([
                'Asma bronquial infantil',
                'Dermatitis atópica',
                'Rinitis alérgica',
                'Reflujo gastroesofágico',
                'Anemia ferropénica'
            ]),
            'allergies' => $this->faker->optional(0.4)->randomElement([
                'Leche', 'Huevos', 'Frutos secos', 'Ácaros del polvo', 'Polen'
            ]),
        ]);
    }
    
    /**
     * Define a state for active patients.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Activo',
        ]);
    }
    
    /**
     * Define a state for inactive patients.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Inactivo',
        ]);
    }
}