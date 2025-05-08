<?php
$userId = Auth::user()->id;
$name = Auth::user()->nama;
$role = Auth::user()->role;
$selectedYear = date('Y');
$selectedVersion = null;
if (isset($_GET['year'])) {
    $selectedYear = htmlspecialchars($_GET['year'], ENT_QUOTES, 'UTF-8');
}
if (isset($_GET['version'])) {
    $selectedVersion = htmlspecialchars($_GET['version'], ENT_QUOTES, 'UTF-8');
}
?>

<link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}" type="text/css">
<style>
    .table th,
    .table td {
        white-space: normal !important;
        overflow-wrap: break-word !important;
        word-break: normal !important;
    }
</style>
@extends('layouts.app')
@section('title', 'Form IKU')
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
                                <li class="breadcrumb-item"><a href="/iku">Pilih Tahun</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Form IKU</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pilih Sasaran Strategis -->
        <div class="ml-3">
            <div class="col-12">
                <div class="card border-0 shadow components-section">
                    <div class="card-body">
                        <h5>Pilih Perspektif</h5>
                        <div id="sasaran-checkbox-list">
                            @foreach ($sasaranStrategis as $sasaran)
                                <div class="form-check d-flex align-items-center mb-2">
                                    <input type="radio" class="form-check-input sasaran-checkbox" name="sasaran_strategis"
                                        value="{{ $sasaran->id }}" id="sasaran_{{ $sasaran->id }}">
                                    <label class="form-check-label ms-2" for="sasaran_{{ $sasaran->id }}">
                                        {{ $sasaran->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="ml-3 main-content">
            <form method="POST" action="{{ route('store-iku') }}">
                @csrf
                <input type="hidden" name="sasaran_id" id="selected-sasaran-id">
                <input type="hidden" name="year" value="{{ $selectedYear }}">
                <input type="hidden" name="version" value="{{ $selectedVersion }}">
                <input type="hidden" name="is_multi_point" value="{{ old('is_multi_point', 0) }}">

                <div class="col-12 mb-4">
                    <div class="card border-0 shadow components-section">
                        <div class="card-body">
                            <h5>Perspektif: <span id="selected-sasaran">-</span></h5>

                            <!-- IKU Type Selection -->
                            <div class="mb-3">
                                <label>IKU Type:</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="iku_type" id="singlePoint"
                                        value="single" checked>
                                    <label class="form-check-label" for="singlePoint">Satu Poin</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="iku_type" id="multiplePoints"
                                        value="multiple">
                                    <label class="form-check-label" for="multiplePoints">Beberapa Poin</label>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <!-- Left Section -->
                                <div class="col-lg-4 col-sm-6">
                                    <div class="mb-3">
                                        <h5>Key Address</h5>
                                        <label for="iku_atasan">IKU Atasan</label>
                                        <input type="text" class="form-control" name="iku_atasan" id="iku_atasan">
                                        <label for="target">Target</label>
                                        <input type="text" class="form-control" name="target" id="target">
                                    </div>
                                    <div class="my-4">
                                        <label for="proker">Program Kerja</label>
                                        <textarea class="form-control" placeholder="Tulis Program Kerja Anda...." id="proker" name="proker" rows="4"></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="pj">Penanggung Jawab</label>
                                        <input type="text" class="form-control" name="pj" id="pj">
                                    </div>
                                </div>

                                <div class="col-lg-2 col-sm-6"></div>

                                <!-- Right Section -->
                                <div class="col-lg-4 col-sm-6">
                                    <div class="mb-3">
                                        <label for="iku">Indikator Kinerja Utama (IKU)</label>
                                        <input type="text" class="form-control" name="iku" id="iku">
                                    </div>

                                    <!-- Single Point Section -->
                                    <div id="single-point-section">
                                        <h5>Detail Isi IKU</h5>
                                        <label>Base</label>
                                        <input type="text" class="form-control" name="single_base" id="single_base">
                                        <label>Stretch</label>
                                        <input type="text" class="form-control" name="single_stretch"
                                            id="single_stretch">
                                        <label>Satuan</label>
                                        <input type="text" class="form-control" name="single_satuan"
                                            id="single_satuan">
                                        <label>Polaritas</label>
                                        <select name="single_polaritas" id="single_polaritas" class="form-control">
                                            <option value="maximize">Maximize</option>
                                            <option value="minimize">Minimize</option>
                                        </select>
                                        <label>Bobot</label>
                                        <input type="number" class="form-control" name="single_bobot" id="single_bobot"
                                            step="0.01">
                                    </div>

                                    <!-- IKU Points Section -->
                                    <div id="multiple-points-section" style="display: none;">
                                        <h5>Poin IKU</h5>
                                        <div id="iku-points-container">
                                            <div class="iku-point mb-3">
                                                <label>Poin IKU</label>
                                                <input type="text" class="form-control" name="points[0][name]">
                                                <label>Base</label>
                                                <input type="text" class="form-control" name="points[0][base]">
                                                <label>Stretch</label>
                                                <input type="text" class="form-control" name="points[0][stretch]">
                                                <label>Satuan</label>
                                                <input type="text" class="form-control" name="points[0][satuan]">
                                                <label>Polaritas</label>
                                                <select name="points[0][polaritas]" class="form-select">
                                                    <option value="maximize">Maximize</option>
                                                    <option value="minimize">Minimize</option>
                                                </select>
                                                <label>Bobot</label>
                                                <input type="number" class="form-control point-bobot"
                                                    name="points[0][bobot]" step="0.01">
                                                <button type="button"
                                                    class="btn btn-danger btn-sm remove-point">Hapus</button>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-primary" id="add-iku-point">Tambah Poin
                                            Baru</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button class="mb-4 btn btn-primary" type="submit"
                            style="max-width: 420px; margin-left: 460px">Submit</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="ml-3 main-content table-responsive">
            <table class="table table-bordered table-striped ikuTable" id="ikuTable">
                <thead>
                    <tr>
                        <th class="border-0 text-center" rowspan="2">#</th>
                        <th class="border-0 text-center" rowspan="2">Perspektif</th>
                        <th class="border-0 text-center" colspan="2">Key Address</th>
                        <th class="border-0 text-center" rowspan="2">Indikator Kerja Utama</th>
                        <th class="border-0 text-center" colspan="2">Target</th>
                        <th class="border-0 text-center" rowspan="2">Satuan</th>
                        <th class="border-0 text-center" rowspan="2">Polaritas</th>
                        <th class="border-0 text-center" rowspan="2">Bobot</th>
                        <th class="border-0 text-center" rowspan="2">Program Kerja</th>
                        <th class="border-0 text-center" rowspan="2">Penanggung Jawab</th>
                        <th class="border-0 text-center" rowspan="2">Action</th>
                    </tr>
                    <tr>
                        <th class="border-0 text-center">IKU Atasan</th>
                        <th class="border-0 text-center">Target</th>
                        <th class="border-0 text-center">Base</th>
                        <th class="border-0 text-center">Stretch</th>
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
                                    <td class="fw-bold align-middle text-center" rowspan="{{ $totalRows }}">
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

                                <td class="fw-normal text-center" rowspan="{{ $maxRows }}">{!! nl2br(e($iku->proker)) !!}
                                </td>
                                <td class="fw-normal text-center" rowspan="{{ $maxRows }}">{{ $iku->pj }}</td>
                                <td class="fw-normal text-center" rowspan="{{ $maxRows }}">
                                    <div style="display: flex; justify-content: center; align-items: center; gap: 8px;">
                                        <!-- Edit Button -->
                                        <a href="{{ route('edit-iku', $iku->id) }}"
                                            style="display: flex; justify-content: center; align-items: center;">
                                            <img src="{{ asset('assets/img/edit.png') }}" alt="Edit"
                                                style="width: 25px; height: 25px; object-fit: contain;">
                                        </a>

                                        <!-- Delete Button -->
                                        <form id="delete-form-{{ $iku->id }}"
                                            action="{{ route('delete-iku', $iku->id) }}" method="POST"
                                            style="margin: 0;">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="redirect_url"
                                                id="redirect-url-{{ $iku->id }}">
                                            <button type="button" class="trash-button"
                                                onclick="confirmDelete({{ $iku->id }})"
                                                style="padding: 0; border: none; background: none;">
                                                <img src="{{ asset('assets/img/trash.png') }}" alt="Delete"
                                                    style="width: 25px; height: 25px; object-fit: contain;">
                                            </button>
                                        </form>
                                    </div>
                                </td>

                                <script>
                                    function confirmDelete(id) {
                                        let currentUrl = window.location.href;
                                        document.getElementById(`redirect-url-${id}`).value = currentUrl;

                                        Swal.fire({
                                            title: "Apakah Anda yakin?",
                                            text: "Data ini akan dihapus secara permanen dan tidak dapat dikembalikan!",
                                            icon: "warning",
                                            showCancelButton: true,
                                            confirmButtonColor: "#d33",
                                            cancelButtonColor: "#3085d6",
                                            confirmButtonText: "Iya, Hapus!",
                                            cancelButtonText: "Batal"
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                document.getElementById(`delete-form-${id}`).submit();
                                            }
                                        });
                                    }
                                </script>

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


            <h4 id="total-bobot">Total Bobot = <span id="bobot-value">0</span></h4>
        </div>
    </div>
@endsection


<script>
    document.addEventListener('DOMContentLoaded', function() {
        let pointIndex = 0;

        // DOM Elements
        const sasaranRadios = document.querySelectorAll('.sasaran-checkbox');
        const selectedSasaranInput = document.getElementById('selected-sasaran-id');
        const selectedSasaranText = document.getElementById('selected-sasaran');
        const singlePointRadio = document.getElementById("singlePoint");
        const multiplePointsRadio = document.getElementById("multiplePoints");
        const singlePointSection = document.getElementById("single-point-section");
        const multiplePointsSection = document.getElementById("multiple-points-section");
        const totalBobotElement = document.getElementById("total-bobot");
        const bobotValueElement = document.getElementById("bobot-value");
        const ikuPointsContainer = document.getElementById('iku-points-container');
        const addIkuPointButton = document.getElementById('add-iku-point');

        // Toggle sections based on selected radio button
        function toggleSections() {
            if (singlePointRadio.checked) {
                singlePointSection.style.display = "block";
                multiplePointsSection.style.display = "none";
            } else if (multiplePointsRadio.checked) {
                singlePointSection.style.display = "none";
                multiplePointsSection.style.display = "block";
            }
        }

        // Update the total bobot and its color
        function updateTotalBobot() {
            let totalBobot = 0;

            document.querySelectorAll('.bobot-cell').forEach(cell => {
                totalBobot += parseFloat(cell.textContent.trim()) || 0;
            });

            totalBobotElement.textContent = `Total Bobot = ${totalBobot.toFixed(2)}`;
            totalBobotElement.style.color = totalBobot > 100 ? "red" : "green";
            bobotValueElement.textContent = totalBobot.toFixed(2);
        }

        // Event listeners
        sasaranRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                selectedSasaranInput.value = this.value;
                selectedSasaranText.textContent = this.nextElementSibling.textContent;
            });
        });

        singlePointRadio.addEventListener("change", toggleSections);
        multiplePointsRadio.addEventListener("change", toggleSections);

        // Initial total bobot update
        updateTotalBobot();

        // Recalculate total bobot when a bobot cell changes
        document.querySelectorAll('.bobot-cell').forEach(cell => {
            cell.addEventListener('DOMSubtreeModified', updateTotalBobot);
        });

        // Add a new IKU point
        addIkuPointButton.addEventListener('click', function() {
            pointIndex++;
            const pointHtml = `
                <div class="iku-point mb-3" data-index="${pointIndex}">
                    <label>Point Name</label>
                    <input type="text" class="form-control" name="points[${pointIndex}][name]">
                    <label>Base</label>
                    <input type="text" class="form-control" name="points[${pointIndex}][base]">
                    <label>Stretch</label>
                    <input type="text" class="form-control" name="points[${pointIndex}][stretch]">
                    <label>Satuan</label>
                    <input type="text" class="form-control" name="points[${pointIndex}][satuan]">
                    <label>Polaritas</label>
                    <select name="points[${pointIndex}][polaritas]" class="form-select">
                        <option value="maximize">Maximize</option>
                        <option value="minimize">Minimize</option>
                    </select>
                    <label>Bobot</label>
                    <input type="number" class="form-control point-bobot" name="points[${pointIndex}][bobot]" step="0.01">
                    <button type="button" class="btn btn-danger btn-sm remove-point">Hapus</button>
                </div>`;
            ikuPointsContainer.insertAdjacentHTML('beforeend', pointHtml);
        });

        // Remove an IKU point
        ikuPointsContainer.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-point')) {
                e.target.closest('.iku-point').remove();
            }
        });
    });
</script>
