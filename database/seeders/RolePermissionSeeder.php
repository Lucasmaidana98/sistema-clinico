<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions for each module
        $permissions = [
            // Patient permissions
            'patients.create',
            'patients.read',
            'patients.update',
            'patients.delete',
            'patients.view',
            
            // Appointment permissions
            'appointments.create',
            'appointments.read',
            'appointments.update',
            'appointments.delete',
            'appointments.view',
            'appointments.schedule',
            'appointments.cancel',
            
            // Medical Record permissions
            'medical-records.create',
            'medical-records.read',
            'medical-records.update',
            'medical-records.delete',
            'medical-records.view',
            'medical-records.prescribe',
            
            // User management permissions
            'users.create',
            'users.read',
            'users.update',
            'users.delete',
            'users.view',
            'users.manage',
            
            // Role management permissions
            'roles.create',
            'roles.read',
            'roles.update',
            'roles.delete',
            'roles.view',
            'roles.assign',
            
            // Report permissions
            'reports.view',
            'reports.create',
            'reports.export',
            'reports.financial',
            'reports.medical',
            'reports.statistical',
            
            // System permissions
            'dashboard.view',
            'settings.view',
            'settings.update',
            'system.backup',
            'system.restore',
            'system.maintenance',
            
            // Financial permissions
            'billing.view',
            'billing.create',
            'billing.update',
            'billing.delete',
            'payments.view',
            'payments.process',
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $doctor = Role::firstOrCreate(['name' => 'Doctor']);
        $nurse = Role::firstOrCreate(['name' => 'Nurse']);
        $receptionist = Role::firstOrCreate(['name' => 'Receptionist']);

        // Assign permissions to Super Admin (all permissions)
        $superAdmin->givePermissionTo(Permission::all());

        // Assign permissions to Admin (most permissions except system-level ones)
        $adminPermissions = [
            'patients.create', 'patients.read', 'patients.update', 'patients.delete', 'patients.view',
            'appointments.create', 'appointments.read', 'appointments.update', 'appointments.delete', 
            'appointments.view', 'appointments.schedule', 'appointments.cancel',
            'medical-records.create', 'medical-records.read', 'medical-records.update', 
            'medical-records.delete', 'medical-records.view',
            'users.create', 'users.read', 'users.update', 'users.delete', 'users.view',
            'roles.read', 'roles.view', 'roles.assign',
            'reports.view', 'reports.create', 'reports.export', 'reports.financial', 
            'reports.medical', 'reports.statistical',
            'dashboard.view', 'settings.view', 'settings.update',
            'billing.view', 'billing.create', 'billing.update', 'billing.delete',
            'payments.view', 'payments.process',
        ];
        $admin->givePermissionTo($adminPermissions);

        // Assign permissions to Doctor (patient care focused)
        $doctorPermissions = [
            'patients.create', 'patients.read', 'patients.update', 'patients.view',
            'appointments.create', 'appointments.read', 'appointments.update', 
            'appointments.view', 'appointments.schedule', 'appointments.cancel',
            'medical-records.create', 'medical-records.read', 'medical-records.update', 
            'medical-records.view', 'medical-records.prescribe',
            'reports.view', 'reports.medical',
            'dashboard.view',
            'billing.view',
        ];
        $doctor->givePermissionTo($doctorPermissions);

        // Assign permissions to Nurse (patient care support)
        $nursePermissions = [
            'patients.read', 'patients.update', 'patients.view',
            'appointments.read', 'appointments.update', 'appointments.view', 'appointments.schedule',
            'medical-records.read', 'medical-records.update', 'medical-records.view',
            'dashboard.view',
            'reports.view',
        ];
        $nurse->givePermissionTo($nursePermissions);

        // Assign permissions to Receptionist (front office)
        $receptionistPermissions = [
            'patients.create', 'patients.read', 'patients.update', 'patients.view',
            'appointments.create', 'appointments.read', 'appointments.update', 
            'appointments.view', 'appointments.schedule', 'appointments.cancel',
            'dashboard.view',
            'billing.view', 'billing.create', 'billing.update',
            'payments.view', 'payments.process',
        ];
        $receptionist->givePermissionTo($receptionistPermissions);

        $this->command->info('Roles and permissions created successfully!');
    }
}
