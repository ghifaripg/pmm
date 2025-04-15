<?php
$userId = Auth::user()->id;
$name = Auth::user()->nama;
$selectedYear = date('Y');
if (isset($_GET['year'])) {
    $selectedYear = htmlspecialchars($_GET['year']);
}
?>
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

@section('title', 'Form Penjabaran')
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
                                <li class="breadcrumb-item"><a href="/dashboard"><i class="fas fa-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="/penjabaran">Pilih Tahun</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Form Penjabaran</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="ml-6 mt-3">
        <div class="col-12 mb-4">
            <div class="card border-0 shadow components-section">
                <div class="card-body">
                    <h4>Penjabaran Tahun {{ $selectedYear }}</h4>
                    <div id="sasaran-checkbox-list">
                        <!-- kpi Selector -->
                        <div class="mb-3">
                            <label for="kpi-selector"><strong>Pilih Kontrak Manajemen</strong></label>
                            <select id="kpi-selector" class="form-control">
                                <option value="">-- Pilih Kontrak Manajemen --</option>
                                @foreach ($combinedData as $entry)
                                    <option
                                        value="{{ $entry['form']->id }}"
                                        data-sasaran="{{ $entry['form']->kpi_name }}"
                                        data-target="{{ $entry['form']->target }}"
                                        data-satuan="{{ $entry['form']->satuan }}"
                                    >
                                        {{ $entry['form']->kpi_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Penjabaran -->
    <div class="ml-6">
        <form method="POST" action="{{ route('store-penjabaran') }}">
            @csrf
            <input type="hidden" id="selected_form_id" name="form_id">
            <input type="hidden" name="year" value="{{ $selectedYear }}">

            <div class="col-12 mb-4">
                <div class="card border-0 shadow components-section">
                    <div class="card-body">
                        <h5>Kontrak Manajemen: <span id="selected-iku-heading">-</span></h5>
                        <div class="row mb-4">
                            <div class="col-lg-4 col-sm-6">
                                <div class="mb-3">
                                    <label for="sasaran">Sasaran Strategis</label>
                                    <input name="sasaran" class="form-control" id="sasaran" required>
                                </div>
                                <div class="mb-3">
                                    <label for="target">Target</label>
                                    <input type="text" class="form-control" name="target" id="target">
                                </div>
                                <div class="mb-3">
                                    <label for="satuan">Satuan</label>
                                    <input type="text" class="form-control" name="satuan" id="satuan">
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-6"></div>
                            <div class="col-lg-4 col-sm-6">
                                <div class="mb-3">
                                    <label for="proses">Proses Bisnis Terkait</label>
                                    <input type="text" class="form-control" name="proses" id="proses">
                                </div>
                                <div class="mb-3">
                                    <label for="proker">Strategic Inisiatif</label>
                                    <textarea class="form-control" id="strategis" name="strategis" rows="4"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="pic">PIC</label>
                                    <input type="text" class="form-control" name="pic" id="pic">
                                </div>
                            </div>
                        </div>
                    </div>
                    <button class="mb-4 btn btn-primary" type="submit"
                        style="max-width: 420px; margin-left: 480px">Submit</button>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('kpi-selector').addEventListener('change', function () {
            const option = this.options[this.selectedIndex];

            document.getElementById('selected_form_id').value = option.value || '';
            document.getElementById('selected-iku-heading').innerText = option.getAttribute('data-sasaran') || '-';
            document.getElementById('sasaran').value = option.getAttribute('data-sasaran') || '';
            document.getElementById('target').value = option.getAttribute('data-target') || '';
            document.getElementById('satuan').value = option.getAttribute('data-satuan') || '';
        });
    </script>
@endsection
