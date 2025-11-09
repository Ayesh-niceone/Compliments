<aside class="left-sidebar">
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
            <a href="{{ route('dashboard') }}" class="text-nowrap logo-img">
                <img src="../assets/images/logos/logo.png" width="100" alt="" class="mx-5 mt-2" />
            </a>
            <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                <i class="ti ti-x fs-8"></i>
            </div>
        </div>

        <!-- Sidebar navigation -->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
            <ul id="sidebarnav">

                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">{{ __('Home') }}</span>
                </li>

                {{-- Dashboard --}}
                @can('view dashboard')
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="{{ route('dashboard') }}" aria-expanded="false">
                            <span><i class="ti ti-layout-dashboard"></i></span>
                            <span class="hide-menu">{{ __('Dashboard') }}</span>
                        </a>
                    </li>
                @endcan

                {{-- Compliments --}}
                @canany(['view compliments', 'create compliments', 'edit compliments', 'delete compliments'])
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="{{ route('compliments.index') }}" aria-expanded="false">
                            <span><i class="ti ti-gift"></i></span>
                            <span class="hide-menu">{{ __('Compliments') }}</span>
                        </a>
                    </li>
                @endcanany

                {{-- Departments --}}
                @canany(['view departments', 'create departments', 'edit departments', 'delete departments'])
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="{{ route('departments.index') }}" aria-expanded="false">
                            <span><i class="ti ti-building"></i></span>
                            <span class="hide-menu">{{ __('Departments') }}</span>
                        </a>
                    </li>
                @endcanany

                {{-- Statuses --}}
                @canany(['view statuses', 'create statuses', 'edit statuses', 'delete statuses'])
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="{{ route('statuses.index') }}" aria-expanded="false">
                            <span><i class="ti ti-checklist"></i></span>
                            <span class="hide-menu">{{ __('Statuses') }}</span>
                        </a>
                    </li>
                @endcanany

                {{-- Completion Types --}}
                @canany(['view completion_types', 'create completion_types', 'edit completion_types', 'delete completion_types'])
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="{{ route('completion_types.index') }}" aria-expanded="false">
                            <span><i class="ti ti-list"></i></span>
                            <span class="hide-menu">{{ __('Completion Types') }}</span>
                        </a>
                    </li>
                @endcanany

                {{-- Users --}}
                @canany(['view users', 'create users', 'edit users', 'delete users'])
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="{{ route('users.index') }}" aria-expanded="false">
                            <span><i class="ti ti-users"></i></span>
                            <span class="hide-menu">{{ __('Users') }}</span>
                        </a>
                    </li>
                @endcanany

                {{-- Roles --}}
                @canany(['view roles', 'create roles', 'edit roles', 'delete roles'])
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="{{ route('roles.index') }}" aria-expanded="false">
                            <span><i class="ti ti-shield"></i></span>
                            <span class="hide-menu">{{ __('Roles') }}</span>
                        </a>
                    </li>
                @endcanany

                {{-- Permissions --}}
                @canany(['view permissions', 'create permissions', 'edit permissions', 'delete permissions'])
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="{{ route('permissions.index') }}" aria-expanded="false">
                            <span><i class="ti ti-lock"></i></span>
                            <span class="hide-menu">{{ __('Permissions') }}</span>
                        </a>
                    </li>
                @endcanany

                {{-- Workers --}}
                @canany(['view workers', 'create workers', 'edit workers', 'delete workers'])
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="{{ route('workers.index') }}" aria-expanded="false">
                            <span><i class="ti ti-briefcase"></i></span>
                            <span class="hide-menu">{{ __('Workers') }}</span>
                        </a>
                    </li>
                @endcanany

                {{-- Settings --}}
                @canany(['view settings', 'edit settings'])
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="{{ route('settings.edit', ['id' => 1]) }}" aria-expanded="false">
                            <span><i class="ti ti-settings"></i></span>
                            <span class="hide-menu">{{ __('Settings') }}</span>
                        </a>
                    </li>
                @endcanany

            </ul>
        </nav>
    </div>
</aside>
