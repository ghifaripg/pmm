<?php
$userId = Auth::user()->id;
$name = Auth::user()->nama;
$selectedYear = date('Y');
if (isset($_GET['year'])) {
    $selectedYear = htmlspecialchars($_GET['year']);
}
$department_id = Auth::user()->department_id;
$department = DB::table('department')->where('department_id', $department_id)->select('department_username')->first();
$departmentName = (string) $department->department_username;
?>

<style>
    .table-container {
        overflow-x: auto;
        max-height: 800px;
    }

    .table th,
    .table td {
        white-space: normal !important;
        word-break: normal !important;
        overflow-wrap: break-word !important;
        max-width: 250px;
        text-align: center;
        vertical-align: middle;
        padding: 8px;
    }

    /* Optional: Responsive tweaks for small screens */
    @media (max-width: 768px) {
        .table-container {
            max-height: 500px;
        }

        .table th,
        .table td {
            font-size: 12px;
        }
    }
</style>
<link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}" type="text/css">

<!-- Favicon -->
<link rel="apple-touch-icon" sizes="120x120" href="{{ asset('assets/img/apple-touch-icon.png') }}">
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/img/favicon-32x32.png') }}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/favicon-16x16.png') }}">
<link rel="shortcut icon" href="{{ asset('assets/img/favicon.ico') }}">

@extends('layouts.app')

