@extends('adminlte::page')

@section('title', 'وجهة | المنشئات')

@section('content_header')
    <div dir="rtl" style="text-align: right; margin-right: 40px;">
        <h1>إدارة المنشئات</h1>
    </div>
    
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/badge.css') }}">
@stop

@section('content')
@livewire('establishments')

@stop
