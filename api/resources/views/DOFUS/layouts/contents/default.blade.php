@extends('layouts.master')

@section('page')
    <div class="container">
        <div id="play">
            <a href="@{{ URL::route('register') }}">Jouer !</a>
        </div>

        <div id="main">
            <div class="left">
@yield('menu')
            </div> <!-- left -->

            <div class="right">
@yield('content')
            </div> <!-- right -->
        </div> <!-- main-->
    </div> <!-- container -->
@stop
