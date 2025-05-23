<?php
$userId = Auth::user()->id;
$name = Auth::user()->nama;
$role = Auth::user()->role;
?>
<link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}" type="text/css">
<style>
    .table th,
    .table td {
        white-space: normal !important;
        overflow-wrap: break-word !important;
        word-break: normal !important;
        max-width: 250px;
    }
</style>
<!-- Favicon -->
<link rel="apple-touch-icon" sizes="120x120" href="{{ asset('assets/img/apple-touch-icon.png') }}">
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/img/favicon-32x32.png') }}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/favicon-16x16.png') }}">
<link rel="shortcut icon" href="{{ asset('assets/img/favicon.ico') }}">
@extends('layouts.app')

@section('title', 'Evaluasi IKU')
@section('content')

    <div class="ml-5 main-content" id="panel" style="overflow-x: hidden">
        <!-- Topnav -->
        @include('partials.top')

        <div class="container-fluid">
            <div class="header-body">
                <div class="row align-items-center py-4">
                    <div class="col-lg-6 col-7">
                        <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                            <ol class="breadcrumb breadcrumb-links">
                                <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="/evaluasi">Evaluasi</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Pilih Periode</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <div class="ml-4 main-content">
            <div class="ml-4 d-flex justify-content-between w-100 flex-wrap">
                <div class="mb-3 mb-lg-0">
                    <h2>Form Evaluasi Iku {{ $departmentName }} Bulan {{ $selectedMonthName }}</h2>
                    <form method="GET" class="mb-3">
                        <label for="month-year" class="form-label">Pnilih Periode:</label>
                        <input type="month" id="month-year" name="month-year" class="form-control w-auto d-inline"
                            value="{{ date('Y-m', strtotime("$selectedYear-$selectedMonth-01")) }}">
                        <button type="submit" class="btn btn-primary">Pilih</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="ml-4 table-responsive" style="overflow-y: hidden; max-width: 1450px">
            <div
                style="display: flex; align-items: center; margin-left: 12px; margin-top: 25px; margin-bottom: 25px;">
                <img src="{{ asset('assets/img/logo.webp') }}" class="img-kiecs" alt="">
                <h4 style="text-transform: uppercase; margin-left: 650px">EVALUASI PENCAPAIAN INDIKATOR KINERJA UTAMA (IKU) s/d BULAN
                    <?php echo $selectedMonthName; ?></h4>
            </div>
            <div id="zoomContainer table-responsive">
                <table class="table table-bordered table-striped w-100" id="ikuTable">
                    <thead class="text-white" style="background-color: #2e2abd;">
                        <tr>
                            <th class="border-0 text-center" rowspan="2">Indikator Kinerja Utama</th>
                            <th class="border-0 text-center" rowspan="2">Polaritas</th>
                            <th class="border-0 text-center" rowspan="2">Bobot (A)</th>
                            <th class="border-0 text-center" rowspan="2">Unit</th>
                            <th class="border-0 text-center" colspan="3">Target</th>
                            <th class="border-0 text-center" colspan="2">Realisasi</th>
                            <th class="border-0 text-center" colspan="2">Prosentase Pencapaian THD Target</th>
                            <th class="border-0 text-center" colspan="2">Score</th>
                            <th class="border-0 text-center" rowspan="3">Penyebab Tidak Tercapai</th>
                            <th class="border-0 text-center" rowspan="3">Program Kerja/Langkah Kerja/langkah Pencapaian
                                target IKU (jika capaian < 95%)</th>
                        </tr>
                        <tr>
                            <th class="border-0 text-center">Tahun (1)</th>
                            <th class="border-0 text-center"style="white-space:pre">Bulan ini
                                (2)</th>
                            <th class="border-0 text-center" style="white-space:pre">s/d Bulan ini
                                (3)</th>
                            <th class="border-0 text-center" style="white-space:pre">Bulan ini
                                (4)</th>
                            <th class="border-0 text-center" style="white-space:pre">s/d Bulan ini
                                (5)</th>
                            <th class="border-0 text-center" style="white-space:pre">6=(5):(3)
                                (6)</th>
                            <th class="border-0 text-center" style="white-space:pre">7=(5):(1)
                                (7)</th>
                            <th class="border-0 text-center">Ttl</th>
                            <th class="border-0 text-center">Adj.</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach ($evaluations as $eval)
                            <tr>
                                <td class="fw-normal text-center">{{ $eval->iku_name }}
                                    @if ($eval->sub_point_name)
                                        <br> <span
                                            style="font-size: 0.9em; color: gray;">{{ $eval->sub_point_name }}</span>
                                    @endif
                                </td>
                                <td class="fw-normal text-center">{{ $eval->polaritas }}</td>
                                <td class="fw-normal text-center">{{ number_format($eval->bobot) }}</td>
                                <td class="fw-normal text-center">{{ $eval->satuan }}</td>
                                <td class="fw-normal text-center">{{ number_format((float) $eval->base) }}</td>
                                <td class="fw-normal text-center">{{ number_format($eval->target_bulan_ini) }}</td>
                                <td class="fw-normal text-center">{{ number_format($eval->target_sdbulan_ini) }}</td>
                                <td class="fw-normal text-center">{{ number_format($eval->realisasi_bulan_ini) }}</td>
                                <td class="fw-normal text-center">{{ number_format($eval->realisasi_sdbulan_ini) }}</td>
                                <td class="fw-normal text-center">{{ number_format((float) $eval->percent_target) }}%</td>
                                <td class="fw-normal text-center">{{ number_format((float) $eval->percent_year) }}%</td>
                                <td class="fw-normal text-center">{{ number_format($eval->ttl, 2) }}</td>
                                <td class="fw-normal text-center">{{ number_format($eval->adj, 2) }}</td>
                                <td class="fw-normal text-center">{{ $eval->penyebab_tidak_tercapai }}</td>
                                <td class="fw-normal text-center">{{ $eval->program_kerja }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="ml-6 mt-2 mb-2">
            <a href="/form-evaluasi?month={{ $selectedMonth }}&year={{ $selectedYear }}"
                class="btn btn-outline-primary d-inline-flex align-items-center">
                <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                    </path>
                </svg>
                Tambah/Ubah
            </a>
        </div>
    </div>
@endsection
