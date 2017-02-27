@extends('layouts.contents.default')
@include('layouts.menus.base')

@section('breadcrumbs')
{? $page_name = 'Nouveau ticket' ?}
{!! Breadcrumbs::render('support.page', $page_name) !!}
@stop

@section('content')
<div class="ak-container ak-main-center">
    <div class="ak-title-container ak-backlink">
        <h1><span class="ak-icon-big ak-support"></span></a> Support - {{ $page_name }}</h1>
        <a href="{{ URL::route('support') }}" class="ak-backlink-button">Retour à mes tickets</a>
    </div>

    <div class="ak-container ak-panel-stack">
        <div class="ak-container ak-panel ak-glue">
        <div class="ak-panel-title">
                <span class="ak-panel-title-icon"></span> Ouvrir un nouveau ticket
            </div>
            <div class="ak-panel-content">
            
                <div class="panel-main">
                    <div id="ticket-success" style="display:none;">
                        <h4 class="text-center">Votre ticket a bien été envoyé. Il sera traité le plus rapidement possible.</h4>
                        <p style="text-align: center;">Une copie vous a été envoyé à: <span id="success-email" class="ak-bold"></span>. <br> Le délai de réponse peut varier entre 1 et 24 heures.</p>
                    </div>
                    <form class="ak-form" id="support" action="{{route('support.store')}}" method="post" enctype="multipart/form-data">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('bottom')
<style>

#support .hint {
    display: block;
    font-size: 12px;
    color: #777777;
}

#support .character {
    margin: 5px;
    border: #b9ae9d 2px solid;
    border-radius: 5px;
}

#support .character:hover {
    border-color: #8f8065;
    background-color: #FFF;
}

#support .character input[type=radio] {
    margin-left: 10px;
}

#support .character img {
    vertical-align:middle;
    width: 50px;
    height: 50px;
}
</style>
<script>
var $ = require('jquery');

var nb_part = 0;

function get_params() {
    params = {};

    $('#support select.special, #support input.special').each(function() {
        paramValue = $(this).val().split('|')[1];
        paramName  = $(this).attr('name').split('|')[0];
        params[paramName] = paramValue;

    });

    return params;
}

function get_child(id, name, query) {
    remove_parts(id);
    params = get_params();

    var url_base = '{{ route('home') }}';
    var url = ''+url_base+'/api/support/child/' +name;

    if (query != null && query != '') url +=  '/' + query;

    $.post(url, params, function(data) {
        /*if (id == 0) {
            $('#support').append('<div class="part" part="0">' + data + '</div>');
        } else {
            $('#support .part[part='+id+']').after('<div class="part" part="' + id + '">' + data + '</div>');
            nb_part = id + "";
        }*/
        $('#support').append('<div class="part" part="' + id + '">' + data + '</div>');
        nb_part = id + "";
    });
}

function remove_parts(id) {
    if (nb_part >= id) {
        for (var i = id; i <= nb_part; i++) {
            $('#support [part='+i+']').remove();
        }
        nb_part = id;
    }
}

// On field change
$('#support').on('change', 'select, input[type=radio]', function() {
    data = $(this).val().split('|');
    id = parseInt($(this).closest('.part').attr('part'));
    tag = data[0];

    if (tag == 'child')
    {
        child = (data[2] != null ? data[2] : '');
        query = (data[1] != null ? data[1] : '');

        get_child(id + 1, child, query);
    }
    else if (tag == 'reset')
    {
        remove_parts(id + 1);
    }
});

// On submit ticket
/*$('#support').on('click', 'input[type=submit]', function() {
    params = get_params();

    $.post('/api/support/store', params, function(data) {
        $('#support').html(data);
    });

    return false;
});*/

       $("#support").on("submit", function (event) {
            
            var button = $('#support input[type="submit"]');
            button.prop('disabled', true);
            event.preventDefault();

            var $form = $(this);
            var formdata = (window.FormData) ? new FormData($form[0]) : null;
            var data = (formdata !== null) ? formdata : $form.serialize();

            $.ajax({
                contentType: false,
                processData: false,
                method: 'POST',
                url: $form.attr("action"),
                data: data,
                success: function (email) {
                    $('#support').fadeOut(1000);
                    window.scrollTo(0, 0);
                    $('#success-email').text(email);
                    $('#ticket-success').fadeIn(1500);
                },
                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    var errorsHtml;
                    if (errors) {
                        errorsHtml = '';
                        $.each(errors, function (key, value) {
                            errorsHtml += '<li>' + value[0] + '</li>';
                        });
                    }
                    else {
                        errorsHtml = 'Unknown error';
                    }
                    toastr.error(errorsHtml);
                    button.prop('disabled', false);
                }
            });
        });

get_child(1, 'support');
//get_child(0, 'final');

</script>
@endsection
