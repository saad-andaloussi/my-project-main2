<nav x-data="{ open: false }" style="background: #fff; border-bottom: 1px solid var(--border-color);">
    <!-- Primary Navigation Menu -->
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 1rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; height: 64px;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <!-- Logo -->
                <div style="display: flex; align-items: center;">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="nav-menu">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="user-menu">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="btn btn-secondary" style="background: none; color: var(--text-color); padding: 0.5rem;">
                            <div>{{ Auth::user()->name }}</div>

                            <div>
                                <svg style="height: 1rem; width: 1rem;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div style="display: none;">
                <button @click="open = ! open" style="background: none; border: 1px solid var(--border-color); padding: 0.5rem; border-radius: 4px;">
                    <svg style="height: 24px; width: 24px;" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" style="display: none;">
        <div style="padding: 0.5rem 0;">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div style="padding: 1rem 0; border-top: 1px solid var(--border-color);">
            <div style="padding: 0 1rem;">
                <div style="font-weight: 600;">{{ Auth::user()->name }}</div>
                <div style="font-size: 0.9rem; color: var(--secondary-color);">{{ Auth::user()->email }}</div>
            </div>

            <div style="margin-top: 0.5rem;">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
