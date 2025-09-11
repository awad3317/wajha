@extends('layouts.download-wajha-app')

@section('title', 'وجهة - تطبيقك للوجهة الأفضل')

@section('content')
    <div class="card-landing mx-auto text-center" style=" background:rgb(55, 64, 85) ;">

        <img src="{{ asset('img/wjahh.jpg') }}" class="mb-4 rounded-circle shadow" width="120" alt="شعار وجهة">

        <h1 class="fw-bold mb-3">✨ مرحباً بك في <span style="color:#ffdd57">وجهة</span> ✨</h1>

        <p class="lead mb-4">
            مع <strong>وجهة</strong>، كل الطرق بين يديك 🚀
            اكتشف، احجز، وتوجه بكل سهولة إلى وجهتك القادمة.
        </p>

        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="https://apps.apple.com" target="_blank" class="download-btn">
                <img src="{{ asset('img/app-store.svg') }}" alt="App Store">
            </a>
            <a href="https://play.google.com" target="_blank" class="download-btn">
                <img src="{{ asset('img/Google_Play.svg') }}" alt="Google Play">
            </a>
        </div>
    </div>
@endsection
