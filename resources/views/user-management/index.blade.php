<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark">
            {{ __('User Management') }}
        </h2>
    </x-slot>

    <div class="py-5">
        <div class="container-fluid">
            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card shadow-sm">
                <div class="card-body">
                    <!-- Header with Create Button -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-users me-2 text-primary"></i>
                            Users & Roles Management
                        </h3>
                        @can('create users')
                            <a href="{{ route('user-management.create') }}" 
                               class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>
                                Add New User
                            </a>
                        @endcan
                    </div>

                    <!-- Users Table -->
                    <div class="table-responsive">
                        <table class="table table-hover" id="usersTable">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" class="fw-medium text-uppercase">
                                        <i class="fas fa-user me-2"></i>User
                                    </th>
                                    <th scope="col" class="fw-medium text-uppercase">
                                        <i class="fas fa-envelope me-2"></i>Email
                                    </th>
                                    <th scope="col" class="fw-medium text-uppercase">
                                        <i class="fas fa-user-tag me-2"></i>Roles
                                    </th>
                                    <th scope="col" class="fw-medium text-uppercase">
                                        <i class="fas fa-calendar me-2"></i>Created
                                    </th>
                                    <th scope="col" class="fw-medium text-uppercase text-end">
                                        <i class="fas fa-cogs me-2"></i>Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="me-3">
                                                    <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" 
                                                         style="width: 40px; height: 40px;">
                                                        <i class="fas fa-user text-white"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="fw-medium text-dark">
                                                        {{ $user->name }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ $user->email }}</span>
                                        </td>
                                        <td>
                                            @foreach($user->roles as $role)
                                                <span class="badge 
                                                    @if($role->name === 'Super Admin') bg-purple text-white 
                                                    @else bg-primary @endif me-1">
                                                    @if($role->name === 'Super Admin')
                                                        <i class="fas fa-crown me-1"></i>
                                                    @else
                                                        <i class="fas fa-user-tag me-1"></i>
                                                    @endif
                                                    {{ $role->name }}
                                                </span>
                                            @endforeach
                                            @if($user->roles->isEmpty())
                                                <span class="badge bg-secondary">No Role</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="text-muted">
                                                {{ $user->created_at->format('M d, Y') }}
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <div class="btn-group" role="group">
                                                @can('edit users')
                                                    <a href="{{ route('user-management.edit', $user) }}" 
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endcan
                                                
                                                @can('assign roles')
                                                    <button type="button" 
                                                            class="btn btn-sm btn-outline-info"
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#roleModal"
                                                            onclick="openRoleModal({{ $user->id }}, '{{ $user->name }}')">
                                                        <i class="fas fa-user-cog"></i>
                                                    </button>
                                                @endcan
                                                
                                                @can('delete users')
                                                    @if($user->id !== auth()->id())
                                                        <button type="button" 
                                                                class="btn btn-sm btn-outline-danger"
                                                                onclick="deleteUser({{ $user->id }}, '{{ $user->name }}')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    @endif
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="fas fa-users fa-3x mb-3 opacity-50"></i>
                                                <p class="mb-0">No users found</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Role Assignment Modal -->
    <div class="modal fade" id="roleModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-user-cog me-2"></i>
                        Assign Role
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="roleForm">
                        @csrf
                        <input type="hidden" id="userId" name="user_id">
                        
                        <div class="mb-3">
                            <label class="form-label fw-medium">User:</label>
                            <p id="userName" class="text-muted mb-3"></p>
                        </div>

                        <div class="mb-3">
                            <label for="roleSelect" class="form-label fw-medium">Select Role:</label>
                            <select class="form-select" id="roleSelect" name="role_id" required>
                                <option value="">Choose a role...</option>
                                @foreach(\Spatie\Permission\Models\Role::all() as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-medium">Current Roles:</label>
                            <div id="currentRoles" class="border rounded p-3 bg-light">
                                <!-- Current roles will be loaded here -->
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="assignRole()">
                        <i class="fas fa-save me-2"></i>
                        Assign Role
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Confirm Delete
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete user <strong id="deleteUserName"></strong>?</p>
                    <p class="text-danger">
                        <i class="fas fa-warning me-2"></i>
                        This action cannot be undone.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                        <i class="fas fa-trash me-2"></i>
                        Delete User
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    
    <!-- DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#usersTable').DataTable({
                responsive: true,
                language: {
                    search: "Search users:",
                    lengthMenu: "Show _MENU_ users per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ users",
                    emptyTable: "No users found"
                },
                order: [[3, 'desc']], // Sort by created date
                pageLength: 10
            });
        });

        let deleteUserId = null;

        function openRoleModal(userId, userName) {
            document.getElementById('userId').value = userId;
            document.getElementById('userName').textContent = userName;
            
            // Load current roles
            loadCurrentRoles(userId);
        }

        function loadCurrentRoles(userId) {
            fetch(`/user-management/${userId}/data`)
                .then(response => response.json())
                .then(data => {
                    const currentRolesDiv = document.getElementById('currentRoles');
                    if (data.roles && data.roles.length > 0) {
                        currentRolesDiv.innerHTML = data.roles.map(role => 
                            `<span class="badge bg-primary me-2 mb-2">
                                ${role.name}
                                <button type="button" class="btn-close btn-close-white ms-2" 
                                        onclick="removeRole(${userId}, ${role.id}, '${role.name}')" 
                                        style="font-size: 0.7em;"></button>
                            </span>`
                        ).join('');
                    } else {
                        currentRolesDiv.innerHTML = '<span class="text-muted">No roles assigned</span>';
                    }
                })
                .catch(error => {
                    console.error('Error loading roles:', error);
                    document.getElementById('currentRoles').innerHTML = '<span class="text-danger">Error loading roles</span>';
                });
        }

        function assignRole() {
            const userId = document.getElementById('userId').value;
            const roleId = document.getElementById('roleSelect').value;
            
            if (!roleId) {
                alert('Please select a role');
                return;
            }

            fetch(`/user-management/${userId}/assign-role`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ role_id: roleId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Error assigning role');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error assigning role');
            });
        }

        function removeRole(userId, roleId, roleName) {
            if (confirm(`Remove role "${roleName}" from this user?`)) {
                fetch(`/user-management/${userId}/remove-role`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ role_id: roleId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadCurrentRoles(userId);
                        // Optionally reload page to update table
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        alert(data.message || 'Error removing role');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error removing role');
                });
            }
        }

        function deleteUser(userId, userName) {
            deleteUserId = userId;
            document.getElementById('deleteUserName').textContent = userName;
            
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        }

        function confirmDelete() {
            if (deleteUserId) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/user-management/${deleteUserId}`;
                
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                
                const tokenInput = document.createElement('input');
                tokenInput.type = 'hidden';
                tokenInput.name = '_token';
                tokenInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                form.appendChild(methodInput);
                form.appendChild(tokenInput);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>

    <style>
        .bg-purple {
            background-color: #8b5cf6 !important;
        }
        
        .table th {
            border-top: none;
            font-weight: 600;
            color: #495057;
            background-color: #f8f9fa !important;
        }
        
        .btn-group .btn {
            margin-right: 0;
        }
        
        .badge {
            font-size: 0.8em;
        }
        
        .modal-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }
        
        .card {
            border: none;
        }
        
        .card-body {
            padding: 2rem;
        }
    </style>
</x-app-layout>