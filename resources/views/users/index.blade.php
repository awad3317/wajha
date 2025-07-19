@extends('adminlte::page')

@section('title', 'وجهة | المستخدمين')

@section('content_header')
    <h1>المستخدمين</h1>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/badge.css') }}">
@stop

@section('content')
@livewire('users')
@stop
