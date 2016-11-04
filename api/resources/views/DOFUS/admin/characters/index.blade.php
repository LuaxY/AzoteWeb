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
                    <div class="m-b-30" style="background-color: white;border-radius: 5px;">
                        <ul class="nav nav-pills nav-justified">
                            @foreach(config('dofus.servers') as $k => $server)
                                <li class="@if($k == 0) active @endif">
                                    <a href="#cardpills-{{$server}}" data-toggle="tab" id="{{$server}}" aria-expanded="@if($k == 0)true@else else @endif">{{ucfirst($server)}} Characters</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="card-box card-tabs">
                        <div class="tab-content">
                            @foreach(config('dofus.servers') as $k => $server)
                                <div id="cardpills-{{$server}}" class="tab-pane fade @if($k == 0) active in @endif">
                                    {{ Html::image('imgs/admin/'.$server.'.png', $server, ['class' => 'center-block m-b-30']) }}
                                    <table id="{{$server}}-table" class="table table-striped table-bordered dataTable no-footer" role="grid" aria-describedby="datatable_info">
                                        <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Name</th>
                                            <th>Class</th>
                                            <th>Level</th>
                                            <th>Status</th>
                                            <th>Game account</th>
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
            var servers = '{!! json_encode(config('dofus.servers'))!!}';
            $.each(JSON.parse(servers), function (i,server) {
                oTable = $('#'+server+'-table').on( 'init.dt', function () {
                    $('[data-toggle="tooltip"]').tooltip()
                } ).DataTable({
                    "order": [[ 0, "desc" ]],
                    processing: false,
                    serverSide: true,
                    autoWidth: true,
                    responsive: true,
                    ajax: '{{ route('datatables.characterdata') }}/'+server,
                    columns: [
                        {data: 'Id', name: 'Id'},
                        {data: 'Name', name: 'Name'},
                        {data: 'Breed', name: 'Breed', type: 'string'},
                        {data: 'Experience', name: 'Experience'},
                        {data: 'Status', name: 'Status'},
                        {data: 'GameAccount', name: 'GameAccount'}
                    ],
                    rowId: 'Id'
                });
            })
        });
    </script>
@endsection
