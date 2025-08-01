<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark">
            {{ __('Permission Management') }}
        </h2>
    </x-slot>

    <div class="py-5">
        <div class="container-fluid">
            <!-- Success/Error Messages -->
            <div id="alert-container" class="mb-4"></div>

            <!-- Permission Statistics -->
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="card text-center h-100">
                        <div class="card-body">
                            <h3 class="text-primary fw-bold mb-2">{{ $permissions->flatten()->count() }}</h3>
                            <p class="text-muted mb-0">Total Permissions</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center h-100">
                        <div class="card-body">
                            <h3 class="text-success fw-bold mb-2">{{ $roles->count() }}</h3>
                            <p class="text-muted mb-0">Total Roles</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center h-100">
                        <div class="card-body">
                            <h3 class="text-purple fw-bold mb-2">{{ $users->count() }}</h3>
                            <p class="text-muted mb-0">Total Users</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center h-100">
                        <div class="card-body">
                            <h3 class="text-warning fw-bold mb-2">{{ $permissions->count() }}</h3>
                            <p class="text-muted mb-0">Permission Categories</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs Navigation -->
            <div class="card">
                <div class="card-header p-0">
                    <ul class="nav nav-tabs card-header-tabs" id="permissionTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="role-permissions-tab" data-bs-toggle="tab" 
                                    data-bs-target="#role-permissions" type="button" role="tab">
                                <i class="fas fa-user-shield me-2"></i>
                                Role Permissions
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="user-permissions-tab" data-bs-toggle="tab" 
                                    data-bs-target="#user-permissions" type="button" role="tab">
                                <i class="fas fa-user-cog me-2"></i>
                                User Permissions
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="create-permission-tab" data-bs-toggle="tab" 
                                    data-bs-target="#create-permission" type="button" role="tab">
                                <i class="fas fa-plus-circle me-2"></i>
                                Create Permission
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="tab-content" id="permissionTabsContent">
                    <!-- Role Permissions Tab -->
                    <div class="tab-pane fade show active" id="role-permissions" role="tabpanel">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4 class="mb-0">
                                    <i class="fas fa-user-shield me-2 text-primary"></i>
                                    Role Permission Matrix
                                </h4>
                                <div class="btn-group">
                                    <button onclick="toggleAllPermissions(true)" 
                                            class="btn btn-success btn-sm">
                                        <i class="fas fa-check-double me-1"></i>
                                        Grant All
                                    </button>
                                    <button onclick="toggleAllPermissions(false)" 
                                            class="btn btn-danger btn-sm">
                                        <i class="fas fa-times-circle me-1"></i>
                                        Revoke All
                                    </button>
                                </div>
                            </div>

                            <!-- Permission Matrix -->
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th class="sticky-column text-white">Permission</th>
                                            @foreach($roles as $role)
                                                <th class="text-center">
                                                    <div class="d-flex flex-column align-items-center">
                                                        @if($role->name === 'Super Admin')
                                                            <i class="fas fa-crown text-warning mb-1"></i>
                                                        @else
                                                            <i class="fas fa-user-tag text-info mb-1"></i>
                                                        @endif
                                                        <small class="text-white">{{ $role->name }}</small>
                                                    </div>
                                                </th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($permissions as $category => $categoryPermissions)
                                            <!-- Category Header -->
                                            <tr class="table-primary">
                                                <td colspan="{{ $roles->count() + 1 }}" class="fw-bold">
                                                    <i class="fas fa-folder me-2"></i>
                                                    {{ ucfirst($category) }} Permissions
                                                </td>
                                            </tr>
                                            
                                            @foreach($categoryPermissions as $permission)
                                                <tr>
                                                    <td class="sticky-column">
                                                        <div class="d-flex align-items-center">
                                                            <i class="fas fa-key me-2 text-muted"></i>
                                                            {{ $permission->name }}
                                                        </div>
                                                    </td>
                                                    @foreach($roles as $role)
                                                        <td class="text-center">
                                                            <div class="form-check form-switch d-flex justify-content-center">
                                                                <input class="form-check-input permission-toggle" 
                                                                       type="checkbox" 
                                                                       data-role-id="{{ $role->id }}" 
                                                                       data-permission-id="{{ $permission->id }}"
                                                                       data-permission-name="{{ $permission->name }}"
                                                                       data-role-name="{{ $role->name }}"
                                                                       {{ $role->hasPermissionTo($permission) ? 'checked' : '' }}
                                                                       onchange="toggleRolePermission(this)">
                                                            </div>
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- User Permissions Tab -->
                    <div class="tab-pane fade" id="user-permissions" role="tabpanel">
                        <div class="card-body">
                            <h4 class="mb-4">
                                <i class="fas fa-user-cog me-2 text-primary"></i>
                                Individual User Permissions
                            </h4>

                            <!-- User Selection -->
                            <div class="mb-4">
                                <label for="userSelect" class="form-label fw-medium">
                                    Select User to Manage Permissions
                                </label>
                                <select id="userSelect" onchange="loadUserPermissions(this.value)" 
                                        class="form-select">
                                    <option value="">Choose a user...</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">
                                            {{ $user->name }} ({{ $user->email }}) - {{ $user->roles->pluck('name')->join(', ') ?: 'No roles' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Selected User Permissions -->
                            <div id="selectedUserPermissions" class="d-none">
                                <div class="alert alert-info">
                                    <h6 class="alert-heading">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Permission Information
                                    </h6>
                                    <p class="mb-0">
                                        <strong>Role Permissions:</strong> Permissions inherited from assigned roles<br>
                                        <strong>Direct Permissions:</strong> Permissions assigned directly to this user
                                    </p>
                                </div>

                                <div id="userPermissionMatrix">
                                    <!-- User permission matrix will be loaded here -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Create Permission Tab -->
                    <div class="tab-pane fade" id="create-permission" role="tabpanel">
                        <div class="card-body">
                            <h4 class="mb-4">
                                <i class="fas fa-plus-circle me-2 text-primary"></i>
                                Create New Permission
                            </h4>

                            <form id="createPermissionForm">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="permissionCategory" class="form-label fw-medium">
                                                <i class="fas fa-folder me-2"></i>Permission Category
                                            </label>
                                            <select id="permissionCategory" name="category" required 
                                                    class="form-select">
                                                <option value="">Select category...</option>
                                                @foreach($permissions->keys() as $category)
                                                    <option value="{{ $category }}">{{ ucfirst($category) }}</option>
                                                @endforeach
                                                <option value="custom">Create new category</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="permissionName" class="form-label fw-medium">
                                                <i class="fas fa-key me-2"></i>Permission Name
                                            </label>
                                            <input type="text" id="permissionName" name="name" required 
                                                   class="form-control"
                                                   placeholder="e.g., create, edit, delete">
                                        </div>
                                    </div>
                                </div>

                                <div id="customCategoryInput" class="mb-3 d-none">
                                    <label for="customCategory" class="form-label fw-medium">
                                        <i class="fas fa-plus me-2"></i>Custom Category Name
                                    </label>
                                    <input type="text" id="customCategory" 
                                           class="form-control"
                                           placeholder="e.g., reports, analytics">
                                </div>

                                <div class="alert alert-light">
                                    <h6 class="fw-medium">Permission Preview</h6>
                                    <p class="mb-0" id="permissionPreview">
                                        Full permission name will appear here...
                                    </p>
                                </div>

                                <div class="d-flex justify-content-end gap-2">
                                    <button type="button" onclick="resetCreateForm()" 
                                            class="btn btn-secondary">
                                        <i class="fas fa-undo me-2"></i>
                                        Reset
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i>
                                        Create Permission
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <script>
        // Toggle Role Permission
        function toggleRolePermission(checkbox) {
            const roleId = checkbox.dataset.roleId;
            const permissionId = checkbox.dataset.permissionId;
            const roleName = checkbox.dataset.roleName;
            const permissionName = checkbox.dataset.permissionName;
            
            // Add loading state
            checkbox.disabled = true;
            
            fetch('/permission-management/toggle-role-permission', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    role_id: roleId,
                    permission_id: permissionId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert(data.message, 'success');
                    checkbox.checked = data.has_permission;
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

        // Show Alert
        function showAlert(message, type) {
            const alertContainer = document.getElementById('alert-container');
            const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
            const iconClass = type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle';
            
            const alert = document.createElement('div');
            alert.className = `alert ${alertClass} alert-dismissible fade show`;
            alert.innerHTML = `
                <i class="${iconClass} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            alertContainer.appendChild(alert);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (alert.parentElement) {
                    alert.remove();
                }
            }, 5000);
        }

        // Load User Permissions
        function loadUserPermissions(userId) {
            if (!userId) {
                document.getElementById('selectedUserPermissions').classList.add('d-none');
                return;
            }

            document.getElementById('selectedUserPermissions').classList.remove('d-none');
            
            // Show loading
            document.getElementById('userPermissionMatrix').innerHTML = `
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="text-muted mt-3">Loading user permissions...</p>
                </div>
            `;

            fetch(`/permission-management/users/${userId}/permissions`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('userPermissionMatrix').innerHTML = html;
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('userPermissionMatrix').innerHTML = `
                        <div class="text-center py-5">
                            <i class="fas fa-exclamation-triangle text-danger fa-3x mb-3"></i>
                            <p class="text-danger">Failed to load user permissions</p>
                        </div>
                    `;
                });
        }

        // Create Permission Form
        document.getElementById('createPermissionForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const category = document.getElementById('permissionCategory').value === 'custom' 
                ? document.getElementById('customCategory').value 
                : document.getElementById('permissionCategory').value;
            
            if (!category) {
                showAlert('Please select or enter a category', 'danger');
                return;
            }
            
            const submitButton = this.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creating...';
            
            fetch('/permission-management/create-permission', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    name: formData.get('name'),
                    category: category
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert(data.message, 'success');
                    this.reset();
                    updatePermissionPreview();
                    // Reload the page to show new permission
                    setTimeout(() => location.reload(), 2000);
                } else {
                    showAlert(data.message, 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('An error occurred while creating permission', 'danger');
            })
            .finally(() => {
                submitButton.disabled = false;
                submitButton.innerHTML = '<i class="fas fa-plus me-2"></i>Create Permission';
            });
        });

        // Permission Preview
        function updatePermissionPreview() {
            const category = document.getElementById('permissionCategory').value === 'custom' 
                ? document.getElementById('customCategory').value 
                : document.getElementById('permissionCategory').value;
            const name = document.getElementById('permissionName').value;
            
            const preview = document.getElementById('permissionPreview');
            if (category && name) {
                preview.innerHTML = `<strong>${category} ${name}</strong>`;
            } else {
                preview.textContent = 'Full permission name will appear here...';
            }
        }

        // Category Selection Handler
        document.getElementById('permissionCategory').addEventListener('change', function() {
            const customInput = document.getElementById('customCategoryInput');
            if (this.value === 'custom') {
                customInput.classList.remove('d-none');
            } else {
                customInput.classList.add('d-none');
            }
            updatePermissionPreview();
        });

        // Input Event Listeners
        document.getElementById('permissionName').addEventListener('input', updatePermissionPreview);
        document.getElementById('customCategory').addEventListener('input', updatePermissionPreview);

        // Reset Form
        function resetCreateForm() {
            document.getElementById('createPermissionForm').reset();
            document.getElementById('customCategoryInput').classList.add('d-none');
            updatePermissionPreview();
        }

        // Toggle All Permissions
        function toggleAllPermissions(grant) {
            const checkboxes = document.querySelectorAll('.permission-toggle');
            const action = grant ? 'grant' : 'revoke';
            
            if (!confirm(`Are you sure you want to ${action} ALL permissions for ALL roles?`)) {
                return;
            }
            
            checkboxes.forEach(checkbox => {
                if (checkbox.checked !== grant) {
                    checkbox.checked = grant;
                    toggleRolePermission(checkbox);
                }
            });
        }
    </script>

    <style>
        .text-purple {
            color: #8b5cf6 !important;
        }
        
        .sticky-column {
            position: sticky;
            left: 0;
            background-color: white;
            z-index: 10;
            border-right: 2px solid #dee2e6;
        }
        
        .table-dark .sticky-column {
            background-color: #212529;
        }
        
        .form-check-input:checked {
            background-color: #198754;
            border-color: #198754;
        }
        
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        
        .nav-tabs .nav-link {
            border: none;
            border-bottom: 3px solid transparent;
            color: #6c757d;
        }
        
        .nav-tabs .nav-link.active {
            background-color: transparent;
            border-color: transparent transparent #0d6efd transparent;
            color: #0d6efd;
        }
        
        .table th {
            font-weight: 600;
            font-size: 0.875rem;
        }
        
        .btn-group .btn {
            border-radius: 0.375rem;
            margin-left: 0.25rem;
        }
        
        .alert-heading {
            font-weight: 600;
        }
    </style>
</x-app-layout>