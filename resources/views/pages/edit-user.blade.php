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
                                <form method="POST" action="{{ url('/users/update/' . $user->id) }}">
                                    @csrf
                                    @method('POST')

                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />

                                    <!-- User ID (Hidde -->
                                    <input type="hidden" name="id" value="{{ $user->id }}">

                                    <!-- Name -->
                                    <div class="form-group mb-4">
                                        <label for="username">Your Name</label>
                                        <input type="text" name="username" class="form-control" id="username"
                                            value="{{ old('username', $user->username) }}" required>
                                    </div>

                                    <!-- Full Name -->
                                    <div class="form-group mb-4">
                                        <label for="nama">Full Name</label>
                                        <input type="text" name="nama" class="form-control" id="nama"
                                            value="{{ old('nama', $user->nama) }}" required>
                                    </div>

                                    <div class="form-group mb-4">
                                        <label for="department_id">Department</label>
                                        <select name="department_id" id="department_id" class="form-control">
                                            @foreach ($departments as $dept)
                                                <option value="{{ $dept->department_id }}"
                                                    {{ $user->department_id == $dept->department_id ? 'selected' : '' }}>
                                                    {{ $dept->department_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group mb-4">
                                        <label for="department_role">Role</label>
                                        <select name="department_role" id="department_role" class="form-control">
                                            @foreach ($roles as $role)
                                                <option value="{{ $role }}"
                                                    {{ isset($currentRole) && $currentRole === $role ? 'selected' : '' }}>
                                                    {{ $role }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Password -->
                                    <div class="form-group mb-4">
                                        <label for="password">New Password (leave blank to keep current password)</label>
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

                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    @endsection
