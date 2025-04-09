<link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}" type="text/css">
<?php
use Carbon\Carbon;
$userId = Auth::user()->id;
$name = Auth::user()->nama;
$selectedYear = date('Y');
if (isset($_GET['year'])) {
    $selectedYear = htmlspecialchars($_GET['year']);
}
$department_id = Auth::user()->department_id;
$department = DB::table('department')->where('department_id', $department_id)->select('department_name', 'department_username')->first();
$departmentName = (string) $department->department_name;
$departmentUsername = (string) $department->department_username;

?>
<<style>
    .table-responsive {
        max-height: 870px;
        overflow-y: auto;
    }

    .table th,
    .table td {
        white-space: normal !important;
        overflow-wrap: break-word !important;
        word-break: normal !important;
        max-width: 250px;
    }

    th,
    td {
        word-break: break-word;
    }

    .status-indicator {
        display: inline-block;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        /* Makes it a circle */
        margin-left: 8px;
        /* Space between score and circle */
    }
</style>
<!-- Favicon -->
<link rel="apple-touch-icon" sizes="120x120" href="{{ asset('assets/img/apple-touch-icon.png') }}">
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/img/favicon-32x32.png') }}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/favicon-16x16.png') }}">
<link rel="shortcut icon" href="{{ asset('assets/img/favicon.ico') }}">
@extends('layouts.app')
@section('title', 'Dashboard Admin')
@section('content')

    <!-- Main content -->
    <div class="ml-5 main-content" id="panel">
        <!-- Topnav -->
        @include('partials.top')

        <!-- Header -->
        <div class="header bg-primary pb-6">
            <div class="container-fluid">
                <div class="header-body">
                    <div class="row align-items-center py-4">
                        <div class="col-lg-6 col-7">
                            <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                                    <li class="breadcrumb-item"><a href="/dsahboard-admin"><i class="fas fa-home"></i></a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Dashboard Admin</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Page content -->
        <div class="container-fluid mt--6">
            <div class="row">
                <div class="col-xl-6">
                    <!-- Column Chart -->
                    <div class="card">
                        <div class="card-header bg-transparent">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="text-light text-uppercase ls-1 mb-1">Pilar</h6>
                                </div>
                            </div>
                            <div class="d-block mb-3 mb-sm-0">
                                <div id="column-chart"></div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div id="bar-chart"></div>
                                </div>
                                <div class="col-md-6">
                                    <div id="bar-chart-2"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <livewire:breakdown-iku-admin :year="$selectedYear" :month="$selectedMonth" />

            </div>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                function createChart(selector, options) {
                    var chart = new ApexCharts(document.querySelector(selector), options);
                    chart.render();
                }

                var columnChartOptions = {
                    series: [{
                        name: 'Actual',
                        data: [{
                                x: '2011',
                                y: 1292,
                                goals: [{
                                    name: 'Expected',
                                    value: 1400,
                                    strokeHeight: 5,
                                    strokeColor: '#775DD0'
                                }]
                            },
                            {
                                x: '2012',
                                y: 4432,
                                goals: [{
                                    name: 'Expected',
                                    value: 5400,
                                    strokeHeight: 5,
                                    strokeColor: '#775DD0'
                                }]
                            },
                            {
                                x: '2013',
                                y: 5423,
                                goals: [{
                                    name: 'Expected',
                                    value: 5200,
                                    strokeHeight: 5,
                                    strokeColor: '#775DD0'
                                }]
                            },
                            {
                                x: '2014',
                                y: 6653,
                                goals: [{
                                    name: 'Expected',
                                    value: 6500,
                                    strokeHeight: 5,
                                    strokeColor: '#775DD0'
                                }]
                            },
                            {
                                x: '2015',
                                y: 8133,
                                goals: [{
                                    name: 'Expected',
                                    value: 6600,
                                    strokeHeight: 13,
                                    strokeWidth: 0,
                                    strokeLineCap: 'round',
                                    strokeColor: '#775DD0'
                                }]
                            },
                            {
                                x: '2016',
                                y: 7132,
                                goals: [{
                                    name: 'Expected',
                                    value: 7500,
                                    strokeHeight: 5,
                                    strokeColor: '#775DD0'
                                }]
                            },
                            {
                                x: '2017',
                                y: 7332,
                                goals: [{
                                    name: 'Expected',
                                    value: 8700,
                                    strokeHeight: 5,
                                    strokeColor: '#775DD0'
                                }]
                            },
                            {
                                x: '2018',
                                y: 6553,
                                goals: [{
                                    name: 'Expected',
                                    value: 7300,
                                    strokeHeight: 2,
                                    strokeDashArray: 2,
                                    strokeColor: '#775DD0'
                                }]
                            }
                        ]
                    }],
                    chart: {
                        height: 350,
                        type: 'bar'
                    },
                    plotOptions: {
                        bar: {
                            columnWidth: '60%'
                        }
                    },
                    colors: ['#00E396'],
                    dataLabels: {
                        enabled: false
                    },
                    legend: {
                        show: true,
                        showForSingleSeries: true,
                        customLegendItems: ['Actual', 'Expected'],
                        markers: {
                            fillColors: ['#00E396', '#775DD0']
                        }
                    }
                };

                var barChartOptions = {
                    series: [{
                        name: 'Actual',
                        data: [{
                                x: '2011',
                                y: 12,
                                goals: [{
                                    name: 'Expected',
                                    value: 14,
                                    strokeWidth: 2,
                                    strokeDashArray: 2,
                                    strokeColor: '#775DD0'
                                }]
                            },
                            {
                                x: '2012',
                                y: 44,
                                goals: [{
                                    name: 'Expected',
                                    value: 54,
                                    strokeWidth: 5,
                                    strokeHeight: 10,
                                    strokeColor: '#775DD0'
                                }]
                            },
                            {
                                x: '2013',
                                y: 54,
                                goals: [{
                                    name: 'Expected',
                                    value: 52,
                                    strokeWidth: 10,
                                    strokeHeight: 0,
                                    strokeLineCap: 'round',
                                    strokeColor: '#775DD0'
                                }]
                            },
                            {
                                x: '2014',
                                y: 66,
                                goals: [{
                                    name: 'Expected',
                                    value: 61,
                                    strokeWidth: 10,
                                    strokeHeight: 0,
                                    strokeLineCap: 'round',
                                    strokeColor: '#775DD0'
                                }]
                            },
                            {
                                x: '2015',
                                y: 81,
                                goals: [{
                                    name: 'Expected',
                                    value: 66,
                                    strokeWidth: 10,
                                    strokeHeight: 0,
                                    strokeLineCap: 'round',
                                    strokeColor: '#775DD0'
                                }]
                            },
                            {
                                x: '2016',
                                y: 67,
                                goals: [{
                                    name: 'Expected',
                                    value: 70,
                                    strokeWidth: 5,
                                    strokeHeight: 10,
                                    strokeColor: '#775DD0'
                                }]
                            }
                        ]
                    }],
                    chart: {
                        height: 350,
                        type: 'bar'
                    },
                    plotOptions: {
                        bar: {
                            horizontal: true
                        }
                    },
                    colors: ['#00E396'],
                    dataLabels: {
                        formatter: function(val, opt) {
                            const goals = opt.w.config.series[opt.seriesIndex].data[opt.dataPointIndex].goals;
                            return goals && goals.length ? `${val} / ${goals[0].value}` : val;
                        }
                    },
                    legend: {
                        show: true,
                        showForSingleSeries: true,
                        customLegendItems: ['Actual', 'Expected'],
                        markers: {
                            fillColors: ['#00E396', '#775DD0']
                        }
                    }
                };

                var barChartOptions2 = {
                    series: [{
                        name: 'Comparison',
                        data: [{
                                x: '2011',
                                y: 10,
                                goals: [{
                                    name: 'Expected',
                                    value: 15,
                                    strokeColor: '#FF4560'
                                }]
                            },
                            {
                                x: '2012',
                                y: 38,
                                goals: [{
                                    name: 'Expected',
                                    value: 50,
                                    strokeColor: '#FF4560'
                                }]
                            },
                            {
                                x: '2013',
                                y: 49,
                                goals: [{
                                    name: 'Expected',
                                    value: 48,
                                    strokeColor: '#FF4560'
                                }]
                            },
                            {
                                x: '2014',
                                y: 70,
                                goals: [{
                                    name: 'Expected',
                                    value: 65,
                                    strokeColor: '#FF4560'
                                }]
                            },
                            {
                                x: '2015',
                                y: 90,
                                goals: [{
                                    name: 'Expected',
                                    value: 75,
                                    strokeColor: '#FF4560'
                                }]
                            },
                            {
                                x: '2016',
                                y: 78,
                                goals: [{
                                    name: 'Expected',
                                    value: 80,
                                    strokeColor: '#FF4560'
                                }]
                            }
                        ]
                    }],
                    chart: {
                        height: 350,
                        type: 'bar'
                    },
                    plotOptions: {
                        bar: {
                            horizontal: true
                        }
                    },
                    colors: ['#008FFB'],
                    legend: {
                        show: true,
                        showForSingleSeries: true,
                        customLegendItems: ['Comparison', 'Expected'],
                        markers: {
                            fillColors: ['#008FFB', '#FF4560']
                        }
                    }
                };

                createChart("#column-chart", columnChartOptions);
                createChart("#bar-chart", barChartOptions);
                createChart("#bar-chart-2", barChartOptions2);
            });
        </script>
