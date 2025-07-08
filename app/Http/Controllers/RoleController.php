<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:roles.view')->only(['index', 'show']);
        $this->middleware('permission:roles.create')->only(['create', 'store']);
        $this->middleware('permission:roles.update')->only(['edit', 'update']);
        $this->middleware('permission:roles.delete')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Role::with(['permissions', 'users']);

            // Search functionality
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('guard_name', 'LIKE', "%{$search}%");
                });
            }

            // Filter by guard
            if ($request->filled('guard_name')) {
                $query->where('guard_name', $request->get('guard_name'));
            }

            // Sort by
            $sortBy = $request->get('sort_by', 'name');
            $sortOrder = $request->get('sort_order', 'asc');
            $query->orderBy($sortBy, $sortOrder);

            $roles = $query->paginate(15)->withQueryString();

            return response()->json([
                'success' => true,
                'data' => $roles,
                'message' => 'Roles obtenidos exitosamente.'
            ]);
        } catch (Exception $e) {
            Log::error('Error al obtener roles: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener la lista de roles.'
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $permissions = Permission::orderBy('name')->get();
            $guardNames = ['web', 'api'];

            return response()->json([
                'success' => true,
                'data' => [
                    'permissions' => $permissions,
                    'guard_names' => $guardNames,
                ],
                'message' => 'Formulario de creación de rol disponible.'
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
    public function store(StoreRoleRequest $request)
    {
        try {
            DB::beginTransaction();

            $role = Role::create([
                'name' => $request->name,
                'guard_name' => $request->guard_name,
            ]);

            // Assign permissions if provided
            if ($request->has('permissions') && is_array($request->permissions)) {
                $permissions = Permission::whereIn('id', $request->permissions)->get();
                $role->syncPermissions($permissions);
            }

            DB::commit();

            Log::info('Rol creado exitosamente', ['role_id' => $role->id, 'user_id' => auth()->id()]);

            return response()->json([
                'success' => true,
                'data' => $role->load('permissions'),
                'message' => 'Rol creado exitosamente.'
            ], 201);
        } catch (Exception $e) {
            DB::rollback();
            Log::error('Error al crear rol: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el rol. Por favor, intente nuevamente.'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        try {
            $role->load(['permissions', 'users']);
            
            return response()->json([
                'success' => true,
                'data' => $role,
                'message' => 'Rol obtenido exitosamente.'
            ]);
        } catch (Exception $e) {
            Log::error('Error al obtener rol: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener la información del rol.'
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        try {
            $permissions = Permission::orderBy('name')->get();
            $guardNames = ['web', 'api'];
            $role->load('permissions');

            return response()->json([
                'success' => true,
                'data' => [
                    'role' => $role,
                    'permissions' => $permissions,
                    'guard_names' => $guardNames,
                ],
                'message' => 'Formulario de edición de rol disponible.'
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
    public function update(UpdateRoleRequest $request, Role $role)
    {
        try {
            DB::beginTransaction();

            $role->update([
                'name' => $request->name,
                'guard_name' => $request->guard_name,
            ]);

            // Update permissions
            if ($request->has('permissions')) {
                if (is_array($request->permissions)) {
                    $permissions = Permission::whereIn('id', $request->permissions)->get();
                    $role->syncPermissions($permissions);
                } else {
                    $role->syncPermissions([]);
                }
            }

            DB::commit();

            Log::info('Rol actualizado exitosamente', ['role_id' => $role->id, 'user_id' => auth()->id()]);

            return response()->json([
                'success' => true,
                'data' => $role->fresh(['permissions']),
                'message' => 'Rol actualizado exitosamente.'
            ]);
        } catch (Exception $e) {
            DB::rollback();
            Log::error('Error al actualizar rol: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el rol. Por favor, intente nuevamente.'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        try {
            // Check if role has users assigned
            if ($role->users()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede eliminar el rol porque tiene usuarios asignados.'
                ], 422);
            }

            // Prevent deletion of system roles
            $systemRoles = ['super-admin', 'admin', 'doctor', 'nurse', 'receptionist'];
            if (in_array($role->name, $systemRoles)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede eliminar un rol del sistema.'
                ], 422);
            }

            DB::beginTransaction();

            $roleId = $role->id;
            $roleName = $role->name;
            
            // Remove all permissions from role
            $role->syncPermissions([]);
            
            // Delete the role
            $role->delete();

            DB::commit();

            Log::info('Rol eliminado exitosamente', ['role_id' => $roleId, 'role_name' => $roleName, 'user_id' => auth()->id()]);

            return response()->json([
                'success' => true,
                'message' => 'Rol eliminado exitosamente.'
            ]);
        } catch (Exception $e) {
            DB::rollback();
            Log::error('Error al eliminar rol: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el rol. Por favor, intente nuevamente.'
            ], 500);
        }
    }

    /**
     * Get role statistics
     */
    public function statistics()
    {
        try {
            $stats = [
                'total_roles' => Role::count(),
                'roles_with_users' => Role::has('users')->count(),
                'roles_without_users' => Role::doesntHave('users')->count(),
                'total_permissions' => Permission::count(),
                'roles_by_guard' => Role::select('guard_name', DB::raw('count(*) as count'))
                    ->groupBy('guard_name')
                    ->get(),
                'most_used_roles' => Role::withCount('users')
                    ->orderBy('users_count', 'desc')
                    ->limit(10)
                    ->get(),
                'permissions_per_role' => Role::with('permissions')
                    ->get()
                    ->map(function ($role) {
                        return [
                            'role_name' => $role->name,
                            'permissions_count' => $role->permissions->count(),
                        ];
                    }),
            ];

            return response()->json([
                'success' => true,
                'data' => $stats,
                'message' => 'Estadísticas de roles obtenidas exitosamente.'
            ]);
        } catch (Exception $e) {
            Log::error('Error al obtener estadísticas de roles: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener las estadísticas de roles.'
            ], 500);
        }
    }

    /**
     * Get all permissions grouped by category
     */
    public function permissions()
    {
        try {
            $permissions = Permission::orderBy('name')->get();
            
            // Group permissions by the first word (e.g., 'view patients' -> 'view')
            $groupedPermissions = $permissions->groupBy(function ($permission) {
                return explode(' ', $permission->name)[0];
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'permissions' => $permissions,
                    'grouped_permissions' => $groupedPermissions,
                ],
                'message' => 'Permisos obtenidos exitosamente.'
            ]);
        } catch (Exception $e) {
            Log::error('Error al obtener permisos: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener la lista de permisos.'
            ], 500);
        }
    }

    /**
     * Assign role to multiple users
     */
    public function assignToUsers(Request $request, Role $role)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        try {
            DB::beginTransaction();

            $users = \App\Models\User::whereIn('id', $request->user_ids)->get();
            
            foreach ($users as $user) {
                $user->assignRole($role);
            }

            DB::commit();

            Log::info('Rol asignado a usuarios exitosamente', ['role_id' => $role->id, 'user_ids' => $request->user_ids, 'user_id' => auth()->id()]);

            return response()->json([
                'success' => true,
                'message' => "Rol '{$role->name}' asignado exitosamente a " . count($request->user_ids) . " usuario(s)."
            ]);
        } catch (Exception $e) {
            DB::rollback();
            Log::error('Error al asignar rol a usuarios: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al asignar el rol a los usuarios. Por favor, intente nuevamente.'
            ], 500);
        }
    }

    /**
     * Remove role from multiple users
     */
    public function removeFromUsers(Request $request, Role $role)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        try {
            DB::beginTransaction();

            $users = \App\Models\User::whereIn('id', $request->user_ids)->get();
            
            foreach ($users as $user) {
                $user->removeRole($role);
            }

            DB::commit();

            Log::info('Rol removido de usuarios exitosamente', ['role_id' => $role->id, 'user_ids' => $request->user_ids, 'user_id' => auth()->id()]);

            return response()->json([
                'success' => true,
                'message' => "Rol '{$role->name}' removido exitosamente de " . count($request->user_ids) . " usuario(s)."
            ]);
        } catch (Exception $e) {
            DB::rollback();
            Log::error('Error al remover rol de usuarios: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al remover el rol de los usuarios. Por favor, intente nuevamente.'
            ], 500);
        }
    }
}