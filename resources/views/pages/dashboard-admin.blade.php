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
                        <!-- Include Chart.js CDN in your page -->
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
                                            75.9<br><small>Gap: 2.12</small>
                                        </div>
                                    </div>

                                    <!-- List Section -->
                                    <div class="mt-3 text-center" style="width: 100%;">
                                        <h3 class="fw-bold mb-2">Top 3 Gap:</h3>
                                        <ul class="list-unstyled small mb-0 px-2">
                                            <li class="mb-1 d-flex justify-content-between">
                                                <span>Business Innovation Development</span>
                                                <span class="text-danger fw-bold">-10</span>
                                            </li>
                                            <li class="mb-1 d-flex justify-content-between">
                                                <span>Investment Expansion</span>
                                                <span class="text-danger fw-bold">-12</span>
                                            </li>
                                            <li class="d-flex justify-content-between">
                                                <span>Talent Development</span>
                                                <span class="text-danger fw-bold">-9</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                {{-- Bar Chart 1 --}}
                                <div class="col-md-6">
                                    <h4 class="text-white p-2 mb-4" style="background-color: #0A48B3;">Direktorat</h4>

                                    <div id="bar-chart"></div>
                                </div>
                                {{-- Bar Chart 2 --}}
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
            const ctx = document.getElementById('doughnutChart').getContext('2d');
            const doughnutChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Total', 'Gap'],
                    datasets: [{
                        data: [75, 25],
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
            document.addEventListener("DOMContentLoaded", function() {
                function createChart(selector, options) {
                    var chart = new ApexCharts(document.querySelector(selector), options);
                    chart.render();
                }

                const data = [{
                        x: 'Nilai Ekonomi dan Sosial Untuk Indonesia',
                        actual: 67,
                        target: 69
                    },
                    {
                        x: 'Inovasi Model Bisnis',
                        actual: 71,
                        target: 81
                    },
                    {
                        x: 'Kepemimpinan Teknologi',
                        actual: 81,
                        target: 75
                    },
                    {
                        x: 'Peningkatan Investasi',
                        actual: 90,
                        target: 85
                    },
                    {
                        x: 'Pengembangan Talenta',
                        actual: 85,
                        target: 73
                    }
                ];

                const categories = data.map(d => d.x);

                const actualSeries = data.map(d => d.actual);

                const aboveTargetSeries = data.map(d => {
                    return d.target > d.actual ? d.target - d.actual : 0;
                });

                const belowTargetSeries = data.map(d => {
                    return d.target < d.actual ? d.actual - d.target : 0;
                });

                const options = {
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
                const belowTargetSeries1 = barChartData1.map(d => d.expected < d.y ? d.y - d.expected : 0);

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

                const barChartData2 = [{
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

                const categories2 = barChartData2.map(d => d.x);
                const actualSeries2 = barChartData2.map(d => d.y);
                const aboveTargetSeries2 = barChartData2.map(d => d.expected > d.y ? d.expected - d.y : 0);
                const belowTargetSeries2 = barChartData2.map(d => d.expected < d.y ? d.y - d.expected : 0);

                const barChartOptions2 = {
                    series: [{
                            name: 'Actual',
                            data: actualSeries2
                        },
                        {
                            name: 'Above Target',
                            data: aboveTargetSeries2
                        },
                        {
                            name: 'Below Target',
                            data: belowTargetSeries2
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
                        categories: categories2,
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

                createChart("#column-chart", options);
                createChart("#bar-chart", barChartOptions1);
                createChart("#bar-chart-2", barChartOptions2);

            });
        </script>
