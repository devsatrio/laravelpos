<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>AdminLTE 3 | 500 Error</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{asset('assets/plugins/fontawesome-free/css/all.min.css')}}">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="{{asset('assets/dist/css/adminlte.min.css')}}">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>

<body>
    <section class="content pt-5">
        <div class="error-page mt-5 pt-5">

            <div class="error-content m-0 text-center">
            <h1 class="headline text-danger">403 Forbidden</h1>
                <h3><i class="fas fa-exclamation-triangle text-danger"></i> You don't have permission to access / on this server</h3>

                <p>
                    You may <a href="{{url('backend/home')}}">return to dashboard</a>
                </p>
            </div>
        </div>

    </section>
    <script src="{{asset('assets/plugins/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('assets/dist/js/adminlte.min.js')}}"></script>
    <script src="{{asset('assets/dist/js/demo.js')}}"></script>
</body>

</html>