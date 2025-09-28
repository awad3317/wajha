
<!DOCTYPE html>
<html dir="rtl" lang="ar">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="hWJsNKgs7yi0H97SmNj9XpKjooBJ2wsJaCNVKtRd">
    <title>وجهه - حجز فنادق، شاليهات وصالات أفراح في اليمن</title>
    <link rel="stylesheet" href="{{asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/fontawesome-free/css/all.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/adminlte/dist/css/adminlte.min.css')}}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
     <meta name="description"
        content="وجهه - منصة متكاملة لحجز الفنادق، الشاليهات وصالات الأفراح في اليمن، مع تفاصيل شاملة وخيارات متنوعة تناسب جميع المناسبات.">
    <meta name="keywords"
        content="وجهه, حجز فنادق اليمن, شاليهات اليمن, صالات أفراح, قاعات مناسبات, حجز فندق, قاعة زفاف, حجز شاليه, Yemen Hotels Booking">
    <meta name="author" content="منصة وجهه">

    {{-- Open Graph Meta (للسوشيال ميديا) --}}
    <meta property="og:title" content="وجهه - حجز فنادق، شاليهات وصالات أفراح في اليمن">
    <meta property="og:description"
        content="سهولة وراحة في حجز الفنادق، الشاليهات وقاعات المناسبات عبر منصة وجهه في اليمن.">
    <meta property="og:image" content="{{ asset('favicons/favicon-96x96.png') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta name="twitter:card" content="summary_large_image">


    {{-- Favicon & App Icons --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicons/favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicons/favicon-16x16.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicons/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicons/favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('favicons/web-app-manifest-192x192.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicons/apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('favicons/site.webmanifest') }}">
    <meta name="msapplication-TileImage" content="{{ asset('favicons/favicon-96x96.png') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
</head>

<body class="login-page" >
   
    <div class="login-box">
        <div class="login-logo">
            <a href="{{route('home')}}">
                <img src="{{asset('img/wjahh.jpg')}}"alt="Auth Logo"width="50"height="50">
                <b></b>
            </a>
        </div>
        <div class="card card-outline card-primary">
            <div class="card-header ">
                <h3 class="card-title float-none text-center">تسجيل الدخول الى وجهة</h3>
                 @error('phone')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
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