@section('title', 'IKU')
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
                                <li class="breadcrumb-item"><a href="/iku">IKU</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Pilih Tahun</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <div class="ml-5 main-content">
            <div class="mb-3 mb-lg-0">
                <h1>IKU <?php echo $departmentName; ?> Tahun <?php echo $selectedYear; ?></h1>
                <form method="GET" class="mb-3">
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
                    <button type="submit" class="btn btn-primary">Pilih</button>
                </form>
            </div>
        </div>


        <div class="ml-4 main-content row">
            <div class="col-12 mb-4">
                <h5>Versi IKU</h5>
                <div class="card border-0 shadow components-section">
                    <!-- Form to Add New Version -->
                    <form method="POST" action="{{ route('iku.addVersion') }}" class="mt-3 ml-3 mr-3"
                        style="max-width: 45%">
                        @csrf
                        <input type="hidden" name="iku_id" value="{{ $iku_ikuIdentifier }}">
                        <input type="hidden" name="year" value="{{ $selectedYear }}">
                        <button type="submit" class="btn btn-primary">Tambah Versi</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Pilih Versi IKU -->
        <div class="ml-4 main-content row">
            <div class="col-12 mb-4">
                <div class="card border-0 shadow components-section">
                    <div class="card-body">
                        <h5>Pilih Versi IKU</h5>
                        <div id="version-radio-list">
                            @foreach ($versions as $version)
                                <div class="form-check d-flex align-items-center mb-2">
                                    <input type="radio" class="form-check-input version-radio" name="selected_version"
                                        value="{{ $version }}" id="version_{{ $version }}"
                                        {{ $version == $selectedVersion ? 'checked' : '' }}>
                                    <label class="form-check-label ms-2" for="version_{{ $version }}">
                                        Versi {{ $version }}
                                    </label>
                                    <form
                                        action="{{ route('iku.deleteVersion', ['iku_id' => $iku_ikuIdentifier, 'version' => $version]) }}"
                                        method="POST" onsubmit="return confirm('Hapus Versi Ini?');">
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
                        <!-- Button to Choose Selected Version -->
                        <form method="GET" action="{{ route('iku.show') }}" class="mt-3">
                            <input type="hidden" name="year" value="{{ $selectedYear }}">
                            <input type="hidden" name="version" id="selected_version_input">
                            <button type="submit" class="btn btn-primary" id="choose-version-btn">Pilih Versi
                                Ini</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-content" style="padding-left: 20px;">
            <div style="display: flex; align-items: center; margin-top: 25px; margin-bottom: 25px;">
                <img src="{{ asset('assets/img/logo-ksp.png') }}" class="img-kiecs" alt="">
                <h4 style="text-transform: uppercase; margin-left: auto;">form iku <?php echo $departmentName; ?> <?php echo $selectedYear; ?>
                </h4>
            </div>

            <!-- Search Bar -->
            <div class="mb-3" style="text-align: right; max-width: 100%;">
                <input type="text" id="searchInput" class="form-control" placeholder="Search...">
            </div>

            <!-- Table Container -->
            <div class="table-container">
                <table class="table table-bordered table-striped w-100" id="ikuTable">
                    <thead class="text-white" style="background-color: #2e2abd;">
                        <tr>
                            <th class="text-center" rowspan="2">#</th>
                            <th class="text-center" rowspan="2">Perspektif</th>
                            <th colspan="2">Key Address</th>
                            <th class="text-center" rowspan="2">Indikator Kerja Utama</th>
                            <th colspan="2">Target</th>
                            <th class="text-center" rowspan="2">Satuan</th>
                            <th class="text-center" rowspan="2">Polaritas</th>
                            <th class="text-center" rowspan="2">Bobot</th>
                            <th class="text-center" rowspan="2">Program Kerja</th>
                            <th class="text-center" rowspan="2">Penanggung Jawab</th>
                        </tr>
                        <tr>
                            <th>IKU Atasan</th>
                            <th>Target</th>
                            <th>Base</th>
                            <th>Stretch</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sasaranGrouped as $sasaran)
                            @php
                                $ikuCount = count($sasaran['ikus']);
                                $totalRows = 0;
                                $ikuAtasanRowspan = [];
                                $targetRowspan = [];

                                foreach ($sasaran['ikus'] as $iku) {
                                    $ikuPointList = collect($iku->points ?? []);
                                    $maxRows = max(1, $ikuPointList->count());
                                    $totalRows += $maxRows;

                                    $ikuAtasanRowspan[$iku->iku_atasan] =
                                        ($ikuAtasanRowspan[$iku->iku_atasan] ?? 0) + $maxRows;
                                    $targetRowspan[$iku->target] = ($targetRowspan[$iku->target] ?? 0) + $maxRows;
                                }
                            @endphp

                            @foreach ($sasaran['ikus'] as $index => $iku)
                                @php
                                    $ikuPointList = collect($iku->points ?? []);
                                    $maxRows = max(1, $ikuPointList->count());
                                @endphp

                                <tr>
                                    @if ($index == 0)
                                        <td class="align-middle text-center" rowspan="{{ $totalRows }}">
                                            {{ $sasaran['number'] }}
                                        </td>
                                        <td class="fw-normal align-middle text-center" rowspan="{{ $totalRows }}">
                                            {{ $sasaran['perspektif'] }}
                                        </td>
                                    @endif

                                    @if ($ikuAtasanRowspan[$iku->iku_atasan] > 0)
                                        <td class="fw-normal text-center"
                                            rowspan="{{ $ikuAtasanRowspan[$iku->iku_atasan] }}">
                                            {{ $iku->iku_atasan }}
                                        </td>
                                        @php
                                            $ikuAtasanRowspan[$iku->iku_atasan] = 0;
                                        @endphp
                                    @endif

                                    @if ($targetRowspan[$iku->target] > 0)
                                        <td class="fw-normal text-center" rowspan="{{ $targetRowspan[$iku->target] }}">
                                            {{ $iku->target }}
                                        </td>
                                        @php
                                            $targetRowspan[$iku->target] = 0;
                                        @endphp
                                    @endif

                                    <td class="fw-normal text-start" rowspan="{{ $maxRows }}">
                                        <a class="fw-normal text-center">{{ $iku->iku }}</a>
                                        @if ($ikuPointList->isNotEmpty())
                                            <ul class="m-0 p-0">
                                                @foreach ($ikuPointList as $point)
                                                    <li style="font-size: 0.875rem;">{{ $point->point_name }}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </td>

                                    @php
                                        $firstPoint = $ikuPointList->first() ?? null;
                                    @endphp
                                    <td class="fw-normal text-center">
                                        {{ $firstPoint->base ?? ($iku->base ?? '-') }}
                                    </td>
                                    <td class="fw-normal text-center">
                                        {{ $firstPoint->stretch ?? ($iku->stretch ?? '-') }}
                                    </td>
                                    <td class="fw-normal text-center">
                                        {{ $firstPoint->satuan ?? ($iku->satuan ?? '-') }}
                                    </td>
                                    <td class="fw-normal text-center">
                                        {{ ucfirst($firstPoint->polaritas ?? ($iku->polaritas ?? '-')) }}
                                    </td>
                                    <td class="fw-normal bobot-cell">
                                        {{ $firstPoint->bobot ?? ($iku->bobot ?? '-') }}
                                    </td>

                                    <td class="fw-normal text-center" rowspan="{{ $maxRows }}">
                                        {!! nl2br(e($iku->proker)) !!}</td>
                                    <td class="fw-normal text-center" rowspan="{{ $maxRows }}">{{ $iku->pj }}
                                    </td>
                                </tr>

                                @if ($ikuPointList->count() > 1)
                                    @foreach ($ikuPointList->slice(1) as $point)
                                        <tr>
                                            <td class="fw-normal text-center">{{ $point->base ?? '-' }}</td>
                                            <td class="fw-normal text-center">{{ $point->stretch ?? '-' }}</td>
                                            <td class="fw-normal text-center">{{ $point->satuan ?? '-' }}</td>
                                            <td class="fw-normal text-center">{{ ucfirst($point->polaritas ?? '-') }}</td>
                                            <td class="fw-normal bobot-cell">{{ $point->bobot ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>


    </div>

    <script>
        document.getElementById("searchInput").addEventListener("keyup", function() {
            var filter = this.value.toLowerCase();
            var rows = document.querySelectorAll("#ikuTable tbody tr");

            rows.forEach(function(row) {
                var text = row.innerText.toLowerCase();
                row.style.display = text.includes(filter) ? "" : "none";
            });
        });
    </script>
    <br>

    <div class="ml-6 main-content mt-1 mb-3 d-flex align-items-center">
        <!-- First button -->
        <a href="/form-iku?year=<?php echo $selectedYear; ?>&version=<?php echo $selectedVersion; ?>"
            class="btn btn-outline-primary d-inline-flex align-items-center me-2">
            <svg class="icon icon-xs me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                </path>
            </svg>
            Tambah/Ubah
        </a>

        <!-- Second button container -->
        @php
            $isAccepted = DB::table('progres')
                ->where('iku_id', $iku_ikuIdentifier)
                ->where('status', 'accept')
                ->exists();
        @endphp

        @if ($isAccepted)
            <form action="{{ route('export.iku') }}" method="GET" class="me-auto">
                <input type="hidden" name="year" value="{{ $selectedYear }}">
                <button type="button" class="mt-3 ml-2 btn btn-outline-success d-inline-flex align-items-center"
                    onclick="getNamesAndExport()">
                    <svg class="icon icon-xs ms-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M2 9.5A3.5 3.5 0 005.5 13H9v2.586l-1.293-1.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 15.586V13h2.5a4.5 4.5 0 10-.616-8.958 4.002 4.002 0 10-7.753 1.977A3.5 3.5 0 002 9.5zm9 3.5H9V8a1 1 0 012 0v5z"
                            clip-rule="evenodd" />
                    </svg>
                    Export to Excel
                </button>
            </form>
        @else
            <form action="/progres" method="GET" class="me-auto">
                <button type="submit" class="btn btn-pill btn-outline-info">Progres Form IKU</button>
            </form>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const versionRadios = document.querySelectorAll('.version-radio');
            const selectedVersionInput = document.getElementById('selected_version_input');
            const chooseVersionBtn = document.getElementById('choose-version-btn');

            versionRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    selectedVersionInput.value = this.value;
                });
            });

            const selectedRadio = document.querySelector('.version-radio:checked');
            if (selectedRadio) {
                selectedVersionInput.value = selectedRadio.value;
            }
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let zoomLevel = 1;
            const zoomContainer = document.getElementById("zoomContainer");

            document.querySelectorAll(".zoom-btn").forEach(button => {
                button.addEventListener("click", function() {
                    const zoomType = this.getAttribute("data-zoom");

                    if (zoomType === "in" && zoomLevel < 1.5) {
                        zoomLevel += 0.1;
                    } else if (zoomType === "out" && zoomLevel > 0.7) {
                        zoomLevel -= 0.1;
                    }

                    zoomContainer.style.transform = `scale(${zoomLevel})`;
                    zoomContainer.style.transformOrigin = "top center";
                });
            });
        });
    </script>
    <script>
        async function getNamesAndExport() {
            const {
                value: formValues
            } = await Swal.fire({
                title: "Masukkan Nama Pimpinan",
                html: `
            <label for="swal-input1">HC & Finance Directorate</label>
            <input id="swal-input1" class="swal2-input" placeholder="Nama HC & Finance Directorate">
            <label for="swal-input2">Manager</label>
            <input id="swal-input2" class="swal2-input" placeholder="Nama Manager">
        `,
                focusConfirm: false,
                preConfirm: () => {
                    return {
                        hc_directorate: document.getElementById("swal-input1").value,
                        manager: document.getElementById("swal-input2").value,
                    };
                }
            });

            if (formValues) {
                const year = document.querySelector('input[name="year"]').value;
                formValues.year = year;

                const queryString = new URLSearchParams(formValues).toString();
                window.location.href = "{{ route('export.iku') }}?" + queryString;
            }
        }
    </script>
@endsection
