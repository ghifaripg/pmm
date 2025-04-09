<?php

namespace App\Exports;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class IkuEvaluationsExport
{
    protected $selectedYear;
    protected $selectedMonth;
    protected $selectedMonthName;
    protected $monthYear;
    protected $departmentUsername;

    public function __construct($monthYear)
    {
        if (preg_match('/^\d{4}-\d{2}$/', $monthYear)) {
            [$this->selectedYear, $this->selectedMonth] = explode('-', $monthYear);
        } else {
            $this->selectedYear = date('Y');
            $this->selectedMonth = date('n');
        }

        $this->selectedMonth = (int) $this->selectedMonth;
        $this->selectedMonthName = date('F', mktime(0, 0, 0, $this->selectedMonth, 1)); // Get full month name

        // Get department username
        $user = Auth::user();

        if ($user) {
            $department = DB::table('department')
                ->where('department_id', $user->department_id)
                ->select('department_username')
                ->first();

            $this->departmentUsername = $department->department_username ?? 'Unknown';
        } else {
            $this->departmentUsername = 'Unknown';
        }
    }

    public function export()
    {
        // Load Excel template
        $spreadsheet = IOFactory::load(public_path('templates/Evaluasi_IKU.xlsx'));
        $sheet = $spreadsheet->getActiveSheet();

        $data = DB::table('iku_evaluations as ie')
            ->join('form_iku as fi', 'ie.iku_id', '=', 'fi.id')
            ->select(
                DB::raw('ROW_NUMBER() OVER(ORDER BY ie.id) as No'),
                'fi.iku_id as IKU',
                'ie.polaritas',
                'ie.bobot',
                'ie.satuan',
                'ie.base as TargetTahun',
                'ie.target_bulan_ini as TargetBulanIni',
                'ie.target_sdbulan_ini as TargetsdBulanini',
                'ie.realisasi_bulan_ini as RealisasiBulanini',
                'ie.realisasi_sdbulan_ini as RealisasisdBulanIni',
                'ie.percent_target',
                'ie.percent_year',
                'ie.ttl',
                'ie.adj',
                'ie.penyebab_tidak_tercapai',
                'ie.program_kerja'
            )
            ->where('ie.year', $this->selectedYear)
            ->where('ie.month', $this->selectedMonth)
            ->orderBy('ie.id', 'asc')
            ->get();

        if ($data->isEmpty()) {
            return back()->with('error', "No data found for year {$this->selectedYear} and month {$this->selectedMonth}");
        }

        // Start writing data from row 9
        $startRow = 9;
        $rowNumber = $startRow;

        foreach ($data as $row) {
            $sheet->setCellValue('A' . $rowNumber, $row->No);
            $sheet->setCellValue('B' . $rowNumber, $row->IKU);
            $sheet->setCellValue('C' . $rowNumber, $row->polaritas);
            $sheet->setCellValue('D' . $rowNumber, $row->bobot);
            $sheet->setCellValue('E' . $rowNumber, $row->satuan);
            $sheet->setCellValue('F' . $rowNumber, $row->TargetTahun);
            $sheet->setCellValue('G' . $rowNumber, $row->TargetBulanIni);
            $sheet->setCellValue('H' . $rowNumber, $row->TargetsdBulanini);
            $sheet->setCellValue('I' . $rowNumber, $row->RealisasiBulanini);
            $sheet->setCellValue('J' . $rowNumber, $row->RealisasisdBulanIni);
            $sheet->setCellValue('K' . $rowNumber, $row->percent_target);
            $sheet->setCellValue('L' . $rowNumber, $row->percent_year);
            $sheet->setCellValue('M' . $rowNumber, $row->ttl);
            $sheet->setCellValue('N' . $rowNumber, $row->adj);
            $sheet->setCellValue('O' . $rowNumber, $row->penyebab_tidak_tercapai);
            $sheet->setCellValue('P' . $rowNumber, $row->program_kerja);

            $rowNumber++;
        }

        // Save the file with department and selected month
        $filePath = storage_path("app/Evaluasi_IKU_{$this->departmentUsername}_{$this->selectedMonthName}.xlsx");
        $writer = new Xlsx($spreadsheet);
        $writer->save($filePath);

        return response()->download($filePath)->deleteFileAfterSend(true);
    }
}
