<?php
$userId = Auth::user()->id;
$name = Auth::user()->nama;
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

@section('title', 'Edit Penjabaran')
@section('content')

    <main class="content">
        <div class="container">
            <h2 class="mt-5">Edit Penjabaran</h2>
            <form action="{{ route('check-penjabaran', ['year' => $selectedYear]) }}">
                <button class="btn btn-primary" type="submit">Back</button>
            </form>
            <br>
            <div class="row">
                <div class="col-12">
                    <form method="POST" action="{{ route('update-penjabaran', ['id' => $penjabaran->id]) }}">
                        @csrf
                        @method('PUT')
                        <div class="card border-0 shadow">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-4 col-sm-6">
                                        <div class="mb-3">
                                            <label for="kpi">Key Performance Indicator</label>
                                            <input type="text" class="form-control" name="kpi_name"
                                                value="{{ $kpi->kpi_name }}" disabled>
                                        </div>
                                        <div class="mb-3">
                                            <label for="target">Target</label>
                                            <input type="text" class="form-control" name="target"
                                                value="{{ $kpi->target }}" disabled>
                                        </div>
                                        <div class="mb-3">
                                            <label for="satuan">Satuan</label>
                                            <input type="text" class="form-control" name="satuan"
                                                value="{{ $kpi->satuan }}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-sm-6">
                                    </div>
                                    <div class="col-lg-4 col-sm-6">
                                        <div class="mb-3">
                                            <label for="proses_bisnis">Proses Bisnis Terkait</label>
                                            <input type="text" class="form-control" name="proses_bisnis"
                                                value="{{ $penjabaran->proses_bisnis }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="proses_bisnis">Strategic Inisiatif</label>
                                            <textarea class="form-control" id="strategis" name="strategis" rows="4">{{ $penjabaran->strategis }}</textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="pic">PIC</label>
                                            <input type="text" class="form-control" name="pic"
                                                value="{{ $penjabaran->pic }}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center mb-4">
                                <button class="btn btn-primary" type="submit">
                                  Update Penjabaran
                                </button>
                              </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection
