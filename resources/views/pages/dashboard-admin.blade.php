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
<style>
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
        margin-left: 8px;
    }
</style>
<!-- Favicon -->
<link rel="apple-touch-icon" sizes="120x120" href="{{ asset('assets/img/apple-touch-icon.png') }}">
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/img/favicon-32x32.png') }}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/favicon-16x16.png') }}">
<link rel="shortcut icon" href="{{ asset('assets/img/favicon.ico') }}">
@extends('layouts.app')
@section('title', 'Dashboard')
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
                                    <li class="breadcrumb-item"><a href="/dsahboard-admin"><i class="fas fa-home"></i></a>
                                    </li>
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
                        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                        <div class="card-header bg-transparent">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h4 class="text-white p-2 mb-4" style="background-color: #0A48B3;">Pilar</h4>
                                </div>
                            </div>
                            <div class="row">
                                <!-- Column Chart -->
                                <div class="d-block mt-2 col-md-8">
                                    <div id="column-chart"></div>
                                </div>

                                <!-- Doughnut Chart -->
                                <div class="col-md-4 d-flex flex-column align-items-center">
                                    <div style="position: relative; width: 200px; height: 200px;">
                                        <canvas id="doughnutChart"></canvas>
                                        <!-- Text inside doughnut -->
                                        <div id="doughnutChartText"
                                            style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); font-weight: 600; font-size: 14px; text-align: center;">
                                            {{ $totalActual }}<br><small>Gap: {{ $gapTo100 }}</small>
                                        </div>
                                    </div>

                                    <!-- List Section -->
                                    <div class="mt-3 text-center" style="width: 100%;">
                                        <h3 class="fw-bold mb-2">Top 3 Gap:</h3>
                                        <ul class="list-unstyled small mb-0 px-2">
                                            @foreach ($topGap as $gapItem)
                                                @php
                                                    $isBelow = $gapItem['gap'] < 0;
                                                    $gapValue = abs($gapItem['gap']);
                                                    $sign = $isBelow ? '-' : '+';
                                                    $color = $isBelow ? 'text-danger' : 'text-success';
                                                @endphp
                                                <li class="mb-1 d-flex justify-content-between">
                                                    <span>{{ $gapItem['x'] }}</span>
                                                    <span
                                                        class="{{ $color }} fw-bold">{{ $sign }}{{ $gapValue }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <!-- Bar Chart 1 -->
                                <div class="col-md-6">
                                    <h4 class="text-white p-2 mb-4" style="background-color: #0A48B3;">Direktorat</h4>

                                    <div id="bar-chart"></div>
                                </div>
                                <!-- Bar Chart 2 -->
                                <div class="col-md-6">
                                    <h4 class="text-white p-2 mb-4" style="background-color: #0A48B3;">Departemen</h4>
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
                // Doughnut Chart (Chart.js)
                const ctx = document.getElementById('doughnutChart').getContext('2d');
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Total', 'Gap'],
                        datasets: [{
                            data: [{{ $totalActual }}, {{ $gapTo100 }}],
                            backgroundColor: ['#0A48B3', '#FF4560'],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        cutout: '70%',
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });

                // Helper for rendering ApexCharts
                function createChart(selector, options) {
                    var chart = new ApexCharts(document.querySelector(selector), options);
                    chart.render();
                }

                // Chart Data from backend
                const chartData = @json($chartData0);

                const categories = chartData.map(d => d.x);
                const actualSeries = chartData.map(d => parseFloat(parseFloat(d.actual).toFixed(2)));
                const aboveTargetSeries = chartData.map(d => {
                    const gap = d.target > d.actual ? d.target - d.actual : 0;
                    return parseFloat(gap.toFixed(2));
                });
                const belowTargetSeries = chartData.map(d => {
                    const gap = d.actual > d.target ? d.actual - d.target : 0;
                    return parseFloat(gap.toFixed(2));
                });

                // Column Chart (Vertical)
                const columnChartOptions = {
                    series: [{
                            name: 'Actual',
                            data: actualSeries
                        },
                        {
                            name: 'Above Target',
                            data: aboveTargetSeries
                        },
                        {
                            name: 'Below Target',
                            data: belowTargetSeries
                        }
                    ],
                    chart: {
                        type: 'bar',
                        height: 400,
                        stacked: true
                    },
                    plotOptions: {
                        bar: {
                            columnWidth: '40%'
                        }
                    },
                    colors: ['#008FFB', '#00E396', '#FF4560'],
                    xaxis: {
                        categories: categories,
                        labels: {
                            rotate: -40,
                            trim: false,
                            style: {
                                fontSize: '9px'
                            }
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    tooltip: {
                        shared: true,
                        intersect: false
                    },
                    legend: {
                        position: 'top',
                        horizontalAlign: 'center'
                    }
                };

                // Bar Chart 1 (Static Demo Data)
                const barChartData1 = [{
                        x: '2011',
                        y: 10,
                        expected: 15
                    },
                    {
                        x: '2012',
                        y: 38,
                        expected: 50
                    },
                    {
                        x: '2013',
                        y: 49,
                        expected: 48
                    },
                    {
                        x: '2014',
                        y: 70,
                        expected: 65
                    },
                    {
                        x: '2015',
                        y: 90,
                        expected: 75
                    },
                    {
                        x: '2016',
                        y: 78,
                        expected: 80
                    }
                ];
                const categories1 = barChartData1.map(d => d.x);
                const actualSeries1 = barChartData1.map(d => d.y);
                const aboveTargetSeries1 = barChartData1.map(d => d.expected > d.y ? d.expected - d.y : 0);
                const belowTargetSeries1 = barChartData1.map(d => d.y > d.expected ? d.y - d.expected : 0);

                const barChartOptions1 = {
                    series: [{
                            name: 'Actual',
                            data: actualSeries1
                        },
                        {
                            name: 'Above Target',
                            data: aboveTargetSeries1
                        },
                        {
                            name: 'Below Target',
                            data: belowTargetSeries1
                        }
                    ],
                    chart: {
                        type: 'bar',
                        height: 400,
                        stacked: true
                    },
                    plotOptions: {
                        bar: {
                            horizontal: true,
                            barHeight: '60%'
                        }
                    },
                    colors: ['#008FFB', '#00E396', '#FF4560'],
                    xaxis: {
                        categories: categories1,
                        labels: {
                            style: {
                                fontSize: '12px'
                            }
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    tooltip: {
                        shared: true,
                        intersect: false
                    },
                    legend: {
                        position: 'top',
                        horizontalAlign: 'center'
                    }
                };

                // Bar Chart 2
                const barChartData2 = @json($chartData1);
                const categories2 = barChartData2.map(d => d.x);

                const barChartOptions2 = {
                    series: [],
                    chart: {
                        type: 'bar',
                        height: 400,
                        stacked: true
                    },
                    plotOptions: {
                        bar: {
                            horizontal: true,
                            barHeight: '60%'
                        }
                    },
                    colors: ['#008FFB', '#00E396', '#FF4560'],
                    xaxis: {
                        categories: categories2,
                        labels: {
                            style: {
                                fontSize: '12px'
                            },
                        }
                    },
                    yaxis: {
                        categories: categories2,
                        labels: {
                            style: {
                                fontSize: '12px'
                            },
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    tooltip: {
                        shared: true,
                        intersect: false
                    },
                    legend: {
                        position: 'top',
                        horizontalAlign: 'center'
                    }
                };

                // Render Charts
                createChart("#column-chart", columnChartOptions);
                createChart("#bar-chart", barChartOptions1);
                createChart("#bar-chart-2", barChartOptions2);
            });
        </script>
