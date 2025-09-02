@extends('adminlte::page')

@section('title', 'وجهة | عرض تفاصيل المنشأة')

@section('content_header')
    <div class="d-flex justify-content-between">
        <div dir="rtl" style="text-align: right; margin-right: 40px;">
            <h1>إدارة المنشئات</h1>
        </div>
        <div>
            <a href="{{ url('establishments') }}" class="btn btn-dark  rounded-circle">
                <i class="fas fa-arrow-left"></i> </a>
        </div>


    </div>

@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/badge.css') }}">
@stop

@section('content')
    @livewire('show-info', ['id' => $establishment->id])

@stop
