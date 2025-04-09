<div>
    <h2 class="fs-5 fw-bold mb-0">Total Skor IKU Perspektif (Perbandingan Per Tahun)</h2>

    <label for="month-year" class="mt-3 mb-3 form-label">Pilih Periode:</label>
    <input type="month" id="month-year" class="form-control w-auto d-inline"
        wire:model.lazy="selectedPeriod">

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
                    <tr>
                        <td style="border: none; text-align:left">{{ $index + 1 }}</td>
                        <td style="border: none; text-align:left">{{ $sasaran->perspektif }}</td>
                        <td class="fw-normal text-center iku-cell" style="border: none; text-align:left">{{ $sasaran->total }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <small class="text-tertiary mb-0">Total Skor = <span id="total-iku">{{ $totalIku }}</span></small>
</div>
