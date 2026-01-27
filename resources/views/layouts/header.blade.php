    <!--  Header Start -->
      <header class="app-header">
        <nav class="navbar navbar-expand-lg navbar-light">
          <ul class="navbar-nav">
            <li class="nav-item d-block d-xl-none">
              <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
                <i class="ti ti-menu-2"></i>
              </a>
            </li>


          </ul>
          <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
            <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
                            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownNotifications" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="ti ti-bell"></i>
                    <span class="badge bg-danger" id="notif-count">{{ auth()->user()->unreadNotifications->count() }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownNotifications" style="width: 300px;">
                    <li class="dropdown-header"><i class="ti ti-bell"></i></li>
                    <div id="notif-list">
                        @forelse(auth()->user()->unreadNotifications as $notification)
                            <li>
                                <a href="/compliments/{{$notification->data['data']['id']}}" class="dropdown-item notif-item" data-id="{{ $notification->id }}">
                                    {{ $notification->data['message'] }}
                                    <br>
                                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                </a>
                            </li>
                        @empty
                            <li><span class="dropdown-item text-muted">No new notifications</span></li>
                        @endforelse
                    </div>
                    <li><hr class="dropdown-divider"></li>
                    <li><a href="{{ route('notifications.index') }}" class="dropdown-item text-center">View All</a></li>
                </ul>
            </li>
            <li class="nav-item dropdown me-3">
                <a class="nav-link dropdown-toggle nav-icon-hover" href="javascript:void(0)"
                id="langDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="ti ti-language"></i>
                    <span class="ms-1 fw-bold">
                        {{ app()->getLocale() == 'ar' ? 'العربية' : 'English' }}
                    </span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="langDropdown">
                    @if(app()->getLocale() == 'ar')
                        <li>
                            <a class="dropdown-item text-center fw-bold" href="{{ url('change-language/en') }}">
                                🇬🇧 English
                            </a>
                        </li>
                    @else
                        <li>
                            <a class="dropdown-item text-center fw-bold" href="{{ url('change-language/ar') }}">
                                🇸🇦 العربية
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
              <li class="nav-item dropdown">
                <a class="nav-link nav-icon-hover" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown"
                  aria-expanded="false">
                  <img src="../assets/images/profile/no-image.jpg" alt="" width="35" height="35" class="rounded-circle">
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                  <div class="message-body">
                    <a href="{{ route('profile.edit') }}" class="dropdown-item">
                        <i class="ti ti-user me-2"></i>{{ __('Profile') }}
                    </a>
                    <a href="{{ route('logout') }}" class="btn btn-outline-primary mx-3 mt-2 d-block">{{ __('Logout') }}</a>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </nav>
      </header>
