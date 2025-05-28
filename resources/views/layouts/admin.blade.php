<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">

    <!-- Favicon -->
    <link href="{{ asset('img/builidng.png') }}" rel="icon" type="image/png">

    @vite('resources/sass/app.scss')
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav sidebar sidebar-dark accordion" style="background-color:#357081" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{route('landing')}}">
                <div class="sidebar-brand-text mx-6">Dashboard Admin</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item {{ Nav::isRoute('dashboard') }}">
                <a class="nav-link" href="{{ route('dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>{{ __('Dashboard') }}</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                {{ __('Menu Dashboard') }}
            </div>
            @if (auth()->user()->role == 1)

            {{-- Menambahkan menu dashboard admin --}}
            <!-- Notifikasi Pesanan Masuk -->
            <li class="nav-item dropdown no-arrow mx-1">
                <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-bell fa-fw"></i>
                    <span class="badge badge-danger badge-counter">{{ auth()->user()->unreadNotifications->count() }}</span>
                </a>
                <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                     aria-labelledby="alertsDropdown">
                    <h6 class="dropdown-header">
                        Notifikasi
                    </h6>
                    @foreach(auth()->user()->unreadNotifications as $notification)
                        <a class="dropdown-item d-flex align-items-center"
                           href="{{ $notification->data['type'] === 'order' ? route('admin.orders.index') : route('admin.payments.index') }}">
                            <div>
                                <span class="font-weight-bold">{{ $notification->data['message'] }}</span>
                                <div class="small text-gray-500">Total: Rp {{ number_format($notification->data['amount'], 0, ',', '.') }}</div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </li>

            <!-- Nav Item - Product -->
            <li class="nav-item {{ Nav::isRoute('products.index') }}">
                <a class="nav-link" href="{{ route('products.index') }}">
                    <i class="fas fa-shopping-cart"></i>
                    <span>{{ __('Produk') }}</span>
                </a>
            </li>


            <!-- Nav Item - Product -->
            <li class="nav-item {{ Nav::isRoute('category.index') }}">
                <a class="nav-link" href="{{ route('category.index') }}">
                    <i class="fas fa-list"></i>
                    <span>{{ __('Kategori Produk') }}</span>
                </a>
            </li>

            <!-- Nav Item - Pesanan -->
            <li class="nav-item {{ Nav::isRoute('order.index') }}">
                <a class="nav-link" href="{{ route('admin.orders.index') }}">
                    <i class="bi bi-receipt-cutoff"></i>
                    <span>{{ __('Pesanan') }}</span>
                </a>
            </li>

            <!-- Nav Item - Transaksi -->
            <li class="nav-item {{ Nav::isRoute('transaction.index') }}">
                <a class="nav-link" href="{{ route('transaction.index') }}">
                    <i class="fas fa-money-bill-wave"></i>
                    <span>{{ __('Pembayaran') }}</span>
                </a>
            </li>

            <!-- Nav Item - Bahan baku -->
            <li class="nav-item {{ Nav::isRoute('material.index') }}">
                <a class="nav-link" href="{{ route('materials.index') }}">
                    <i class="fas fa-box"></i>
                    <span>{{ __('Bahan Baku') }}</span>
                </a>
            </li>
            @endif

            {{-- Menambahkan menu dashboard user:2 --}}
            <li class="nav-item {{ Nav::isRoute('category.index') }}">
                <a class="nav-link" href="{{ route('category.index') }}">
                    <i class="fa-brands fa-product-hunt"></i>
                    <span>{{ __('Test') }}</span>
                </a>
            </li>
            <li class="nav-item {{ Nav::isRoute('category.index') }}">
                <a class="nav-link" href="{{ route('category.index') }}">
                    <i class="fa-brands fa-product-hunt"></i>
                    <span>{{ __('Test') }}</span>
                </a>
            </li>


            <!-- Nav Item - User -->
            {{-- <li class="nav-item {{ Nav::isRoute('user') }}">
                <a class="nav-link" href="{{ route('user') }}">
                    <i class="fas fa-fw fa-user"></i>
                    <span>{{ __('User') }}</span>
                </a>
            </li> --}}



            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span
                                    class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name }}</span>
                                <figure class="img-profile rounded-circle avatar font-weight-bold"
                                    data-initial="{{ Auth::user()->name[0] }}"></figure>
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="{{ route('profile') }}">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    {{ __('Profile') }}
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    {{ __('Logout') }}
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    @yield('main-content')

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright by &copy; Raffi Elendiaz {{ now()->year }}</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('Ready to Leave?') }}</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-link" type="button" data-dismiss="modal">{{ __('Cancel') }}</button>
                    <a class="btn btn-danger" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>
</body>

</html>
