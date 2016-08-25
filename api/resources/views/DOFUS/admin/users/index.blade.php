@extends('layouts.admin.admin')
@section('title') List users @endsection
@section('page-title') Users (Web accounts): List @endsection
@section('header')
    {{ Html::style('css/vendor_admin_datatables.min.css') }}
    {{ Html::style('css/sweetalert.min.css') }}
@endsection
@section('content')
        <!-- Start content -->
        <div class="content">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box">
                            <div class="m-b-30">
                                <a href="{{ route('admin.user.create') }}" class="btn btn-primary waves-effect waves-light btn-lg m-b-5"><i class="zmdi zmdi-plus"></i> Create User</a>
                            </div>
                            <table id="users-table" class="table table-striped table-bordered dataTable no-footer" role="grid" aria-describedby="datatable_info">
                                <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Email</th>
                                    <th>Firstname</th>
                                    <th>Lastname</th>
                                    <th>Rank</th>
                                    <th>Points</th>
                                    <th>Votes</th>
                                    <th>Active</th>
                                    <th>Actions</th>
                                    <th>test</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @endsection

        @section('bottom')
            {!! Html::script('js/admin/vendor_admin_datatables.min.js') !!}
            {!! Html::script('js/admin/sweetalert.min.js') !!}
            <script>
                $(document).ready(function () {

                    var token = '{{ Session::token() }}';
                    var oTable;
                    oTable = $('#users-table').on( 'init.dt', function () {
                        $('[data-toggle="tooltip"]').tooltip()
                    } ).DataTable({
                        processing: false,
                        serverSide: true,
                        autoWidth: true,
                        responsive: true,
                        ajax: '{!! route('datatables.userdata') !!}',
                        columns: [
                            {data: 'id', name: 'id'},
                            {data: 'email', name: 'email'},
                            {data: 'firstname', name: 'firstname'},
                            {data: 'lastname', name: 'lastname'},
                            {data: 'rank', name: 'rank'},
                            {data: 'points', name: 'points'},
                            {data: 'votes', name: 'votes'},
                            {data: 'active', name: 'active', class: 'activate'},
                            {data: 'action', name: 'action',  orderable: false, searchable: false},
                            {data: 'banReason', name: 'banReason', class:'banReason hidden'}
                        ],
                        rowId: 'id'
                    });

                    $('#users-table tbody').on('click', 'tr .ban', function () {
                        // Find ID of the user
                        var clickedId = $(this).attr('id');
                        var userId = clickedId.replace("ban-", "");
                        var element = $(this);
                        // Some variables
                        var url_users_base = '{{ route('admin.users')}}';
                        swal({
                            title: "Are you sure to ban this user?",
                            text: "Please, write a ban reason:",
                            type: "input",
                            showCancelButton: true,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "Yes, ban this user!",
                            closeOnConfirm: false }, function(inputValue){
                            if (inputValue === false)
                                return false;
                            if (inputValue === "")
                            {     swal.showInputError("You need to write something!");
                                return false
                            }
                            var banReason = inputValue;

                            $.ajax({
                                method: 'PATCH',
                                url: ''+url_users_base+'/'+userId+'/ban',
                                data: { _token: token, banReason : banReason},

                                success: function (msg) {
                                    swal("Banned!", "This user has been banned.", "success");
                                    var button = '<a id="unban-'+userId+'" class="unban pull-right btn btn-xs btn-info" data-toggle="tooltip" title="Unban" data-original-title="Unban"><i class="fa fa-ban"></i></a>';
                                    element.replaceWith(button);
                                    $('[data-toggle="tooltip"]').tooltip()
                                    $('#'+userId).children('td:hidden').text(banReason);
                                },

                                error: function(jqXhr, json, errorThrown) {
                                    var errors = jqXhr.responseJSON;
                                    var errorsHtml;
                                    if(errors)
                                    {
                                        errorsHtml= '';
                                        $.each( errors, function( key, value ) {
                                            errorsHtml += '<li>' + value[0] + '</li>';
                                        });
                                    }
                                    else
                                    {
                                        errorsHtml = 'Unknow error';
                                    }
                                    toastr.error(errorsHtml);
                                }
                            });

                        });
                    });

                    $('#users-table tbody').on('click', 'tr .unban', function () {
                        // Find ID of the user
                        var clickedId = $(this).attr('id');
                        var userId = clickedId.replace("unban-", "");
                        var element = $(this);
                        // Some variables
                        var url_users_base = '{{ route('admin.users')}}';
                        var banReason = $('#'+userId).children('td:hidden').text();

                        swal({
                            title: "Are you sure?",
                            text: "This user will be unbanned!<br/>Ban reason:<br/><strong> "+banReason+"</strong>",
                            html: true,
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#DD6B55",
                            confirmButtonText: "Yes, unban him!",
                            closeOnConfirm: false }, function(){

                            $.ajax({
                                method: 'PATCH',
                                url: ''+url_users_base+'/'+userId+'/unban',
                                data: { _token: token },

                                success: function (msg) {
                                    swal("Unbanned!", "User is unbanned.", "success");
                                    var button = '<a id="ban-'+userId+'" class="ban pull-right btn btn-xs btn-danger" data-toggle="tooltip" title="Ban" data-original-title="Ban"><i class="fa fa-ban"></i></a>';
                                    element.replaceWith(button);
                                    $('[data-toggle="tooltip"]').tooltip()
                                    $('#'+userId).children('td:hidden').text('');
                                },

                                error: function(jqXhr, json, errorThrown) {
                                    var errors = jqXhr.responseJSON;
                                    var errorsHtml;
                                    if(errors)
                                    {
                                        errorsHtml= '';
                                        $.each( errors, function( key, value ) {
                                            errorsHtml += '<li>' + value[0] + '</li>';
                                        });
                                    }
                                    else
                                    {
                                        errorsHtml = 'Unknow error';
                                    }
                                    toastr.error(errorsHtml);
                                }
                            });

                        });
                    });

                    $('#users-table tbody').on('click', 'tr .activ', function () {
                        // Find ID of the user
                        var clickedId = $(this).attr('id');
                        var userId = clickedId.replace("active-", "");
                        var element = $(this);
                        // Some variables
                        var url_users_base = '{{ route('admin.users')}}';

                            $.ajax({
                                method: 'PATCH',
                                url: ''+url_users_base+'/'+userId+'/activate',
                                data: { _token: token },

                                success: function (msg) {
                                    var button = '<td class=" activate"><span class="label label-success">Confirmed</span></td>';
                                    $('[data-toggle="tooltip"]').tooltip()
                                    $('#'+userId).children('td.activate').replaceWith(button);
                                    element.hide();
                                },

                                error: function(jqXhr, json, errorThrown) {
                                    var errors = jqXhr.responseJSON;
                                    var errorsHtml;
                                    if(errors)
                                    {
                                        errorsHtml= '';
                                        $.each( errors, function( key, value ) {
                                            errorsHtml += '<li>' + value[0] + '</li>';
                                        });
                                    }
                                    else
                                    {
                                        errorsHtml = 'Unknow error';
                                    }
                                    toastr.error(errorsHtml);
                                }
                            });
                    });

                });
            </script>
@endsection