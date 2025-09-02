@extends('adminlte::page')

@section('title', '| تفاصيل الحجز | وجهة | الحجوزات')

@section('content_header')
    <div class="d-flex justify-content-between">
        <div dir="rtl" style="text-align: right; margin-right: 40px;">
            <h1>تفاصيل الحجز</h1>
        </div>
        <div>
            <a href="{{ url('booking') }}" class="btn btn-dark  rounded-circle">
                <i class="fas fa-arrow-left"></i> </a>
        </div>


    </div>

@stop


@section('css')
    <link rel="stylesheet" href="{{ asset('css/badge.css') }}">

@stop

@section('content')
    @livewire('showbookingdetails', ['bookingId' => $booking->id])


    <script src="{{ asset('vendor/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('show-toast', ({
                type,
                message
            }) => {
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
