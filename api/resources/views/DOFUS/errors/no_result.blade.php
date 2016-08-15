@extends('layouts.contents.default')
@include('layouts.menus.base')

@section('content')
    {{ $e->getMessage() }}
@stop
