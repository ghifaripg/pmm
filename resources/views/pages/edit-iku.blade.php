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

@section('title', 'Edit IKU')

@section('content')
    <main class="content">
        <div class="container">
            <h2>Edit IKU</h2>
            <a href="{{ url('/form-iku?year=' . date('Y')) }}" class="btn btn-primary mb-3">Back</a>

            <form method="POST" action="{{ route('update-iku', ['id' => $iku->id]) }}">
                @csrf
                @method('PUT')

                <input type="hidden" name="sasaran_id" value="{{ $iku->sasaran_id }}">

                <div class="card border-0 shadow">
                    <div class="card-body">
                        <div class="row">
                            <!-- Left Section -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">IKU Atasan</label>
                                    <input type="text" name="iku_atasan" class="form-control"
                                        value="{{ $iku->iku_atasan }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Target</label>
                                    <input type="text" name="target" class="form-control" value="{{ $iku->target }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">IKU</label>
                                    <input type="text" name="iku" class="form-control" value="{{ $iku->iku }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Base</label>
                                    <input type="text" name="base" class="form-control" value="{{ $iku->base }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Stretch</label>
                                    <input type="text" name="stretch" class="form-control" value="{{ $iku->stretch }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Satuan</label>
                                    <input type="text" name="satuan" class="form-control" value="{{ $iku->satuan }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Polaritas</label>
                                    <select name="polaritas" class="form-control">
                                        <option class="form-control" value="maximize"
                                            {{ $iku->polaritas == 'maximize' ? 'selected' : '' }}>Maximize</option>
                                        <option class="form-control" value="minimize"
                                            {{ $iku->polaritas == 'minimize' ? 'selected' : '' }}>Minimize</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Bobot</label>
                                    <input type="number" name="bobot" class="form-control" value="{{ $iku->bobot }}"
                                        min="0" max="100">
                                </div>

                                <div class="mb-3">
                                    <label for="proker">Program Kerja</label>
                                    <textarea class="form-control" id="proker" name="proker" rows="4">{{ $iku->proker }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Penanggung Jawab</label>
                                    <input type="text" name="pj" class="form-control" value="{{ $iku->pj }}">
                                </div>
                            </div>

                            <!-- Right Section -->
                            <div class="col-md-6">
                                <!-- IKU Points Section -->
                                @foreach ($ikuPoints as $index => $point)
                                    <h4>IKU Points</h4>
                                    <input type="hidden" name="points[{{ $point->id }}][id]" class="form-control"
                                        value="{{ $point->id }}">
                                    <div class="mb-3">
                                        <label class="form-label">Point Name</label>
                                        <input type="text" name="points[{{ $point->id }}][point_name]"
                                            class="form-control" value="{{ $point->point_name }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Base</label>
                                        <input type="text" name="points[{{ $point->id }}][base]"
                                            class="form-control" value="{{ $point->base }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Stretch</label>
                                        <input type="text" name="points[{{ $point->id }}][stretch]"
                                            class="form-control" value="{{ $point->stretch }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Satuan</label>
                                        <input type="text" name="points[{{ $point->id }}][satuan]"
                                            class="form-control" value="{{ $point->satuan }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Polaritas</label>
                                        <select name="points[{{ $point->id }}][polaritas]" class="form-control">
                                            <option class="form-control" value="maximize"
                                                {{ $point->polaritas == 'maximize' ? 'selected' : '' }}>Maximize</option>
                                            <option class="form-control" value="minimize"
                                                {{ $point->polaritas == 'minimize' ? 'selected' : '' }}>Minimize</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Bobot</label>
                                        <input type="number" name="points[{{ $point->id }}][bobot]"
                                            class="form-control" value="{{ $point->bobot }}" min="0"
                                            max="100">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="mb-4 d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>
@endsection
