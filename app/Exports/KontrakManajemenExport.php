<?php
namespace App\Exports;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromArray;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;

class KontrakManajemenExport implements WithTitle, FromArray
{
    protected $selectedYear;
    protected $spreadsheet;

    public function __construct($year)
    {
        $this->selectedYear = $year;
        $templatePath = public_path('templates/Kontrak_Manajemen.xlsx');

        if (!file_exists($templatePath)) {
            throw new \Exception("Template file not found at: " . $templatePath);
        }

        $this->spreadsheet = IOFactory::load($templatePath);
    }

    public function title(): string
    {
        return "Kontrak Manajemen {$this->selectedYear}";
    }

    public function array(): array
    {
        return [];
    }

    public function populateData()
{
    $sheet = $this->spreadsheet->getActiveSheet();
    $kontrak_id = 'KM_' . $this->selectedYear;

    $sasaranStrategis = DB::table('sasaran_strategis')
        ->where('kontrak_id', $kontrak_id)
        ->orderBy('id', 'asc')
        ->get();

    $kpiData = DB::table('form_kontrak_manajemen')
        ->join('sasaran_strategis', 'form_kontrak_manajemen.sasaran_id', '=', 'sasaran_strategis.id')
        ->where('sasaran_strategis.kontrak_id', $kontrak_id)
        ->select('form_kontrak_manajemen.*', 'sasaran_strategis.name as sasaran_name', 'sasaran_strategis.id as sasaran_id')
        ->orderBy('sasaran_strategis.id', 'asc')
        ->get();

    if ($sasaranStrategis->isEmpty() || $kpiData->isEmpty()) {
        throw new \Exception("No data found for kontrak_id: " . $kontrak_id);
    }

    $sasaranGrouped = [];
    $letter = 'A';
    foreach ($sasaranStrategis as $sasaran) {
        $sasaranGrouped[$sasaran->id] = [
            'letter' => $letter,
            'name' => $sasaran->name,
            'kpis' => [],
        ];
        $letter++;
    }

    foreach ($kpiData as $kpi) {
        $sasaranGrouped[$kpi->sasaran_id]['kpis'][] = $kpi;
    }

    $sheet->getStyle($sheet->calculateWorksheetDimension())->getAlignment()->setWrapText(true);

    $row = 12;
    foreach ($sasaranGrouped as $sasaran) {
        $kpiCount = count($sasaran['kpis']);

        // Black Separator
        if ($row > 12) {
            for ($col = 'A'; $col <= 'L'; $col++) {
                $sheet->getStyle("{$col}{$row}")->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '000000']
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'FFFFFF']
                        ]
                    ]
                ]);
            }
            $row++;
        }

        foreach ($sasaran['kpis'] as $index => $kpi) {
            $mergeEndRow = $row + $kpiCount - 1;

            if ($index == 0) {
                $sheet->mergeCells("B{$row}:B{$mergeEndRow}");
                $sheet->setCellValue("B{$row}", $sasaran['name']);

                $sheet->mergeCells("A{$row}:A{$mergeEndRow}");
                $sheet->setCellValue("A{$row}", $sasaran['letter']);

                $sheet->getStyle("A{$row}:A{$mergeEndRow}")->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'BEE8F0']
                    ],
                    'font' => ['bold' => true],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'FFFFFF']
                        ]
                    ]
                ]);

                $sheet->getStyle("B{$row}:B{$mergeEndRow}")->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'D0F1F7']
                    ],
                    'font' => ['bold' => true],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'FFFFFF']
                        ]
                    ]
                ]);

            }

            // Alternating Row Colors
            $backgroundColor = ($row % 2 == 0) ? 'DBE9F9' : 'EFF7FF';

            $sheet->getStyle("C{$row}:L{$row}")->applyFromArray([
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => $backgroundColor]
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'FFFFFF']
                    ]
                ]
            ]);
            $sheet->setCellValue("A3", "KONTRAK MANAJEMEN TAHUN {$this->selectedYear}");
            $sheet->getStyle("A3")->getFont()->setBold(true)->setSize(14);

            // KPI Data
            $sheet->setCellValue("C{$row}", ($index + 1) . ". " . $kpi->kpi_name);
            $sheet->setCellValue("D{$row}", $kpi->target);
            $sheet->setCellValue("E{$row}", $kpi->satuan);
            $sheet->setCellValue("F{$row}", $kpi->milestone ?? '-');
            $sheet->setCellValue("G{$row}", $kpi->esgc);
            $sheet->setCellValue("H{$row}", ucfirst($kpi->polaritas));
            $sheet->setCellValue("I{$row}", $kpi->bobot);
            $sheet->setCellValue("J{$row}", $kpi->du);
            $sheet->setCellValue("K{$row}", $kpi->dk);
            $sheet->setCellValue("L{$row}", $kpi->do);

            $row++;
        }
    }
}

public function export(Request $request)
{
    $this->populateData();

    $sheet = $this->spreadsheet->getActiveSheet();

    // Insert names into specified Excel cells
    $sheet->setCellValue('B43', $request->input('direktur_utama', ''));
    $sheet->setCellValue('D43', $request->input('plt_keuangan_sdm', ''));
    $sheet->setCellValue('I43', $request->input('direktur_operasi', ''));

    $filePath = storage_path("app/Kontrak_Manajemen_{$this->selectedYear}.xlsx");
    $writer = new Xlsx($this->spreadsheet);
    $writer->save($filePath);

    return response()->download($filePath)->deleteFileAfterSend(true);
}

}
