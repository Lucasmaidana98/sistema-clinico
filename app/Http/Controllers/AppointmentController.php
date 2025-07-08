<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class AppointmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view appointments')->only(['index', 'show']);
        $this->middleware('permission:create appointments')->only(['create', 'store']);
        $this->middleware('permission:update appointments')->only(['edit', 'update']);
        $this->middleware('permission:delete appointments')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Appointment::with(['patient', 'doctor']);

            // Search functionality
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function ($q) use ($search) {
                    $q->where('reason', 'LIKE', "%{$search}%")
                      ->orWhere('appointment_type', 'LIKE', "%{$search}%")
                      ->orWhere('status', 'LIKE', "%{$search}%")
                      ->orWhereHas('patient', function ($patientQuery) use ($search) {
                          $patientQuery->where('first_name', 'LIKE', "%{$search}%")
                                       ->orWhere('last_name', 'LIKE', "%{$search}%");
                      })
                      ->orWhereHas('doctor', function ($doctorQuery) use ($search) {
                          $doctorQuery->where('name', 'LIKE', "%{$search}%");
                      });
                });
            }

            // Filter by status
            if ($request->filled('status')) {
                $query->where('status', $request->get('status'));
            }

            // Filter by appointment type
            if ($request->filled('appointment_type')) {
                $query->where('appointment_type', $request->get('appointment_type'));
            }

            // Filter by patient
            if ($request->filled('patient_id')) {
                $query->where('patient_id', $request->get('patient_id'));
            }

            // Filter by doctor
            if ($request->filled('doctor_id')) {
                $query->where('doctor_id', $request->get('doctor_id'));
            }

            // Filter by date range
            if ($request->filled('date_from')) {
                $query->where('appointment_date', '>=', $request->get('date_from'));
            }

            if ($request->filled('date_to')) {
                $query->where('appointment_date', '<=', $request->get('date_to'));
            }

            // Sort by
            $sortBy = $request->get('sort_by', 'appointment_date');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            $appointments = $query->paginate(15)->withQueryString();

            return response()->json([
                'success' => true,
                'data' => $appointments,
                'message' => 'Citas obtenidas exitosamente.'
            ]);
        } catch (Exception $e) {
            Log::error('Error al obtener citas: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener la lista de citas.'
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

            return response()->json([
                'success' => true,
                'data' => [
                    'patients' => $patients,
                    'doctors' => $doctors,
                    'appointment_types' => ['Consulta General', 'Consulta Especializada', 'Control', 'Emergencia', 'Seguimiento'],
                    'statuses' => ['Programada', 'Confirmada', 'En Progreso', 'Completada', 'Cancelada', 'No Presentado']
                ],
                'message' => 'Formulario de creación de cita disponible.'
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
    public function store(StoreAppointmentRequest $request)
    {
        try {
            DB::beginTransaction();

            // Check for appointment conflicts
            $conflictingAppointment = Appointment::where('doctor_id', $request->doctor_id)
                ->where('appointment_date', $request->appointment_date)
                ->where('status', '!=', 'Cancelada')
                ->exists();

            if ($conflictingAppointment) {
                return response()->json([
                    'success' => false,
                    'message' => 'El doctor ya tiene una cita programada en esa fecha y hora.'
                ], 422);
            }

            $appointment = Appointment::create($request->validated());

            DB::commit();

            Log::info('Cita creada exitosamente', ['appointment_id' => $appointment->id, 'user_id' => auth()->id()]);

            return response()->json([
                'success' => true,
                'data' => $appointment->load(['patient', 'doctor']),
                'message' => 'Cita creada exitosamente.'
            ], 201);
        } catch (Exception $e) {
            DB::rollback();
            Log::error('Error al crear cita: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la cita. Por favor, intente nuevamente.'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        try {
            $appointment->load(['patient', 'doctor', 'medicalRecord']);
            
            return response()->json([
                'success' => true,
                'data' => $appointment,
                'message' => 'Cita obtenida exitosamente.'
            ]);
        } catch (Exception $e) {
            Log::error('Error al obtener cita: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener la información de la cita.'
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Appointment $appointment)
    {
        try {
            $patients = Patient::where('status', 'Activo')->select('id', 'first_name', 'last_name')->get();
            $doctors = User::role('doctor')->select('id', 'name')->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'appointment' => $appointment,
                    'patients' => $patients,
                    'doctors' => $doctors,
                    'appointment_types' => ['Consulta General', 'Consulta Especializada', 'Control', 'Emergencia', 'Seguimiento'],
                    'statuses' => ['Programada', 'Confirmada', 'En Progreso', 'Completada', 'Cancelada', 'No Presentado']
                ],
                'message' => 'Formulario de edición de cita disponible.'
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
    public function update(UpdateAppointmentRequest $request, Appointment $appointment)
    {
        try {
            DB::beginTransaction();

            // Check for appointment conflicts (excluding current appointment)
            $conflictingAppointment = Appointment::where('doctor_id', $request->doctor_id)
                ->where('appointment_date', $request->appointment_date)
                ->where('status', '!=', 'Cancelada')
                ->where('id', '!=', $appointment->id)
                ->exists();

            if ($conflictingAppointment) {
                return response()->json([
                    'success' => false,
                    'message' => 'El doctor ya tiene una cita programada en esa fecha y hora.'
                ], 422);
            }

            $appointment->update($request->validated());

            DB::commit();

            Log::info('Cita actualizada exitosamente', ['appointment_id' => $appointment->id, 'user_id' => auth()->id()]);

            return response()->json([
                'success' => true,
                'data' => $appointment->fresh(['patient', 'doctor']),
                'message' => 'Cita actualizada exitosamente.'
            ]);
        } catch (Exception $e) {
            DB::rollback();
            Log::error('Error al actualizar cita: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la cita. Por favor, intente nuevamente.'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        try {
            // Check if appointment has medical records
            if ($appointment->medicalRecord()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede eliminar la cita porque tiene registros médicos asociados.'
                ], 422);
            }

            DB::beginTransaction();

            $appointmentId = $appointment->id;
            $appointment->delete();

            DB::commit();

            Log::info('Cita eliminada exitosamente', ['appointment_id' => $appointmentId, 'user_id' => auth()->id()]);

            return response()->json([
                'success' => true,
                'message' => 'Cita eliminada exitosamente.'
            ]);
        } catch (Exception $e) {
            DB::rollback();
            Log::error('Error al eliminar cita: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la cita. Por favor, intente nuevamente.'
            ], 500);
        }
    }

    /**
     * Get appointment statistics
     */
    public function statistics()
    {
        try {
            $stats = [
                'total_appointments' => Appointment::count(),
                'appointments_by_status' => [
                    'programada' => Appointment::where('status', 'Programada')->count(),
                    'confirmada' => Appointment::where('status', 'Confirmada')->count(),
                    'en_progreso' => Appointment::where('status', 'En Progreso')->count(),
                    'completada' => Appointment::where('status', 'Completada')->count(),
                    'cancelada' => Appointment::where('status', 'Cancelada')->count(),
                    'no_presentado' => Appointment::where('status', 'No Presentado')->count(),
                ],
                'appointments_by_type' => [
                    'consulta_general' => Appointment::where('appointment_type', 'Consulta General')->count(),
                    'consulta_especializada' => Appointment::where('appointment_type', 'Consulta Especializada')->count(),
                    'control' => Appointment::where('appointment_type', 'Control')->count(),
                    'emergencia' => Appointment::where('appointment_type', 'Emergencia')->count(),
                    'seguimiento' => Appointment::where('appointment_type', 'Seguimiento')->count(),
                ],
                'appointments_today' => Appointment::whereDate('appointment_date', now()->toDateString())->count(),
                'appointments_this_week' => Appointment::whereBetween('appointment_date', [now()->startOfWeek(), now()->endOfWeek()])->count(),
                'appointments_this_month' => Appointment::whereMonth('appointment_date', now()->month)->count(),
            ];

            return response()->json([
                'success' => true,
                'data' => $stats,
                'message' => 'Estadísticas de citas obtenidas exitosamente.'
            ]);
        } catch (Exception $e) {
            Log::error('Error al obtener estadísticas de citas: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las estadísticas de citas.'
            ], 500);
        }
    }

    /**
     * Get upcoming appointments
     */
    public function upcoming()
    {
        try {
            $appointments = Appointment::with(['patient', 'doctor'])
                ->where('appointment_date', '>=', now())
                ->where('status', 'Programada')
                ->orderBy('appointment_date', 'asc')
                ->limit(10)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $appointments,
                'message' => 'Próximas citas obtenidas exitosamente.'
            ]);
        } catch (Exception $e) {
            Log::error('Error al obtener próximas citas: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las próximas citas.'
            ], 500);
        }
    }
}