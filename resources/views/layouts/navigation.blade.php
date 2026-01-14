<nav x-data="{ open: false }">
    <div class="navbar-custom">
        <!-- Logo -->
        <div>
            <a href="{{ route('dashboard') }}" class="navbar-brand">
                <span>Bitcar</span>
            </a>
        </div>

        <!-- Navigation Links -->
        <div class="navbar-links">
            <a href="{{ route('dashboard') }}" class="navbar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-house-door fw-bold"></i>
                <span> Inicio </span>
            </a>
            
            <a href="{{ route('users.index') }}" class="navbar-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <i class="bi bi-people fw-bold"></i>
                <span> Personal </span>
            </a>
            
            <a href="{{ route('vehicles.index') }}" class="navbar-link {{ request()->routeIs('vehicles.*') ? 'active' : '' }}">
                <i class="bi bi-truck fw-bold"></i>
                <span> Unidades </span>
            </a>
            
            <a href="#" class="navbar-link">
                <i class="bi bi-gear fw-bold"></i>
                <span> Configuración </span>
            </a>
        </div>

        <!-- Settings Dropdown -->
        <div class="navbar-user">
            <x-dropdown align="right">
                <x-slot name="trigger">
                    <button class="navbar-user-button">
                        <i class="bi bi-person-fill"></i>
                        <i class="bi bi-caret-down-fill ml-1"></i>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <div style="padding: 0.75rem 1rem; border-bottom: 1px solid #e5e7eb;">
                        <p style="font-size: 0.875rem; font-weight: 500; color: #111827;">{{ Auth::user()->name }}</p>
                        <p style="font-size: 0.75rem; color: #6b7280;">{{ Auth::user()->email }}</p>
                    </div>
                    
                    <x-dropdown-link :href="route('profile.edit')">
                        <i class="bi bi-person-fill" style="width: 1rem; height: 1rem; margin-right: 0.5rem; display: inline; color: #6b7280;"></i>
                        Perfil
                    </x-dropdown-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                            <i class="bi bi-box-arrow-right" style="width: 1rem; height: 1rem; margin-right: 0.5rem; display: inline; color: #6b7280;"></i>
                            Cerrar Sesión
                        </x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        </div>

        <!-- Mobile Toggle -->
            <button @click="open = ! open" class="navbar-mobile-toggle">
                <svg stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{'hidden': open, 'inline-flex': ! open }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
    </div>

    <!-- Mobile Menu -->
    <div :class="{'open': open}" class="navbar-mobile-menu">
        <div class="navbar-mobile-links">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                Dashboard
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('vehicles.index')" :active="request()->routeIs('vehicles.*')">
                Vehículos
            </x-responsive-nav-link>
            @if(auth()->user()->isAdmin() || auth()->user()->isSupervisor())
                <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                    Usuarios
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('checklists.index')" :active="request()->routeIs('checklists.*')">
                    Checklists
                </x-responsive-nav-link>
            @endif
            <x-responsive-nav-link :href="route('vehicle-logs.index')" :active="request()->routeIs('vehicle-logs.*')">
                Bitácoras
            </x-responsive-nav-link>
        </div>

        <div class="navbar-mobile-user">
            <div class="navbar-mobile-user-info">
                <div class="navbar-mobile-user-name">{{ Auth::user()->name }}</div>
                <div class="navbar-mobile-user-email">{{ Auth::user()->email }}</div>
                <div class="navbar-mobile-user-role">{{ ucfirst(auth()->user()->role) }}</div>
            </div>

            <div class="navbar-mobile-user-links">
                <x-responsive-nav-link :href="route('profile.edit')">
                    Perfil
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                        Cerrar Sesión
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
