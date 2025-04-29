<link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}" type="text/css">
@extends('layouts.app')

@section('title', 'Kontrak Manajemen')
@section('content')


    <body>
        <?php
        $userId = Auth::user()->id;
        $name = Auth::user()->nama;
        $selectedYear = date('Y');
        if (isset($_GET['year'])) {
            $selectedYear = htmlspecialchars($_GET['year']);
        }
        ?>

        <div class="ml-5 main-content" id="panel">
            <!-- Topnav -->
            @include('partials.top')

            <div class="container-fluid">
                <div class="header-body">
                    <div class="row align-items-center py-4">
                        <div class="col-lg-6 col-7">
                            <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                                <ol class="breadcrumb breadcrumb-links">
                                    <li class="breadcrumb-item"><a href="/dashboard"><i class="fas fa-home"></i></a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Progres</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            @if (Auth::id() === 1)
                <!-- Admin View -->
                <div class="card card-body border-0 shadow table-wrapper table-responsive">
                    <!-- DataTable -->
                    <table class="table table-bordered" id="progresTable">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama Form</th>
                                <th>Status</th>
                                <th>Need Discussion</th>
                                <th>Meeting Date</th>
                                <th>Notes</th>
                                <th>Save</th>
                                <th>Detail Isi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($progresData as $index => $progres)
                                <tr>
                                    <form method="POST" action="{{ route('progres.update', $progres->id) }}">
                                        @csrf
                                        @method('PUT')
                                        <td>{{ $progresData->firstItem() + $index }}</td>
                                        <td>{{ $progres->iku_id }}</td>
                                        <td>
                                            <select name="status" class="form-control">
                                                <option value="pending"
                                                    {{ $progres->status === 'pending' ? 'selected' : '' }}>
                                                    Pending</option>
                                                <option value="accept"
                                                    {{ $progres->status === 'accept' ? 'selected' : '' }}>
                                                    Accept</option>
                                                <option value="reject"
                                                    {{ $progres->status === 'reject' ? 'selected' : '' }}>
                                                    Reject</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="need_discussion" class="form-control">
                                                <option value="0"
                                                    {{ $progres->need_discussion == 0 ? 'selected' : '' }}>
                                                    No</option>
                                                <option value="1"
                                                    {{ $progres->need_discussion == 1 ? 'selected' : '' }}>
                                                    Yes</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="date" name="meeting_date" class="form-control"
                                                value="{{ old('meeting_date', $progres->meeting_date) }}">
                                        </td>
                                        <td>
                                            <textarea name="notes" class="form-control">{{ $progres->notes }}</textarea>
                                        </td>
                                        <td>
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </td>
                                        <td>
                                            <a href="{{ route('iku.detail', $progres->iku_id) }}"
                                                class="btn btn-info">Detail</a>
                                        </td>
                                    </form>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="card card-body border-0 shadow table-wrapper table-responsive">
                    <table id="progresTable" class="table table-centered table-nowrap mb-0 rounded display">
                        <thead>
                            <tr>
                                <th class="border-0">No.</th>
                                <th class="border-0">Nama Form</th>
                                <th class="border-0">Status</th>
                                <th class="border-0">Need Discussion</th>
                                <th class="border-0">Meeting Date</th>
                                <th class="border-0">Revision</th>
                                <th class="border-0">Notes</th>
                                <th class="border-0">Download</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($progresData as $index => $progres)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $progres->iku_id }}</td>
                                    <td>{{ ucfirst($progres->status) }}</td>
                                    <td>{{ $progres->need_discussion ? 'Yes' : 'No' }}</td>
                                    <td>{{ $progres->meeting_date ? date('d/m/Y', strtotime($progres->meeting_date)) : '-' }}
                                    </td>
                                    <td>
                                        @if ($progres->status === 'reject')
                                            <a href="/iku" class="btn btn-warning">Revise</a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $progres->notes ?? '-' }}</td>
                                    <td>
                                        @if ($progres->status === 'accept')
                                            <a href="/iku" class="btn btn-success">Download</a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#progresTable').DataTable({
                    "paging": true,
                    "searching": true,
                    "ordering": true,
                    "info": true,
                    "lengthMenu": [5, 10, 25, 50, 100],
                    "pageLength": 10,
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
