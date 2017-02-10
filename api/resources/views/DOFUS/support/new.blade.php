@extends('layouts.contents.default')
@include('layouts.menus.base')

@section('breadcrumbs')
{? $page_name = 'Support' ?}
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
                    <form class="ak-form" id="support" action="/api/support/store" method="post" enctype="multipart/form-data">
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

    var url = '/api/support/child/' + name;
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

get_child(1, 'support');
//get_child(0, 'final');

</script>
@endsection
