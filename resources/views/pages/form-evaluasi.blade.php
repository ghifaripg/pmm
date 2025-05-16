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
    .table th,
    .table td {
        white-space: normal !important;
        overflow-wrap: break-word !important;
        word-break: normal !important;
        max-width: 250px;
    }
</style>

@extends('layouts.app')

@section('title', 'Form Evaluasi')
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
                                <li class="breadcrumb-item"><a href="/evaluasi">Pilih Periode</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Form Evaluasi</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="ml-6 mt-3">
        <div class="col-12 mb-4">
            <div class="card border-0 shadow components-section">
                <div class="card-body">
                    <h4>Form Evaluasi IKU Bulan {{ $selectedMonthName }}</h4>
                    <div id="sasaran-checkbox-list">
                        <!-- IKU Selector -->
                        <div class="mb-3">
                            <label for="iku-selector"><strong>Pilih Indikator Kinerja Utama</strong></label>
                            <select id="iku-selector" class="form-control">
                                <option value="">-- Pilih IKU --</option>
                                @foreach ($sasaranGrouped as $perspektif)
                                    @if (!empty($perspektif['ikus']))
                                        <optgroup label="{{ $perspektif['number'] }}. {{ $perspektif['perspektif'] }}">
                                            @foreach ($perspektif['ikus'] as $iku)
                                                <option value="{{ $iku->id }}"
                                                    data-is-multiple="{{ $iku->is_multi_point }}"
                                                    data-polaritas="{{ $iku->polaritas }}" data-bobot="{{ $iku->bobot }}"
                                                    data-satuan="{{ $iku->satuan }}" data-base="{{ $iku->base }}">
                                                    {{ $iku->iku }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <!-- Container for IKU Sub-Points -->
                        <div id="iku-sub-points" style="display: none;">
                            <h5>Subpoin:</h5>
                            <ul id="sub-points-list">
                                @foreach ($ikuPoints as $formIkuId => $points)
                                    <ul class="sub-points-group" data-iku-id="{{ $formIkuId }}" style="display: none;">
                                        @foreach ($points as $point)
                                            <li>
                                                <input type="radio" name="selected_iku_point" value="{{ $point->id }}"
                                                    id="point_{{ $point->id }}"
                                                    data-polaritas="{{ $point->polaritas }}"
                                                    data-bobot="{{ $point->bobot }}" data-satuan="{{ $point->satuan }}"
                                                    data-base="{{ $point->base }}">
                                                <label for="point_{{ $point->id }}">
                                                    {{ $point->point_name }} - {{ $point->base }}
                                                    ({{ $point->satuan }})
                                                </label>
                                            </li>
                                        @endforeach

                                    </ul>
                                @endforeach
                            </ul>
                        </div>
                        <p>IKU Terpilih: <span id="selected-iku-text">-</span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form KPI -->
    <div class="ml-6">
        <form method="POST" action="{{ route('store-eval') }}">
            @csrf
            <input type="hidden" id="selected-iku-id" name="selected_iku_id">
            <input type="hidden" id="selected-sub-points" name="selected_sub_points">
            <input type="hidden" name="year" value="{{ $selectedYear }}">
            <input type="hidden" name="month" value="{{ $selectedMonth }}">
            <div class="col-12 mb-4">
                <div class="card border-0 shadow components-section">
                    <div class="card-body">
                        <h5>IKU: <span id="selected-iku-heading">-</span></h5>
                        <div class="row mb-4">
                            <div class="col-lg-4 col-sm-6">
                                <div class="mb-3">
                                    <label for="polaritas">Polaritas</label>
                                    <input name="polaritas" class="form-control" required readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="bobot">Bobot</label>
                                    <input type="number" class="form-control" name="bobot" id="bobot" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="satuan">Satuan</label>
                                    <input type="text" class="form-control" name="satuan" id="satuan" readonly>
                                </div>
                                <div class="mb-3">
                                    <h5>Target</h5>
                                    <label>Tahun (1)</label>
                                    <input type="text" class="form-control" name="base" id="base" readonly>
                                    <label for="target_bulan_ini">Bulan Ini (2)</label>
                                    <input type="text" class="form-control" name="target_bulan_ini"
                                        id="target_bulan_ini">
                                    <label for="target_sdbulan_ini">s/d Bulan Ini (3)</label>
                                    <input type="text" class="form-control" name="target_sdbulan_ini"
                                        id="target_sdbulan_ini">
                                </div>
                                <div class="mb-3">
                                    <h5>Realisasi</h5>
                                    <label for="realisasi_bulan_ini">Bulan Ini (4)</label>
                                    <input type="text" class="form-control" name="realisasi_bulan_ini"
                                        id="realisasi_bulan_ini">
                                    <label for="realisasi_sdbulan_ini">s/d Bulan Ini (5)</label>
                                    <input type="text" class="form-control" name="realisasi_sdbulan_ini"
                                        id="realisasi_sdbulan_ini">
                                </div>
                            </div>
                            <div class="col-lg-2 col-sm-6">
                            </div>
                            <div class="col-lg-4 col-sm-6">
                                <div class="mb-3">
                                    <h5>Prosentase Pencapaian THD Target</h5>
                                    <label for="percent_target">6 = (5):(3) (6)</label>
                                    <input type="text" class="form-control" name="percent_target"
                                        id="percent_target">

                                    <label for="percent_year">7 = (5):(1) (7)</label>
                                    <input type="text" class="form-control" name="percent_year" id="percent_year">

                                </div>
                                <div class="mb-3">
                                    <h5>Score</h5>
                                    <label for="ttl">Ttl</label>
                                    <input type="text" class="form-control" name="ttl" id="ttl">
                                    <label for="adj">Adj.</label>
                                    <input type="text" class="form-control" name="adj" id="adj">
                                </div>
                                <div class="my-4">
                                    <label for="proker">Penyebab Tidak Tercapai</label>
                                    <textarea class="form-control" id="proker" name="proker" rows="4"></textarea>
                                </div>
                                <div class="my-4">
                                    <label for="proker">Program Kerja/Langkah Kerja/langkah Pencapaian target IKU
                                        (jika capaian < 95%)</label>
                                            <textarea class="form-control" id="proker" name="proker" rows="4"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Submit Button -->
                    <div class="mb-4 d-flex justify-content-center">
                        <button class="mb-4 btn btn-primary" type="submit" style="width: 420px;">Submit</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="ml-6 table-responsive" style="overflow-y: hidden; max-width: 1440px">
        <table class="table table-bordered table-striped w-100" id="ikuTable">
            <thead class="text-white" style="background-color: #2e2abd;">
                <tr>
                    <th class="border-0 text-center" rowspan="2">Indikator Kinerja Utama</th>
                    <th class="border-0 text-center" rowspan="2">Polaritas</th>
                    <th class="border-0 text-center" rowspan="2">Bobot (A)</th>
                    <th class="border-0 text-center" rowspan="2">Satuan</th>
                    <th class="border-0 text-center" colspan="3">Target</th>
                    <th class="border-0 text-center" colspan="2">Realisasi</th>
                    <th class="border-0 text-center" colspan="2">Prosentase Pencapaian THD Target</th>
                    <th class="border-0 text-center" colspan="2">Score</th>
                    <th class="border-0 text-center" rowspan="3">Penyebab Tidak Tercapai</th>
                    <th class="border-0 text-center" rowspan="3">Program Kerja/Langkah Kerja/langkah Pencapaian
                        target IKU (jika capaian < 95%)</th>
                    <th class="border-0 text-center" rowspan="3">Action</th>
                </tr>
                <tr>
                    <th class="border-0 text-center">Tahun (1)</th>
                    <th class="border-0 text-center" style="white-space: pre">Bulan ini (2)</th>
                    <th class="border-0 text-center" style="white-space: pre">s/d Bulan ini (3)</th>
                    <th class="border-0 text-center" style="white-space: pre">Bulan ini (4)</th>
                    <th class="border-0 text-center" style="white-space: pre">s/d Bulan ini (5)</th>
                    <th class="border-0 text-center" style="white-space: pre">6=(5):(3) (6)</th>
                    <th class="border-0 text-center" style="white-space: pre">7=(5):(1) (7)</th>
                    <th class="border-0 text-center">Ttl</th>
                    <th class="border-0 text-center">Adj.</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($evaluations as $eval)
                    <tr>
                        <td class="fw-normal text-center">{{ $eval->iku_name }}
                            @if ($eval->sub_point_name)
                                <br> <span style="font-size: 0.9em; color: gray;">{{ $eval->sub_point_name }}</span>
                            @endif
                        </td>
                        <td class="fw-normal text-center">{{ $eval->polaritas }}</td>
                        <td class="fw-normal text-center">{{ number_format($eval->bobot) }}</td>
                        <td class="fw-normal text-center">{{ $eval->satuan }}</td>
                        <td class="fw-normal text-center">{{ number_format((float) $eval->base) }}</td>
                        <td class="fw-normal text-center">{{ number_format($eval->target_bulan_ini) }}</td>
                        <td class="fw-normal text-center">{{ number_format($eval->target_sdbulan_ini) }}</td>
                        <td class="fw-normal text-center">{{ number_format($eval->realisasi_bulan_ini) }}</td>
                        <td class="fw-normal text-center">{{ number_format($eval->realisasi_sdbulan_ini) }}</td>
                        <td class="fw-normal text-center">{{ number_format((float) $eval->percent_target) }}%</td>
                        <td class="fw-normal text-center">{{ number_format((float) $eval->percent_year) }}%</td>
                        <td class="fw-normal text-center">{{ number_format($eval->ttl, 2) }}</td>
                        <td class="fw-normal text-center">{{ number_format($eval->adj, 2) }}</td>
                        <td class="fw-normal text-center">{{ $eval->penyebab_tidak_tercapai }}</td>
                        <td class="fw-normal text-center">{{ $eval->program_kerja }}</td>
                        <td class="fw-normal text-center">
                            <div style="display: flex; justify-content: center; align-items: center; gap: 8px;">
                                <!-- Edit Button -->
                                <a
                                    href="{{ route('evaluasi.edit', ['id' => $eval->id, 'month' => request('month'), 'year' => request('year')]) }}">
                                    <img src="{{ asset('assets/img/edit.png') }}" alt="Edit"
                                        style="width: 25px; height: 25px; object-fit: contain;">
                                </a>

                                <!-- Delete Button with SweetAlert -->
                                <form id="delete-form-{{ $eval->id }}"
                                    action="{{ route('evaluasi.destroy', $eval->id) }}" method="POST"
                                    style="margin: 0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="trash-button"
                                        onclick="confirmDelete({{ $eval->id }})"
                                        style="padding: 0; border: none; background: none;">
                                        <img src="{{ asset('assets/img/trash.png') }}" alt="Delete"
                                            style="width: 25px; height: 25px; object-fit: contain;">
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <script>
            function confirmDelete(id) {
                let currentUrl = window.location.href;

                Swal.fire({
                    title: "Apakah Anda yakin?",
                    text: "Data ini akan dihapus secara permanen dan tidak dapat dikembalikan!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Ya, hapus!",
                    cancelButtonText: "Batal"
                }).then((result) => {
                    if (result.isConfirmed) {
                        let form = document.getElementById(`delete-form-${id}`);
                        let input = document.createElement("input");
                        input.type = "hidden";
                        input.name = "redirect_url";
                        input.value = currentUrl;
                        form.appendChild(input);
                        form.submit();
                    }
                });
            }
        </script>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ikuSelector = document.getElementById("iku-selector");
            const ikuSubPointsContainer = document.getElementById("iku-sub-points");
            const selectedIkuDisplay = document.getElementById("selected-iku-text");
            const selectedIkuHeading = document.getElementById("selected-iku-heading");
            const selectedIkuInput = document.getElementById("selected-iku-id");
            const subPointsList = document.getElementById("sub-points-list");
            const selectedSubPointsInput = document.getElementById("selected-sub-points");

            const polaritasInput = document.querySelector("input[name='polaritas']");
            const bobotInput = document.querySelector("input[name='bobot']");
            const satuanInput = document.querySelector("input[name='satuan']");
            const baseInput = document.querySelector("input[name='base']");

            ikuSelector.addEventListener("change", function() {
                let selectedOption = ikuSelector.options[ikuSelector.selectedIndex];

                if (!selectedOption || !selectedOption.value) return;

                let isMultiPoint = selectedOption.getAttribute("data-is-multiple") === "1";
                let selectedIkuId = selectedOption.value.trim();

                selectedIkuDisplay.textContent = selectedOption.text;
                selectedIkuHeading.textContent = selectedOption.text;
                selectedIkuInput.value = selectedIkuId;

                polaritasInput.value = selectedOption.getAttribute("data-polaritas");
                bobotInput.value = selectedOption.getAttribute("data-bobot");
                satuanInput.value = selectedOption.getAttribute("data-satuan");
                baseInput.value = selectedOption.getAttribute("data-base");

                document.querySelectorAll(".sub-points-group").forEach(group => {
                    group.style.display = "none";
                });

                if (isMultiPoint) {
                    ikuSubPointsContainer.style.display = "block";
                    let subPointGroup = document.querySelector(
                        `.sub-points-group[data-iku-id='${selectedIkuId}']`);
                    if (subPointGroup) {
                        subPointGroup.style.display = "block";
                    }
                } else {
                    ikuSubPointsContainer.style.display = "none";
                    selectedSubPointsInput.value = "";
                }
            });

            subPointsList.addEventListener("change", function(event) {
                if (event.target.name === "selected_iku_point") {
                    let selectedSubPoint = event.target;
                    let pointName = selectedSubPoint.nextElementSibling.textContent.trim();

                    selectedIkuDisplay.textContent = pointName;
                    selectedIkuHeading.textContent = pointName;
                    selectedSubPointsInput.value = selectedSubPoint.value;

                    polaritasInput.value = selectedSubPoint.getAttribute("data-polaritas");
                    bobotInput.value = selectedSubPoint.getAttribute("data-bobot");
                    satuanInput.value = selectedSubPoint.getAttribute("data-satuan");
                    baseInput.value = selectedSubPoint.getAttribute("data-base");
                }
            });

            function calculateResults() {
                const nilai5 = parseFloat(document.querySelector('input[name="realisasi_sdbulan_ini"]').value) || 0;
                const nilai3 = parseFloat(document.querySelector('input[name="target_sdbulan_ini"]').value) || 0;
                const nilai1 = parseFloat(document.querySelector('input[name="base"]').value) || 0;
                const bobot = parseFloat(document.querySelector('input[name="bobot"]').value) || 0;
                const polaritas = document.querySelector('input[name="polaritas"]').value.trim().toLowerCase();

                let percentTarget;
                if (polaritas === "maximize") {
                    percentTarget = nilai3 !== 0 ? (nilai5 / nilai3) * 100 : 0;
                } else {
                    percentTarget = nilai5 !== 0 ? (nilai3 / nilai5) * 100 : 0;
                }

                let percentYear;
                if (polaritas === "maximize") {
                    percentYear = nilai1 !== 0 ? (nilai5 / nilai1) * 100 : 0;
                } else {
                    percentYear = nilai5 !== 0 ? (nilai1 / nilai5) * 100 : 0;
                }

                percentTarget = Math.round(Math.min(percentTarget, 250));
                percentYear = Math.round(Math.min(percentYear, 250));

                const N = percentYear;
                const O = Math.round(N * bobot);

                let Q = Math.min(Math.max(N, 0), 120);
                const adj = Math.round(Q * bobot) / 100;
                const ttl = O < 0 ? 0 : Math.round(O) / 100;

                document.querySelector('input[name="percent_target"]').value = percentTarget + "%";
                document.querySelector('input[name="percent_year"]').value = percentYear + "%";
                document.querySelector('input[name="ttl"]').value = ttl.toFixed(2);
                document.querySelector('input[name="adj"]').value = adj.toFixed(2);
            }

            // Attach event listeners
            document.querySelectorAll(
                'input[name="realisasi_sdbulan_ini"], input[name="target_sdbulan_ini"], input[name="base"], input[name="bobot"], input[name="polaritas"]'
            ).forEach(input => {
                input.addEventListener('input', calculateResults);
            });

        });
    </script>
@endsection
