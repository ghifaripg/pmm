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

@section('title', 'Edit User')

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
                                <h1 class="h1">Edit User</h1>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <form method="POST" action="{{ url('/users/update/' . $user->id) }}">
                                        @csrf
                                        @method('PUT')

                                        <input type="hidden" name="id" value="{{ $user->id }}">

                                        <!-- Username -->
                                        <div class="form-group mb-4">
                                            <label for="username">Username</label>
                                            <input type="text" name="username" class="form-control" id="username"
                                                value="{{ old('username', $user->username) }}" required>
                                        </div>

                                        <!-- Name -->
                                        <div class="form-group mb-4">
                                            <label for="nama">Full Name</label>
                                            <input type="text" name="nama" class="form-control" id="nama"
                                                value="{{ old('nama', $user->nama) }}" required>
                                        </div>

                                        <!-- Unit Kerja -->
                                        <div class="form-group mb-4">
                                            <label for="unit_kerja">Pilih Unit Kerja</label>
                                            <select name="unit_kerja" id="unit_kerja" class="form-control"
                                                @if ($user->id != 1) @endif>
                                                <option value="">-- Pilih Unit Kerja --</option>

                                                <optgroup label="Director">
                                                    @foreach ($directors as $director)
                                                        <option value="director_{{ $director->director_id }}"
                                                            @if ($user->director_id == $director->director_id && !$user->department_id && !$user->division_id) selected @endif>
                                                            -- {{ $director->director_name }}
                                                        </option>
                                                    @endforeach
                                                </optgroup>

                                                <optgroup label="Division">
                                                    @foreach ($directors as $director)
                                                        @foreach ($director->divisions as $division)
                                                            <option value="division_{{ $division->division_id }}"
                                                                @if ($user->division_id == $division->division_id && !$user->department_id) selected @endif>
                                                                -- {{ $division->division_name }}
                                                            </option>
                                                        @endforeach
                                                    @endforeach
                                                </optgroup>

                                                <optgroup label="Department">
                                                    @foreach ($directors as $director)
                                                        @foreach ($director->divisions as $division)
                                                            @foreach ($division->departments as $department)
                                                                <option value="department_{{ $department->department_id }}"
                                                                    @if ($user->department_id == $department->department_id) selected @endif>
                                                                    -- {{ $department->department_name }}
                                                                </option>
                                                            @endforeach
                                                        @endforeach

                                                        @foreach ($director->departments as $department)
                                                            @if (is_null($department->division_id))
                                                                <option value="department_{{ $department->department_id }}"
                                                                    @if ($user->department_id == $department->department_id) selected @endif>
                                                                    -- {{ $department->department_name }}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    @endforeach
                                                </optgroup>
                                            </select>

                                            @if ($user->id != 1)
                                                <input type="hidden" name="department_id"
                                                    value="{{ $user->department_id }}">
                                            @endif
                                        </div>
                                </div>

                                <div class="col-md-6">
                                    <!-- Department Role (conditionally shown) -->
                                    <div class="form-group mb-4" id="departmentRoleContainer" style="display: none;">
                                        <label for="department_role">Department Role</label>
                                        <select name="department_role" id="department_role" class="form-control">
                                            <option value="User"
                                                {{ $user->department_role == 'User' ? 'selected' : '' }}>User</option>
                                            <option value="Admin"
                                                {{ $user->department_role == 'Admin' ? 'selected' : '' }}>Admin</option>
                                        </select>
                                    </div>

                                    <!-- Password -->
                                    <div class="form-group mb-4">
                                        <label for="password">New Password (leave blank to keep current)</label>
                                        <input type="password" name="password" class="form-control" id="password">
                                    </div>

                                    <!-- Confirm Password -->
                                    <div class="form-group mb-4">
                                        <label for="password_confirmation">Confirm Password</label>
                                        <input type="password" name="password_confirmation" class="form-control"
                                            id="password_confirmation">
                                    </div>

                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <div class="d-grid mt-3">
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const select = document.getElementById('unit_kerja');
        const roleContainer = document.getElementById('departmentRoleContainer');

        function toggleRoleVisibility() {
            const val = select.value;
            if (val.startsWith('department_')) {
                roleContainer.style.display = '';
            } else {
                roleContainer.style.display = 'none';
            }
        }

        select.addEventListener('change', toggleRoleVisibility);
        toggleRoleVisibility();
    });
</script>

@endsection
