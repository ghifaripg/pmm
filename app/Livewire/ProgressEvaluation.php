<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProgressEvaluation extends Component
{
    public $page = 1;
    public $selectedYear;
    public $selectedDepartment;
    public $months = [];
    public $totalPages;
    public $totalIku;
    public $totalEvaluatedIku;
    public $departmentName;
    public $progressPercentage;
    public $adjSeriesJson;

    public function mount($selectedYear = null)
    {
        $this->selectedYear = $selectedYear ?? date('Y');
        $this->selectedDepartment = Auth::user()->department_id;
        $this->loadProgress();
    }

    public function loadProgress()
    {
        $user_id = Auth::user()->id;
        $selectedDepartment = $this->selectedDepartment;

        // Get department details
        if ($selectedDepartment) {
            $department = DB::table('department')
                ->where('department_id', $selectedDepartment)
                ->select('department_username')
                ->first();
            $this->departmentName = $department->department_username ?? 'Unknown';
        } else {
            $this->departmentName = 'Semua Unit Kerja';
        }

        // Query for the monthly progress
        $queryParamsMonth = [$this->selectedYear];
        $whereDepartment = $selectedDepartment ? "AND u.department_id = ?" : "";
        if ($selectedDepartment) {
            $queryParamsMonth[] = $selectedDepartment;
        }

        $totalAdjPerMonth = DB::select("
            SELECT
                ie.month AS month,
                SUM(ie.adj) AS total
            FROM form_iku fi
            LEFT JOIN iku_evaluations ie ON fi.id = ie.iku_id
            LEFT JOIN users u ON ie.user_id = u.id
            WHERE ie.year = ?
            $whereDepartment
            GROUP BY ie.month
            ORDER BY ie.month ASC;
        ", $queryParamsMonth);

        // Build the adjusted series for progress
        $adjSeries = array_fill(0, 12, 0);
        foreach ($totalAdjPerMonth as $data) {
            $adjSeries[(int)$data->month - 1] = (float)$data->total;
        }
        $this->adjSeriesJson = json_encode($adjSeries);

        // Total IKUs calculations
        $departmentName = $this->departmentName;
        $totalIkus = DB::table('form_iku')
            ->where('iku_id', 'LIKE', "IKU{$departmentName}_{$this->selectedYear}%")
            ->count();

        $totalIkuPoints = DB::table('form_iku')
            ->join('iku_point', 'iku_point.form_iku_id', '=', 'form_iku.id')
            ->where('form_iku.iku_id', 'LIKE', "IKU{$departmentName}_{$this->selectedYear}%")
            ->where('form_iku.is_multi_point', 1)
            ->count();

        $totalIkuWithPoints = DB::table('form_iku')
            ->where('iku_id', 'LIKE', "IKU{$departmentName}_{$this->selectedYear}%")
            ->where('is_multi_point', 1)
            ->count();

        $this->totalIku = $totalIkus + $totalIkuPoints - $totalIkuWithPoints + 1;

        // Evaluated IKUs per month
        $evaluatedIkuPerMonth = DB::table('iku_evaluations')
            ->join('form_iku', 'iku_evaluations.iku_id', '=', 'form_iku.id')
            ->where('form_iku.iku_id', 'LIKE', "IKU{$departmentName}_{$this->selectedYear}%")
            ->selectRaw('iku_evaluations.month as month, COUNT(*) as count')
            ->groupBy('iku_evaluations.month')
            ->pluck('count', 'month')
            ->toArray();

        $monthlyProgress = [];
        for ($i = 1; $i <= 12; $i++) {
            $evaluatedCount = isset($evaluatedIkuPerMonth[$i]) ? $evaluatedIkuPerMonth[$i] : 0;
            $progress = ($this->totalIku > 0) ? round(($evaluatedCount / $this->totalIku) * 100, 2) : 0;
            $monthlyProgress[$i] = $progress;
        }

        $this->progressPercentage = ($this->totalIku > 0) ? round(array_sum($evaluatedIkuPerMonth) / $this->totalIku * 100, 2) : 0;

        // Pagination Logic
        $this->months = array_slice($monthlyProgress, ($this->page - 1) * 4, 4, true);
        $this->totalPages = ceil(count($monthlyProgress) / 4);
    }

    public function nextPage()
    {
        if ($this->page < $this->totalPages) {
            $this->page++;
            $this->loadProgress();
        }
    }

    public function prevPage()
    {
        if ($this->page > 1) {
            $this->page--;
            $this->loadProgress();
        }
    }

    public function render()
    {
        return view('livewire.progress-evaluation');
    }
}
