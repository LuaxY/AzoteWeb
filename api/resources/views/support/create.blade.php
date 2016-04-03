<!DOCTYPE html>
<html>
<head>
    <title>Support</title>
    <script src="https://code.jquery.com/jquery-2.2.2.js" integrity="sha256-4/zUCqiq0kqxhZIyp4G0Gk+AOtCJsY1TA00k5ClsZYE=" crossorigin="anonymous"></script>
    <style>
        #support .part {
            margin: 5px;
            border: black 1px solid;
        }
    </style>
</head>
<body>
    <form id="support" action="/support/store" method="post">
    </form>
</body>
<script>

var nb_part = 0;

function get_child(id, name) {
    remove_parts(id);

    $.get('/support/child/' + name, function(data) {
        $('#support').append('<div class="part" part="' + id + '">' + data + '</div>');
        nb_part = id + "";
    });
}

function remove_parts(id) {
    if (nb_part > id) {
        console.log(nb_part +' > '+ id);
        for (var i = id; i <= nb_part; i++) {
            $('#support [part='+i+']').remove();
        }
        nb_part = id;
    }
}

// On field change
$('#support').on('change', 'select, input[type=radio]', function() {
    data = $(this).val().split('|');
    id = parseInt($(this).parent().attr('part'));

    if (data[0] == 'c') {
        get_child(id + 1, data[1]);
    } else if (data[0] == 'r') {
        remove_parts(id + 1);
    }
});

get_child(1, 'support');

</script>
</html>
