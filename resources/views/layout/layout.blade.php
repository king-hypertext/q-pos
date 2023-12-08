<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="{{ url('logo.png') }}" type="image/x-icon" />
    <link rel="apple-touch-icon" href="{{ url('logo.png') }}" />
    <link rel="stylesheet" href="{{ url('assets/fonts/font-awesome/css/all.min.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/plugins/Waves/waves.min.css') }}" />
    <link rel="stylesheet"
        href="{{ asset('assets/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2-bootstrap-5-theme.css') }}">
    <link rel="stylesheet" href="{{ url('assets/plugins/datatables/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ url('assets/plugins/bootstrap-datepicker/css/bootstrap-datetimepicker.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/plugins/bootstrap-daterangepicker/daterangepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/alert/sweetalert2.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/plugins/mdb/mdb.min.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/css/bootstrap.css') }}" />
    <link rel="stylesheet" href="{{ url('assets/css/index.css') }}">
    <script src="{{ asset('assets/plugins/alert/sweetalert2.all.min.js') }}"></script>
    <script src="{{ url('assets/plugins/jquery-ui-1.13.2/external/jquery/jquery.js') }}"></script>
    <title>Q-POS | {{ $title ?? '' }}</title>
</head>

<body class="overflow-x-hidden">
    <div class="loader-bg">
        <div class="loader-bar">
        </div>
    </div>
    <div class="main-content">
        <aside class="sidebar fixed-top bg-light text-dark" id="sidebar">
            <div class="sidebar-wrapper">
                <ul class="list-unstyled">
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" title="Dashboard" class="link nav-link">
                            <i class="fas fa-chevron-right"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('products') }}" title="Inventories" class="link nav-link">
                            <i class="fas fa-chevron-right"></i>
                            <span>Inventories</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('customers') }}" title="Workers Van" class="link nav-link">
                            <i class="fas fa-chevron-right"></i>
                            <span>Workers Van</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('suppliers') }}" title="Suppliers" class="link nav-link">
                            <i class="fas fa-chevron-right"></i>
                            <span>Suppliers</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('orders') }}" title="Oders" class="link nav-link">
                            <i class="fas fa-chevron-right"></i>
                            <span>Orders</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('returns') }}" title="Returns" class="link nav-link">
                            <i class="fas fa-chevron-right"></i>
                            <span>Returns</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('settings') }}" class="link nav-link">
                            <i class="fas fa-chevron-right"></i>
                            <span>Settings</span>
                        </a>
                    </li>
                </ul>
                <div class="log-status-wrapper">
                    <h4 class="log-status-header text-center h6 p-2">
                        Login Status
                    </h4>
                    <ul class="list-unstyled">
                        <li><span class="fw-semibold"> Login As: </span>
                            <span class="text-capitalize"> {{ auth()->user()->name }}</span>
                        </li>
                        <li><span class="fw-semibold"> User Type: </span>
                            {{ auth()->user()->admin == 1 ? 'Admin' : '' }}
                        </li>
                        <li><span class="fw-semibold"> Since: </span> {{ auth()->user()->login }}</li>
                        <li><span class="fw-semibold"> Last Logout: </span> {{ auth()->user()->logout }}</li>
                    </ul>
                </div>
            </div>
        </aside>
        <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark p-0">
            <div class="container-fluid">
                <a href="javascript:void(0)" class="btn d- d-md-none" data-toggle-nav="sidebar" type="button">
                    <span id="x-toggle" class="fas text-white fa-bars"></span>
                </a>
                <div class="brand-wrapper">
                    <a href="#" class="navbar-brand">
                        <img class="" alt="">Q-POS </a>
                </div>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <ul class="navbar-nav ms-2">
                        <form class="d-flex my-0 py-0" role="search" action="{{ url('/search') }}" method="GET">
                            <input required name="q" class="form-control form-control-lg form-search me-2"
                                type="search" placeholder="Search for products, customers ..." aria-label="Search">
                            <button class="btn btn-outline-light p-1" title="Search" type="submit"><i
                                    class="fas fa-magnifying-glass"></i></button>
                        </form>
                    </ul>
                </div>
                <div class="dropdown">
                    <a href="javascript:void(0)" class="nav-link" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        @if (auth()->user()->user_image == '')
                            <img src="{{ asset('assets/images/avatar-1.png') }}" alt="product-image"
                                class="img-user" />
                        @else
                            <img src="{{ asset('assets/images/admin/' . auth()->user()->user_image) }}"
                                alt="product-image" class="img-user" />
                        @endif
                        <span class="text-white text-capitalize">{{ auth()->user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu user-nav shadow outline-0 border-0">
                        <li>
                            <h6 class="dropdown-header">Hello, <span
                                    class="text-capitalize">{{ auth()->user()->name }}</span>!
                            </h6>
                        </li>
                        <li><a class="dropdown-item" href="{{ route('dashboard') }}">Notifications</a></li>
                        <li><a class="dropdown-item" href="{{ route('settings') }}">Settings</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <button type="submit" class="btn  dropdown-item shadow-none" data-bs-toggle="modal"
                                data-bs-target="#modal-logout"><i class="fas fa-"></i>
                                <span>Log Out</span>
                            </button>
                        </li>
                    </ul>
                </div>
                <style>
                    .dropdown-menu[data-bs-popper] {
                        right: 0;
                        left: auto;
                        transition: all 0.3s ease-out;
                    }

                    .dropdown-menu.user-nav {
                        right: -15px !important;
                        top: 55px !important;
                    }

                    .dropdown-menu.user-nav.show {
                        border-radius: 0;
                        transition: all 0.3s ease-out;
                    }
                </style>
            </div>
        </nav>
        <div class="container-fluid">
            @yield('content')
            @include('modals.logout-modal')
        </div>
    </div>
    <script src="{{ url('assets/plugins/bootstrap/js/bootstrap.bundle.js') }}"></script>
    <script async src="{{ url('assets/plugins/mdb/js/mdb.min.js') }}"></script>
    <script src="{{ url('assets/plugins/moment/moment.js') }}"></script>
    <script src="{{ url('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ url('assets/plugins/jquery-ui-1.13.2/jquery-ui.min.js') }}"></script>
    <script src="{{ url('assets/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ url('assets/plugins/bootstrap-datepicker/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ url('assets/plugins/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ url('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('assets/plugins/datatables/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ url('assets/plugins/Waves/waves.min.js') }}"></script>
    <script src="{{ url('assets/js/main.js') }}"></script>
    <script src="{{ url('assets/js/menu.js') }}"></script>
    <script src="{{ url('assets/plugins/datatables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ url('assets/plugins/datatables/jszip.min.js') }}"></script>
    <script src="{{ url('assets/plugins/datatables/pdfmake.min.js') }}"></script>
    <script src="{{ url('assets/plugins/datatables/vfs_fonts.js') }}"></script>
    <script src="{{ url('assets/plugins/datatables/buttons.html5.min.js') }}"></script>
    <script src="{{ url('assets/plugins/datatables/buttons.print.min.js') }}"></script>
    @yield('js')
</body>

</html>
