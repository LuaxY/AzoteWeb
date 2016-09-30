@extends('layouts.contents.default')
@include('layouts.menus.base')

@section('header')
    {!! Html::style('css/homepage.css') !!}
    <script>
        var $jQuery = jQuery.noConflict();
    </script>
    <style>
    .ak-pagetop {
        margin-top: -85px;
    }
    </style>
@stop

<?php $carousel = true; ?>

@section('background') ak-background-type-homepage @stop

@section('breadcrumbs')
{? $page_name = 'Actualités' ?}
{!! Breadcrumbs::render('page', $page_name) !!}
@stop

@section('content')
<div class="ak-list-paginated">
    <div class="ak-item-list ak-grid-padding">
            @include('posts.templates.posts')
    </div>
</div>

<div class="row ak-container">
    <div class="ak-column ak-container col-sm-4"><a class="ak-link-news" href="/fr/mmorpg/actualites/news"> Toutes les news </a></div>
    <div class="ak-column ak-container col-sm-4"><a class="ak-link-devblog" href="/fr/mmorpg/actualites/devblog"> Tous les devblog </a></div>
    <div class="ak-column ak-container col-sm-4"><a class="ak-link-patch" href="/fr/mmorpg/actualites/maj"> Tous les patchs </a></div>
</div>
@stop
@section('bottom')
    <script>
        $jQuery(function() {
            $jQuery(document).ready(function() {
                $jQuery(document).on('click', '.pagination li a', function (e) {
                    getPosts($jQuery(this).attr('href').split('page=')[1]);
                    e.preventDefault();
                });
            });
            function getPosts(page) {
                $jQuery.ajax({
                    url : '?page=' + page,
                    dataType: 'json',
                }).done(function (data) {
                    $jQuery('.ak-item-list').html(data);
                    history.replaceState(null, 'page '+page, '?page='+page);
                }).fail(function () {
                    toastr.error('Unknown error');
                });
            }

        });
    </script>
@endsection
