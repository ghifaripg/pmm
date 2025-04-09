<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class TotalSkorIku extends Component
{
    public $selectedYear;
    public $selectedMonth;
    public $selectedDepartment;
    public $selectedPeriod;
    public $totalAdjPerSasaran = [];

    public function mount($year, $month, $department)
    {
        $this->selectedYear = $year;
        $this->selectedMonth = $month;
        $this->selectedDepartment = $department;
        $this->selectedPeriod = sprintf('%04d-%02d', $year, $month); // Ensure default format

        $this->loadData();
    }

    public function updatedSelectedPeriod($value)
    {
        $parts = explode('-', $value);
        if (count($parts) === 2 && ctype_digit($parts[0]) && ctype_digit($parts[1])) {
            $this->selectedYear = (int) $parts[0];
            $this->selectedMonth = (int) $parts[1];
            $this->loadData();
        }
    }

    public function loadData()
    {
        $queryParamsSasaran = [$this->selectedYear, $this->selectedMonth];
        $whereDepartment = "";

        if (!empty($this->selectedDepartment)) {
            $whereDepartment = "AND u.department_id = ?";
            $queryParamsSasaran[] = $this->selectedDepartment;
        }

        $this->totalAdjPerSasaran = DB::select("
            SELECT
                ss.name AS perspektif,
                SUM(ie.adj) AS total
            FROM form_iku fi
            LEFT JOIN sasaran_strategis ss ON fi.sasaran_id = ss.id
            LEFT JOIN iku_evaluations ie ON fi.id = ie.iku_id
            LEFT JOIN users u ON ie.user_id = u.id
            WHERE ie.year = ? AND ie.month = ?
            $whereDepartment
            GROUP BY ss.id, ss.name
            ORDER BY ss.id ASC;
        ", $queryParamsSasaran);
    }

    public function render()
    {
        return view('livewire.total-skor-iku', [
            'totalAdjPerSasaran' => $this->totalAdjPerSasaran,
            'totalIku' => collect($this->totalAdjPerSasaran)->sum('total'),
        ]);
    }
}
