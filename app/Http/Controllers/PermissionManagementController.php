<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PermissionManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view permissions')->only('index', 'rolePermissions', 'userPermissions');
        $this->middleware('permission:manage permissions')->only(['assignPermissionToRole', 'removePermissionFromRole', 'assignPermissionToUser', 'removePermissionFromUser', 'createPermission']);
    }

    /**
     * Display permission management dashboard
     */
    public function index()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all()->groupBy(function($permission) {
            return explode(' ', $permission->name)[1] ?? 'general';
        });
        
        $users = User::with(['roles', 'permissions'])->get();
        
        return view('permission-management.index', compact('roles', 'permissions', 'users'));
    }

    /**
     * Show role permissions matrix
     */
    public function rolePermissions()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all()->groupBy(function($permission) {
            $parts = explode(' ', $permission->name);
            return $parts[1] ?? 'general';
        });

        return view('permission-management.role-permissions', compact('roles', 'permissions'));
    }

    /**
     * Show user permissions
     */
    public function userPermissions(User $user)
    {
        $user->load(['roles', 'permissions']);
        $allPermissions = Permission::all()->groupBy(function($permission) {
            $parts = explode(' ', $permission->name);
            return $parts[1] ?? 'general';
        });

        return view('permission-management.user-permissions', compact('user', 'allPermissions'));
    }

    /**
     * Assign permission to role
     */
    public function assignPermissionToRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role_id' => 'required|exists:roles,id',
            'permission_id' => 'required|exists:permissions,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid data provided',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $role = Role::findById($request->role_id);
            $permission = Permission::findById($request->permission_id);
            
            if (!$role->hasPermissionTo($permission)) {
                $role->givePermissionTo($permission);
                
                return response()->json([
                    'success' => true,
                    'message' => "Permission '{$permission->name}' assigned to role '{$role->name}' successfully!",
                    'action' => 'assigned'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => "Role '{$role->name}' already has permission '{$permission->name}'"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to assign permission: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove permission from role
     */
    public function removePermissionFromRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role_id' => 'required|exists:roles,id',
            'permission_id' => 'required|exists:permissions,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid data provided',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $role = Role::findById($request->role_id);
            $permission = Permission::findById($request->permission_id);
            
            if ($role->hasPermissionTo($permission)) {
                $role->revokePermissionTo($permission);
                
                return response()->json([
                    'success' => true,
                    'message' => "Permission '{$permission->name}' removed from role '{$role->name}' successfully!",
                    'action' => 'removed'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => "Role '{$role->name}' doesn't have permission '{$permission->name}'"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove permission: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle permission for role
     */
    public function toggleRolePermission(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role_id' => 'required|exists:roles,id',
            'permission_id' => 'required|exists:permissions,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid data provided',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $role = Role::findById($request->role_id);
            $permission = Permission::findById($request->permission_id);
            
            if ($role->hasPermissionTo($permission)) {
                $role->revokePermissionTo($permission);
                $action = 'removed';
                $message = "Permission '{$permission->name}' removed from '{$role->name}'";
            } else {
                $role->givePermissionTo($permission);
                $action = 'assigned';
                $message = "Permission '{$permission->name}' assigned to '{$role->name}'";
            }
            
            return response()->json([
                'success' => true,
                'message' => $message,
                'action' => $action,
                'has_permission' => $role->hasPermissionTo($permission)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle permission: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Assign permission directly to user
     */
    public function assignPermissionToUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'permission_id' => 'required|exists:permissions,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid data provided',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::findOrFail($request->user_id);
            $permission = Permission::findById($request->permission_id);
            
            if (!$user->hasDirectPermission($permission)) {
                $user->givePermissionTo($permission);
                
                return response()->json([
                    'success' => true,
                    'message' => "Direct permission '{$permission->name}' assigned to user '{$user->name}' successfully!",
                    'action' => 'assigned'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => "User '{$user->name}' already has direct permission '{$permission->name}'"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to assign permission: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove permission from user
     */
    public function removePermissionFromUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'permission_id' => 'required|exists:permissions,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid data provided',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::findOrFail($request->user_id);
            $permission = Permission::findById($request->permission_id);
            
            if ($user->hasDirectPermission($permission)) {
                $user->revokePermissionTo($permission);
                
                return response()->json([
                    'success' => true,
                    'message' => "Direct permission '{$permission->name}' removed from user '{$user->name}' successfully!",
                    'action' => 'removed'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => "User '{$user->name}' doesn't have direct permission '{$permission->name}'"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove permission: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle permission for user
     */
    public function toggleUserPermission(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'permission_id' => 'required|exists:permissions,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid data provided',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::findOrFail($request->user_id);
            $permission = Permission::findById($request->permission_id);
            
            if ($user->hasDirectPermission($permission)) {
                $user->revokePermissionTo($permission);
                $action = 'removed';
                $message = "Direct permission '{$permission->name}' removed from '{$user->name}'";
            } else {
                $user->givePermissionTo($permission);
                $action = 'assigned';
                $message = "Direct permission '{$permission->name}' assigned to '{$user->name}'";
            }
            
            return response()->json([
                'success' => true,
                'message' => $message,
                'action' => $action,
                'has_permission' => $user->hasDirectPermission($permission)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle permission: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create new permission
     */
    public function createPermission(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:permissions,name',
            'category' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid data provided',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $permissionName = $request->input('category') . ' ' . $request->input('name');
            
            $permission = Permission::create([
                'name' => $permissionName,
                'guard_name' => 'web'
            ]);
            
            return response()->json([
                'success' => true,
                'message' => "Permission '{$permissionName}' created successfully!",
                'permission' => $permission
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create permission: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get permission statistics
     */
    public function getPermissionStats()
    {
        $totalPermissions = Permission::count();
        $totalRoles = Role::count();
        $totalUsers = User::count();
        
        $rolePermissionCount = DB::table('role_has_permissions')
            ->select('role_id', DB::raw('count(*) as permission_count'))
            ->groupBy('role_id')
            ->get();
            
        $userPermissionCount = DB::table('model_has_permissions')
            ->where('model_type', 'App\\Models\\User')
            ->count();

        return response()->json([
            'success' => true,
            'stats' => [
                'total_permissions' => $totalPermissions,
                'total_roles' => $totalRoles,
                'total_users' => $totalUsers,
                'role_permissions' => $rolePermissionCount,
                'direct_user_permissions' => $userPermissionCount
            ]
        ]);
    }

    /**
     * Bulk assign permissions to role
     */
    public function bulkAssignToRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role_id' => 'required|exists:roles,id',
            'permission_ids' => 'required|array',
            'permission_ids.*' => 'exists:permissions,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid data provided',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $role = Role::findById($request->role_id);
            $permissions = Permission::whereIn('id', $request->permission_ids)->get();
            
            $assigned = 0;
            foreach ($permissions as $permission) {
                if (!$role->hasPermissionTo($permission)) {
                    $role->givePermissionTo($permission);
                    $assigned++;
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => "{$assigned} permissions assigned to role '{$role->name}' successfully!",
                'assigned_count' => $assigned
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to bulk assign permissions: ' . $e->getMessage()
            ], 500);
        }
    }
}