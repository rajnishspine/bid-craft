<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark">
            {{ __('Edit User') }}
        </h2>
    </x-slot>

    <div class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <!-- Header -->
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h3 class="card-title mb-0">
                                    <i class="fas fa-user-edit me-2 text-primary"></i>
                                    Edit User: {{ $user->name }}
                                </h3>
                                <a href="{{ route('user-management.index') }}" 
                                   class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>
                                    Back to Users
                                </a>
                            </div>

                            <!-- Form -->
                            <form method="POST" action="{{ route('user-management.update', $user) }}">
                                @csrf
                                @method('PUT')

                                <!-- Name -->
                                <div class="mb-3">
                                    <label for="name" class="form-label fw-medium">
                                        <i class="fas fa-user me-2"></i>Name
                                    </label>
                                    <input type="text" 
                                           name="name" 
                                           id="name" 
                                           value="{{ old('name', $user->name) }}" 
                                           required 
                                           class="form-control @error('name') is-invalid @enderror">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="mb-3">
                                    <label for="email" class="form-label fw-medium">
                                        <i class="fas fa-envelope me-2"></i>Email Address
                                    </label>
                                    <input type="email" 
                                           name="email" 
                                           id="email" 
                                           value="{{ old('email', $user->email) }}" 
                                           required 
                                           class="form-control @error('email') is-invalid @enderror">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Current Roles -->
                                <div class="mb-4">
                                    <label class="form-label fw-medium">
                                        <i class="fas fa-user-tag me-2"></i>Current Roles
                                    </label>
                                    <div class="border rounded p-3 bg-light">
                                        @if($user->roles->count() > 0)
                                            @foreach($user->roles as $role)
                                                <span class="badge bg-primary me-2 mb-2">
                                                    @if($role->name === 'Super Admin')
                                                        <i class="fas fa-crown me-1"></i>
                                                    @else
                                                        <i class="fas fa-user-tag me-1"></i>
                                                    @endif
                                                    {{ $role->name }}
                                                    <button type="button" 
                                                            class="btn-close btn-close-white ms-2" 
                                                            onclick="removeRole({{ $role->id }}, '{{ $role->name }}')"
                                                            style="font-size: 0.7em;"></button>
                                                </span>
                                            @endforeach
                                        @else
                                            <span class="text-muted fst-italic">No roles assigned</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Assign New Role -->
                                <div class="mb-4">
                                    <label for="new_role" class="form-label fw-medium">
                                        <i class="fas fa-plus-circle me-2"></i>Assign New Role
                                    </label>
                                    <div class="input-group">
                                        <select id="new_role" class="form-select">
                                            <option value="">Select a role to assign</option>
                                            @foreach($roles as $role)
                                                @if(!$user->hasRole($role->name))
                                                    <option value="{{ $role->id }}">
                                                        {{ $role->name }}
                                                        @if($role->name === 'Super Admin')
                                                            - (Full Access)
                                                        @else
                                                            - (Limited Access)
                                                        @endif
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <button type="button" 
                                                class="btn btn-primary" 
                                                onclick="assignRole()">
                                            <i class="fas fa-plus me-1"></i>
                                            Assign
                                        </button>
                                    </div>
                                </div>

                                <!-- Password Section -->
                                <div class="card border-0 bg-light mb-4">
                                    <div class="card-body">
                                        <h6 class="card-title">
                                            <i class="fas fa-lock me-2"></i>Change Password (Optional)
                                        </h6>
                                        <p class="text-muted small mb-3">Leave blank to keep current password</p>

                                        <!-- New Password -->
                                        <div class="mb-3">
                                            <label for="password" class="form-label">New Password</label>
                                            <div class="input-group">
                                                <input type="password" 
                                                       name="password" 
                                                       id="password" 
                                                       class="form-control @error('password') is-invalid @enderror">
                                                <button type="button" 
                                                        class="btn btn-outline-secondary"
                                                        onclick="togglePassword('password', 'passwordToggle')">
                                                    <i id="passwordToggle" class="fas fa-eye"></i>
                                                </button>
                                                @error('password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Confirm Password -->
                                        <div class="mb-0">
                                            <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                            <div class="input-group">
                                                <input type="password" 
                                                       name="password_confirmation" 
                                                       id="password_confirmation" 
                                                       class="form-control">
                                                <button type="button" 
                                                        class="btn btn-outline-secondary"
                                                        onclick="togglePassword('password_confirmation', 'passwordConfirmToggle')">
                                                    <i id="passwordConfirmToggle" class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- User Information -->
                                <div class="alert alert-info">
                                    <h6 class="alert-heading">
                                        <i class="fas fa-info-circle me-2"></i>User Information
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>Created:</strong> {{ $user->created_at->format('M d, Y') }}</p>
                                            <p class="mb-1"><strong>Last Updated:</strong> {{ $user->updated_at->format('M d, Y') }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>Email Verified:</strong> 
                                                @if($user->email_verified_at)
                                                    <span class="badge bg-success">Yes</span>
                                                @else
                                                    <span class="badge bg-warning">No</span>
                                                @endif
                                            </p>
                                            <p class="mb-0"><strong>Status:</strong> 
                                                <span class="badge bg-success">Active</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('user-management.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-2"></i>
                                        Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>
                                        Update User
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        function assignRole() {
            const roleSelect = document.getElementById('new_role');
            const roleId = roleSelect.value;
            
            if (!roleId) {
                alert('Please select a role to assign');
                return;
            }

            console.log('Assigning role ID:', roleId);

            fetch(`/user-management/{{ $user->id }}/assign-role`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ role_id: roleId })
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert(data.message || 'Error assigning role');
                }
            })
            .catch(error => {
                console.error('Error details:', error);
                alert('Error assigning role: ' + error.message);
            });
        }

        function removeRole(roleId, roleName) {
            if (confirm(`Remove role "${roleName}" from this user?`)) {
                fetch(`/user-management/{{ $user->id }}/remove-role`, {
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
                        alert(data.message || 'Error removing role');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error removing role');
                });
            }
        }
    </script>

    <style>
        .card {
            border: none;
        }
        
        .form-label {
            color: #495057;
        }
        
        .btn-outline-secondary {
            border-color: #ced4da;
        }
        
        .alert-heading {
            font-weight: 600;
        }
        
        .input-group .btn {
            border-left: none;
        }
        
        .badge {
            font-size: 0.8em;
        }
        
        .btn-close-white {
            filter: invert(1) grayscale(100%) brightness(200%);
        }
    </style>
</x-app-layout>