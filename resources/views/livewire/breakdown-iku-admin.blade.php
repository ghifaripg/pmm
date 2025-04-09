<div class="col-xl-6">
    <div class="card">
        <div class="card-header bg-transparent">
            <div class="row align-items-center">
                <div class="col">
                    <h6 class="text-uppercase text-muted ls-1 mb-1">Breakdown IKU</h6>
                    <label for="month-year" class="form-label">Pilih Periode:</label>
                    <input type="month" id="month-year" class="form-control w-auto d-inline"
                        wire:model.lazy="selectedPeriod">
                    <br>
                    <label for="department" class="mt-3 form-label">Departemen:</label>
                    <select id="department" class="form-control w-auto d-inline" wire:model="selectedDepartment">
                        <option value="">Semua Departemen</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->department_id }}">{{ $department->department_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="card-body p-2">
            <div class="table-responsive">
                <table class="table table-bordered table-striped w-100">
                    <thead class="text-white" style="background-color: #2e2abd;">
                        <tr>
                            <th>Pilar</th>
                            <th>KPI</th>
                            <th>Bobot</th>
                            <th>Satuan</th>
                            <th>Target</th>
                            <th>Real</th>
                            <th>Capaian</th>
                            <th>Score</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $printedSasaran = collect();
                        @endphp
                        @foreach ($evaluations as $eval)
                            <tr>
                                @if (!$printedSasaran->has($eval->sasaran_name))
                                    <td
                                        rowspan="{{ collect($evaluations)->where('sasaran_name', $eval->sasaran_name)->count() }}">
                                        {{ $eval->sasaran_name }}
                                    </td>
                                    @php $printedSasaran[$eval->sasaran_name] = true; @endphp
                                @endif
                                <td>{{ $eval->iku_name }}
                                    @if ($eval->sub_point_name)
                                        <br> <span
                                            style="font-size: 0.9em; color: gray;">{{ $eval->sub_point_name }}</span>
                                    @endif
                                </td>
                                <td>{{ number_format($eval->bobot) }}</td>
                                <td>{{ $eval->satuan }}</td>
                                <td>{{ number_format($eval->target_bulan_ini) }}</td>
                                <td>{{ number_format($eval->realisasi_bulan_ini) }}</td>
                                <td>{{ number_format((float) $eval->percent_target) }}%</td>
                                <td>
                                    {{ number_format($eval->adj, 2) }}
                                    <span class="status-indicator"
                                        style="background-color: {{ $eval->percent_target < 95 ? 'red' : ($eval->percent_target > 100 ? 'green' : 'gray') }};"></span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
