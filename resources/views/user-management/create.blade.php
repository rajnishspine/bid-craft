<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark">
            {{ __('Create New User') }}
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
                                    <i class="fas fa-user-plus me-2 text-primary"></i>
                                    Add New User
                                </h3>
                                <a href="{{ route('user-management.index') }}" 
                                   class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>
                                    Back to Users
                                </a>
                            </div>

                            <!-- Form -->
                            <form method="POST" action="{{ route('user-management.store') }}">
                                @csrf

                                <!-- Name -->
                                <div class="mb-3">
                                    <label for="name" class="form-label fw-medium">
                                        <i class="fas fa-user me-2"></i>Name
                                    </label>
                                    <input type="text" 
                                           name="name" 
                                           id="name" 
                                           value="{{ old('name') }}" 
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
                                           value="{{ old('email') }}" 
                                           required 
                                           class="form-control @error('email') is-invalid @enderror">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Role -->
                                <div class="mb-3">
                                    <label for="role" class="form-label fw-medium">
                                        <i class="fas fa-user-tag me-2"></i>Role
                                    </label>
                                    <select name="role" 
                                            id="role" 
                                            required 
                                            class="form-select @error('role') is-invalid @enderror">
                                        <option value="">Select a role</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->name }}" {{ old('role') === $role->name ? 'selected' : '' }}>
                                                {{ $role->name }}
                                                @if($role->name === 'Super Admin')
                                                    - (Full Access)
                                                @else
                                                    - (Limited Access)
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Password -->
                                <div class="mb-3">
                                    <label for="password" class="form-label fw-medium">
                                        <i class="fas fa-lock me-2"></i>Password
                                    </label>
                                    <div class="input-group">
                                        <input type="password" 
                                               name="password" 
                                               id="password" 
                                               required 
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
                                <div class="mb-4">
                                    <label for="password_confirmation" class="form-label fw-medium">
                                        <i class="fas fa-lock me-2"></i>Confirm Password
                                    </label>
                                    <div class="input-group">
                                        <input type="password" 
                                               name="password_confirmation" 
                                               id="password_confirmation" 
                                               required 
                                               class="form-control">
                                        <button type="button" 
                                                class="btn btn-outline-secondary"
                                                onclick="togglePassword('password_confirmation', 'passwordConfirmToggle')">
                                            <i id="passwordConfirmToggle" class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Role Information -->
                                <div class="alert alert-info">
                                    <h6 class="alert-heading">
                                        <i class="fas fa-info-circle me-2"></i>Role Information
                                    </h6>
                                    <div id="roleInfo" class="mb-0">
                                        Select a role to see its permissions
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="d-flex justify-content-end mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>
                                        Create User
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

        // Role information
        const roleDescriptions = {
            'Super Admin': 'Full access to all features including user management, role assignment, and system settings. Can view and manage all data.',
            'Team Member': 'Limited access to templates, bid recommendations, and AI requests. Can view own data and create new content but cannot manage users or system settings.'
        };

        document.getElementById('role').addEventListener('change', function() {
            const roleInfo = document.getElementById('roleInfo');
            const selectedRole = this.value;
            
            if (selectedRole && roleDescriptions[selectedRole]) {
                roleInfo.innerHTML = `<strong>${selectedRole}:</strong> ${roleDescriptions[selectedRole]}`;
            } else {
                roleInfo.textContent = 'Select a role to see its permissions';
            }
        });
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
    </style>
</x-app-layout>