<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\User;
use App\Models\Appointment;
use App\Http\Requests\StoreMedicalRecordRequest;
use App\Http\Requests\UpdateMedicalRecordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class MedicalRecordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view medical records')->only(['index', 'show']);
        $this->middleware('permission:create medical records')->only(['create', 'store']);
        $this->middleware('permission:update medical records')->only(['edit', 'update']);
        $this->middleware('permission:delete medical records')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = MedicalRecord::with(['patient', 'doctor', 'appointment']);

            // Search functionality
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function ($q) use ($search) {
                    $q->where('diagnosis', 'LIKE', "%{$search}%")
                      ->orWhere('symptoms', 'LIKE', "%{$search}%")
                      ->orWhere('treatment', 'LIKE', "%{$search}%")
                      ->orWhere('prescription', 'LIKE', "%{$search}%")
                      ->orWhereHas('patient', function ($patientQuery) use ($search) {
                          $patientQuery->where('first_name', 'LIKE', "%{$search}%")
                                       ->orWhere('last_name', 'LIKE', "%{$search}%");
                      })
                      ->orWhereHas('doctor', function ($doctorQuery) use ($search) {
                          $doctorQuery->where('name', 'LIKE', "%{$search}%");
                      });
                });
            }

            // Filter by patient
            if ($request->filled('patient_id')) {
                $query->where('patient_id', $request->get('patient_id'));
            }

            // Filter by doctor
            if ($request->filled('doctor_id')) {
                $query->where('doctor_id', $request->get('doctor_id'));
            }

            // Filter by visit date range
            if ($request->filled('date_from')) {
                $query->where('visit_date', '>=', $request->get('date_from'));
            }

            if ($request->filled('date_to')) {
                $query->where('visit_date', '<=', $request->get('date_to'));
            }

            // Sort by
            $sortBy = $request->get('sort_by', 'visit_date');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            $medicalRecords = $query->paginate(15)->withQueryString();

            return response()->json([
                'success' => true,
                'data' => $medicalRecords,
                'message' => 'Registros médicos obtenidos exitosamente.'
            ]);
        } catch (Exception $e) {
            Log::error('Error al obtener registros médicos: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener la lista de registros médicos.'
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $patients = Patient::where('status', 'Activo')->select('id', 'first_name', 'last_name')->get();
            $doctors = User::role('doctor')->select('id', 'name')->get();
            $appointments = Appointment::with(['patient', 'doctor'])
                ->where('status', 'Completada')
                ->whereDoesntHave('medicalRecord')
                ->select('id', 'patient_id', 'doctor_id', 'appointment_date', 'reason')
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'patients' => $patients,
                    'doctors' => $doctors,
                    'appointments' => $appointments,
                ],
                'message' => 'Formulario de creación de registro médico disponible.'
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
    public function store(StoreMedicalRecordRequest $request)
    {
        try {
            DB::beginTransaction();

            $medicalRecord = MedicalRecord::create($request->validated());

            DB::commit();

            Log::info('Registro médico creado exitosamente', ['medical_record_id' => $medicalRecord->id, 'user_id' => auth()->id()]);

            return response()->json([
                'success' => true,
                'data' => $medicalRecord->load(['patient', 'doctor', 'appointment']),
                'message' => 'Registro médico creado exitosamente.'
            ], 201);
        } catch (Exception $e) {
            DB::rollback();
            Log::error('Error al crear registro médico: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el registro médico. Por favor, intente nuevamente.'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(MedicalRecord $medicalRecord)
    {
        try {
            $medicalRecord->load(['patient', 'doctor', 'appointment']);
            
            return response()->json([
                'success' => true,
                'data' => $medicalRecord,
                'message' => 'Registro médico obtenido exitosamente.'
            ]);
        } catch (Exception $e) {
            Log::error('Error al obtener registro médico: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener la información del registro médico.'
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MedicalRecord $medicalRecord)
    {
        try {
            $patients = Patient::where('status', 'Activo')->select('id', 'first_name', 'last_name')->get();
            $doctors = User::role('doctor')->select('id', 'name')->get();
            $appointments = Appointment::with(['patient', 'doctor'])
                ->where(function ($query) use ($medicalRecord) {
                    $query->where('status', 'Completada')
                          ->whereDoesntHave('medicalRecord')
                          ->orWhere('id', $medicalRecord->appointment_id);
                })
                ->select('id', 'patient_id', 'doctor_id', 'appointment_date', 'reason')
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'medical_record' => $medicalRecord,
                    'patients' => $patients,
                    'doctors' => $doctors,
                    'appointments' => $appointments,
                ],
                'message' => 'Formulario de edición de registro médico disponible.'
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
    public function update(UpdateMedicalRecordRequest $request, MedicalRecord $medicalRecord)
    {
        try {
            DB::beginTransaction();

            $medicalRecord->update($request->validated());

            DB::commit();

            Log::info('Registro médico actualizado exitosamente', ['medical_record_id' => $medicalRecord->id, 'user_id' => auth()->id()]);

            return response()->json([
                'success' => true,
                'data' => $medicalRecord->fresh(['patient', 'doctor', 'appointment']),
                'message' => 'Registro médico actualizado exitosamente.'
            ]);
        } catch (Exception $e) {
            DB::rollback();
            Log::error('Error al actualizar registro médico: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el registro médico. Por favor, intente nuevamente.'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MedicalRecord $medicalRecord)
    {
        try {
            DB::beginTransaction();

            $medicalRecordId = $medicalRecord->id;
            $medicalRecord->delete();

            DB::commit();

            Log::info('Registro médico eliminado exitosamente', ['medical_record_id' => $medicalRecordId, 'user_id' => auth()->id()]);

            return response()->json([
                'success' => true,
                'message' => 'Registro médico eliminado exitosamente.'
            ]);
        } catch (Exception $e) {
            DB::rollback();
            Log::error('Error al eliminar registro médico: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el registro médico. Por favor, intente nuevamente.'
            ], 500);
        }
    }

    /**
     * Get medical records statistics
     */
    public function statistics()
    {
        try {
            $stats = [
                'total_medical_records' => MedicalRecord::count(),
                'records_this_month' => MedicalRecord::whereMonth('visit_date', now()->month)->count(),
                'records_this_week' => MedicalRecord::whereBetween('visit_date', [now()->startOfWeek(), now()->endOfWeek()])->count(),
                'records_today' => MedicalRecord::whereDate('visit_date', now()->toDateString())->count(),
                'average_records_per_patient' => MedicalRecord::count() > 0 ? 
                    round(MedicalRecord::count() / MedicalRecord::distinct('patient_id')->count(), 2) : 0,
                'most_common_diagnoses' => MedicalRecord::select('diagnosis', DB::raw('count(*) as count'))
                    ->whereNotNull('diagnosis')
                    ->groupBy('diagnosis')
                    ->orderBy('count', 'desc')
                    ->limit(10)
                    ->get(),
                'records_by_doctor' => MedicalRecord::select('doctor_id', DB::raw('count(*) as count'))
                    ->with('doctor:id,name')
                    ->groupBy('doctor_id')
                    ->orderBy('count', 'desc')
                    ->limit(10)
                    ->get(),
            ];

            return response()->json([
                'success' => true,
                'data' => $stats,
                'message' => 'Estadísticas de registros médicos obtenidas exitosamente.'
            ]);
        } catch (Exception $e) {
            Log::error('Error al obtener estadísticas de registros médicos: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las estadísticas de registros médicos.'
            ], 500);
        }
    }

    /**
     * Get patient medical history
     */
    public function patientHistory($patientId)
    {
        try {
            $patient = Patient::findOrFail($patientId);
            
            $medicalRecords = MedicalRecord::with(['doctor', 'appointment'])
                ->where('patient_id', $patientId)
                ->orderBy('visit_date', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'patient' => $patient,
                    'medical_records' => $medicalRecords,
                    'total_records' => $medicalRecords->count(),
                    'first_visit' => $medicalRecords->last()?->visit_date,
                    'last_visit' => $medicalRecords->first()?->visit_date,
                ],
                'message' => 'Historial médico del paciente obtenido exitosamente.'
            ]);
        } catch (Exception $e) {
            Log::error('Error al obtener historial médico del paciente: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el historial médico del paciente.'
            ], 500);
        }
    }

    /**
     * Get doctor medical records
     */
    public function doctorRecords($doctorId)
    {
        try {
            $doctor = User::findOrFail($doctorId);
            
            $medicalRecords = MedicalRecord::with(['patient', 'appointment'])
                ->where('doctor_id', $doctorId)
                ->orderBy('visit_date', 'desc')
                ->paginate(15);

            return response()->json([
                'success' => true,
                'data' => [
                    'doctor' => $doctor,
                    'medical_records' => $medicalRecords,
                ],
                'message' => 'Registros médicos del doctor obtenidos exitosamente.'
            ]);
        } catch (Exception $e) {
            Log::error('Error al obtener registros médicos del doctor: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los registros médicos del doctor.'
            ], 500);
        }
    }
}