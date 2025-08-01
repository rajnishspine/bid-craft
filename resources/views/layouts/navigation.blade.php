<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <!-- Brand -->
        <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
            <i class="fas fa-file-contract me-2"></i>
            {{ config('app.name', 'BidCraft') }}
        </a>

        <!-- Mobile Toggle -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navigation Links -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Left Side -->
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="fas fa-tachometer-alt me-1"></i>
                        Dashboard
                    </a>
                </li>
                @can('view templates')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('templates.index') ? 'active' : '' }}" href="{{ route('templates.index') }}">
                        <i class="fas fa-file-alt me-1"></i>
                        Manage Templates
                    </a>
                </li>
                @endcan
                @can('view bid recommendations')
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('bid-recommendations.*') ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-brain me-1"></i>
                        Manage BidCraft
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a target="_blank" class="dropdown-item {{ request()->routeIs('bid-recommendations.index') ? 'active' : '' }}" href="{{ route('bid-recommendations.index') }}">
                                <i class="fas fa-calculator me-2"></i>
                                New Analysis
                            </a>
                        </li>
                    </ul>
                </li>
                @endcan
                @can('view users')
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('user-management.*') || request()->routeIs('permission-management.*') ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-users-cog me-1"></i>
                        Administration
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('user-management.*') ? 'active' : '' }}" href="{{ route('user-management.index') }}">
                                <i class="fas fa-users me-2"></i>
                                User Management
                            </a>
                        </li>
                        @can('view permissions')
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('permission-management.*') ? 'active' : '' }}" href="{{ route('permission-management.index') }}">
                                <i class="fas fa-key me-2"></i>
                                Permission Management
                            </a>
                        </li>
                        @endcan
                        @can('view templates')
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('templates.*') ? 'active' : '' }}" href="{{ route('templates.index') }}">
                                <i class="fas fa-file-alt me-2"></i>
                                Template Management
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcan
            </ul>

            <!-- Right Side -->
            <ul class="navbar-nav">
                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-2"></i>
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="fas fa-user-edit me-2"></i>
                                    Profile
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}">
                                    <i class="fas fa-sign-out-alt me-2"></i>
                                    Log Out
                                </a>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt me-1"></i>
                            Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">
                            <i class="fas fa-user-plus me-1"></i>
                            Register
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
