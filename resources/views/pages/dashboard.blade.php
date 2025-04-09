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
        /* Allow text to wrap */
        overflow-wrap: break-word !important;
        /* Ensure long words wrap properly */
        word-break: normal !important;
        /* Prevents breaking inside words */
        max-width: 250px;
        /* Prevents columns from becoming too wide */
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
                                    <li class="breadcrumb-item"><a href="/dsahboard"><i class="fas fa-home"></i></a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                    <!-- Card stats -->
                    <div class="row">
                        <div class="col-xl-3 col-md-6">
                            <div class="card card-stats">
                                <!-- Card body -->
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <h5 class="card-title text-uppercase text-muted mb-0">Unit Kerja</h5>
                                            <span class="h2 font-weight-bold mb-0">{{ $departmentUsername }}</span>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                                                <i class="ni ni-active-40"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mt-3 mb-0 text-sm">
                                        <span class="text-primary mr-2">{{ $departmentName }} </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card card-stats">
                                <!-- Card body -->
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <h5 class="card-title text-uppercase text-muted mb-0">Form Evaluasi</h5>
                                            <span class="h2 font-weight-bold mb-0">Bulan </span>
                                        </div>
                                        <div class="col-auto">
                                            <div
                                                class="icon icon-shape bg-gradient-orange text-white rounded-circle shadow">
                                                <i class="ni ni-chart-pie-35"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mt-3 mb-0 text-sm">
                                        <span class="text-nowrap">Done</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card card-stats">
                                <!-- Card body -->
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <h5 class="card-title text-uppercase text-muted mb-0">Total Skor</h5>
                                            <span class="h2 font-weight-bold mb-0">Bulan Ini</span>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon icon-shape bg-gradient-green text-white rounded-circle shadow">
                                                <i class="ni ni-money-coins"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mt-3 mb-0 text-sm">
                                        <span class="text-nowrap">13.52</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card card-stats">
                                <!-- Card body -->
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <h5 class="card-title text-uppercase text-muted mb-0">Performance</h5>
                                            <span class="h2 font-weight-bold mb-0">49,65%</span>
                                        </div>
                                        <div class="col-auto">
                                            <div class="icon icon-shape bg-gradient-info text-white rounded-circle shadow">
                                                <i class="ni ni-chart-bar-32"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mt-3 mb-0 text-sm">
                                        <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                                        <span class="text-nowrap">Since last month</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Page content -->
        <div class="container-fluid mt--6">
            <div class="row">
                <!-- Line Chart and Page Visits in the same column to prevent gaps -->
                <div class="col-xl-6">
                    <!-- Line Chart -->
                    <div class="card">
                        <div class="card-header bg-transparent">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h6 class="text-light text-uppercase ls-1 mb-1">Line Chart</h6>
                                </div>
                            </div>
                            <div class="d-block mb-3 mb-sm-0">
                                <form method="GET" class="mb-3">
                                    <label for="year" class="form-label">Pilih Tahun:</label>
                                    <select name="year" id="year" class="form-control w-auto d-inline">
                                        <?php for ($year = 2024; $year <= 2030; $year++): ?>
                                        <option value="<?= $year ?>" <?= $year == $selectedYear ? 'selected' : '' ?>>
                                            <?= $year ?>
                                        </option>
                                        <?php endfor; ?>
                                    </select>

                                    @if (auth()->user()->id == 1)
                                        <label for="department" class="form-label ms-3">Pilih Unit Kerja:</label>
                                        <select name="department" id="department" class="form-control w-auto d-inline"
                                            required>
                                            @foreach ($departments as $dept)
                                                <option value="{{ $dept->department_id }}"
                                                    {{ $dept->department_id == $selectedDepartment ? 'selected' : '' }}>
                                                    {{ $dept->department_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @endif
                                    <button type="submit" class="btn btn-secondary">Pilih</button>
                                </form>

                                <div class="fs-5 fw-normal mb-2">
                                    Performance Management - Capaian IKU {{ $selectedYear }}
                                </div>
                                @if (auth()->user()->id == 1)
                                    @foreach ($departments as $dept)
                                        @if ($dept->department_id == $selectedDepartment)
                                            <h2 class="h2 mb-0">Unit Kerja: {{ $dept->department_name }}</h2>
                                        @endif
                                    @endforeach
                                @else
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="sales-chart"></div>
                        </div>
                    </div>

                    <!-- Page Visits -->
                    <div class="card mt-3">
                        <div class="card-header border-0">
                            <div class="row align-items-center">
                                <div class="col">
                                    <livewire:TotalSkorIku :year="$selectedYear" :month="$selectedMonth" :department="$selectedDepartment" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Breakdown IKU -->
                <livewire:breakdown-iku :year="$selectedYear" :month="$selectedMonth" :department="$selectedDepartment" />

            </div>
        </div>
    </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var options = {
                chart: {
                    type: 'area',
                    height: 300,
                    animations: {
                        enabled: true,
                        easing: 'easeout',
                        speed: 800
                    }
                },
                series: [{
                    name: "Skor IKU",
                    data: {!! $adjSeriesJson !!}
                }],
                colors: ['#3333cc'], // Dark blue for better contrast
                xaxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agust', 'Sept', 'Okt', 'Nov',
                        'Des'
                    ],
                    labels: {
                        rotate: -45,
                        style: {
                            colors: '#333333', // Dark gray text
                            fontSize: '12px'
                        }
                    },
                    axisBorder: {
                        show: true,
                        color: '#A0A0A0' // Medium gray axis border
                    },
                    axisTicks: {
                        show: true,
                        color: '#A0A0A0' // Medium gray axis ticks
                    }
                },
                yaxis: {
                    labels: {
                        formatter: function(value) {
                            return value.toFixed(2);
                        },
                        style: {
                            colors: '#333333', // Dark gray text
                            fontSize: '12px'
                        }
                    }
                },
                stroke: {
                    curve: 'smooth',
                    width: 2
                },
                grid: {
                    borderColor: '#E0E0E0', // Light gray grid
                    strokeDashArray: 4
                },
                fill: {
                    type: "gradient",
                    gradient: {
                        shadeIntensity: 0.8,
                        opacityFrom: 0.5,
                        opacityTo: 0,
                        colorStops: [{
                                offset: 0,
                                color: "#6666ff",
                                opacity: 0.4
                            },
                            {
                                offset: 100,
                                color: "#ffffff",
                                opacity: 0
                            }
                        ]
                    }
                },
                tooltip: {
                    theme: "dark"
                }
            };

            var chart = new ApexCharts(document.querySelector("#sales-chart"), options);
            chart.render();
        });
    </script>
