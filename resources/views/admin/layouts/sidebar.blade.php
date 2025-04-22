<div class="startbar d-print-none">
    <!-- Brand Logo -->
    <div class="brand py-4 text-center ">
        <a href="{{ route('dashboard') }}" class="logo">
            @if ($setting->logo)
                <img src="{{ asset('storage/' . $setting->logo) }}" style="height: 65px" alt="logo"
                    class="logo-light img-fluid">
            @else
                <img src="{{ asset('assets/images/logo-sm.png') }}" style="height: 65px" alt="logo-large"
                    class="logo-light img-fluid">
            @endif
        </a>
    </div>

    <!-- Sidebar Content -->
    <div class="startbar-menu bg-gradient-dark">
        <div class="startbar-collapse" id="startbarCollapse" data-simplebar>
            <div class="d-flex align-items-start flex-column w-100 py-2">

                <!-- Main Navigation -->
                <ul class="navbar-nav w-100 mb-2">


                    @permission('dashboard-list')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard') }}">
                                <i class="fas fa-tachometer-alt me-2"></i>
                                <span>{{ __('admin.dashboard') }}</span>
                            </a>
                        </li>
                    @endpermission
                </ul>








                <!-- User Management -->
                @php
                    $hasUserManagementPermission =
                        auth()->user()->hasPermission('roles-list') || auth()->user()->hasPermission('users-list');
                @endphp

                @if ($hasUserManagementPermission)
                    <ul class="navbar-nav w-100 mb-2">
                        <li class="menu-category">
                            <span>{{ __('admin.user_management') }}</span>
                        </li>

                        @permission('roles-list')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('roles.index') }}">
                                    <i class="fas fa-user-shield me-2"></i>
                                    <span>{{ __('admin.roles') }}</span>
                                </a>
                            </li>
                        @endpermission


                        @permission('users-list')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('users.index') }}">
                                    <i class="fas fa-user-plus me-2"></i>
                                    <span>{{ __('admin.users') }}</span>
                                </a>
                            </li>
                        @endpermission
                    </ul>
                @endif






                @php
                    $hasServicesPermission =
                        auth()->user()->hasPermission('cleaners-list') || auth()->user()->hasPermission('customers-list');
                @endphp

                @if ($hasServicesPermission)
                    <ul class="navbar-nav w-100 mb-2">
                        <li class="menu-category">
                            <span>{{ __('admin.services') }}</span>
                        </li>

                        @permission('cleaners-list')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('cleaners.index') }}">
                                    <i class="fas fa-broom me-2"></i>
                                    <span>{{ __('admin.cleaners') }}</span>
                                </a>
                            </li>
                        @endpermission

                        @permission('customers-list')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('customers.index') }}">
                                    <i class="fas fa-user-tie me-2"></i>
                                    <span>{{ __('admin.customers') }}</span>
                                </a>
                            </li>
                        @endpermission
                    </ul>
                @endif


                <!-- License Management -->
                @php
                    $hasLicensePermission =
                        auth()->user()->hasPermission('products-list') ||
                        auth()->user()->hasPermission('licenses-list') ||
                        auth()->user()->hasPermission('license-docs-access');
                @endphp

                @if ($hasLicensePermission)
                    <ul class="navbar-nav w-100 mb-2">
                        <li class="menu-category">
                            <span>إدارة التراخيص</span>
                        </li>

                        @permission('categories-list')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.categories.index') }}">
                                    <i class="fas fa-sitemap me-2"></i>
                                    <span>تصنيفات المنتجات</span>
                                </a>
                            </li>
                        @endpermission

                        @permission('products-list')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.products.index') }}">
                                    <i class="fas fa-box me-2"></i>
                                    <span>المنتجات</span>
                                </a>
                            </li>
                        @endpermission


                        @permission('licenses-list')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.licenses.index') }}">
                                    <i class="fas fa-key me-2"></i>
                                    <span>التراخيص</span>
                                </a>
                            </li>
                        @endpermission

                        @permission('license-docs-access')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('integration-docs') }}">
                                    <i class="fas fa-file-code me-2"></i>
                                    <span>وثائق التكامل</span>
                                </a>
                            </li>
                        @endpermission

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('secure-connection-demo') }}">
                                <i class="fas fa-shield-alt me-2"></i>
                                <span>توصيل آمن</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('simple-integration-guide') }}">
                                <i class="fas fa-plug me-2"></i>
                                <span>دليل الربط السريع</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('installation.guide') }}">
                                <i class="fas fa-book me-2"></i>
                                <span>دليل تركيب نظام الحماية</span>
                            </a>
                        </li>
                    </ul>
                @endif


                <!-- Configuration -->
                @php
                    $hasSettingsPermission = auth()->user()->hasPermission('settings');
                @endphp

                @if ($hasSettingsPermission)
                    <ul class="navbar-nav w-100 mb-2">
                        <li class="menu-category">
                            <span>{{ __('admin.configuration') }}</span>
                        </li>

                        @permission('settings')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.settings.index') }}">
                                    <i class="fas fa-cogs me-2"></i>
                                    <span>{{ __('admin.settings') }}</span>
                                </a>
                            </li>
                        @endpermission

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('package.development') }}">
                                <i class="fas fa-box-open me-2"></i>
                                <span>تطوير الحزم</span>
                            </a>
                        </li>
                    </ul>
                @endif

            </div>
        </div>
    </div>

    <style>
        /* Modern Sidebar Styles */
        .startbar {
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }



        .startbar .brand {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .menu-category {
            padding: 10px 15px;
            margin-top: 10px;
            text-transform: uppercase;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.5px;
            color: #90a4ae;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        /* Hide menu-category when sidebar is collapsed */
        body[data-sidebar-size="collapsed"] .startbar:not(:hover) .menu-category {
            display: none;
        }

        .navbar-nav .nav-item .nav-link {
            color: #ecf0f1;
            padding: 8px 15px;
            border-radius: 5px;
            margin: 2px 10px;
            transition: all 0.3s;
        }

        .navbar-nav .nav-item .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        .navbar-nav .nav-item .nav-link.active {
            background-color: rgba(255, 255, 255, 0.15);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            color: #fff;
        }

        .sub-menu {
            border-radius: 5px;
            margin: 0 15px;
            padding: 5px;
        }

        .sub-menu .nav-link {
            padding: 6px 15px !important;
            font-size: 0.9rem;
            color: #ddd !important;
        }

        .sub-menu .nav-link:hover {
            color: #fff !important;
        }

        .has-arrow:after {
            content: "\f054";
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            position: absolute;
            right: 15px;
            font-size: 10px;
            top: 50%;
            transform: translateY(-50%);
            transition: transform 0.3s;
        }

        [aria-expanded="true"].has-arrow:after {
            transform: translateY(-50%) rotate(90deg);
        }
    </style>
</div>
<div class="startbar-overlay d-print-none"></div>
