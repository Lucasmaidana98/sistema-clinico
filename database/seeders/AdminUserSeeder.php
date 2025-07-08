<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin user
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@clinico.com'],
            [
                'name' => 'Super Administrador',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ]
        );
        $superAdmin->assignRole('Super Admin');

        // Create Admin user
        $admin = User::firstOrCreate(
            ['email' => 'administrador@clinico.com'],
            [
                'name' => 'Administrador General',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('Admin');

        // Create Doctor user
        $doctor = User::firstOrCreate(
            ['email' => 'doctor@clinico.com'],
            [
                'name' => 'Dr. María González',
                'password' => Hash::make('doctor123'),
                'email_verified_at' => now(),
            ]
        );
        $doctor->assignRole('Doctor');

        // Create additional doctors
        $doctor2 = User::firstOrCreate(
            ['email' => 'doctor2@clinico.com'],
            [
                'name' => 'Dr. Carlos Rodríguez',
                'password' => Hash::make('doctor123'),
                'email_verified_at' => now(),
            ]
        );
        $doctor2->assignRole('Doctor');

        $doctor3 = User::firstOrCreate(
            ['email' => 'doctor3@clinico.com'],
            [
                'name' => 'Dra. Ana López',
                'password' => Hash::make('doctor123'),
                'email_verified_at' => now(),
            ]
        );
        $doctor3->assignRole('Doctor');

        // Create Nurse user
        $nurse = User::firstOrCreate(
            ['email' => 'nurse@clinico.com'],
            [
                'name' => 'Enfermera Carmen Pérez',
                'password' => Hash::make('nurse123'),
                'email_verified_at' => now(),
            ]
        );
        $nurse->assignRole('Nurse');

        // Create additional nurses
        $nurse2 = User::firstOrCreate(
            ['email' => 'nurse2@clinico.com'],
            [
                'name' => 'Enfermera Isabel Martín',
                'password' => Hash::make('nurse123'),
                'email_verified_at' => now(),
            ]
        );
        $nurse2->assignRole('Nurse');

        // Create Receptionist user
        $receptionist = User::firstOrCreate(
            ['email' => 'recepcion@clinico.com'],
            [
                'name' => 'Recepcionista Laura Sánchez',
                'password' => Hash::make('recepcion123'),
                'email_verified_at' => now(),
            ]
        );
        $receptionist->assignRole('Receptionist');

        // Create additional receptionist
        $receptionist2 = User::firstOrCreate(
            ['email' => 'recepcion2@clinico.com'],
            [
                'name' => 'Recepcionista Patricia Jiménez',
                'password' => Hash::make('recepcion123'),
                'email_verified_at' => now(),
            ]
        );
        $receptionist2->assignRole('Receptionist');

        $this->command->info('Test users created successfully!');
        $this->command->info('Login credentials:');
        $this->command->info('Super Admin: admin@clinico.com / admin123');
        $this->command->info('Admin: administrador@clinico.com / admin123');
        $this->command->info('Doctor: doctor@clinico.com / doctor123');
        $this->command->info('Nurse: nurse@clinico.com / nurse123');
        $this->command->info('Receptionist: recepcion@clinico.com / recepcion123');
    }
}
