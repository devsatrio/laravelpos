<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('token')
    @php
        $websetting = DB::table('settings')->orderby('id', 'desc')->limit(1)->get();
    @endphp
    @foreach ($websetting as $row_websetting)
        <title>{{ $row_websetting->singkatan_nama_program }}</title>
    @endforeach
    <link rel="shortcut icon" type="image/jpg" href="{{ asset('laptop.png') }}" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
    @yield('customcss')
    <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">
</head>

<body class="hold-transition layout-top-nav">
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand-md navbar-dark navbar-dark">
            <div class="container">
                <a href="{{ url('/backend/home') }}" class="navbar-brand">
                    @foreach ($websetting as $row_websetting)
                        <span class="brand-text font-weight-light">{{ $row_websetting->singkatan_nama_program }}</span>
                    @endforeach
                </a>

                <button class="navbar-toggler order-1" type="button" data-toggle="collapse"
                    data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse order-3" id="navbarCollapse">
                    @include('layouts/nav')

                </div>
                <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link" data-toggle="dropdown" href="#">
                            <i class="far fa-user-circle"></i> {{ Auth::user()->username }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                            <span class="dropdown-header">{{ Auth::user()->username }}</span>
                            <div class="dropdown-item text-center mb-2">
                                @if (Auth::user()->gambar != '')
                                    <img class="img-circle elevation-2"
                                        src="{{ asset('img/admin/' . Auth::user()->gambar) }}" width="100px;"
                                        alt="Avatar">
                                @else
                                    <i class="far fa-user-circle fa-5x"></i>
                                @endif
                            </div>
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('editprofile') }}" class="dropdown-item">
                                <i class="fas fa-user-edit"></i> Edit Profile
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"
                                class="dropdown-item">
                                <i class="fas fa-sign-out-alt"></i>
                                Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                style="display: none;">
                                @csrf
                            </form>
                            <div class="dropdown-divider"></div>

                        </div>
                    </li>
                </ul>
            </div>
        </nav>
        <div class="content-wrapper">
            @yield('content')
        </div>
        <footer class="main-footer text-sm">
            Made By <b><a href="https://www.google.com/search?q=Hamba Allah" target="blank()" class="text-black">Hamba
                    Allah</a></b> Using <a href="https://adminlte.io" target="blank">AdminLTE.io</a>
            <div class="float-right d-none d-sm-inline-block">
                <b>Version</b> 0.1.0
            </div>
        </footer>
    </div>
    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/moment/moment.min.js') }}"></script>
    @stack('customjs')
    <script src="{{ asset('assets/dist/js/adminlte.min.js') }}"></script>
    @stack('customscripts')
</body>

</html>
