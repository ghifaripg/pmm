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
    public $selectedPerspektif = null;
    public $underperformingIku = [];

    public function mount($year, $month, $department)
    {
        $this->selectedYear = $year;
        $this->selectedMonth = $month;
        $this->selectedDepartment = $department;
        $this->selectedPeriod = sprintf('%04d-%02d', $year, $month);

        $this->loadData();
    }

    public function showUnderperformingIku($perspektif)
    {
        // Toggle off if same perspektif clicked
        if ($this->selectedPerspektif === $perspektif) {
            $this->selectedPerspektif = null;
            $this->underperformingIku = [];
            return;
        }

        $queryParams = [$this->selectedYear, $this->selectedMonth, $perspektif];
        $whereDepartment = "";

        if (!empty($this->selectedDepartment)) {
            $whereDepartment = "AND u.department_id = ?";
            $queryParams[] = $this->selectedDepartment;
        }

        $this->underperformingIku = DB::select("
            SELECT isi.iku AS iku_name, ip.point_name AS sub_point_name, ie.percent_target
            FROM form_iku fi
            LEFT JOIN sasaran_strategis ss ON fi.sasaran_id = ss.id
            LEFT JOIN iku_evaluations ie ON fi.id = ie.iku_id
            LEFT JOIN isi_iku isi ON fi.isi_iku_id = isi.id
            LEFT JOIN iku_point ip ON ie.point_id = ip.id
            LEFT JOIN users u ON ie.user_id = u.id
            WHERE ie.year = ? AND ie.month = ?
              AND ss.name = ?
              AND ie.percent_target < 100
              $whereDepartment
            ORDER BY fi.id ASC
        ", $queryParams);

        $this->selectedPerspektif = $perspektif;
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
