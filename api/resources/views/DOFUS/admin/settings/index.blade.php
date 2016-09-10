@extends('layouts.admin.admin')
@section('title') Website Settings @endsection
@section('page-title') Website: Settings @endsection
@section('content')
        <!-- Start content -->
<div class="content">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="card-box">
                    <h4 class="header-title m-b-30">Message of the Day (MOTD)</h4>

                    @if(!empty($posts))
                        {!! Form::model($motd, ['route' => 'admin.settings.update']) !!}
                        {{ method_field('PATCH') }}
                        {!! Form::text('settings_type', 'motd', ['class' => 'hidden disabled']) !!}
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group {{ $errors->has('title') ? ' has-error' : '' }}">
                                    <label for="title">Title:</label>
                                    {!! Form::text('title', null, ['class' => 'form-control', 'id' => 'title']) !!}
                                    @if ($errors->has('title'))
                                        <span class="help-block">
                                                                        <strong>{{ $errors->first('title') }}</strong>
                                                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group {{ $errors->has('subtitle') ? ' has-error' : '' }}">
                                    <label for="subtitle">Subtitle:</label>
                                    {!! Form::text('subtitle', null, ['class' => 'form-control', 'id' => 'subtitle']) !!}
                                    @if ($errors->has('subtitle'))
                                        <span class="help-block">
                                                                        <strong>{{ $errors->first('subtitle') }}</strong>
                                                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group {{ $errors->has('postid') ? ' has-error' : '' }}">
                                    <label for="postid">Linked Post:</label>
                                    {!! Form::select('postid', $posts, null,['class' => 'form-control', 'id' => 'postid']) !!}
                                    @if ($errors->has('postid'))
                                        <span class="help-block">
                                                                        <strong>{{ $errors->first('postid') }}</strong>
                                                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        {!! Form::submit('Update', ['class' => 'btn btn-info']) !!}
                        {!! Form::close() !!}
                    @else
                        <div class="alert alert-warning">
                            <strong>Info!</strong> You can't edit MOTD if you don't have any posts.
                        </div>
                    @endif
                </div>

                <!-- <div class="card-box">
                     <h4 class="header-title m-b-30">Box 2nd</h4>
                 </div> -->

            </div>
        </div>
    </div>
    @endsection
