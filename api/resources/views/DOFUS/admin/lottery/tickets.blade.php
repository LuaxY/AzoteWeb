@extends('layouts.admin.admin')
@section('title') List tickets @endsection
@section('page-title') Tickets: List @endsection
@section('header')
    {{ Html::style('css/vendor_admin_datatables.min.css') }}
@endsection
@section('content')
    <!-- Start content -->
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card-box">
                        <table id="tickets-table" class="table table-striped table-bordered dataTable no-footer" role="grid" aria-describedby="datatable_info">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>Ticket</th>
                                <th>User</th>
                                <th>Used</th>
                                <th>Item</th>
                                <th>Date</th>
                                <th>Giver</th>
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
    <script>
        $(document).ready(function () {
            var token = '{{ Session::token() }}';
            var oTable;
                oTable = $('#tickets-table').DataTable({
                    "order": [[ 0, "desc" ]],
                    processing: false,
                    serverSide: true,
                    autoWidth: true,
                    responsive: true,
                    ajax: '{{ route('datatables.lotteryticketsdata') }}',
                    columns: [
                        {data: 'id', name: 'id'},
                        {data: 'type', name: 'type'},
                        {data: 'user_id', name: 'user_id'},
                        {data: 'used', name: 'used', searchable: false},
                        {data: 'item_id', name: 'item_id'},
                        {data: 'created_at', name: 'created_at'},
                        {data: 'giver', name: 'giver', type: 'html'}
                    ],
                    rowId: 'id'
                });
            });
    </script>
@endsection
