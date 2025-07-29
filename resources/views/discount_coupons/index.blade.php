@extends('adminlte::page')

@section('title', 'وجهة | كوبنات الخصم')



@section('css')
    <link rel="stylesheet" href="{{ asset('css/badge.css') }}">
     @livewireStyles
@stop

@section('content')
@livewire('discount-coupons')

<script src="{{ asset('vendor/sweetalert2/sweetalert2.all.min.js')}}"></script>
@livewireScripts
<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('show-toast', ({ type, message }) => {
            console.log('📦 نوع التنبيه:', type);
            console.log('📣 الرسالة:', message);

            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: type || 'success',
                title: message || 'تم تنفيذ العملية بنجاح',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });
        });
    });
</script>
@stop
