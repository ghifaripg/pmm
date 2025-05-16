<link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}" type="text/css">
<style>
    .table th,
    .table td {
        white-space: normal !important;
        overflow-wrap: break-word !important;
        word-break: normal !important;
        max-width: 250px;
    }
</style>
@extends('layouts.app')

@section('title', 'Kontrak Manajemen')
@section('content')

    <body>
        <?php
        $userId = Auth::user()->id;
        $name = Auth::user()->nama;
        $role = Auth::user()->role;
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
                                    <li class="breadcrumb-item"><a href="/kontrak">Kontrak Manajemen</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Pilih Tahun</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <form method="GET" class="ml-5 mt-3 main-content">
                <label for="year" class="form-label">Pilih Tahun:</label>
                <select name="year" id="year" class="form-control w-auto d-inline">
                    <?php for ($year = 2024; $year <= 2030; $year++): ?>
                    <option value="<?php echo $year; ?>" <?php if ($year == $selectedYear) {
                        echo 'selected';
                    } ?>>
                        <?php echo $year; ?>
                    </option>
                    <?php endfor; ?>
                </select>
                <button type="submit" class="btn btn-default">Pilih</button>
            </form>
            <form action="{{ route('export.kontrak') }}" class="ml-5 main-content" method="GET"
                style="width: 200px; height: 10px">
                <input type="hidden" name="year" value="{{ $selectedYear }}">
                <button type="button" class="btn btn-outline-success" onclick="getNamesAndExport()">
                    Export to Excel
                    <svg style="width: 20px; height: 20px" class="icon icon-xxs ms-2" xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M2 9.5A3.5 3.5 0 005.5 13H9v2.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 15.586V13h2.5a4.5 4.5 0 10-.616-8.958 4.002 4.002 0 10-7.753 1.977A3.5 3.5 0 002 9.5zm9 3.5H9V8a1 1 0 012 0v5z"
                            clip-rule="evenodd" />
                    </svg>

                </button>
            </form>
            <div class="ml-4 main-content" style="max-width: 1440px">
                <div
                    style="display: flex; justify-content: space-between; align-items: center; margin-left: 12px; margin-top: 25px; margin-bottom: 25px;">
                    <h3>KONTRAK MANAJEMEN TAHUN <?php echo $selectedYear; ?></h3>
                    <img src="{{ asset('assets/img/Picture1.png') }}" class="img-kiec" alt="">
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped w-100" id="ikuTable">
                        <thead class="text-white" style="background-color: #2e2abd;">
                            <tr>
                                <th class="text-center" rowspan="2">#</th>
                                <th class="text-center" rowspan="2">Sasaran Strategis</th>
                                <th class="text-center" rowspan="2">Key Performance Indicator</th>
                                <th class="text-center" rowspan="2">Target</th>
                                <th class="text-center" rowspan="2">Satuan</th>
                                <th class="text-center" rowspan="2">Milestone</th>
                                <th class="text-center" rowspan="2">ESG/C</th>
                                <th class="text-center" rowspan="2">Polaritas</th>
                                <th class="text-center" rowspan="2">Bobot</th>
                                <th class="text-center" colspan="3">Matriks Tanggung Jawab</th>
                            </tr>
                            <tr>
                                <th class="text-center">DU</th>
                                <th class="text-center">DK</th>
                                <th class="text-center">DO</th>
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
                                            <td class="fw-bold align-middle text-center" rowspan="{{ $rowCount }}">
                                                {{ $sasaran['letter'] }}</td>
                                            <td class="fw-normal align-middle text-center" rowspan="{{ $rowCount }}">
                                                {{ $sasaran['name'] }}</td>
                                        @endif
                                        <td class="fw-normal text-center">{{ $index + 1 }}. {{ $kpi->kpi_name }}
                                        </td>
                                        <td class="fw-normal text-center">{{ $kpi->target }}</td>
                                        <td class="fw-normal text-center">{{ $kpi->satuan }}</td>
                                        <td class="fw-normal text-center">{{ $kpi->milestone ?? '-' }}</td>
                                        <td class="fw-normal text-center">{{ $kpi->esgc }}</td>
                                        <td class="fw-normal text-center">{{ ucfirst($kpi->polaritas) }}</td>
                                        <td class="fw-normal text-center">{{ $kpi->bobot }}</td>
                                        <td class="fw-normal text-center">{{ $kpi->du }}</td>
                                        <td class="fw-normal text-center">{{ $kpi->dk }}</td>
                                        <td class="fw-normal text-center">{{ $kpi->do }}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <h5 style="white-space: pre">
                O (Overall) : Bertanggung Jawab secara keseluruhan
                R (Responsible) : Penanggung Jawab, Pemilik Proses
                S (Support) : Pendukung
            </h5>
            <div class="ml-5 mb-4 main-content">
                <a href="{{ route('check-kontrak', ['year' => $selectedYear]) }}" class="btn btn-primary">
                    <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tambah/Ubah
                </a>
            </div>
        </div>
        <!-- Notyf CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">

        <!-- Notyf JS -->
        <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>

        <script>
            async function getNamesAndExport() {
                const {
                    value: formValues
                } = await Swal.fire({
                    title: "Masukkan Nama Pimpinan",
                    html: `
                <label for="swal-input1">Direktur Utama</label>
                <input id="swal-input1" class="swal2-input" placeholder="Nama Direktur Utama">
                <label for="swal-input2">Plt. Direktur Keuangan & SDM</label>
                <input id="swal-input2" class="swal2-input" placeholder="Nama Plt. Direktur Keuangan & SDM">
                <label for="swal-input3">Direktur Operasi</label>
                <input id="swal-input3" class="swal2-input" placeholder="Nama Direktur Operasi">
            `,
                    focusConfirm: false,
                    preConfirm: () => {
                        return {
                            direktur_utama: document.getElementById("swal-input1").value,
                            plt_keuangan_sdm: document.getElementById("swal-input2").value,
                            direktur_operasi: document.getElementById("swal-input3").value,
                        };
                    }
                });

                if (formValues) {
                    const queryString = new URLSearchParams(formValues).toString();
                    window.location.href = "/export-kontrak-manajemen?" + queryString;
                }
            }
        </script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const notyf = new Notyf({
                    duration: 4000,
                    position: {
                        x: 'right',
                        y: 'top'
                    },
                    dismissible: true
                });

                @if (session('error'))
                    notyf.error("{{ session('error') }}");
                @endif
            });
        </script>
