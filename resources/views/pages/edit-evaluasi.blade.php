<?php
$userId = Auth::user()->id;
$name = Auth::user()->nama;
$role = Auth::user()->role;
$selectedYear = date('Y');
if (isset($_GET['year'])) {
    $selectedYear = htmlspecialchars($_GET['year']);
}
?>

<!-- Favicon -->
<link rel="apple-touch-icon" sizes="120x120" href="{{ asset('assets/img/apple-touch-icon.png') }}">
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/img/favicon-32x32.png') }}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/favicon-16x16.png') }}">
<link rel="shortcut icon" href="{{ asset('assets/img/favicon.ico') }}">

@extends('layouts.app')

<main class="content">
    @section('content')
        <div class="container">
            <h2 class="mb-4">Edit Evaluasi</h2>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form action="{{ route('evaluasi.update', $eval->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Left Column -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="iku_name" class="form-label">IKU Name</label>
                            <input type="text" class="form-control" id="iku_name" name="iku_name"
                                value="{{ $eval->iku_name }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="polaritas" class="form-label">Polaritas</label>
                            <select name="polaritas" class="form-control">
                                <option class="form-control" value="maximize"
                                    {{ $eval->polaritas == 'maximize' ? 'selected' : '' }}>Maximize</option>
                                <option class="form-control" value="minimize"
                                    {{ $eval->polaritas == 'minimize' ? 'selected' : '' }}>Minimize</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="bobot" class="form-label">Bobot</label>
                            <input type="text" step="0.01" class="form-control" id="bobot" name="bobot"
                                value="{{ $eval->bobot }}">
                        </div>
                        <div class="mb-3">
                            <label for="satuan" class="form-label">Satuan</label>
                            <input type="text" class="form-control" id="satuan" name="satuan"
                                value="{{ $eval->satuan }}">    m
                        </div>
                        <div class="mb-3">
                            <label for="base" class="form-label">Base</label>
                            <input type="text" step="0.01" class="form-control" id="base" name="base"
                                value="{{ $eval->base }}">
                        </div>
                        <div class="mb-3">
                            <label for="target_bulan_ini" class="form-label">Target Bulan Ini</label>
                            <input type="text" step="0.01" class="form-control" id="target_bulan_ini"
                                name="target_bulan_ini" value="{{ $eval->target_bulan_ini }}">
                        </div>
                    </div>
0
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="realisasi_bulan_ini" class="form-label">Realisasi Bulan Ini</label>
                            <input type="text" step="0.01" class="form-control" id="realisasi_bulan_ini"
                                name="realisasi_bulan_ini" value="{{ $eval->realisasi_bulan_ini }}">
                        </div>
                        <div class="mb-3">
                            <label for="percent_target" class="form-label">% Target</label>
                            <input type="text" step="0.01" class="form-control" id="percent_target"
                                name="percent_target" value="{{ $eval->percent_target }}">
                        </div>
                        <div class="mb-3">
                            <label for="percent_year" class="form-label">% Year</label>
                            <input type="text" step="0.01" class="form-control" id="percent_year" name="percent_year"
                                value="{{ $eval->percent_year }}">
                        </div>
                        <div class="mb-3">
                            <label for="ttl" class="form-label">TTL</label>
                            <input type="text" step="0.01" class="form-control" id="ttl" name="ttl"
                                value="{{ $eval->ttl }}">
                        </div>
                        <div class="mb-3">
                            <label for="adj" class="form-label">Adjusted</label>
                            <input type="text" step="0.01" class="form-control" id="adj" name="adj"
                                value="{{ $eval->adj }}">
                        </div>
                        <div class="mb-3">
                            <label for="penyebab_tidak_tercapai" class="form-label">Penyebab Tidak Tercapai</label>
                            <textarea class="form-control" id="penyebab_tidak_tercapai" name="penyebab_tidak_tercapai">{{ $eval->penyebab_tidak_tercapai }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="program_kerja" class="form-label">Program Kerja</label>
                    <textarea class="form-control" id="program_kerja" name="program_kerja">{{ $eval->program_kerja }}</textarea>
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
                <a href="/form-evaluasi?month=<?php echo $selectedMonth; ?>&year=<?php echo $selectedYear; ?>"
                    class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    @endsection
