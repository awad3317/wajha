
<!DOCTYPE html>
<html dir="rtl" lang="ar">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="hWJsNKgs7yi0H97SmNj9XpKjooBJ2wsJaCNVKtRd">
    <title>وجهة</title>
    <link rel="stylesheet" href="{{asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/fontawesome-free/css/all.min.css')}}>
    <link rel="stylesheet" href="{{asset('vendor/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/adminlte/dist/css/adminlte.min.css')}}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>

<body class="login-page" >
    <div class="login-box">
        <div class="login-logo">
            <a href="http://127.0.0.1:8000/home">
                <img src="http://127.0.0.1:8000/img/wjahh.jpg"alt="Auth Logo"width="50"height="50">
                <b></b>
            </a>
        </div>
        <div class="card card-outline card-primary">
            <div class="card-header ">
                <h3 class="card-title float-none text-center">تسجيل الدخول الى وجهة</h3>
            </div>
            <div class="card-body login-card-body ">
                <form action="{{route('login')}}" method="post">
                    @csrf
                    {{-- <input type="hidden" name="_token" value="hWJsNKgs7yi0H97SmNj9XpKjooBJ2wsJaCNVKtRd" autocomplete="off"> --}}
                    <div class="input-group mb-3">
                        <input type="text" name="phone" class="form-control "value="" placeholder="مثال: 9665XXXXXXXX" autofocus>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control ">
                    </div>

                    <div class="col-12">
                        <button type=submit class="btn btn-block btn-flat btn-primary">
                            {{-- <span class="fas fa-sign-in-alt"></span> --}}
                            تسجيل الدخول
                        </button>
                    </div>
                </form>
            </div>
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
            
</body>

</html>
