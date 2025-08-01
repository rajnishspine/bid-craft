<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;

class UserManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view users')->only('index', 'show');
        $this->middleware('permission:create users')->only('create', 'store');
        $this->middleware('permission:edit users')->only('edit', 'update');
        $this->middleware('permission:delete users')->only('destroy');
        $this->middleware('permission:assign roles')->only('assignRole', 'removeRole');
    }

    /**
     * Display a listing of users
     */
    public function index()
    {
        $users = User::with('roles')->paginate(15);
        $roles = Role::all();
        
        return view('user-management.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        $roles = Role::all();
        return view('user-management.create', compact('roles'));
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(),
        ]);

        $user->assignRole($request->role);

        // Automatically assign Team Member permissions if user gets Team Member role
        if ($request->role === 'Team Member') {
            $this->assignTeamMemberPermissions($user);
        }

        return redirect()->route('user-management.index')
            ->with('success', 'User created successfully with appropriate permissions!');
    }

    /**
     * Show the form for editing a user
     */
    public function edit(User $user_management)
    {
        $user = $user_management;
        $roles = Role::all();
        return view('user-management.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user_management)
    {
        $user = $user_management;
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        // Note: Role management is handled separately via AJAX endpoints
        // assignRole() and removeRole() methods handle role assignments

        return redirect()->route('user-management.index')
            ->with('success', 'User updated successfully!');
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user_management)
    {
        $user = $user_management;
        // Prevent deleting own account
        if ($user->id === Auth::id()) {
            return redirect()->route('user-management.index')
                ->with('error', 'You cannot delete your own account!');
        }

        $user->delete();

        return redirect()->route('user-management.index')
            ->with('success', 'User deleted successfully!');
    }

    /**
     * Assign role to user via AJAX
     */
    public function assignRole(Request $request, User $user_management)
    {
        try {
            $user = $user_management;
            
            \Log::info('Assign role request START', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'request_data' => $request->all(),
                'current_roles_before' => $user->roles->pluck('name')->toArray()
            ]);
            
            $request->validate([
                'role_id' => 'required|exists:roles,id',
            ]);

            $role = Role::findOrFail($request->role_id);
            
            \Log::info('Role found', [
                'role_id' => $role->id,
                'role_name' => $role->name
            ]);
            
            // Check if user already has this role
            if ($user->hasRole($role)) {
                \Log::info('User already has role', [
                    'user_id' => $user->id,
                    'role_name' => $role->name
                ]);
                return response()->json([
                    'success' => false,
                    'message' => "User already has the role '{$role->name}'"
                ]);
            }
            
            \Log::info('About to assign role', [
                'user_id' => $user->id,
                'role_id' => $role->id,
                'role_name' => $role->name
            ]);
            
            // Try assignment with more specific method
            $user->assignRole($role->name);
            
            \Log::info('Role assignment called', [
                'user_id' => $user->id,
                'role_name' => $role->name
            ]);
            
            // Automatically assign Team Member permissions if role is Team Member
            if ($role->name === 'Team Member') {
                $this->assignTeamMemberPermissions($user);
            }
            
            // Refresh user from database to check if assignment worked
            $user->refresh();
            $user->load('roles');
            
            \Log::info('After assignment - checking roles', [
                'user_id' => $user->id,
                'roles_after' => $user->roles->pluck('name')->toArray(),
                'roles_count' => $user->roles->count(),
                'has_role_check' => $user->hasRole($role->name)
            ]);
            
            // Double-check with direct database query
            $directRoleCheck = \DB::table('model_has_roles')
                ->where('model_type', 'App\\Models\\User')
                ->where('model_id', $user->id)
                ->where('role_id', $role->id)
                ->exists();
                
            \Log::info('Direct database check', [
                'user_id' => $user->id,
                'role_id' => $role->id,
                'exists_in_db' => $directRoleCheck
            ]);

            return response()->json([
                'success' => true,
                'message' => "Role '{$role->name}' assigned successfully!",
                'user' => $user->load('roles'),
                'debug' => [
                    'roles_count' => $user->roles->count(),
                    'direct_db_check' => $directRoleCheck,
                    'has_role' => $user->hasRole($role->name)
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Assign role error', [
                'user_id' => $user_management->id ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error assigning role: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove role from user via AJAX
     */
    public function removeRole(Request $request, User $user_management)
    {
        try {
            $user = $user_management;
            
            \Log::info('Remove role request', [
                'user_id' => $user->id,
                'request_data' => $request->all()
            ]);
            
            $request->validate([
                'role_id' => 'required|exists:roles,id',
            ]);

            $role = Role::findOrFail($request->role_id);
            
            // Check if user has this role
            if (!$user->hasRole($role)) {
                return response()->json([
                    'success' => false,
                    'message' => "User doesn't have the role '{$role->name}'"
                ]);
            }
            
            $user->removeRole($role);

            return response()->json([
                'success' => true,
                'message' => "Role '{$role->name}' removed successfully!",
                'user' => $user->load('roles')
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Remove role error', [
                'user_id' => $user_management->id ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error removing role: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user data via AJAX
     */
    public function getUserData(User $user_management)
    {
        $user = $user_management;
        return response()->json([
            'success' => true,
            'user' => $user->load('roles', 'permissions'),
            'roles' => $user->roles
        ]);
    }

    /**
     * Automatically assign Team Member permissions to user
     * This ensures Team Members always have the required permissions
     */
    private function assignTeamMemberPermissions(User $user)
    {
        $teamMemberPermissions = [
            'view dashboard',
            'view bid recommendations',  // Can see the bid form
            'create ai requests',        // Can use "Ask AI" button
            'view ai history',           // Can see AI request history
            'view settings',             // Can view own profile
        ];
        
        $assignedPermissions = [];
        
        foreach ($teamMemberPermissions as $permissionName) {
            try {
                $permission = Permission::where('name', $permissionName)->first();
                if ($permission && !$user->hasDirectPermission($permission)) {
                    $user->givePermissionTo($permission);
                    $assignedPermissions[] = $permissionName;
                }
            } catch (\Exception $e) {
                \Log::warning("Permission '{$permissionName}' assignment failed for user {$user->id}: " . $e->getMessage());
            }
        }
        
        if (!empty($assignedPermissions)) {
            \Log::info('Team Member permissions auto-assigned', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'assigned_permissions' => $assignedPermissions
            ]);
        }
        
        return $assignedPermissions;
    }
}