
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Register</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{asset('assets/plugins/fontawesome-free/css/all.min.css')}}">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="{{asset('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/dist/css/adminlte.min.css')}}">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href="#"><b>Register</b>Page</a>
        </div>
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Create account to start your session</p>


                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <label for="username" class="col-form-label text-md-right">{{ __('Username') }}</label>
                    <div class="mb-3">
                        <input id="username" type="text" class="form-control @error('username') is-invalid @enderror"
                            name="username" value="{{ old('username') }}" required autocomplete="username" autofocus>
                        @error('username')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <label for="level" class="col-form-label text-md-right">{{ __('Level') }}</label>
                    <div class="mb-3">
                        @php
                        $data = DB::table('roles')->orderby('id','desc')->get();
                        @endphp
                        <select name="level" class="form-control">
                            @foreach($data as $row)
                            <option value="{{$row->name}}">{{$row->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <label for="name" class="col-form-label text-md-right">{{ __('Name') }}</label>
                    <div class="mb-3">
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                            name="name" value="{{ old('name') }}" required autocomplete="name">
                        @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <label for="email" class="col-form-label text-md-right">{{ __('E-Mail Address') }}</label>
                    <div class="mb-3">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                            name="email" value="{{ old('email') }}" required autocomplete="email">

                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <label for="password" class="col-form-label text-md-right">{{ __('Password') }}</label>
                    <div class="mb-3">
                        <input id="password" type="password"
                            class="form-control @error('password') is-invalid @enderror" name="password" required
                            autocomplete="new-password">
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <label for="password-confirm"
                        class="col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                    <div class="mb-3">
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation"
                            required autocomplete="new-password">
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">Sign Up</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="{{asset('assets/plugins/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('assets/dist/js/adminlte.min.js')}}"></script>

</body>

</html>