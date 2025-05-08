<?php
$userId = Auth::user()->id;
$name = Auth::user()->nama;
$role = Auth::user()->role;
$selectedYear = date('Y');
$selectedMonth = date('m');
if (isset($_GET['year'])) {
    $selectedYear = htmlspecialchars($_GET['year']);
}
if (isset($_GET['month'])) {
    $selectedMonth = htmlspecialchars($_GET['month']);
}
?>

<!-- Favicon -->
<link rel="apple-touch-icon" sizes="120x120" href="{{ asset('assets/img/apple-touch-icon.png') }}">
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/img/favicon-32x32.png') }}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/favicon-16x16.png') }}">
<link rel="shortcut icon" href="{{ asset('assets/img/favicon.ico') }}">

@extends('layouts.app')

@section('title', 'Edit Evaluasi')

@section('content')
<main class="content">
    <div class="container">
        <h2 class="mb-4">Edit Evaluasi</h2>
        <a href="/form-evaluasi?month={{ $selectedMonth }}&year={{ $selectedYear }}" class="btn btn-primary mb-3">Back</a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('evaluasi.update', $eval->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="card border-0 shadow">
                <div class="card-body">
                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">IKU</label>
                                <input type="text" class="form-control" name="iku_name" value="{{ $eval->iku_name }}" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Polaritas</label>
                                <input type="text" class="form-control" name="polaritas" value="{{ $eval->polaritas }}" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Bobot</label>
                                <input type="number" step="0.01" class="form-control" name="bobot" value="{{ $eval->bobot }}" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Satuan</label>
                                <input type="text" class="form-control" name="satuan" value="{{ $eval->satuan }}" readonly>
                            </div>

                            <div class="mb-3">
                                <h5>Target</h5>
                                <label class="form-label">Tahun</label>
                                <input type="number" step="0.01" class="form-control" name="base" value="{{ $eval->base }}" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Bulan Ini</label>
                                <input type="number" step="0.01" class="form-control" name="target_bulan_ini" value="{{ $eval->target_bulan_ini }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Target s.d. Bulan Ini</label>
                                <input type="number" step="0.01" class="form-control" name="target_sdbulan_ini" value="{{ $eval->target_sdbulan_ini }}">
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <h5>Realisasi</h5>
                                <label class="form-label">Bulan Ini</label>
                                <input type="text" step="0.01" class="form-control" name="realisasi_bulan_ini" value="{{ $eval->realisasi_bulan_ini }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">s.d. Bulan Ini</label>
                                <input type="number" step="0.01" class="form-control" name="realisasi_sdbulan_ini" value="{{ $eval->realisasi_sdbulan_ini }}">
                            </div>


                            <div class="mb-3">
                                <label class="form-label">% Target</label>
                                <input type="text" step="0.01" class="form-control" name="percent_target" value="{{ $eval->percent_target }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">% Year</label>
                                <input type="text" step="0.01" class="form-control" name="percent_year" value="{{ $eval->percent_year }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">TTL</label>
                                <input type="text" step="0.01" class="form-control" name="ttl" value="{{ $eval->ttl }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Adjusted</label>
                                <input type="text" step="0.01" class="form-control" name="adj" value="{{ $eval->adj }}">
                            </div>


                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Penyebab Tidak Tercapai</label>
                        <textarea class="form-control" name="penyebab_tidak_tercapai" rows="4">{{ $eval->penyebab_tidak_tercapai }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Program Kerja</label>
                        <textarea class="form-control" name="program_kerja" rows="4">{{ $eval->program_kerja }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="/form-evaluasi?month={{ $selectedMonth }}&year={{ $selectedYear }}" class="btn btn-secondary">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</main>
@endsection
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        function calculateResults() {
            const nilai5 = parseFloat(document.querySelector('input[name="realisasi_sdbulan_ini"]').value) || 0;
            const nilai3 = parseFloat(document.querySelector('input[name="target_sdbulan_ini"]').value) || 0;
            const nilai1 = parseFloat(document.querySelector('input[name="base"]').value) || 0;
            const bobot = parseFloat(document.querySelector('input[name="bobot"]').value) || 0;
            const polaritas = document.querySelector('input[name="polaritas"]').value.trim().toLowerCase();

            let percentTarget;
            if (polaritas === "maximize") {
                percentTarget = nilai3 !== 0 ? (nilai5 / nilai3) * 100 : 0;
            } else {
                percentTarget = nilai5 !== 0 ? (nilai3 / nilai5) * 100 : 0;
            }

            let percentYear;
            if (polaritas === "maximize") {
                percentYear = nilai1 !== 0 ? (nilai5 / nilai1) * 100 : 0;
            } else {
                percentYear = nilai5 !== 0 ? (nilai1 / nilai5) * 100 : 0;
            }

            percentTarget = Math.round(Math.min(percentTarget, 250));
            percentYear = Math.round(Math.min(percentYear, 250));

            const N = percentYear;
            const O = Math.round(N * bobot);

            let Q = Math.min(Math.max(N, 0), 110);
            const adj = Math.round(Q * bobot) / 100;
            const ttl = O < 0 ? 0 : Math.round(O) / 100;

            document.querySelector('input[name="percent_target"]').value = percentTarget + "%";
            document.querySelector('input[name="percent_year"]').value = percentYear + "%";
            document.querySelector('input[name="ttl"]').value = ttl.toFixed(2);
            document.querySelector('input[name="adj"]').value = adj.toFixed(2);
        }

        document.querySelectorAll(
            'input[name="realisasi_sdbulan_ini"], input[name="target_sdbulan_ini"], input[name="base"], input[name="bobot"], input[name="polaritas"]'
        ).forEach(input => {
            input.addEventListener('input', calculateResults);
        });
    });
</script>
@endpush
