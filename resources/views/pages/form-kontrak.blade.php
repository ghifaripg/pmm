<?php
$userId = Auth::user()->id;
$name = Auth::user()->nama;
$role = Auth::user()->role;
$selectedYear = date('Y');
if (isset($_GET['year'])) {
    $selectedYear = htmlspecialchars($_GET['year']);
}
?>
<style>
    body {
        overflow-x: hidden;
    }

    .table th,
    .table td {
        white-space: normal !important;
        /* Allows text wrapping */
        word-wrap: break-word !important;
        max-width: 100px;
    }
</style>

<!-- Favicon -->
<link rel="apple-touch-icon" sizes="120x120" href="{{ asset('assets/img/apple-touch-icon.png') }}">
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/img/favicon-32x32.png') }}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/favicon-16x16.png') }}">
<link rel="shortcut icon" href="{{ asset('assets/img/favicon.ico') }}">
<link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}" type="text/css">

@extends('layouts.app')

@section('title', 'Form Kontrak Manajemen')
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
                                <li class="breadcrumb-item"><a href="/kontrak">Pilih Tahun</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Form Kontrak Manajemen</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Tambah Sasaran Strategis -->
    <div class="ml-6 main-content row">
        <h3>Kontrak Manajemen Tahun <?php echo $selectedYear; ?></h3>
        <div class="col-12 mb-4">
            <h5>Sasaran Strategis</h5>
            <div class="card border-0 shadow components-section">
                <form method="POST" action="{{ route('store-sasaran') }}" class="mt-3 ml-3 mr-3 form-sasaran"
                    style="max-width: 45%">
                    @csrf
                    <input type="hidden" name="year" value="{{ $selectedYear }}">
                    <div class="mb-3">
                        <input type="text" name="sasaran_name" class="form-control" placeholder="Nama Sasaran Strategis"
                            required>
                    </div>
                    <button type="submit" class="btn btn-primary">Tambah Sasaran</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Pilih Sasaran Strategis -->
    <div class="ml-6 main-content row">
        <div class="col-12 mb-4">
            <div class="card border-0 shadow components-section">
                <div class="card-body">
                    <h5>Pilih Sasaran Strategis</h5>
                    <div id="sasaran-checkbox-list">
                        @foreach ($sasaranStrategis as $sasaran)
                            <div class="form-check d-flex align-items-center mb-2">
                                <input type="radio" class="form-check-input sasaran-checkbox" name="sasaran_strategis"
                                    value="{{ $sasaran->id }}" id="sasaran_{{ $sasaran->id }}">
                                <label class="form-check-label ms-2" for="sasaran_{{ $sasaran->id }}">
                                    {{ $sasaran->name }}
                                </label>
                                <form action="{{ route('delete-sasaran', $sasaran->id) }}" method="POST"
                                    onsubmit="return confirm('Hapus Sasaran Strategis Ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"
                                        style="margin-top: 0%; margin-bottom:3px; margin-left:12px; max-height:88%; max-width:90%">
                                        <i class="fas fa-trash-alt me-1"></i>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form KPI -->
    <div class="ml-6 main-content">
        <form method="POST" action="{{ route('store-kpi') }}">
            @csrf
            <input type="hidden" name="sasaran_id" id="selected-sasaran-id">
            <div class="col-12 mb-4">
                <div class="card border-0 shadow components-section">
                    <div class="card-body">
                        <h5>Sasaran Strategis: <span id="selected-sasaran">None</span></h5>
                        <div class="row mb-4">
                            <div class="col-lg-4 col-sm-6">
                                <div class="mb-3">
                                    <label for="kpi">Key Perfomance Indicator</label>
                                    <input type="text" class="form-control" name="kpi_name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="target">Target</label>
                                    <input type="text" class="form-control" name="target" required>
                                </div>
                                <div class="mb-3">
                                    <label for="satuan">Satuan</label>
                                    <input type="text" class="form-control" name="satuan" required>
                                </div>
                                <div class="mb-3">
                                    <label for="milestone">Milestone</label>
                                    <input type="text" class="form-control" name="milestone">
                                </div>
                                <div class="mb-3">
                                    <label for="esgc">ESG/C</label>
                                    <select name="esgc" class="form-control" required>
                                        <option value="E">E</option>
                                        <option value="S">S</option>
                                        <option value="G">G</option>
                                        <option value="C">C</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-6">
                            </div>
                            <div class="col-lg-4 col-sm-6">
                                <div class="mb-3">
                                    <label for="polaritas">Polaritas</label>
                                    <select name="polaritas" class="form-control" required>
                                        <option value="maximize">Maximize</option>
                                        <option value="minimize">Minimize</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="bobot">Bobot</label>
                                    <input type="number" class="form-control" name="bobot" min="0"
                                        max="100" step="0.01" required>
                                </div>
                                <div class="mb-3">
                                    <h5>Matriks Tanggung Jawab</h5>
                                    <label class="form-label">DI</label>
                                    <select name="du" class="form-control mb-2">
                                        <option value="O">O (Overall)</option>
                                        <option value="R">R (Responsible)</option>
                                        <option value="S">S (Support)</option>
                                    </select>
                                    <label class="form-label">DK&SDM</label>
                                    <select name="dk" class="form-control mb-2">
                                        <option value="O">O (Overall)</option>
                                        <option value="R">R (Responsible)</option>
                                        <option value="S">S (Support)</option>
                                    </select>
                                    <label class="form-label">DO</label>
                                    <select name="do" class="form-control mb-2">
                                        <option value="O">O (Overall)</option>
                                        <option value="R">R (Responsible)</option>
                                        <option value="S">S (Support)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4 d-flex justify-content-center">
                        <button class="btn btn-primary" type="submit" style="width: 320px">Submit</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="ml-6" style="background-color: white">
            <table class="table table-bordered table-striped ikuTable" id="ikuTable">

            <thead>
                <tr>
                    <th class="border-gray-200">#</th>
                    <th class="border-gray-200">Sasaran Strategis</th>
                    <th class="border-gray-200">Key Performance Indicator</th>
                    <th class="border-gray-200">Target</th>
                    <th class="border-gray-200">Satuan</th>
                    <th class="border-gray-200">Milestone</th>
                    <th class="border-gray-200">ESG/C</th>
                    <th class="border-gray-200">Polaritas</th>
                    <th class="border-gray-200">Bobot</th>
                    <th class="border-gray-200">DU</th>
                    <th class="border-gray-200">DK</th>
                    <th class="border-gray-200">DO</th>
                    <th class="border-gray-200">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sasaranGrouped as $sasaran)
                    @php
                        $rowCount = count($sasaran['kpis']);
                    @endphp
                    @foreach ($sasaran['kpis'] as $index => $kpi)
                        <tr>
                            @if ($index == 0)
                                <td class="fw-bold align-middle" rowspan="{{ $rowCount }}">{{ $sasaran['letter'] }}
                                </td>
                                <td class="fw-normal align-middle" rowspan="{{ $rowCount }}">{{ $sasaran['name'] }}
                                </td>
                            @endif
                            <td class="fw-normal">{{ $index + 1 }}. {{ $kpi->kpi_name }}</td>
                            <td class="fw-normal">{{ $kpi->target }}</td>
                            <td class="fw-normal">{{ $kpi->satuan }}</td>
                            <td class="fw-normal">{{ $kpi->milestone ?? '-' }}</td>
                            <td class="fw-normal">{{ $kpi->esgc }}</td>
                            <td class="fw-normal">{{ ucfirst($kpi->polaritas) }}</td>
                            <td class="fw-normal bobot-cell">{{ $kpi->bobot }}</td>
                            <td class="fw-normal">{{ $kpi->du }}</td>
                            <td class="fw-normal">{{ $kpi->dk }}</td>
                            <td class="fw-normal">{{ $kpi->do }}</td>
                            <td>
                                <div style="display: flex; justify-content: center; align-items: center; gap: 10px;">
                                    <!-- Edit Button -->
                                    <form action="{{ route('edit-kpi', $kpi->id) }}" method="GET" style="margin: 0;">
                                        @csrf
                                        <button type="submit" class="btn btn-pill btn-outline-tertiary"
                                            style="padding: 0; border: none; background: none;">
                                            <img src="{{ asset('assets/img/edit.png') }}" alt="Edit"
                                                style="width: 30px; height: 30px; object-fit: contain;">
                                        </button>
                                    </form>
                                    <!-- Delete Button -->
                                    <form id="delete-form-{{ $kpi->id }}"
                                        action="{{ route('delete-kpi', $kpi->id) }}" method="POST" style="margin: 0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-pill btn-outline-danger delete-btn"
                                            data-id="{{ $kpi->id }}"
                                            style="padding: 0; border: none; background: none;">
                                            <img src="{{ asset('assets/img/trash.png') }}" alt="Delete"
                                                style="width: 30px; height: 30px; object-fit: contain;">
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
        <h4 id="total-bobot">Total Bobot = 0</h4>
    </div>
