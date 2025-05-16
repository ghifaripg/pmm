<link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}" type="text/css">
<?php
use Carbon\Carbon;
$userId = Auth::user()->id;
$name = Auth::user()->nama;
$role = Auth::user()->role;
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
                                <div class="col-md-6">
                                    <h4 class="text-white p-2 mb-4" style="background-color: #0A48B3;">Division</h4>

                                    <div id="bar-chart3"></div>
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

                const chartData = @json($chartData0);

                // Extract categories and data
                const categories = chartData.map(d => d.x);
                const actualSeries = chartData.map(d => parseFloat(d.actual));
                const aboveTarget = chartData.map(d => d.actual < d.target ? parseFloat((d.target - d.actual).toFixed(
                    2)) : 0);
                const belowTarget = chartData.map(d => d.actual > d.target ? parseFloat((d.actual - d.target).toFixed(
                    2)) : 0);

                const columnChartOptions = {
                    series: [{
                            name: 'Actual',
                            data: actualSeries
                        },
                        {
                            name: 'Above Target',
                            data: aboveTarget
                        },
                        {
                            name: 'Below Target',
                            data: belowTarget
                        }
                    ],
                    chart: {
                        type: 'bar',
                        height: 400,
                        stacked: true
                    },
                    colors: ['#0A48B3', '#00E396', '#FF4560'],
                    plotOptions: {
                        bar: {
                            columnWidth: '40%',
                            dataLabels: {
                                position: 'top'
                            }
                        }
                    },
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

                // Ensure createChart is defined and ApexCharts is loaded
                function createChart(selector, options) {
                    if (typeof ApexCharts !== 'undefined') {
                        const chart = new ApexCharts(document.querySelector(selector), options);
                        chart.render();
                    } else {
                        console.error('ApexCharts library is not loaded.');
                    }
                }
                createChart("#column-chart", columnChartOptions);


                // Bar Chart 1 (Directors)
                const barchartData1 = @json($chartData1);

                const categories1 = barchartData1.map(d => d.x);
                const actualSeries1 = barchartData1.map(d => parseFloat(d.actual));
                const aboveTarget1 = barchartData1.map(d => d.actual < d.target ? parseFloat((d.target - d.actual)
                    .toFixed(2)) : 0);
                const belowTarget1 = barchartData1.map(d => d.actual > d.target ? parseFloat((d.actual - d.target)
                    .toFixed(2)) : 0);

                const barChartOptions1 = {
                    series: [{
                            name: 'Actual',
                            data: actualSeries1
                        },
                        {
                            name: 'Above Target',
                            data: aboveTarget1
                        },
                        {
                            name: 'Below Target',
                            data: belowTarget1
                        }
                    ],
                    chart: {
                        type: 'bar',
                        height: 400,
                        stacked: true
                    },
                    colors: ['#0A48B3', '#00E396', '#FF4560'],
                    plotOptions: {
                        bar: {
                            horizontal: true,
                            barHeight: '60%',
                            dataLabels: {
                                position: 'top'
                            }
                        }
                    },
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

                // Bar Chart 2 (Departments)
                const barchartData2 = @json($chartData2);
                const categories2 = barchartData2.map(d => d.x);
                const actualSeries2 = barchartData2.map(d => parseFloat(d.actual));
                const aboveTarget2 = barchartData2.map(d => d.actual < d.target ? parseFloat((d.target - d.actual)
                    .toFixed(2)) : 0);
                const belowTarget2 = barchartData2.map(d => d.actual > d.target ? parseFloat((d.actual - d.target)
                    .toFixed(2)) : 0);

                const barChartOptions2 = {
                    series: [{
                            name: 'Actual',
                            data: actualSeries2
                        },
                        {
                            name: 'Above Target',
                            data: aboveTarget2
                        },
                        {
                            name: 'Below Target',
                            data: belowTarget2
                        }
                    ],
                    chart: {
                        type: 'bar',
                        height: 400,
                        stacked: true
                    },
                    colors: ['#0A48B3', '#00E396', '#FF4560'],
                    plotOptions: {
                        bar: {
                            horizontal: true,
                            barHeight: '60%',
                            dataLabels: {
                                position: 'top'
                            }
                        }
                    },
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

                // Bar Chart 3 (Divisions)
                const barchartData3 = @json($chartData3);
                const categories3 = barchartData3.map(d => d.x);
                const actualSeries3 = barchartData3.map(d => parseFloat(d.actual));
                const aboveTarget3 = barchartData3.map(d => d.actual < d.target ? parseFloat((d.target - d.actual)
                    .toFixed(2)) : 0);
                const belowTarget3 = barchartData3.map(d => d.actual > d.target ? parseFloat((d.actual - d.target)
                    .toFixed(2)) : 0);

                const barChartOptions3 = {
                    series: [{
                            name: 'Actual',
                            data: actualSeries3
                        },
                        {
                            name: 'Above Target',
                            data: aboveTarget3
                        },
                        {
                            name: 'Below Target',
                            data: belowTarget3
                        }
                    ],
                    chart: {
                        type: 'bar',
                        height: 400,
                        stacked: true
                    },
                    colors: ['#0A48B3', '#00E396', '#FF4560'],
                    plotOptions: {
                        bar: {
                            horizontal: true,
                            barHeight: '60%',
                            dataLabels: {
                                position: 'top'
                            }
                        }
                    },
                    xaxis: {
                        categories: categories3,
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


                // Render Charts
                createChart("#bar-chart", barChartOptions1);
                createChart("#bar-chart-2", barChartOptions2);
                createChart("#bar-chart3", barChartOptions3);

            });
        </script>
