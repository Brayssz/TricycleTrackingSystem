<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0" />
    <meta name="keywords"
        content="admin, estimates, bootstrap, business, corporate, creative, management, minimal, modern,  html5, responsive" />
    <meta name="robots" content="noindex, nofollow" />
    <title>@yield('title', 'Tricycle Tracking')</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('') }}" type="image/x-icon" />

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <!-- FontAwesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Select2 CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">

    <!-- Datatable CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <!-- Datetimepicker CSS -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/6.3.3/css/bootstrap-datetimepicker.min.css">

    <!-- Toatr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.17/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://unpkg.com/tippy.js@6/dist/tippy.css">

    @vite(['resources/assets/css/style.css', 'resources/assets/css/sidebar.css'])
    {{-- @livewireStyles<!-- Styles - --}}


</head>

<body>
    <div id="global-loader">
        <span class="loader"></span>
    </div>

    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <!-- Header -->
        <div class="header">
            <!-- Logo -->
            <div class="header-left active">
                <a href="index.html" class="logo logo-normal">
                    <div class="d-flex align-items-center">
                        <img src="" alt="" class="me-2" alt="Logo"
                            style="max-height: 40px; max-width: 40px;" />
                        <div class="text-start">
                            <h5 class="mb-2">Real-Time Tricycle </h5>
                            <p class="mb-0" style="font-size: 12px;">Tracking System</p>
                        </div>
                    </div>


                </a>
                <a href="index.html" class="logo logo-white">
                    <img src="img/logo.jpg" alt="" style="min-height: 40px; min-width: 40px;" />
                </a>
                <a href="index.html" class="logo-small">
                    <img src="img/logo.jpg" alt="" style="min-height: 40px; min-width: 40px;" />
                </a>
                <a id="toggle_btn" href="javascript:void(0);">
                    <i data-feather="chevrons-left" class="feather-16"></i>
                </a>
            </div>
            <!-- /Logo -->

            <a id="mobile_btn" class="mobile_btn" href="#sidebar">
                <span class="bar-icon">
                    <span></span>
                    <span></span>
                    <span></span>
                </span>
            </a>

            <!-- Header Menu -->
            <ul class="nav user-menu">
                <!-- Search -->
                <li class="nav-item nav-searchinputs">
                    <div class="top-nav-search">

                    </div>
                </li>
                <li class="nav-item nav-item-box">
                    <a href="javascript:void(0);" id="btnFullscreen">
                        <i data-feather="maximize"></i>
                    </a>
                </li>


                <li class="nav-item dropdown has-arrow main-drop">
                    <a href="javascript:void(0);" class="dropdown-toggle nav-link userset" data-bs-toggle="dropdown">
                        <span class="user-info">
                            <span class="user-letter">
                                <span class="avatar avatar bg-success h-100">
                                    <span
                                        class="avatar-title">{{ strtoupper(substr(Auth::user()->name, 0, 1)) . strtoupper(substr(explode(' ', Auth::user()->name)[1], 0, 1)) }}</span>
                                </span>
                            </span>
                            <span class="user-detail">
                                <span class="avatar-title">{{ strtoupper(Auth::user()->name) }}</span>

                                <span class="user-role">{{ ucfirst(Auth::user()->position) }}</span>


                            </span>
                        </span>
                    </a>
                    <div class="dropdown-menu menu-drop-user">
                        <div class="profilename">
                            <div class="profileset">
                                <span class="user-img">
                                    <span class="avatar avatar-md bg-success">
                                        <span
                                            class="avatar-title">{{ strtoupper(substr(Auth::user()->name, 0, 1)) . strtoupper(substr(explode(' ', Auth::user()->name)[1], 0, 1)) }}</span>
                                    </span>

                                    <span class="status online"></span>
                                </span>
                                <div class="profilesets">
                                    <h6>{{ strtoupper(Auth::user()->name) }}
                                    </h6>
                                    <h5>{{ ucfirst(Auth::user()->position) }}</h5>

                                </div>
                            </div>
                            <hr class="m-0" />
                            <a class="dropdown-item" href="/profile">
                                <i class="me-2" data-feather="user"></i> My Profile</a>
                            <a href="#" class="dropdown-item logout pb-0"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <img src="img/icons/log-out.svg" class="me-2" alt="img" />Logout
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </div>
                </li>
            </ul>
            <!-- /Header Menu -->

            <!-- Mobile Menu -->
            <div class="dropdown mobile-user-menu">
                <a href="javascript:void(0);" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"
                    aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="profile">My Profile</a>
                    <a class="dropdown-item logout"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                </div>
            </div>
            <!-- /Mobile Menu -->
        </div>
        <!-- /Header -->


        <div class="sidebar" id="sidebar">
            <div class="sidebar-inner slimscroll">
                <div id="sidebar-menu" class="sidebar-menu">
                    <ul>
                        <li class="submenu-open">
                            <h6 class="submenu-hdr">Main</h6>
                            <ul>
                                <li class="{{ Request::is('dashboard') ? 'active' : '' }}">
                                    <a href="/dashboard"><i data-feather="grid"></i><span>Dashboard</span></a>
                                </li>
                            </ul>
                        </li>
                        @if (Auth::user()->position == 'admin')
                            <li class="submenu-open">
                                <h6 class="submenu-hdr">Management</h6>
                                <ul>
                                    <li class="{{ Request::is('users') ? 'active' : '' }}">
                                        <a href="/users"><i data-feather="user"></i><span>Users</span></a>
                                    </li>
                                    <li class="{{ Request::is('drivers') ? 'active' : '' }}">
                                        <a href="/drivers"><i data-feather="users"></i><span>Drivers</span></a>
                                    </li>
                                    <li class="{{ Request::is('tricycles') ? 'active' : '' }}">
                                        <a href="/tricycles"><i
                                                data-feather="briefcase"></i><span>Tricycles</span></a>
                                    </li>
                                    
                                </ul>
                            </li>
                        @endif

                     

                    </ul>
                </div>
            </div>
        </div>

        <div class="page-wrapper">
            @yield('content')
        </div>

        {{-- @if (Auth::user()->position == 'admin')
            
        @else
            <div class="page-wrapper mx-3">
                @yield('content')
            </div>
        @endif --}}


        {{-- @livewire('content.layout') --}}

    </div>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- FontAwesome JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"></script>

    <!-- Select2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <!-- Datatable JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <!-- Moment.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment.min.js"></script>

    <!-- Datetimepicker JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/6.3.3/js/bootstrap-datetimepicker.min.js">
    </script>


    <script>
        localStorage.clear();
    </script>

    <!-- Feather Icon JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.29.1/feather.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="assets/plugins/apexchart/chart-data.js"></script>

    <!-- Slimscroll JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-slimScroll/1.3.8/jquery.slimscroll.min.js"></script>

    <!-- Sweetalert 2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script src="assets/plugins/apexchart/chart-data.js"></script>
    <!-- Mask JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.4.1/jquery.maskedinput.min.js"></script>

    <!-- Chart JS (Toastr) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.17/dist/sweetalert2.all.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/number-to-words"></script>

    <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

    <script src="https://unpkg.com/@popperjs/core@2/dist/umd/popper.min.js"></script>
    <script src="https://unpkg.com/tippy.js@6/dist/tippy-bundle.umd.js"></script>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>


    @vite(['resources/assets/js/script.js', 'resources/assets/js/custom-select2.js', 'resources/assets/js/mask.js', 'resources/assets/js/theme-script.js', 'resources/assets/js/mask.js'])
    @livewireScripts

    @stack('scripts')
</body>

</html>
