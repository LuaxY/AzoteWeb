@extends('layouts.contents.default')
@include('layouts.menus.base')

@section('content')
    <h1>{{ $title }}</h1>
    <p>{{ $detail }}</p>
@stop
