<div class="row">
    <!-- User Info -->
    <div class="col-12 mb-4">
        <div class="alert alert-info">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h5 class="alert-heading mb-2">
                        <i class="fas fa-user me-2"></i>
                        {{ $user->name }}
                    </h5>
                    <p class="mb-0">{{ $user->email }}</p>
                </div>
                <div class="text-end">
                    <div class="mb-1">
                        <strong>Roles:</strong> 
                        @if($user->roles->count() > 0)
                            {{ $user->roles->pluck('name')->join(', ') }}
                        @else
                            <span class="text-muted fst-italic">No roles assigned</span>
                        @endif
                    </div>
                    <div>
                        <strong>Direct Permissions:</strong> {{ $user->permissions->count() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Permission Matrix -->
    <div class="col-12">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Permission</th>
                        <th class="text-center">
                            <i class="fas fa-user-tag me-1"></i>
                            Via Roles
                        </th>
                        <th class="text-center">
                            <i class="fas fa-user-cog me-1"></i>
                            Direct Permission
                        </th>
                        <th class="text-center">
                            <i class="fas fa-check-circle me-1"></i>
                            Effective Access
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($allPermissions as $category => $categoryPermissions)
                        <!-- Category Header -->
                        <tr class="table-primary">
                            <td colspan="4" class="fw-bold">
                                <i class="fas fa-folder me-2"></i>
                                {{ ucfirst($category) }} Permissions
                            </td>
                        </tr>
                        
                        @foreach($categoryPermissions as $permission)
                            @php
                                $hasViaRole = $user->hasPermissionTo($permission) && !$user->hasDirectPermission($permission);
                                $hasDirectPermission = $user->hasDirectPermission($permission);
                                $hasEffectiveAccess = $user->hasPermissionTo($permission);
                                
                                // Get which roles provide this permission
                                $rolesWithPermission = $user->roles->filter(function($role) use ($permission) {
                                    return $role->hasPermissionTo($permission);
                                });
                            @endphp
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-key me-2 text-muted"></i>
                                        {{ $permission->name }}
                                    </div>
                                </td>
                                
                                <!-- Via Roles -->
                                <td class="text-center">
                                    @if($hasViaRole || $rolesWithPermission->count() > 0)
                                        <div class="d-flex flex-column align-items-center">
                                            <span class="badge bg-success mb-1">
                                                <i class="fas fa-check me-1"></i>
                                                Yes
                                            </span>
                                            @if($rolesWithPermission->count() > 0)
                                                <small class="text-muted">
                                                    via {{ $rolesWithPermission->pluck('name')->join(', ') }}
                                                </small>
                                            @endif
                                        </div>
                                    @else
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-times me-1"></i>
                                            No
                                        </span>
                                    @endif
                                </td>
                                
                                <!-- Direct Permission -->
                                <td class="text-center">
                                    <div class="form-check form-switch d-flex justify-content-center">
                                        <input class="form-check-input user-permission-toggle" 
                                               type="checkbox" 
                                               data-user-id="{{ $user->id }}" 
                                               data-permission-id="{{ $permission->id }}"
                                               data-permission-name="{{ $permission->name }}"
                                               data-user-name="{{ $user->name }}"
                                               {{ $hasDirectPermission ? 'checked' : '' }}
                                               onchange="toggleUserPermission(this)">
                                    </div>
                                </td>
                                
                                <!-- Effective Access -->
                                <td class="text-center">
                                    @if($hasEffectiveAccess)
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle me-1"></i>
                                            Granted
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="fas fa-ban me-1"></i>
                                            Denied
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bulk Actions -->
    <div class="col-12 mt-4">
        <div class="alert alert-light">
            <h5 class="alert-heading">
                <i class="fas fa-layer-group me-2"></i>
                Bulk Actions
            </h5>
            <div class="btn-group" role="group">
                <button onclick="toggleAllUserPermissions({{ $user->id }}, true)" 
                        class="btn btn-success btn-sm">
                    <i class="fas fa-check-double me-1"></i>
                    Grant All Direct
                </button>
                <button onclick="toggleAllUserPermissions({{ $user->id }}, false)" 
                        class="btn btn-danger btn-sm">
                    <i class="fas fa-times-circle me-1"></i>
                    Revoke All Direct
                </button>
                <button onclick="copyRolePermissions({{ $user->id }})" 
                        class="btn btn-primary btn-sm">
                    <i class="fas fa-copy me-1"></i>
                    Copy Role Permissions as Direct
                </button>
            </div>
            <p class="mb-0 mt-3">
                <i class="fas fa-info-circle me-1 text-info"></i>
                <small class="text-muted">
                    Direct permissions override role-based permissions and persist even if roles change.
                </small>
            </p>
        </div>
    </div>
</div>

<script>
    // Toggle User Permission
    function toggleUserPermission(checkbox) {
        const userId = checkbox.dataset.userId;
        const permissionId = checkbox.dataset.permissionId;
        const userName = checkbox.dataset.userName;
        const permissionName = checkbox.dataset.permissionName;
        
        // Add loading state
        checkbox.disabled = true;
        
        fetch('/permission-management/toggle-user-permission', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                user_id: userId,
                permission_id: permissionId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert(data.message, 'success');
                checkbox.checked = data.has_permission;
                // Reload user permissions to update effective access
                loadUserPermissions(userId);
            } else {
                showAlert(data.message, 'danger');
                checkbox.checked = !checkbox.checked; // Revert checkbox state
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('An error occurred while updating permission', 'danger');
            checkbox.checked = !checkbox.checked; // Revert checkbox state
        })
        .finally(() => {
            checkbox.disabled = false;
        });
    }

    // Toggle All User Permissions
    function toggleAllUserPermissions(userId, grant) {
        const action = grant ? 'grant all direct permissions to' : 'revoke all direct permissions from';
        const userName = document.querySelector(`[data-user-id="${userId}"]`).dataset.userName;
        
        if (!confirm(`Are you sure you want to ${action} ${userName}?`)) {
            return;
        }
        
        const checkboxes = document.querySelectorAll(`[data-user-id="${userId}"]`);
        checkboxes.forEach(checkbox => {
            if (checkbox.checked !== grant) {
                checkbox.checked = grant;
                toggleUserPermission(checkbox);
            }
        });
    }

    // Copy Role Permissions as Direct
    function copyRolePermissions(userId) {
        const userName = document.querySelector(`[data-user-id="${userId}"]`).dataset.userName;
        
        if (!confirm(`Copy all role-based permissions as direct permissions for ${userName}?`)) {
            return;
        }
        
        showAlert('Feature coming soon: Copy role permissions as direct permissions', 'info');
    }
</script>

<style>
    .form-check-input:checked {
        background-color: #198754;
        border-color: #198754;
    }
    
    .badge {
        font-size: 0.8em;
    }
    
    .table th {
        font-weight: 600;
        background-color: #212529 !important;
        color: white !important;
    }
    
    .table-primary td {
        background-color: #cfe2ff !important;
        font-weight: 600;
    }
    
    .btn-group .btn {
        margin-right: 0.25rem;
    }
    
    .alert-heading {
        font-weight: 600;
    }
</style>