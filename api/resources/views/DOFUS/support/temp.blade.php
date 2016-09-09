@extends('layouts.contents.default')
@include('layouts.menus.base')

@section('breadcrumbs')
{!! Breadcrumbs::render('page', 'Support') !!}
@stop

@section('content')
<div class="ak-container ak-main-center">
    <div class="ak-title-container">
        <h1><span class="ak-icon-big ak-support"></span></a> Support</h1>
    </div>

    <div class="ak-container ak-panel-stack">
        <div class="ak-container ak-panel ak-glue">
            <div class="ak-panel-content">
                <div class="panel-main">
                    {{Html::image('imgs/support/1.png', 'support', ['class' => 'img-responsive', 'style' => 'padding-bottom:20px;'])}}
                    @if(Auth::guest())
                        <h4 class="text-center">Le support ticket est en cours de construction.</h4>
                        <p style="text-align: center;">Pour nous contacter, veuillez vous {{Html::link(route('login'), 'identifier')}} afin d'utiliser le live chat.</p>
                    @else
                        <h4 class="text-center">Le support ticket est en cours de construction.</h4>
                        <p style="text-align: center;">En cas de problème, veuillez utiliser le live chat pour nous contacter.</p>
                        <div id="status"></div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('scriptlogged')
        <script>
        Tawk_API = Tawk_API || {};
        Tawk_API.onStatusChange = function (status){
            if(status === 'online')
            {
                document.getElementById('status').innerHTML = '<h4 style="text-decoration: underline;">Status du chat:</h4><a class="btn btn-primary btn-lg" href="javascript:void(Tawk_API.toggle())">En ligne - Cliquez pour démarrer</a>';
            }
            else if(status === 'away')
            {
                document.getElementById('status').innerHTML = '<h4 style="text-decoration: underline;">Status du chat:</h4><a class="btn btn-info btn-lg" href="javascript:void(Tawk_API.toggle())">Absent - Nous sommes absent</a>';
            }
            else if(status === 'offline')
            {
                document.getElementById('status').innerHTML = '<h4 style="text-decoration: underline;">Status du chat:</h4><button class="btn btn-danger btn-lg">Hors ligne</button>';
            }
        };
    </script>
@endsection
