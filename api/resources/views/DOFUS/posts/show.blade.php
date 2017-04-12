@extends('layouts.contents.default')
@include('layouts.menus.base')

@section('header')
    {!! Html::style('css/lightbox.min.css') !!}
    {!! Html::script('js/lightbox.min.js') !!}
    <script>
        var $jQuery = jQuery.noConflict();
    </script>
@stop

@section('breadcrumbs')
{? $page_name = $post->title ?}
{!! Breadcrumbs::render('page', 'Actualités') !!}
@stop

@section('content')
<div class="ak-title-container ak-backlink">
    <h1 class="ak-return-link">
        <a href="">
            <span class="ak-icon-big ak-news"></span>
        </a>
        {{ $post->title }}
        <span class="ak-subtitle"><span>{{  config('dofus.news_type.'.$post->type.'.name') }}</span> - {{ date('d F Y', strtotime($post->published_at)) }}</span>
    </h1>

    <a href="{{ URL::route('posts.type', $post->type ) }}" class="ak-backlink-button">Retour à la liste</a>
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
                        <a href="{{ URL::asset($post->image) }}" data-lightbox="image" data-title="{{ $post->title }}"><img alt="" class="img-responsive img-maxresponsive" src="{{ URL::asset($post->image) }}"></a>
                    </div>
                    <p>{!! $post->content !!}</p>
                </div>
            </div>
        </div>
        <div class="ak-container">
            <div class="ak-social">
                <div class="pull-left">
                    <div class="ak-social-block facebook">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{URL::current()}}" onclick="javascript:window.open(this.href,'','menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" class="">Partager</a>        </div>
                    <div class="ak-social-block twitter">
                        <a href="http://twitter.com/home?status={{URL::current()}}" onclick="javascript:window.open(this.href,'','menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" class="">Tweet</a>        </div>
                    <div class="ak-social-block google">
                        <a href="https://plus.google.com/share?url={{URL::current()}}" onclick="javascript:window.open(this.href,'','menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" class="">Partager</a>        </div>
                </div>
            </div>
        </div>
    </div>
    <div class="ak-forum-post-panel ak-container ak-panel" id="ak-block-posts">
        <div class="ak-panel-title">
            <span class="ak-panel-title-icon"></span> Commentaires (<span class="counter">{{ $post->comments->count() }}</span>)
        </div>
        <div class="ak-panel-content">
            <div class="ak-forum-post-list">
                @include('posts.templates.comments')
            </div>
            @if(Auth::check())
                {!! Form::open(['route' => ['posts.comment.store', $post->id, $post->slug], 'class' => 'ak-forum-post-form', 'id' => 'form-add-comment']) !!}
                    <div class="row ak-comment-container">
                        <div class="ak-avatar">
                            <img src="{{ URL::asset(Auth::user()->avatar) }}" alt="" border="0">
                        </div>
                        <div class="ak-comment">
                            {!! Form::textarea('comment', null, ['class' => 'ak-comment-textarea', 'placeholder' => 'Commenter...', 'rows' => '0', 'cols' => '0', 'id' => 'comment', 'style' => 'height:70px;']) !!}
                        </div>
                    </div>
                {!! Form::submit('Valider', ['class' => 'ak-comment-submit btn btn-primary btn-lg']) !!}
                {!! Form::close() !!}
            @endif
        </div>
    </div>
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
                $jQuery('.ak-forum-post-list').html(data);
                history.replaceState(null, 'page '+page, '?page='+page);
            }).fail(function () {
                toastr.error('Unknown error');
            });
        }
        $jQuery("#form-add-comment").on("submit", function (event) {
            $jQuery('#form-add-comment input[type="submit"]').prop('disabled', true);
            event.preventDefault();
            if(location.search)
            {
                var currentpage = location.search.split('page=')[1];
            }
            else
            {
                var currentpage = 1;
            }
            var $this = $jQuery(this);
            var datas = $this.serialize() + '&page=' + currentpage;

            $jQuery.ajax({
                method: 'POST',
                url: $this.attr("action"),
                data: datas,

                beforeSend:function (xhr, s) {
                    var lastpage = $jQuery('#pagination').data('lastpage');
                    if(lastpage == 0){
                        lastpage = 1;
                    }
                    if(currentpage != lastpage){
                        getPosts(lastpage, location.href);
                        s.data = $this.serialize() + '&page=' + lastpage;
                    }
                },
                success: function (html) {
                    $jQuery('#form-add-comment').fadeOut(2000);
                    setTimeout(function(){
                        var added_div = $jQuery(html).insertBefore($jQuery('div .ak-pagination')).hide();
                        $jQuery('div .ak-pagination').prev("div").fadeIn(3000);
                        var counter = $jQuery('.counter').html();
                        $jQuery('.counter').html(parseInt(counter) + 1);
                    }, 500);
                },
                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    var errorsHtml;
                    if (errors) {
                        errorsHtml = '';
                        $jQuery.each(errors, function (key, value) {
                            errorsHtml += '<li>' + value[0] + '</li>';
                        });
                    }
                    else {
                        errorsHtml = 'Unknown error';
                    }
                    toastr.error(errorsHtml);
                    $jQuery('#form-add-comment input[type="submit"]').prop('disabled', false);
                }
            });
        });
    });
</script>
@endsection
