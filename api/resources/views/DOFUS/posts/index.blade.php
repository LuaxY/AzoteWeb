@extends('layouts.contents.default')
@include('layouts.menus.base')

@section('header')
    {!! Html::style('css/news.css') !!}
@stop

@section('content')
    <div class="news">
@foreach ($posts as $post)
        <div class="post {{ $post->type }}">
            <div class="post-image">
                <a href="{{ URL::route('posts.show', [$post->id, $post->slug]) }}"><img src="{{ URL::asset($post->image) }}" alt="{{ $post->title }}" /></a>
            </div>
            <div class="post-info">
                <div class="post-avatar"><img src="{{ URL::asset($post->author->Avatar) }}" /></div>
                <div class="post-info-details">
                    <div class="post-title"><a href="{{ URL::route('posts.show', [$post->id, $post->slug]) }}">{{ $post->title }}</a></div>
                    <div class="post-date"><a href="">@lang('categories.' . $post->type)</a> - {{ date('d F Y', strtotime($post->date)) }}</div>
                </div>
            </div>
            <div class="post-content">{{ $post->preview }}</div>
            <div class="post-ellipsis"></div>
            <div class="post-details">
                <div class="post-comments-count left">
                    <a href="{{ URL::route('posts.show', [$post->id, $post->slug]) }}">
                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="866.001px" height="866.013px" viewBox="930.736 591.271 866.001 866.013" enable-background="new 930.736 591.271 866.001 866.013" xml:space="preserve">
                        <path fill="#010202" d="M930.736,1186.67v54.084c0,29.905,24.229,54.148,54.124,54.148h54.115l0,0h54.134v162.382l243.555-162.382
                            h351.804c0.021,0,0.03,0,0.04,0h54.104c29.885,0,54.125-24.243,54.125-54.148v-595.37c0-29.897-24.24-54.112-54.125-54.112h-108.26
                            h-108.248l0,0h-432.976c-0.01,0-0.01,0-0.01,0H984.86c-29.895,0-54.124,24.214-54.124,54.112v54.127l0,0V1186.67L930.736,1186.67z
                             M1038.975,699.511h108.27l0,0h432.985l0,0h108.238v378.861v108.298h-108.238l0,0h-243.565l-135.306,81.165v-81.165h-54.114l0,0
                            h-108.27V699.511z M1526.104,1078.372h54.114V970.125h-432.975v108.298h378.86V1078.372z M1580.219,807.766h-432.975v108.24h432.975
                            V807.766z"></path>
                        </svg>
                        <span>{{ $post->comments->count() }}</span>
                    </a>
                </div>
            </div>
        </div>
@endforeach
    </div> <!-- news -->
    <div class="pagination-block">
    </div>
@stop
