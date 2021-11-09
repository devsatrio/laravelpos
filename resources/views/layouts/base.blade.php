<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    @yield('token')
    @php
    $websetting = DB::table('settings')->orderby('id','desc')->limit(1)->get();
    @endphp
    @foreach($websetting as $row_websetting)
    <title>{{$row_websetting->singkatan_nama_program}}</title>
    @endforeach
    <link rel="stylesheet" href="{{asset('assets/plugins/fontawesome-free/css/all.min.css')}}">
    @yield('customcss')
    <link rel="stylesheet" href="{{asset('assets/dist/css/adminlte.min.css')}}">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>

<body class="hold-transition layout-top-nav">
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand-md navbar-dark navbar-dark">
            <div class="container">
                <a href="{{url('/backend/home')}}" class="navbar-brand">
                    @foreach($websetting as $row_websetting)
                    <span class="brand-text font-weight-light">{{$row_websetting->singkatan_nama_program}}</span>
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
                            <i class="far fa-user-circle"></i> {{Auth::user()->username}}
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                            <span class="dropdown-header">My Profile</span>
                            <div class="dropdown-divider"></div>
                            <a href="{{route('editprofile')}}" class="dropdown-item">
                                Edit Profile
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"
                                class="dropdown-item">
                                Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                            <div class="dropdown-divider"></div>
                            @if(auth()->user()->can('setting-web'))
                            <a href="{{url('backend/web-setting')}}" class="dropdown-item">
                                Web Setting
                            </a>
                            @endif
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
        <div class="content-wrapper">
            @yield('content')
        </div>

    </div>
    <script src="{{asset('assets/plugins/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    @stack('customjs')
    <script src="{{asset('assets/dist/js/adminlte.min.js')}}"></script>
    @stack('customscripts')
</body>

</html>