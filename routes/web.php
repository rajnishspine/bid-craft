<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\BidRecommendationController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\PermissionManagementController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified', 'permission:view dashboard'])->name('dashboard');



// Bid Recommendations routes - permissions handled in controller constructor
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/bid-recommendations', [BidRecommendationController::class, 'index'])->name('bid-recommendations.index');
    Route::post('/bid-recommendations/ask-ai', [BidRecommendationController::class, 'askAI'])->name('bid-recommendations.ask-ai');
    Route::get('/bid-recommendations/history', [BidRecommendationController::class, 'history'])->name('bid-recommendations.history');
    Route::get('/bid-recommendations/debug', [BidRecommendationController::class, 'debug'])->name('bid-recommendations.debug');
});

// User Management routes - protected by auth middleware and permissions
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('user-management', UserManagementController::class)->except(['show']);
    Route::post('/user-management/{user_management}/assign-role', [UserManagementController::class, 'assignRole'])->name('user-management.assign-role');
    Route::post('/user-management/{user_management}/remove-role', [UserManagementController::class, 'removeRole'])->name('user-management.remove-role');
    Route::get('/user-management/{user_management}/data', [UserManagementController::class, 'getUserData'])->name('user-management.get-data');
    
    // Permission summary route (remove after testing!)
    Route::get('/permission-summary', function() {
        $roles = \Spatie\Permission\Models\Role::with('permissions')->get();
        $users = \App\Models\User::with('roles')->get();
        
        $summary = [];
        
        foreach ($roles as $role) {
            $summary['roles'][$role->name] = [
                'id' => $role->id,
                'permissions' => $role->permissions->pluck('name')->toArray()
            ];
        }
        
        foreach ($users as $user) {
            $summary['users'][$user->name] = [
                'id' => $user->id,
                'email' => $user->email,
                'roles' => $user->roles->pluck('name')->toArray()
            ];
        }
        
        return response()->json($summary);
    })->name('permission.summary');
    
    // Refresh permissions (remove after testing!)
    Route::get('/refresh-permissions', function() {
        try {
            // Clear cache
            \Illuminate\Support\Facades\Artisan::call('cache:clear');
            
            // Run seeder to update roles and permissions
            \Illuminate\Support\Facades\Artisan::call('db:seed', [
                '--class' => 'RolesAndPermissionsSeeder',
                '--force' => true
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Permissions refreshed successfully!'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    })->name('refresh.permissions');
    
    // Test Team Member auto-assignment (remove after testing!)
    Route::get('/test-team-member-assignment/{user}', function($userId) {
        try {
            $user = \App\Models\User::findOrFail($userId);
            
            $before = [
                'user' => $user->name,
                'roles_before' => $user->roles->pluck('name')->toArray(),
                'permissions_before' => $user->getAllPermissions()->pluck('name')->toArray()
            ];
            
            // Assign Team Member role (this should auto-assign permissions)
            $user->assignRole('Team Member');
            
            // Manually trigger permission assignment (in case role already existed)
            $controller = new \App\Http\Controllers\UserManagementController();
            $reflection = new \ReflectionClass($controller);
            $method = $reflection->getMethod('assignTeamMemberPermissions');
            $method->setAccessible(true);
            $method->invoke($controller, $user);
            
            $user->refresh();
            
            $after = [
                'roles_after' => $user->roles->pluck('name')->toArray(),
                'permissions_after' => $user->getAllPermissions()->pluck('name')->toArray(),
                'direct_permissions' => $user->permissions->pluck('name')->toArray()
            ];
            
            return response()->json([
                'success' => true,
                'before' => $before,
                'after' => $after,
                'auto_assigned' => array_diff($after['permissions_after'], $before['permissions_before'])
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    })->name('test.team.member.assignment');
});

// Template Management routes - protected by auth middleware and permissions
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('templates', TemplateController::class)->except(['show']);
    Route::post('/templates/{template}/set-default', [TemplateController::class, 'setDefault'])->name('templates.set-default');
    Route::get('/templates/{template}/variables', [TemplateController::class, 'getVariables'])->name('templates.variables');
    Route::get('/templates/{template}/preview', [TemplateController::class, 'preview'])->name('templates.preview');
    Route::get('/api/templates/default', [TemplateController::class, 'getDefault'])->name('templates.get-default');
});

// Permission Management routes - protected by auth middleware and permissions
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/permission-management', [PermissionManagementController::class, 'index'])->name('permission-management.index');
    Route::get('/permission-management/role-permissions', [PermissionManagementController::class, 'rolePermissions'])->name('permission-management.role-permissions');
    Route::get('/permission-management/users/{user}/permissions', [PermissionManagementController::class, 'userPermissions'])->name('permission-management.user-permissions');
    
    // AJAX endpoints for permission management
    Route::post('/permission-management/assign-role-permission', [PermissionManagementController::class, 'assignPermissionToRole'])->name('permission-management.assign-role-permission');
    Route::post('/permission-management/remove-role-permission', [PermissionManagementController::class, 'removePermissionFromRole'])->name('permission-management.remove-role-permission');
    Route::post('/permission-management/toggle-role-permission', [PermissionManagementController::class, 'toggleRolePermission'])->name('permission-management.toggle-role-permission');
    
    Route::post('/permission-management/assign-user-permission', [PermissionManagementController::class, 'assignPermissionToUser'])->name('permission-management.assign-user-permission');
    Route::post('/permission-management/remove-user-permission', [PermissionManagementController::class, 'removePermissionFromUser'])->name('permission-management.remove-user-permission');
    Route::post('/permission-management/toggle-user-permission', [PermissionManagementController::class, 'toggleUserPermission'])->name('permission-management.toggle-user-permission');
    
    Route::post('/permission-management/create-permission', [PermissionManagementController::class, 'createPermission'])->name('permission-management.create-permission');
    Route::post('/permission-management/bulk-assign-role', [PermissionManagementController::class, 'bulkAssignToRole'])->name('permission-management.bulk-assign-role');
    Route::get('/permission-management/stats', [PermissionManagementController::class, 'getPermissionStats'])->name('permission-management.stats');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
