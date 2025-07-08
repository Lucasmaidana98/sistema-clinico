<?php

namespace App\Http\Controllers;

use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:users.view')->only(['index', 'show']);
        $this->middleware('permission:users.create')->only(['create', 'store']);
        $this->middleware('permission:users.update')->only(['edit', 'update']);
        $this->middleware('permission:users.delete')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = User::with(['roles', 'permissions']);

            // Search functionality
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('email', 'LIKE', "%{$search}%")
                      ->orWhereHas('roles', function ($roleQuery) use ($search) {
                          $roleQuery->where('name', 'LIKE', "%{$search}%");
                      });
                });
            }

            // Filter by role
            if ($request->filled('role')) {
                $query->whereHas('roles', function ($roleQuery) use ($request) {
                    $roleQuery->where('name', $request->get('role'));
                });
            }

            // Filter by email verification
            if ($request->filled('email_verified')) {
                if ($request->get('email_verified') === 'verified') {
                    $query->whereNotNull('email_verified_at');
                } else {
                    $query->whereNull('email_verified_at');
                }
            }

            // Sort by
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            $users = $query->paginate(15)->withQueryString();

            return response()->json([
                'success' => true,
                'data' => $users,
                'message' => 'Usuarios obtenidos exitosamente.'
            ]);
        } catch (Exception $e) {
            Log::error('Error al obtener usuarios: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener la lista de usuarios.'
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $roles = Role::orderBy('name')->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'roles' => $roles,
                ],
                'message' => 'Formulario de creación de usuario disponible.'
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
    public function store(StoreUserRequest $request)
    {
        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'email_verified_at' => now(), // Auto-verify for admin created users
            ]);

            // Assign roles if provided
            if ($request->has('roles') && is_array($request->roles)) {
                $roles = Role::whereIn('id', $request->roles)->get();
                $user->syncRoles($roles);
            }

            DB::commit();

            Log::info('Usuario creado exitosamente', ['user_id' => $user->id, 'created_by' => auth()->id()]);

            return response()->json([
                'success' => true,
                'data' => $user->load('roles'),
                'message' => 'Usuario creado exitosamente.'
            ], 201);
        } catch (Exception $e) {
            DB::rollback();
            Log::error('Error al crear usuario: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el usuario. Por favor, intente nuevamente.'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        try {
            $user->load(['roles', 'permissions', 'appointments', 'medicalRecords']);
            
            // Add some user statistics
            $userStats = [
                'total_appointments' => $user->appointments()->count(),
                'completed_appointments' => $user->appointments()->where('status', 'Completada')->count(),
                'total_medical_records' => $user->medicalRecords()->count(),
                'recent_appointments' => $user->appointments()
                    ->where('appointment_date', '>=', now()->subDays(30))
                    ->count(),
                'account_age_days' => $user->created_at->diffInDays(now()),
                'last_login' => $user->updated_at, // This would be better with a last_login_at field
            ];

            return response()->json([
                'success' => true,
                'data' => [
                    'user' => $user,
                    'statistics' => $userStats,
                ],
                'message' => 'Usuario obtenido exitosamente.'
            ]);
        } catch (Exception $e) {
            Log::error('Error al obtener usuario: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener la información del usuario.'
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        try {
            $roles = Role::orderBy('name')->get();
            $user->load('roles');

            return response()->json([
                'success' => true,
                'data' => [
                    'user' => $user,
                    'roles' => $roles,
                ],
                'message' => 'Formulario de edición de usuario disponible.'
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
    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            DB::beginTransaction();

            $updateData = [
                'name' => $request->name,
                'email' => $request->email,
            ];

            // Only update password if provided
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            $user->update($updateData);

            // Update roles
            if ($request->has('roles')) {
                if (is_array($request->roles)) {
                    $roles = Role::whereIn('id', $request->roles)->get();
                    $user->syncRoles($roles);
                } else {
                    $user->syncRoles([]);
                }
            }

            DB::commit();

            Log::info('Usuario actualizado exitosamente', ['user_id' => $user->id, 'updated_by' => auth()->id()]);

            return response()->json([
                'success' => true,
                'data' => $user->fresh(['roles']),
                'message' => 'Usuario actualizado exitosamente.'
            ]);
        } catch (Exception $e) {
            DB::rollback();
            Log::error('Error al actualizar usuario: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el usuario. Por favor, intente nuevamente.'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            // Prevent deletion of current user
            if ($user->id === auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No puedes eliminar tu propia cuenta.'
                ], 422);
            }

            // Check if user has appointments or medical records
            if ($user->appointments()->exists() || $user->medicalRecords()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede eliminar el usuario porque tiene citas o registros médicos asociados.'
                ], 422);
            }

            // Prevent deletion of super admin
            if ($user->hasRole('super-admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede eliminar un super administrador.'
                ], 422);
            }

            DB::beginTransaction();

            $userId = $user->id;
            $userName = $user->name;
            
            // Remove all roles from user
            $user->syncRoles([]);
            
            // Delete the user
            $user->delete();

            DB::commit();

            Log::info('Usuario eliminado exitosamente', ['user_id' => $userId, 'user_name' => $userName, 'deleted_by' => auth()->id()]);

            return response()->json([
                'success' => true,
                'message' => 'Usuario eliminado exitosamente.'
            ]);
        } catch (Exception $e) {
            DB::rollback();
            Log::error('Error al eliminar usuario: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el usuario. Por favor, intente nuevamente.'
            ], 500);
        }
    }

    /**
     * Get user statistics
     */
    public function statistics()
    {
        try {
            $stats = [
                'total_users' => User::count(),
                'verified_users' => User::whereNotNull('email_verified_at')->count(),
                'unverified_users' => User::whereNull('email_verified_at')->count(),
                'users_by_role' => Role::withCount('users')
                    ->orderBy('users_count', 'desc')
                    ->get(),
                'recent_users' => User::where('created_at', '>=', now()->subDays(30))->count(),
                'active_doctors' => User::role('doctor')->count(),
                'active_nurses' => User::role('nurse')->count(),
                'active_receptionists' => User::role('receptionist')->count(),
                'users_created_this_month' => User::whereMonth('created_at', now()->month)->count(),
            ];

            return response()->json([
                'success' => true,
                'data' => $stats,
                'message' => 'Estadísticas de usuarios obtenidas exitosamente.'
            ]);
        } catch (Exception $e) {
            Log::error('Error al obtener estadísticas de usuarios: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las estadísticas de usuarios.'
            ], 500);
        }
    }

    /**
     * Get current user profile
     */
    public function profile()
    {
        try {
            $user = auth()->user();
            $user->load(['roles', 'permissions']);

            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'Perfil de usuario obtenido exitosamente.'
            ]);
        } catch (Exception $e) {
            Log::error('Error al obtener perfil de usuario: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener el perfil de usuario.'
            ], 500);
        }
    }

    /**
     * Update current user profile
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'current_password' => 'required_with:password|string',
            'password' => 'nullable|string|min:8|confirmed',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'name.regex' => 'El nombre solo puede contener letras y espacios.',
            'email.required' => 'El email es obligatorio.',
            'email.email' => 'El email debe tener un formato válido.',
            'email.unique' => 'Este email ya está registrado.',
            'current_password.required_with' => 'La contraseña actual es obligatoria para cambiar la contraseña.',
            'password.min' => 'La nueva contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de la nueva contraseña no coincide.',
        ]);

        try {
            DB::beginTransaction();

            $user = auth()->user();

            // Verify current password if changing password
            if ($request->filled('password')) {
                if (!Hash::check($request->current_password, $user->password)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'La contraseña actual es incorrecta.'
                    ], 422);
                }
            }

            $updateData = [
                'name' => $request->name,
                'email' => $request->email,
            ];

            // Only update password if provided
            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            $user->update($updateData);

            DB::commit();

            Log::info('Perfil de usuario actualizado exitosamente', ['user_id' => $user->id]);

            return response()->json([
                'success' => true,
                'data' => $user->fresh(['roles']),
                'message' => 'Perfil actualizado exitosamente.'
            ]);
        } catch (Exception $e) {
            DB::rollback();
            Log::error('Error al actualizar perfil de usuario: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el perfil. Por favor, intente nuevamente.'
            ], 500);
        }
    }

    /**
     * Get users by role
     */
    public function byRole($roleName)
    {
        try {
            $users = User::role($roleName)->with('roles')->paginate(15);

            return response()->json([
                'success' => true,
                'data' => $users,
                'message' => "Usuarios con rol '{$roleName}' obtenidos exitosamente."
            ]);
        } catch (Exception $e) {
            Log::error('Error al obtener usuarios por rol: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los usuarios por rol.'
            ], 500);
        }
    }

    /**
     * Toggle user status (activate/deactivate)
     */
    public function toggleStatus(User $user)
    {
        try {
            // Prevent deactivating current user
            if ($user->id === auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No puedes desactivar tu propia cuenta.'
                ], 422);
            }

            // Prevent deactivating super admin
            if ($user->hasRole('super-admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede desactivar un super administrador.'
                ], 422);
            }

            DB::beginTransaction();

            // Toggle email_verified_at to simulate active/inactive status
            $user->email_verified_at = $user->email_verified_at ? null : now();
            $user->save();

            $status = $user->email_verified_at ? 'activado' : 'desactivado';

            DB::commit();

            Log::info("Usuario {$status} exitosamente", ['user_id' => $user->id, 'status' => $status, 'updated_by' => auth()->id()]);

            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => "Usuario {$status} exitosamente."
            ]);
        } catch (Exception $e) {
            DB::rollback();
            Log::error('Error al cambiar estado del usuario: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar el estado del usuario. Por favor, intente nuevamente.'
            ], 500);
        }
    }
}