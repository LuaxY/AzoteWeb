@extends('layouts.contents.default')
@include('layouts.menus.base')

@section('content')
    <div class="content">
        <h1 class="content-title">
            <span class="icon-big icon-devblog"></span> {{ $title }}
        </h1>
        <p style="padding: 10px">{{ $detail }}</p>
    </div> <!-- content -->
@stop
