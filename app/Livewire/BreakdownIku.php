<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BreakdownIku extends Component
{
    public $selectedPeriod;
    public $selectedYear;
    public $selectedMonth;
    public $selectedDepartment;
    public $departments;
    public $evaluations = [];

    public function mount($year = null, $month = null, $department = null)
    {
        $currentDate = Carbon::now();

        // Ensure month is always two digits
        $selectedMonth = str_pad($month ?? $currentDate->month, 2, '0', STR_PAD_LEFT);
        $this->selectedPeriod = ($year ?? $currentDate->year) . '-' . $selectedMonth;

        $this->selectedDepartment = $department ?? (Auth::user()->id == 1 ? null : Auth::user()->department_id);

        $this->fetchDepartments();
        $this->fetchEvaluations();
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['selectedMonth', 'selectedYear', 'selectedDepartment'])) {
            $this->fetchEvaluations();
        }
    }

    public function fetchDepartments()
    {
        $this->departments = DB::table('department')
            ->select('department_id', 'department_name')
            ->get();
    }

    public function updatedSelectedPeriod()
    {
        $date = Carbon::parse($this->selectedPeriod);
        $this->selectedYear = $date->year;
        $this->selectedMonth = str_pad($date->month, 2, '0', STR_PAD_LEFT);
        $this->fetchEvaluations();
    }

    public function fetchEvaluations()
    {
        $user = Auth::user();

        $selectedYear = $this->selectedYear ?? Carbon::now()->year;
        $selectedMonth = $this->selectedMonth ?? Carbon::now()->month;
        $selectedDepartment = $this->selectedDepartment ?? $user->department_id;

        $this->evaluations = DB::select("
            SELECT
                ie.id, ie.iku_id, ie.point_id, ie.polaritas, ie.bobot, ie.satuan, ie.base,
                ie.target_bulan_ini, ie.target_sdbulan_ini, ie.realisasi_bulan_ini, ie.realisasi_sdbulan_ini,
                ie.percent_target, ie.percent_year, ie.ttl, ie.adj, ie.penyebab_tidak_tercapai, ie.program_kerja,
                isi.iku AS iku_name, ip.point_name AS sub_point_name, ss.name AS sasaran_name
            FROM iku_evaluations ie
            LEFT JOIN users u ON ie.user_id = u.id
            LEFT JOIN department d ON u.department_id = d.department_id
            LEFT JOIN form_iku fi ON ie.iku_id = fi.id
            LEFT JOIN isi_iku isi ON fi.isi_iku_id = isi.id
            LEFT JOIN iku_point ip ON ie.point_id = ip.id
            LEFT JOIN sasaran_strategis ss ON fi.sasaran_id = ss.id
            WHERE ie.year = ?
              AND ie.month = ?
              AND u.department_id = ?
            ORDER BY fi.id, ie.id ASC
        ", [$selectedYear, $selectedMonth, $selectedDepartment]);
    }

    public function render()
    {
        return view('livewire.breakdown-iku', [
            'evaluations' => $this->evaluations,
            'departments' => $this->departments
        ]);
    }
}
