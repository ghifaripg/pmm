<?php
$userId = Auth::user()->id;
$name = Auth::user()->nama;
$role = Auth::user()->role;
$selectedYear = date('Y');
if (isset($_GET['year'])) {
    $selectedYear = htmlspecialchars($_GET['year']);
}
?>

@extends('layouts.app')
@section('title', 'List of User')
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
                                <li class="breadcrumb-item active" aria-current="page">List User</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <div class="card border-0 shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="ListUserTable" class="table table-centered table-nowrap mb-0 rounded">
                        <thead class="thead-light">
                            <tr>
                                <th class="border-0 rounded-start">No</th>
                                <th class="border-0">Nama</th>
                                <th class="border-0">Username</th>
                                <th class="border-0">Nama Unit Kerja</th>
                                <th class="border-0">Ubah</th>
                                <th class="border-0 rounded-end">Hapus</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $user->nama }}</td>
                                    <td>{{ $user->username }}</td>
                                    <td>{{ $user->unit_kerja_name ?? 'No Unit Kerja' }}</td>

                                    <td>
                                        <a href="{{ url('/users/edit/' . $user->id) }}"
                                            class="btn btn-pill btn-outline-primary">Ubah</a>
                                    </td>
                                    <td>
                                        <button class="btn btn-pill btn-outline-danger delete-user-btn"
                                            data-id="{{ $user->id }}" data-name="{{ $user->nama }}">
                                            Hapus
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script>
        $(document).ready(function() {
            $('#ListUserTable').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                info: true,
                lengthMenu: [5, 10, 25, 50, 100],
                pageLength: 5,
                language: {
                    search: "Search Data:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    }
                }
            });

            $('.delete-user-btn').click(function() {
                let userId = $(this).data('id');
                let userName = $(this).data('name');

                Swal.fire({
                    title: 'Hapus User?',
                    text: `Apakah Anda yakin ingin menghapus user "${userName}"?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = `/users/delete/${userId}`;
                    }
                });
            });
        });
    </script>

@endsection
