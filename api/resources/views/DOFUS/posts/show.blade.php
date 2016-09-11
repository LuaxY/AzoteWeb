@extends('layouts.contents.default')
@include('layouts.menus.base')

@section('header')
    {!! Html::style('css/news.css') !!}
@stop

@section('breadcrumbs')
{!! Breadcrumbs::render('page', 'Actualités') !!}
@stop

@section('content')
<div class="ak-title-container ak-backlink">
    <h1 class="ak-return-link">
        <a href="">
            <span class="ak-icon-big ak-news"></span>
        </a>
        {{ $post->title }}
        <span class="ak-subtitle"><span>@lang('categories.' . $post->type)</span> - {{ date('d F Y', strtotime($post->published_at)) }}</span>
    </h1>

    <a href="{{ URL::route('posts') }}" class="ak-backlink-button">Retour à la liste</a>
</div>

<div class="ak-container ak-panel-stack ak-glue">
    <div class="ak-container ak-panel ak-nocontentpadding">
        <div class="ak-panel-content">
            <div class="ak-item ak-admin-text">
                <div class="ak-item-mid">
                    <div class="ak-intro">
                        <p>{!! $post->preview !!}</p>
                    </div>
                    <div class="ak-big-image template_block">
                        <img alt="" class="img-responsive img-maxresponsive" src="{{ URL::asset($post->image) }}">
                    </div>
                    <p>{!! $post->content !!}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="ak-forum-post-panel ak-container ak-panel" id="ak-block-posts">
        <div class="ak-panel-title">
            <span class="ak-panel-title-icon"></span> Commentaires ({{ $post->comments->count() }})
        </div>
        <div class="ak-panel-content">
            <div class="ak-forum-post-list">
                @foreach ($comments as $comment)
                <div class="ak-comments-row @if ($comment->author->isStaff()) ak-avatar-admin @endif">
                    <div class="ak-avatar">
                        <div class="ak-avatar-img">
                            <img src="{{ URL::asset($comment->author->avatar) }}" alt="" border="0" /> </div>
                        <div class="ak-avatar-tag">@if ($comment->author->isStaff()) {{ config('dofus.title') }} Staff @else Joueur @endif</div>
                    </div>
                    <div class="ak-comment">
                        <div class="ak-user">
                            <strong>{{ $comment->author->firstname }}</strong>
                            <small class="ak-time">{{ date('d F Y à H:m', strtotime($comment->created_at)) }}</small>
                        </div>
                        <div class="ak-text-content">{{ $comment->text }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@stop
