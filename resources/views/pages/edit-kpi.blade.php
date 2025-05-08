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

@section('title', 'Edit KPI')
@section('content')

    <main class="content">
        <div class="container">
            <h2>Edit KPI</h2>
            <form action="{{ route('check-kontrak', ['year' => $selectedYear]) }}">
                <button class="btn btn-primary" type="submit">Back</button>
            </form>
            <br>
            <div class="row">
                <div class="col-12">
                    <form method="POST" action="{{ route('update-kpi', ['id' => $kpi->id]) }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="sasaran_id" value="{{ $kpi->sasaran_id }}">

                    <div class="card border-0 shadow">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-4 col-sm-6">
                                    <div class="mb-3">
                                        <label for="kpi">Key Performance Indicator</label>
                                        <input type="text" class="form-control" name="kpi_name"
                                            value="{{ $kpi->kpi_name }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="target">Target</label>
                                        <input type="text" class="form-control" name="target"
                                            value="{{ $kpi->target }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="satuan">Satuan</label>
                                        <input type="text" class="form-control" name="satuan"
                                            value="{{ $kpi->satuan }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="milestone">Milestone</label>
                                        <input type="text" class="form-control" name="milestone"
                                            value="{{ $kpi->milestone }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="esgc">ESG/C</label>
                                        <select name="esgc" class="form-control" required>
                                            <option value="E" {{ $kpi->esgc == 'E' ? 'selected' : '' }}>E</option>
                                            <option value="S" {{ $kpi->esgc == 'S' ? 'selected' : '' }}>S</option>
                                            <option value="G" {{ $kpi->esgc == 'G' ? 'selected' : '' }}>G</option>
                                            <option value="C" {{ $kpi->esgc == 'C' ? 'selected' : '' }}>C</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-6">
                                </div>
                                <div class="col-lg-4 col-sm-6">
                                    <div class="mb-3">
                                        <label for="polaritas">Polaritas</label>
                                        <select name="polaritas" class="form-control" required>
                                            <option value="maximize" {{ $kpi->polaritas == 'maximize' ? 'selected' : '' }}>
                                                Maximize</option>
                                            <option value="minimize" {{ $kpi->polaritas == 'minimize' ? 'selected' : '' }}>
                                                Minimize</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="bobot">Bobot</label>
                                        <input type="number" class="form-control" name="bobot" min="0"
                                            max="100" step="0.01" value="{{ $kpi->bobot }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <h5>Matriks Tanggung Jawab</h5>
                                        <label class="form-label">DI</label>
                                        <select name="du" class="form-control mb-2">
                                            <option value="O" {{ $kpi->du == 'O' ? 'selected' : '' }}>O (Overall)
                                            </option>
                                            <option value="R" {{ $kpi->du == 'R' ? 'selected' : '' }}>R
                                                (Responsible)</option>
                                            <option value="S" {{ $kpi->du == 'S' ? 'selected' : '' }}>S (Support)
                                            </option>
                                        </select>
                                        <label class="form-label">DK&SDM</label>
                                        <select name="dk" class="form-control mb-2">
                                            <option value="O" {{ $kpi->dk == 'O' ? 'selected' : '' }}>O (Overall)
                                            </option>
                                            <option value="R" {{ $kpi->dk == 'R' ? 'selected' : '' }}>R
                                                (Responsible)</option>
                                            <option value="S" {{ $kpi->dk == 'S' ? 'selected' : '' }}>S (Support)
                                            </option>
                                        </select>
                                        <label class="form-label">DO</label>
                                        <select name="do" class="form-control mb-2">
                                            <option value="O" {{ $kpi->do == 'O' ? 'selected' : '' }}>O (Overall)
                                            </option>
                                            <option value="R" {{ $kpi->do == 'R' ? 'selected' : '' }}>R
                                                (Responsible)</option>
                                            <option value="S" {{ $kpi->do == 'S' ? 'selected' : '' }}>S (Support)
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mb-3">
                            <button class="btn btn-primary" type="submit">Update KPI</button>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </main>

@endsection
