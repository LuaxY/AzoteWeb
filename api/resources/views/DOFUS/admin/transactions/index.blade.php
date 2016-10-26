@extends('layouts.admin.admin')
@section('title') List transactions @endsection
@section('page-title') Transactions: List @endsection
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
                                    <table id="transactions-table" class="table table-striped table-bordered dataTable no-footer" role="grid" aria-describedby="datatable_info">
                                        <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>User</th>
                                            <th>State</th>
                                            <th>Code</th>
                                            <th>Points</th>
                                            <th>Date</th>
                                        </tr>
                                        </thead>
                                    </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="card-box">
                    <h4 class="header-title m-t-0">Last 30 days: Earnings</h4>
                        <canvas id="earningGraph"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('bottom')
    {!! Html::script('js/admin/vendor_admin_datatables.min.js') !!}
    {!! Html::script('https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.3.0/Chart.bundle.min.js') !!}
    {!! Html::script('https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.3.0/Chart.min.js') !!}
    <script>
        $(document).ready(function () {
            Chart.defaults.global.scaleLabel = true;

            var token = '{{ Session::token() }}';
            var ctx = document.getElementById("myChart");
            $.ajax({
                url: '{{ route('admin.transactions.getdata') }}',
                method: "GET",
                success: function(data) {
                    var days = [];
                    var earnings = [];
                    var json_data = JSON.parse(data);
                    for(var i in json_data) {
                        days.push(json_data[i].day);
                        earnings.push(json_data[i].earn);
                    }
                    var chartdata = {
                        labels: days,
                        datasets : [
                            {
                                fillColor: "rgba(220,220,220,0)",
                                strokeColor: "rgba(220,180,0,1)",
                                pointColor: "rgba(220,180,0,1)",
                                label: 'Earnings',
                                fill: false,
                                lineTension: 0.1,
                                backgroundColor: "rgba(255, 255, 255, 0)",
                                borderColor: "rgba(113, 182, 249, 1)",
                                borderCapStyle: 'butt',
                                borderDash: [],
                                borderDashOffset: 0.0,
                                borderJoinStyle: 'miter',
                                pointBorderColor: "rgba(113, 182, 249, 1)",
                                pointBackgroundColor: "#fff",
                                pointBorderWidth: 1,
                                pointHoverRadius: 5,
                                pointHoverBackgroundColor: "rgba(113, 182, 249, 1)",
                                pointHoverBorderColor: "rgba(0, 0, 0, 1)",
                                pointHoverBorderWidth: 2,
                                pointRadius: 1,
                                pointHitRadius: 10,
                                spanGaps: false,
                                data: earnings
                            }
                        ]
                    };

                    var ctx = $("#earningGraph");

                    var earnGraph = new Chart(ctx, {
                        type: 'line',
                        data: chartdata,
                        options: {
                            tooltips: {
                                enabled: true,
                                mode: 'single',
                                callbacks: {
                                    label: function(tooltipItems, data) {
                                        return tooltipItems.yLabel + ' €';
                                    }
                                }
                            },
                        }
                    });

                },
                error: function(data) {
                    console.log(data);
                }
            });

            var oTable;
                oTable = $('#transactions-table').DataTable({
                    "order": [[ 0, "desc" ]],
                    processing: false,
                    serverSide: false,
                    autoWidth: true,
                    responsive: true,
                    ajax: '{{ route('datatables.transactionsdata') }}',
                    columns: [
                        {data: 'id', name: 'id'},
                        {data: 'user_id', name: 'User'},
                        {data: 'state', name: 'State'},
                        {data: 'code', name: 'Code'},
                        {data: 'points', name: 'Points'},
                        {data: 'created_at', name: 'Date'}
                    ],
                    rowId: 'id'
                });
            });
    </script>
@endsection