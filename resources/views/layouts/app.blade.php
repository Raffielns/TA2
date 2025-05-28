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
    <style>
        ul li a {
            font-size: 18px !important;
            font-weight: 600 !important;
        }

        .navbar .nav-link:hover {
            color: #F9F7F7 !important;
            text-decoration: underline;
        }

        .navbar .btn:hover {
            background-color: #112D4E !important;
        }

        body {
            margin: 0 !important;
            padding: 0 !important;
        }

        main {
            padding-top: 0 !important;
            margin-top: 0 !important;
        }
    </style>

    <!-- Favicon -->
    <link href="{{ asset('img/favicon.png') }}" rel="icon" type="image/png">
    @vite('resources/sass/app.scss')
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md shadow fixed-top shadow-sm" style="background-color: #192737;">
            <div class="container">
                <a class="navbar-brand text-white fw-bold d-flex align-items-center" href="{{ route('landing') }}">
                    <img src="{{ asset('img/Logo CV. ASS.png') }}" alt="Logo CV Anugerah" height="40" class="me-2">
                    CV. Anugerah Sukses Sejahtera
                </a>

                <button class="navbar-toggler bg-light" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side -->
                    <ul class="navbar-nav mr-auto"></ul>

                    <!-- Right Side -->
                    <ul class="navbar-nav ml-auto align-items-center">
                        <li class="nav-item {{ Nav::isRoute('landing') }}">
                            <a class="nav-link text-white fw-semibold" href="{{ route('landing') }}">Home</a>
                        </li>
                        <li class="nav-item {{ Nav::isRoute('catalog') }}">
                            <a class="nav-link text-white fw-semibold" href="{{ route('catalog') }}">Catalog</a>
                        </li>
                        <li class="nav-item {{ Nav::isRoute('aboutus') }}">
                            <a class="nav-link text-white fw-semibold" href="{{ route('aboutus') }}">Tentang Kami</a>
                        </li>
                        <li class="nav-item {{ Nav::isRoute('profile') }}">
                            <a class="nav-link text-white fw-semibold" href="{{ route('profile') }}">Akun</a>
                        </li>

                        @guest
                            <li class="nav-item">
                                <a class="btn btn-sm text-white" href="{{ route('login') }}">
                                    <i class="fas fa-user mr-1"></i> Login
                                </a>
                            </li>
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle text-white fw-semibold"
                                    href="#" role="button" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="{{ route('profile') }}">
                                        <i class="fas fa-user mr-2"></i> Profile
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item text-danger" href="#" data-toggle="modal"
                                        data-target="#logoutModal">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                    </a>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>


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

        <main class="py-5 m-auto">
            @yield('content')
            @stack('scripts')
        </main>

        @hasSection('footer')
            @yield('footer')
        @else
            <footer class="bg-body-tertiary text-center text-lg-start">
                <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.05);">
                    © {{ date('Y') }} Copyright:
                    <a class="text-body" href="https://github.com/Raffiel11">CV. Anugerah Sukses Sejahtera</a>
                </div>
            </footer>
        @endif

    </div>

    <!-- Scripts -->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>
</body>

</html>
