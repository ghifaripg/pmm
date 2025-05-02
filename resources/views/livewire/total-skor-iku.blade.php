<div>
    <h2 class="fs-5 fw-bold mb-0">Total Skor IKU Perspektif (Perbandingan Per Tahun)</h2>

    <label for="month-year" class="mt-3 mb-3 form-label">Pilih Periode:</label>
    <input type="month" id="month-year" class="form-control w-auto d-inline" wire:model.lazy="selectedPeriod">

    <div class="table-responsive" style="overflow-x: unset">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th class="border-0 text-center" style="background-color: #F3F2F2; color:black">No</th>
                    <th class="border-0 text-center" style="background-color: #F3F2F2; color:black">Perspektif</th>
                    <th class="border-0 text-center" style="background-color: #F3F2F2; color:black">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($totalAdjPerSasaran as $index => $sasaran)
                <tr wire:click="showUnderperformingIku('{{ $sasaran->perspektif }}')" style="cursor: pointer;">
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $sasaran->perspektif }}</td>
                    <td class="text-center">{{ $sasaran->total }}</td>
                </tr>

                @if ($selectedPerspektif === $sasaran->perspektif)
                    @foreach ($underperformingIku as $iku)
                        <tr class="table-warning">
                            <td colspan="2">{{ $iku->iku_name }} @if($iku->sub_point_name) - {{ $iku->sub_point_name }} @endif</td>
                            <td class="text-danger text-end">({{ (int) round((float) $iku->percent_target) }}%)</td>
                        </tr>
                    @endforeach
                @endif
            @endforeach

            </tbody>
        </table>
    </div>
    <small class="text-tertiary mb-0">Total Skor = <span id="total-iku">{{ $totalIku }}</span></small>
</div>
