@extends('adminlte::page')

@section('title', 'وجهة | المنشئات')

@section('content_header')
    <h1>المنشئات</h1>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/badge.css') }}">
@stop

@section('content')
@livewire('establishments')
@stop
