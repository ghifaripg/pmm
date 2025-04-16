<?php
$userId = Auth::user()->id;
$name = Auth::user()->nama;
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

        <div class="ml-4 main-content table-responsive" style="width: 1420px">
            <table id="ikuTable">
                <thead>
                    <tr>
                        <th class="border-0 text-center" rowspan="2">#</th>
                        <th class="border-0 text-center" rowspan="2">Perspektif</th>
                        <th class="border-0 text-center" colspan="2">Key Address</th>
                        <th class="border-0 text-center" rowspan="2">Indikator Kerja Utama</th>
                        <th class="border-0 text-center" colspan="2">Target</th>
                        <th class="border-0 text-center" rowspan="2">Satuan</th>
                        <th class="border-0 text-center" rowspan="2">Polaritas</th>
                        <th class="border-0 text-center" rowspan="2">Bobot</th>
                        <th class="border-0 text-center" rowspan="2">Program Kerja</th>
                        <th class="border-0 text-center" rowspan="2">Penanggung Jawab</th>
                    </tr>
                    <tr>
                        <th class="border-0 text-center">IKU Atasan</th>
                        <th class="border-0 text-center">Target</th>
                        <th class="border-0 text-center">Base</th>
                        <th class="border-0 text-center">Stretch</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sasaranGrouped as $sasaran)
                        @php $ikuCount = count($sasaran->ikus) ?: 1; @endphp
                        <tr>
                            <td rowspan="{{ $ikuCount }}">{{ $loop->iteration }}</td>
                            <td rowspan="{{ $ikuCount }}">{{ $sasaran->perspektif }}</td>
                            @foreach ($sasaran->ikus as $iku)
                                <td class="fw-normal text-center">{{ $iku->iku_atasan }}</td>
                                <td class="fw-normal text-center">{{ $iku->target }}</td>
                                <td class="fw-normal text-center">{{ $iku->iku }}</td>
                                <td class="fw-normal text-center">{{ $iku->base }}</td>
                                <td class="fw-normal text-center">{{ $iku->stretch }}</td>
                                <td class="fw-normal text-center">{{ $iku->satuan }}</td>
                                <td class="fw-normal text-center">{{ ucfirst($iku->polaritas) }}</td>
                                <td class="fw-normal text-center">{{ $iku->bobot }}</td>
                                <td class="fw-normal text-center">{!! nl2br(e($iku->proker)) !!}</td>
                                <td class="fw-normal text-center">{{ $iku->pj }}</td>
                        </tr>
                    @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="ml-4 mt-3 mb-3">
            <a href="/progres" class="btn btn-primary" style="width: 120px">Back</a>
        </div>
    </div>
@endsection
