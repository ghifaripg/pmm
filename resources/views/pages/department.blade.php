<?php
$userId = Auth::user()->id;
$name = Auth::user()->nama;
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
                                <li class="breadcrumb-item active" aria-current="page">List Departement</li>
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
                                <th class="border-0">Name</th>
                                <th class="border-0">Username</th>
                                <th class="border-0">PIC</th>
                                <th class="border-0">Edit</th>
                                <th class="border-0 rounded-end">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($departments as $department)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $department->department_name }}</td>
                                    <td>{{ $department->department_username }}</td>
                                    <td>{{ $department->bisnis_names ?? '-' }}</td>
                                    <td>
                                        <a href="{{ url('/departments/edit/' . $department->department_id) }}"
                                            class="btn btn-pill btn-outline-primary">Edit</a>
                                    </td>
                                    <td>
                                        <a href="{{ url('/departments/delete/' . $department->department_id) }}"
                                            onclick="return confirm('Are you sure you want to delete this department?')"
                                            class="btn btn-pill btn-outline-danger">Delete</a>
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
    <script>
        $(document).ready(function() {
            $('#ListUserTable').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "lengthMenu": [5, 10, 25, 50, 100],
                "pageLength": 5,
                "language": {
                    "search": "Search Data:",
                    "lengthMenu": "Show _MENU_ entries",
                    "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                    "paginate": {
                        "first": "First",
                        "last": "Last",
                        "next": "Next",
                        "previous": "Previous"
                    }
                }
            });
        });
    </script>
@endsection
