<x-guest-layout>
    <!-- Form Header -->
    <div class="form-header">
        <h2 class="form-title">Access Terminal</h2>
        <!-- <p class="form-subtitle">Initialize neural network connection protocol</p> -->
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="alert alert-success mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate>
        @csrf

        <!-- Email Address -->
        <div class="form-group">
            <label for="email" class="form-label">Email</label>
            <input type="email" 
                   class="form-control @error('email') is-invalid @enderror" 
                   id="email" 
                   name="email" 
                   value="{{ old('email') }}" 
                   required 
                   autofocus 
                   autocomplete="username"
                   placeholder="Enter neural access ID">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <div class="input-group">
                <input type="password" 
                       class="form-control @error('password') is-invalid @enderror" 
                       id="password" 
                       name="password" 
                       required 
                       autocomplete="current-password"
                       placeholder="Enter matrix password">
                <button class="btn" type="button" onclick="toggleLoginPassword()" title="Toggle password visibility">
                    <i class="fas fa-eye" id="login-password-icon"></i>
                </button>
            </div>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Remember Me -->
        <!-- <div class="form-check">
            <input class="form-check-input" type="checkbox" id="remember_me" name="remember">
            <label class="form-check-label" for="remember_me">
                Maintain Connection
            </label>
        </div> -->

        <!-- Login Button -->
        <div class="form-group">
            <button type="submit" class="btn btn-primary" id="login-btn">
                Sign In
            </button>
        </div>
        
        <!-- Forgot Password Link -->
        <div class="text-center" style="margin: 1rem 0;">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">Forgot Password?</a>
            @endif
        </div>

        <!-- Auth Links -->
        <div class="auth-links">
            @if (Route::has('register'))
                <p>Need Access? <a href="{{ route('register') }}">Request Access</a></p>
            @endif
        </div>
    </form>

    <script>
        // Enhanced password toggle with smooth animation
        function toggleLoginPassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('login-password-icon');
            const button = icon.parentElement;
            
            // Add loading animation
            button.style.transform = 'scale(0.95)';
            
            setTimeout(() => {
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.className = 'fas fa-eye-slash';
                    button.title = 'Hide password';
                } else {
                    input.type = 'password';
                    icon.className = 'fas fa-eye';
                    button.title = 'Show password';
                }
                button.style.transform = 'scale(1)';
            }, 100);
        }

        // Enhanced form interactions
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('.needs-validation');
            const inputs = form.querySelectorAll('.form-control');
            const loginBtn = document.getElementById('login-btn');
            const btnText = loginBtn.querySelector('.btn-text');
            const spinner = loginBtn.querySelector('.spinner');
            
            // Enhanced input focus effects
            inputs.forEach(input => {
                const formGroup = input.closest('.form-group');
                
                input.addEventListener('focus', function() {
                    formGroup.classList.add('focused');
                    this.parentElement.style.transform = 'translateY(-2px)';
                });
                
                input.addEventListener('blur', function() {
                    formGroup.classList.remove('focused');
                    this.parentElement.style.transform = 'translateY(0)';
                });
                
                // Real-time validation feedback
                input.addEventListener('input', function() {
                    if (this.checkValidity()) {
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');
                    } else {
                        this.classList.remove('is-valid');
                    }
                });
            });
            
            // Enhanced form submission with loading state
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                    
                    // Shake animation for invalid form
                    form.style.animation = 'shake 0.5s ease-in-out';
                    setTimeout(() => {
                        form.style.animation = '';
                    }, 500);
                } else {
                    // Show loading state
                    loginBtn.classList.add('btn-loading');
                    btnText.classList.add('d-none');
                    spinner.classList.remove('d-none');
                    
                    // Disable form
                    inputs.forEach(input => input.disabled = true);
                }
                
                form.classList.add('was-validated');
            });
            
            // Auto-hide alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    if (alert && alert.parentElement) {
                        alert.style.opacity = '0';
                        alert.style.transform = 'translateY(-20px)';
                        setTimeout(() => {
                            alert.remove();
                        }, 300);
                    }
                }, 5000);
            });
        });
        
        // Add shake animation CSS
        const style = document.createElement('style');
        style.textContent = `
            @keyframes shake {
                0%, 100% { transform: translateX(0); }
                10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
                20%, 40%, 60%, 80% { transform: translateX(5px); }
            }
            
            .form-control.is-valid {
                border-color: #059669;
                background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23059669' d='m2.3 6.73.8-.77-.1-.1-1.39.15c-.07-.19-.24-.24-.36-.24l-.84.05c-.14.01-.25.13-.27.27l.24 1.67c.02.13.15.24.28.22l.84-.06c.13-.01.25-.13.27-.27l-.09-.13L2.7 6.4z'/%3e%3c/svg%3e");
                background-repeat: no-repeat;
                background-position: right calc(0.375em + 0.1875rem) center;
                background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
            }
        `;
        document.head.appendChild(style);
    </script>
</x-guest-layout>
