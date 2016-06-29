@extends('layouts.contents.default')
@include('layouts.menus.base')

@section('header')
    {!! Html::style('css/news.css') !!}
@stop

@section('content')
    <div class="content">
        <h1 class="content-title">
            <span class="icon-big icon-news"></span> {{ $post->title }}
            <span class="subtitle"><span>@lang('categories.' . $post->type)</span> - {{ date('d F Y', strtotime($post->date)) }}</span>
        </h1>

        <article>
            {{ $post->preview }}
            <img class="big-image" src="{{ URL::asset($post->image) }}" alt="{{ $post->title }}" width="100%" />
            {{ $post->content }}
        </article>
    </div> <!-- content -->

    <div class="content comments" id="comments">
        <div class="title title-arrow">Commentaires ({{ $post->comments->count() }})</div>
        <div class="comments-list">
@foreach ($comments as $comment)
            <div class="comment @if ($comment->author->isAdmin()) admin @elseif ($comment->author->isStaff()) staff @endif">
                <div class="comment-avatar">
                    <div class="author-image"><img src="{{ URL::asset($comment->author->Avatar) }}" /></div>
                    <div class="author-tag">@if ($comment->author->isStaff(0)) <strong>Erezia staff</strong> @else Joueur @endif</div>
                </div>
                <div class="comment-content">
                    <div class="author-name">
                        <strong>{{ $comment->author->Nickname }}</strong>
                        <small>{{ date('d F Y à H:m', strtotime($comment->date)) }}</small>
                    </div>
                    <div class="comment-text">{{ $comment->text }}</div>
                </div>
            </div>
@endforeach
        </div>
        &nbsp;
        <div class="pagination-block">
            {{ $comments->fragment('comments')->render() }}
        </div>
    </div> <!-- content -->
@stop
