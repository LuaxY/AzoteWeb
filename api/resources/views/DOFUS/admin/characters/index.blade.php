@extends('layouts.admin.admin')
@section('title') List characters @endsection
@section('page-title') Characters: List @endsection
@section('header')
    {{ Html::style('css/vendor_admin_datatables.min.css') }}
@endsection
@section('content')
    <!-- Start content -->
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card-box card-tabs">
                        <div class="row m-b-30">
                            <ul class="nav nav-pills pull-left">
                                @foreach(config('dofus.servers') as $k => $server)
                                <li class="@if($k == 0) active @endif">
                                    <a href="#cardpills-{{$server}}" data-toggle="tab" aria-expanded="@if($k == 0)true@else else @endif">{{strtoupper($server)}}</a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="tab-content">
                            @foreach(config('dofus.servers') as $k => $server)
                                <div id="cardpills-{{$server}}" class="tab-pane fade @if($k == 0) active in @endif">
                                    <table id="char-table" class="table table-striped table-bordered dataTable no-footer" role="grid" aria-describedby="datatable_info">
                                        <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Login</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('bottom')
    {!! Html::script('js/admin/vendor_admin_datatables.min.js') !!}
    <script>
        $(document).ready(function () {

            var token = '{{ Session::token() }}';
            var oTable;
            oTable = $('#char-table').on( 'init.dt', function () {
                $('[data-toggle="tooltip"]').tooltip()
            } ).DataTable({
                processing: false,
                serverSide: true,
                autoWidth: true,
                responsive: true,
                ajax: '{!! route('datatables.characterdata') !!}',
                columns: [
                    {data: 'Id', name: 'Id'},
                    {data: 'Login', name: 'Login'},
                ],
                rowId: 'Id'
            });
        });
    </script>
@endsection