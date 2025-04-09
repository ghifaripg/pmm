<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function showDashboard(Request $request)
    {
        $nama = Auth::user()->nama;
        $department_id = Auth::user()->department_id;
        $user_id = Auth::user()->id;

        $monthYear = $request->query('month-year', date('Y-m'));

        if (preg_match('/^\d{4}-\d{2}$/', $monthYear)) {
            [$selectedYear, $selectedMonth] = explode('-', $monthYear);
        } else {
            $selectedYear = date('Y');
            $selectedMonth = date('n');
        }

        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        $selectedMonthName = $months[$selectedMonth] ?? 'Unknown';

        $selectedDepartment = $request->query('department', $user_id == 1 ? null : $department_id);

        if ($selectedDepartment) {
            $department = DB::table('department')
                ->where('department_id', $selectedDepartment)
                ->select('department_username')
                ->first();
            $departmentName = $department->department_username ?? 'Unknown';
        } else {
            $departmentName = 'Semua Unit Kerja';
        }

        $departments = DB::table('department')->select('department_id', 'department_name')->get();

        $queryParamsSasaran = [$selectedYear, $selectedMonth];
        $whereDepartment = "";

        if (!empty($selectedDepartment)) {
            $whereDepartment = "AND u.department_id = ?";
            $queryParamsSasaran[] = $selectedDepartment;
        }

        $totalAdjPerSasaran = DB::select("
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

        $queryParamsMonth = [$selectedYear];
        $whereDepartment = "";

        if (!empty($selectedDepartment)) {
            $whereDepartment = "AND u.department_id = ?";
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

        $adjSeries = array_fill(0, 12, 0);
        foreach ($totalAdjPerMonth as $data) {
            $adjSeries[(int)$data->month - 1] = (float)$data->total;
        }
        $adjSeriesJson = json_encode($adjSeries);

        $department = DB::table('department')
            ->where('department_id', $department_id)
            ->select('department_username')
            ->first();
        $departmentName = (string) $department->department_username;

        $totalIkus = DB::table('form_iku')
            ->where('iku_id', 'LIKE', "IKU{$departmentName}_{$selectedYear}%")
            ->count();

        $totalIkuPoints = DB::table('form_iku')
            ->join('iku_point', 'iku_point.form_iku_id', '=', 'form_iku.id')
            ->where('form_iku.iku_id', 'LIKE', "IKU{$departmentName}_{$selectedYear}%")
            ->where('form_iku.is_multi_point', 1)
            ->count();

        $totalIkuWithPoints = DB::table('form_iku')
            ->where('iku_id', 'LIKE', "IKU{$departmentName}_{$selectedYear}%")
            ->where('is_multi_point', 1)
            ->count();

        $totalIku = $totalIkus + $totalIkuPoints - $totalIkuWithPoints;

        $evaluatedIkuPerMonth = DB::table('iku_evaluations')
            ->join('form_iku', 'iku_evaluations.iku_id', '=', 'form_iku.id')
            ->where('form_iku.iku_id', 'LIKE', "IKU{$departmentName}_{$selectedYear}%")
            ->selectRaw('iku_evaluations.month as month, COUNT(*) as count')
            ->groupBy('iku_evaluations.month')
            ->pluck('count', 'month')
            ->toArray();

        $monthlyProgress = [];
        for ($i = 1; $i <= 12; $i++) {
            $evaluatedCount = isset($evaluatedIkuPerMonth[$i]) ? $evaluatedIkuPerMonth[$i] : 0;
            $progress = ($totalIku > 0) ? round(($evaluatedCount / $totalIku) * 100, 2) : 0;
            $monthlyProgress[$i] = $progress;
        }

        $totalEvaluatedIku = array_sum($evaluatedIkuPerMonth);
        $progressPercentage = ($totalIku > 0) ? round(($totalEvaluatedIku / $totalIku) * 100, 2) : 0;

        $page = $request->query('page', 1);
        $months = array_slice($monthlyProgress, ($page - 1) * 4, 4, true);
        $totalPages = ceil(count($monthlyProgress) / 4);

        $user = Auth::user();

        $evaluations = DB::select("
    SELECT
        ie.id,
        ie.iku_id,
        ie.point_id,
        ie.polaritas,
        ie.bobot,
        ie.satuan,
        ie.base,
        ie.target_bulan_ini,
        ie.target_sdbulan_ini,
        ie.realisasi_bulan_ini,
        ie.realisasi_sdbulan_ini,
        ie.percent_target,
        ie.percent_year,
        ie.ttl,
        ie.adj,
        ie.penyebab_tidak_tercapai,
        ie.program_kerja,
        isi.iku AS iku_name,
        ip.point_name AS sub_point_name,
        ss.name AS sasaran_name
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
", [$selectedYear, $selectedMonth, $user->department_id]);

        return view('pages.dashboard', compact(
            'departments',
            'selectedDepartment',
            'departmentName',
            'selectedYear',
            'selectedMonth',
            'selectedMonthName',
            'totalAdjPerSasaran',
            'adjSeriesJson',
            'progressPercentage',
            'months',
            'page',
            'totalPages',
            'totalIku',
            'totalEvaluatedIku',
            'evaluations'
        ));
    }

    public function showAdmin(Request $request)
    {
        $nama = Auth::user()->nama;
        $department_id = Auth::user()->department_id;
        $user_id = Auth::user()->id;

        $monthYear = $request->query('month-year', date('Y-m'));

        if (preg_match('/^\d{4}-\d{2}$/', $monthYear)) {
            [$selectedYear, $selectedMonth] = explode('-', $monthYear);
        } else {
            $selectedYear = date('Y');
            $selectedMonth = date('n');
        }

        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        $selectedMonthName = $months[$selectedMonth] ?? 'Unknown';

        $selectedDepartment = $request->query('department', $user_id == 1 ? null : $department_id);

        if ($selectedDepartment) {
            $department = DB::table('department')
                ->where('department_id', $selectedDepartment)
                ->select('department_username')
                ->first();
            $departmentName = $department->department_username ?? 'Unknown';
        } else {
            $departmentName = 'Semua Unit Kerja';
        }

        $departments = DB::table('department')->select('department_id', 'department_name')->get();
        return view('pages.dashboard-admin', compact(
            'departments',
            'selectedDepartment',
            'departmentName',
            'selectedYear',
            'selectedMonth',
            'selectedMonthName',
            'months',
        ));
    }
}
