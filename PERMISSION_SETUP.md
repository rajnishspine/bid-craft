# Spatie Permission Setup for BidCraft

## Overview
This setup implements role-based access control using Spatie Laravel Permission package with two main roles:
- **Super Admin (Manager)**: Full access to all features
- **Team Member (User)**: Limited access to core functionality

## Installation Steps

### 1. Add Package to Composer (if not already done)
```bash
composer require spatie/laravel-permission
```

### 2. Run Migrations
```bash
php artisan migrate
```

### 3. Seed Roles and Permissions
```bash
php artisan db:seed --class=RolesAndPermissionsSeeder
```

## Default Users Created

After running the seeder, the following test users will be created:

| Email | Password | Role | Access Level |
|-------|----------|------|--------------|
| admin@bidcraft.com | password123 | Super Admin | Full Access |
| user@bidcraft.com | password123 | Team Member | Limited Access |

## Roles and Permissions

### Super Admin Permissions
- **User Management**: Create, edit, delete users and assign roles
- **Template Management**: Full access to all templates
- **Bid Recommendations**: Full access to all AI requests and history
- **System Settings**: Access to all configuration options
- **Dashboard**: Access to admin dashboard with all statistics

### Team Member Permissions
- **Template Management**: Create and edit own templates
- **Bid Recommendations**: Create AI requests and view own history
- **Dashboard**: Access to basic dashboard
- **Profile**: Manage own profile settings

## Usage Examples

### In Controllers
```php
// Check permission in controller
public function __construct()
{
    $this->middleware('permission:view users')->only('index');
    $this->middleware('permission:create users')->only('create', 'store');
}
```

### In Blade Templates
```blade
@can('view users')
    <a href="{{ route('user-management.index') }}">User Management</a>
@endcan

@role('Super Admin')
    <p>You are a Super Admin!</p>
@endrole
```

### In Routes
```php
Route::middleware(['auth', 'permission:manage users'])->group(function () {
    Route::resource('users', UserController::class);
});
```

## Permission List

### User Management
- `view users` - View user list
- `create users` - Create new users
- `edit users` - Edit existing users
- `delete users` - Delete users
- `assign roles` - Assign roles to users

### Template Management
- `view templates` - View templates
- `create templates` - Create new templates
- `edit templates` - Edit existing templates
- `delete templates` - Delete templates

### Bid Recommendations
- `view bid recommendations` - View bid recommendations
- `create bid recommendations` - Create new recommendations
- `edit bid recommendations` - Edit recommendations
- `delete bid recommendations` - Delete recommendations

### AI Requests
- `view ai requests` - View AI requests
- `create ai requests` - Create AI requests
- `view ai history` - View AI request history

### System
- `view dashboard` - Access basic dashboard
- `view admin dashboard` - Access admin dashboard
- `view settings` - View system settings
- `edit settings` - Edit system settings

## Adding New Permissions

To add new permissions, update the `RolesAndPermissionsSeeder.php` file:

```php
$newPermissions = [
    'view reports',
    'create reports',
    'edit reports',
];

foreach ($newPermissions as $permission) {
    Permission::create(['name' => $permission]);
}

// Assign to roles
$superAdminRole->givePermissionTo($newPermissions);
$teamMemberRole->givePermissionTo(['view reports']);
```

## Navigation Protection

The navigation menu automatically shows/hides links based on user permissions:
- User Management link only shows for users with `view users` permission
- Other menu items can be protected similarly using `@can()` directives

## File Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   └── UserManagementController.php
│   └── Middleware/
│       └── PermissionMiddleware.php
├── Models/
│   └── User.php (updated with HasRoles trait)
config/
└── permission.php
database/
├── migrations/
│   └── 2024_12_19_180001_create_permission_tables.php
└── seeders/
    └── RolesAndPermissionsSeeder.php
resources/views/
└── user-management/
    ├── index.blade.php
    ├── create.blade.php
    └── edit.blade.php
```

## Troubleshooting

### Permission Cache Issues
If permissions don't seem to work, clear the permission cache:
```bash
php artisan cache:clear
```

### Role Assignment Issues
Make sure users are properly assigned roles:
```php
$user = User::find(1);
$user->assignRole('Super Admin');
```

### Middleware Issues
Ensure middleware is properly registered in `app/Http/Kernel.php` and routes are protected.

## Security Notes

1. **Default Passwords**: Change default passwords immediately in production
2. **Permission Names**: Use descriptive permission names for clarity
3. **Role Hierarchy**: Super Admin has all permissions, Team Member has limited access
4. **Route Protection**: All user management routes are protected by permissions
5. **Self-Deletion**: Users cannot delete their own accounts for security