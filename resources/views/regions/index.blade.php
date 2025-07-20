@extends('adminlte::page')

@section('title', 'وجهة | المناطق')

@section('content_header')
    {{-- <div dir="rtl" style="text-align: right; margin-right: 40px;">
        <h1>المناطق</h1>
    </div> --}}
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/badge.css') }}">
    
@stop

@section('content')
@livewire('regions')
@stop
