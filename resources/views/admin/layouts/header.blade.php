<!-- Top Bar Start -->
<div class="topbar d-print-none" style="background-color: #03436b !important ;">
    <div class="container-fluid">
        <nav class="topbar-custom d-flex justify-content-between" id="topbar-custom">

            <ul class="topbar-item list-unstyled d-inline-flex align-items-center mb-0">
                <li>
                    <button class="nav-link mobile-menu-btn nav-icon" id="togglemenu">
                        <i style="color: #ffffff !important" class="iconoir-menu"></i>
                    </button>
                </li>
            </ul>

            <style>
                @media (max-width: 767px) {
                    .mobile-buttons .btn {
                        padding: 0.15rem 0.3rem !important;
                        font-size: 0.75rem !important;
                    }
                    .mobile-buttons .btn i {
                        font-size: 0.9rem !important;
                    }
                    .mobile-buttons li {
                        margin: 0 2px !important;
                    }
                    /* Hide all buttons on mobile and tablet */
                    .mobile-buttons {
                        display: none !important;
                    }
                }
                @media (min-width: 768px) and (max-width: 991px) {
                    /* Hide all buttons on tablet */
                    .mobile-buttons {
                        display: none !important;
                    }
                }
            </style>

       

            <ul class="topbar-item list-unstyled d-inline-flex align-items-center mb-0">
                <li class="dropdown">
                    <!-- Language Switcher with Dropdown -->
                    <a class="nav-link dropdown-toggle arrow-none nav-icon" data-bs-toggle="dropdown" href="#"
                        role="button" aria-haspopup="true" aria-expanded="false">
                        <img src="{{ asset('assets/images/flags/' . (app()->getLocale() === 'en' ? 'us_flag.jpg' : 'iraq.png')) }}"
                            alt="{{ app()->getLocale() === 'en' ? 'English Flag' : 'Arabic Flag' }}"
                            class="thumb-sm rounded-circle">
                    </a>
                    <div class="dropdown-menu">
                        <form id="locale-form" method="POST" action="{{ route('setLocale') }}">
                            @csrf
                            <input type="hidden" id="locale-input" name="locale" value="">
                        </form>
                        <a class="dropdown-item" href="javascript:void(0)" onclick="submitLocaleForm('ar')">
                            <img src="{{ asset('assets/images/flags/iraq.png') }}" alt="Arabic Flag" height="15"
                                class="me-2">
                            {{ __('translations.arabic') }}
                        </a>
                        <a class="dropdown-item" href="javascript:void(0)" onclick="submitLocaleForm('en')">
                            <img src="{{ asset('assets/images/flags/us_flag.jpg') }}" alt="English Flag" height="15"
                                class="me-2">
                            {{ __('translations.english') }}
                        </a>

                    </div>
                </li>

                <script>
                    // JavaScript function to handle locale switching
                    function submitLocaleForm(locale) {
                        document.getElementById('locale-input').value = locale;
                        document.getElementById('locale-form').submit();
                    }
                </script>

                {{-- <li class="topbar-item">
                    <a class="nav-link nav-icon" href="javascript:void(0);" id="light-dark-mode">
                        <i class="iconoir-half-moon dark-mode"></i>
                        <i class="iconoir-sun-light light-mode"></i>
                    </a>
                </li> --}}

                <li class="dropdown topbar-item">
                    <a class="nav-link dropdown-toggle arrow-none nav-icon" data-bs-toggle="dropdown" href="#"
                        role="button" aria-haspopup="false" aria-expanded="false" data-bs-offset="0,19">
                        @if ($user->image)
                            <img src="{{ asset('storage/' . $user->image) }}" alt=""
                                class="thumb-md rounded-circle">
                        @else
                            <img src="{{ asset('assets/images/logo-sm.png') }}" alt=""
                                class="thumb-md rounded-circle">
                        @endif
                    </a>
                    <div class="dropdown-menu dropdown-menu-end py-0">
                        <div class="d-flex align-items-center dropdown-item py-2 bg-secondary-subtle">
                            <div class="flex-shrink-0">
                                @if ($user->image)
                                    <img src="{{ asset('storage/' . $user->image) }}" alt=""
                                        class="thumb-md rounded-circle">
                                @else
                                    <img src="{{ asset('assets/images/logo-sm.png') }}" alt=""
                                        class="thumb-md rounded-circle">
                                @endif
                            </div>
                            <div class="flex-grow-1 ms-2 text-truncate align-self-center">
                                <h6 class="my-0 fw-medium text-dark fs-13">{{ $user->name }}</h6>
                                <small class="text-muted mb-0"> {{ $user->role }} </small>
                            </div><!--end media-body-->
                        </div>
                        <div class="dropdown-divider mt-0"></div>
                        <a class="dropdown-item" href="{{ route('profile.index') }}"><i
                                class="las la-user fs-18 me-1 align-text-bottom"></i> Profile</a>
                        <div class="dropdown-divider mb-0"></div>
                        <form action="{{ route('logout') }}" method="POST" id="logout-form">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="las la-power-off fs-18 me-1 align-text-bottom"></i> Logout
                            </button>
                        </form>
                    </div>
                </li>
            </ul><!--end topbar-nav-->
        </nav>
        <!-- end navbar-->
    </div>
</div>
<!-- Top Bar End -->
