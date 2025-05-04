<!DOCTYPE html>
<html lang="en">
    @php
    $websetting = DB::table('settings')->orderby('id', 'desc')->limit(1)->get();
@endphp
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @foreach ($websetting as $row_websetting)
    <title>{{$row_websetting->singkatan_nama_program}}</title>
    @endforeach
    <link rel="shortcut icon" type="image/jpg" href="{{asset('laptop.png')}}"/>
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">
</head>

<body class="hold-transition lockscreen">
    
    @foreach ($websetting as $row_websetting)
        <div class="lockscreen-wrapper text-center">
            @if ($row_websetting->logo != '')
                <img src="{{ asset('img/setting/' . $row_websetting->logo) }}" alt="" class="img-thumb"
                    width="50%">
            @endif
            <div class="lockscreen-logo m-0">
                {{ $row_websetting->nama_program }}
            </div>
            <div class="lockscreen-name">{{ $row_websetting->deskripsi_program }}</div>
            <div class="text-center mt-3">
                @if (Route::has('login'))
                    <div class="top-right links">
                        @auth
                            <a href="{{ url('/backend/home') }}"  class="btn btn-success">Home</a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                        @endauth
                    </div>
                @endif
            </div>
            <div class="lockscreen-footer text-center mt-5">
                Made With Love In 2021 By <b><a href="https://www.google.com/search?q=Hamba Allah" target="blank()"
                        class="text-black">Hamba Allah</a></b>
            </div>
        </div>
    @endforeach

    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>