@endsection
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".delete-btn").forEach(button => {
            button.addEventListener("click", function() {
                const kpiId = this.getAttribute("data-id");
                Swal.fire({
                    title: "Apakah Anda yakin?",
                    text: "Data ini akan dihapus secara permanen dan tidak dapat dikembalikan!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Ya, Hapus!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(`delete-form-${kpiId}`).submit();
                    }
                });
            });
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sasaranRadios = document.querySelectorAll('.sasaran-checkbox');
        const selectedSasaranInput = document.getElementById('selected-sasaran-id');
        const selectedSasaranText = document.getElementById('selected-sasaran');

        sasaranRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                selectedSasaranInput.value = this.value;
                selectedSasaranText.textContent = this.nextElementSibling.textContent;
            });
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
        function updateTotalBobot() {
            let totalBobot = 0;

            document.querySelectorAll(".bobot-cell").forEach(cell => {
                let bobotValue = parseFloat(cell.textContent.trim()) || 0;
                totalBobot += bobotValue;
            });

            let totalBobotElement = document.getElementById("total-bobot");
            totalBobotElement.textContent = `Total Bobot = ${totalBobot.toFixed(2)}`;

            if (totalBobot > 100) {
                totalBobotElement.style.color = "red";
            } else {
                totalBobotElement.style.color = "green";
            }
        }

        setTimeout(updateTotalBobot, 500);
    });
</script>
