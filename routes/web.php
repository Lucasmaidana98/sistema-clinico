<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::resource('patients', PatientController::class);
    Route::get('patients/{patient}/appointments', [PatientController::class, 'appointments'])->name('patients.appointments');
    Route::get('patients/{patient}/medical-records', [PatientController::class, 'medicalRecords'])->name('patients.medical-records');
    
    Route::resource('appointments', AppointmentController::class);
    Route::get('appointments/{appointment}/complete', [AppointmentController::class, 'complete'])->name('appointments.complete');
    Route::patch('appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('appointments.status');
    
    Route::resource('medical-records', MedicalRecordController::class);
    Route::get('medical-records/patient/{patient}', [MedicalRecordController::class, 'byPatient'])->name('medical-records.by-patient');
    
    Route::resource('roles', RoleController::class);
    Route::post('roles/{role}/users', [RoleController::class, 'assignUsers'])->name('roles.assign-users');
    Route::delete('roles/{role}/users/{user}', [RoleController::class, 'removeUser'])->name('roles.remove-user');
    
    Route::resource('users', UserController::class);
    Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::patch('users/{user}/assign-role', [UserController::class, 'assignRole'])->name('users.assign-role');
    
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('patients', [ReportController::class, 'patients'])->name('patients');
        Route::get('appointments', [ReportController::class, 'appointments'])->name('appointments');
        Route::get('medical-records', [ReportController::class, 'medicalRecords'])->name('medical-records');
        Route::get('patients/pdf', [ReportController::class, 'patientsPdf'])->name('patients.pdf');
        Route::get('appointments/pdf', [ReportController::class, 'appointmentsPdf'])->name('appointments.pdf');
        Route::get('medical-records/pdf', [ReportController::class, 'medicalRecordsPdf'])->name('medical-records.pdf');
    });
});

require __DIR__.'/auth.php';
