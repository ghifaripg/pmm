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

@section('title', 'Edit Department')

@section('content')
    <main>
        <section class="vh-lg-100 mt-5 mt-lg-0 bg-soft d-flex align-items-center">
            <div class="container">
                <p class="text-center">
                    <a href="/dashboard" class="d-flex align-items-center justify-content-center">
                        <svg class="icon icon-xs me-2" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                        Back to homepage
                    </a>
                </p>
                <div class="row justify-content-center form-bg-image">
                    <div class="col-12 d-flex align-items-center justify-content-center">
                        <div class="bg-white shadow border-0 rounded border-light p-4 p-lg-5 w-100 fmxw-500">
                            <div class="text-center text-md-center mb-4">
                                <h1 class="h1">Edit Department</h1>
                            </div>
                            <form method="POST" action="{{ route('departments.update', $department->department_id) }}">
                                @csrf
                                <!-- User ID (Hidde -->
                                <div class="form-group">
                                    <label for="department_name">Department Name</label>
                                    <input type="text" name="department_name" id="department_name" class="form-control"
                                        value="{{ old('department_name', $department->department_name) }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="department_username">Department Username</label>
                                    <input type="text" name="department_username" id="department_username"
                                        class="form-control"
                                        value="{{ old('department_username', $department->department_username) }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="bisnis_terkait">PIC</label>
                                    <select name="bisnis_terkait[]" id="bisnis_terkait" class="form-control" multiple>
                                        @foreach ($allBisnis as $bisnis)
                                            <option value="{{ $bisnis->id }}"
                                                {{ in_array($bisnis->id, $selectedBisnisIds) ? 'selected' : '' }}>
                                                {{ $bisnis->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary mt-3">Save Changes</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
@push('scripts')
    <!-- jQuery (required for Select2) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#bisnis_terkait').select2({
                placeholder: "Select related bisnis",
                allowClear: true,
                maximumSelectionLength: 10
            });
        });
    </script>
@endpush
