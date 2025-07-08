<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class PatientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:patients.view')->only(['index', 'show']);
        $this->middleware('permission:patients.create')->only(['create', 'store']);
        $this->middleware('permission:patients.update')->only(['edit', 'update']);
        $this->middleware('permission:patients.delete')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Patient::query();

            // Search functionality
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'LIKE', "%{$search}%")
                      ->orWhere('last_name', 'LIKE', "%{$search}%")
                      ->orWhere('email', 'LIKE', "%{$search}%")
                      ->orWhere('identification_number', 'LIKE', "%{$search}%")
                      ->orWhere('phone', 'LIKE', "%{$search}%");
                });
            }

            // Filter by status
            if ($request->filled('status')) {
                $query->where('status', $request->get('status'));
            }

            // Filter by gender
            if ($request->filled('gender')) {
                $query->where('gender', $request->get('gender'));
            }

            // Sort by
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            $patients = $query->paginate(15)->withQueryString();

            return response()->json([
                'success' => true,
                'data' => $patients,
                'message' => 'Pacientes obtenidos exitosamente.'
            ]);
        } catch (Exception $e) {
            Log::error('Error al obtener pacientes: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener la lista de pacientes.'
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            return response()->json([
                'success' => true,
                'message' => 'Formulario de creación de paciente disponible.'
            ]);
        } catch (Exception $e) {
            Log::error('Error al mostrar formulario de creación: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al mostrar el formulario de creación.'
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePatientRequest $request)
    {
        try {
            DB::beginTransaction();

            $patient = Patient::create($request->validated());

            DB::commit();

            Log::info('Paciente creado exitosamente', ['patient_id' => $patient->id, 'user_id' => auth()->id()]);

            return response()->json([
                'success' => true,
                'data' => $patient,
                'message' => 'Paciente creado exitosamente.'
            ], 201);
        } catch (Exception $e) {
            DB::rollback();
            Log::error('Error al crear paciente: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el paciente. Por favor, intente nuevamente.'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient)
    {
        try {
            $patient->load(['appointments.doctor', 'medicalRecords.doctor']);
            
            return response()->json([
                'success' => true,
                'data' => $patient,
                'message' => 'Paciente obtenido exitosamente.'
            ]);
        } catch (Exception $e) {
            Log::error('Error al obtener paciente: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener la información del paciente.'
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Patient $patient)
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $patient,
                'message' => 'Formulario de edición de paciente disponible.'
            ]);
        } catch (Exception $e) {
            Log::error('Error al mostrar formulario de edición: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al mostrar el formulario de edición.'
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePatientRequest $request, Patient $patient)
    {
        try {
            DB::beginTransaction();

            $patient->update($request->validated());

            DB::commit();

            Log::info('Paciente actualizado exitosamente', ['patient_id' => $patient->id, 'user_id' => auth()->id()]);

            return response()->json([
                'success' => true,
                'data' => $patient->fresh(),
                'message' => 'Paciente actualizado exitosamente.'
            ]);
        } catch (Exception $e) {
            DB::rollback();
            Log::error('Error al actualizar paciente: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el paciente. Por favor, intente nuevamente.'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient)
    {
        try {
            // Check if patient has appointments or medical records
            if ($patient->appointments()->exists() || $patient->medicalRecords()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede eliminar el paciente porque tiene citas o registros médicos asociados.'
                ], 422);
            }

            DB::beginTransaction();

            $patientId = $patient->id;
            $patient->delete();

            DB::commit();

            Log::info('Paciente eliminado exitosamente', ['patient_id' => $patientId, 'user_id' => auth()->id()]);

            return response()->json([
                'success' => true,
                'message' => 'Paciente eliminado exitosamente.'
            ]);
        } catch (Exception $e) {
            DB::rollback();
            Log::error('Error al eliminar paciente: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el paciente. Por favor, intente nuevamente.'
            ], 500);
        }
    }

    /**
     * Get patient statistics
     */
    public function statistics()
    {
        try {
            $stats = [
                'total_patients' => Patient::count(),
                'active_patients' => Patient::where('status', 'Activo')->count(),
                'inactive_patients' => Patient::where('status', 'Inactivo')->count(),
                'patients_by_gender' => [
                    'male' => Patient::where('gender', 'M')->count(),
                    'female' => Patient::where('gender', 'F')->count(),
                    'other' => Patient::where('gender', 'Otro')->count(),
                ],
                'recent_patients' => Patient::where('created_at', '>=', now()->subDays(30))->count(),
            ];

            return response()->json([
                'success' => true,
                'data' => $stats,
                'message' => 'Estadísticas de pacientes obtenidas exitosamente.'
            ]);
        } catch (Exception $e) {
            Log::error('Error al obtener estadísticas de pacientes: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las estadísticas de pacientes.'
            ], 500);
        }
    }
}