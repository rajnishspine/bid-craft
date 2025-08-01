<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // User Management
            'view users',
            'create users',
            'edit users',
            'delete users',
            
            // Template Management
            'view templates',
            'create templates',
            'edit templates',
            'delete templates',
            
            // Bid Recommendations
            'view bid recommendations',
            'create bid recommendations',
            'edit bid recommendations',
            'delete bid recommendations',
            
            // AI Requests
            'view ai requests',
            'create ai requests',
            'edit ai requests',
            'delete ai requests',
            'view ai history',
            
            // Role Management
            'view roles',
            'create roles',
            'edit roles',
            'delete roles',
            'assign roles',
            
            // Permission Management
            'view permissions',
            'manage permissions',
            'create permissions',
            'assign permissions to roles',
            'assign permissions to users',
            'revoke permissions from roles',
            'revoke permissions from users',
            
            // Dashboard
            'view dashboard',
            'view admin dashboard',
            
            // Settings
            'view settings',
            'edit settings',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions

        // Super Admin (Manager) - Full access
        $superAdminRole = Role::create(['name' => 'Super Admin']);
        $superAdminRole->givePermissionTo(Permission::all());

        // Team Member (User) - Limited access - Can only view bid form and use AI
        $teamMemberRole = Role::create(['name' => 'Team Member']);
        $teamMemberRole->givePermissionTo([
            // Basic access
            'view dashboard',
            
            // Bid Form Access - Can see the form
            'view bid recommendations',
            
            // AI Access - Can use askAI functionality
            'create ai requests',
            'view ai history',
            
            // Own profile
            'view settings',
        ]);

        // Create a default Super Admin user if it doesn't exist
        $superAdminUser = User::where('email', 'admin@bidcraft.com')->first();
        if (!$superAdminUser) {
            $superAdminUser = User::create([
                'name' => 'Super Admin',
                'email' => 'admin@bidcraft.com',
                'password' => bcrypt('password123'),
                'email_verified_at' => now(),
            ]);
        }
        $superAdminUser->assignRole($superAdminRole);

        // Create a default Team Member user if it doesn't exist
        $teamMemberUser = User::where('email', 'user@bidcraft.com')->first();
        if (!$teamMemberUser) {
            $teamMemberUser = User::create([
                'name' => 'Team Member',
                'email' => 'user@bidcraft.com',
                'password' => bcrypt('password123'),
                'email_verified_at' => now(),
            ]);
        }
        $teamMemberUser->assignRole($teamMemberRole);

        $this->command->info('Roles and permissions seeded successfully!');
        $this->command->info('Super Admin: admin@bidcraft.com / password123');
        $this->command->info('Team Member: user@bidcraft.com / password123');
    }
}