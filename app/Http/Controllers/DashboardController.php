<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function showDashboard(Request $request)
    {
        $nama = Auth::user()->nama;
        $user_id = Auth::user()->id;
        $departmentInfo = DB::table('re_user_department as rud')
            ->join('department as d', 'rud.department_id', '=', 'd.department_id')
            ->where('rud.user_id', $user_id)
            ->select('rud.department_id', 'd.department_username')
            ->first();

        $department_id = $departmentInfo?->department_id ?? null;
        $departmentName = $departmentInfo?->department_username ?? 'Unknown';

        $isAdmin = DB::table('re_user_department')
            ->where('user_id', Auth::id())
            ->where('department_role', 'admin')
            ->exists();

        $isDirector = DB::table('users')
            ->where('id', Auth::id())
            ->where('role', 'director')
            ->exists();

        $isDivision = DB::table('users')
            ->where('id', Auth::id())
            ->where('role', 'division')
            ->exists();

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

        $user = Auth::user();

        $totalIku = DB::table('form_iku')
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

        $totalIkuFinal = $totalIku + $totalIkuPoints - $totalIkuWithPoints;

        $evaluatedIkuPerMonth = DB::table('iku_evaluations')
            ->join('form_iku', 'iku_evaluations.iku_id', '=', 'form_iku.id')
            ->where('form_iku.iku_id', 'LIKE', "IKU{$departmentName}_{$selectedYear}%")
            ->selectRaw('iku_evaluations.month as month, COUNT(*) as count')
            ->groupBy('iku_evaluations.month')
            ->pluck('count', 'month')
            ->toArray();

        $selectedMonth = (int) $selectedMonth;

        $progressThisMonth = isset($evaluatedIkuPerMonth[$selectedMonth]) && $totalIkuFinal > 0
            ? round(($evaluatedIkuPerMonth[$selectedMonth] / $totalIkuFinal) * 100, 2)
            : 0;

        $statusThisMonth = match (true) {
            $progressThisMonth === 0 => 'Incomplete',
            $progressThisMonth === 100 => 'Complete',
            default => 'In Progress',
        };

        return view('pages.dashboard', compact(
            'departments',
            'selectedDepartment',
            'departmentName',
            'selectedYear',
            'selectedMonth',
            'selectedMonthName',
            'totalAdjPerSasaran',
            'adjSeriesJson',
            'months',
            'isAdmin',
            'isDirector',
            'isDivision',
            'progressThisMonth',
            'statusThisMonth',
        ));
    }

    public function showAdmin(Request $request)
    {
        $nama = Auth::user()->nama;
        $isAdmin = DB::table('re_user_department')
            ->where('user_id', Auth::id())
            ->where('department_role', 'admin')
            ->exists();
        $isDirector = DB::table('users')
            ->where('id', Auth::id())
            ->where('role', 'director')
            ->exists();

        $isDivision = DB::table('users')
            ->where('id', Auth::id())
            ->where('role', 'division')
            ->exists();
        $user_id = Auth::user()->id;
        $departmentInfo = DB::table('re_user_department as rud')
            ->join('department as d', 'rud.department_id', '=', 'd.department_id')
            ->where('rud.user_id', $user_id)
            ->select('rud.department_id', 'd.department_username')
            ->first();

        $department_id = $departmentInfo?->department_id ?? null;
        $departmentName = $departmentInfo?->department_username ?? 'Unknown';

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

        // -1 is used to exclude the 'admin' department from the count
        $totalDepartments = DB::table('department')->count() - 1;

        $chartData0 = DB::table('iku_evaluations as ie')
            ->join('form_iku as fi', 'ie.iku_id', '=', 'fi.id')
            ->join('sasaran_strategis as ss', 'fi.sasaran_id', '=', 'ss.id')
            ->join('kontrak_manajemen as km', 'ss.kontrak_id', '=', 'km.kontrak_id')
            ->where('ie.year', $selectedYear)
            ->where('ie.month', $selectedMonth)
            ->groupBy('ss.name', 'ss.position')
            ->orderBy('ss.position')
            ->select(
                'ss.name as x',
                DB::raw("SUM(ie.ttl) / $totalDepartments as target"),
                DB::raw("SUM(ie.adj) / $totalDepartments as actual")
            )
            ->get()
            ->map(function ($item) {
                return [
                    'x' => $item->x,
                    'actual' => round($item->actual, 2),
                    'target' => round($item->target, 2),
                ];
            });

        // Compute doughnut chart values
        $totalActual = round($chartData0->sum('actual'), 2);

        $gapTo100 = round(100 - $totalActual, 2);

        // Top 3 gaps
        $topGap = $chartData0
            ->map(function ($item) {
                $gap = round($item['target'] - $item['actual'], 2);
                return [
                    'x' => $item['x'],
                    'gap' => $gap
                ];
            })
            ->sortByDesc(function ($item) {
                return abs($item['gap']);
            })
            ->take(3)
            ->values();

        // Department
        $chartData2 = DB::table('iku_evaluations as ie')
            ->join('users as u', 'ie.user_id', '=', 'u.id')
            ->join('department as d', 'u.department_id', '=', 'd.department_id')
            ->where('ie.year', $selectedYear)
            ->where('ie.month', $selectedMonth)
            ->where('d.department_id', '!=', 1)
            ->groupBy('d.department_id', 'd.department_name')
            ->select(
                'd.department_name as x',
                DB::raw('SUM(ie.ttl) as target'),
                DB::raw('SUM(ie.adj) as actual')
            )
            ->get()
            ->map(function ($item) {
                return [
                    'x' => $item->x,
                    'actual' => round($item->actual, 2),
                    'target' => round($item->target, 2),
                ];
            });

        // Director
        $chartData1 = DB::table('director as dr')
            ->leftJoin('department as d', 'dr.director_id', '=', 'd.director_id')
            ->leftJoin('users as u', 'd.department_id', '=', 'u.department_id')
            ->leftJoin('iku_evaluations as ie', 'u.id', '=', 'ie.user_id')
            ->where('ie.year', $selectedYear)
            ->where('ie.month', $selectedMonth)
            ->groupBy('dr.director_id', 'dr.director_name')
            ->select(
                'dr.director_name as x',
                DB::raw('COUNT(DISTINCT d.department_id) as total_departments'),
                DB::raw('SUM(ie.ttl) as total_target'),
                DB::raw('SUM(ie.adj) as total_actual'),
                DB::raw('IFNULL(SUM(ie.ttl) / NULLIF(COUNT(DISTINCT d.department_id), 0), 0) as target'),
                DB::raw('IFNULL(SUM(ie.adj) / NULLIF(COUNT(DISTINCT d.department_id), 0), 0) as actual')
            )
            ->get()
            ->map(function ($item) {
                return [
                    'x' => $item->x,
                    'actual' => round($item->actual, 2),
                    'target' => round($item->target, 2),
                ];
            });

        // Divisions
        $chartData3 = DB::table('division as dv')
            ->leftJoin('department as d', 'dv.division_id', '=', 'd.division_id')
            ->leftJoin('users as u', 'd.department_id', '=', 'u.department_id')
            ->leftJoin('iku_evaluations as ie', 'u.id', '=', 'ie.user_id')
            ->where('ie.year', $selectedYear)
            ->where('ie.month', $selectedMonth)
            ->groupBy('dv.division_id', 'dv.division_name')
            ->select(
                'dv.division_name as x',
                DB::raw('COUNT(DISTINCT d.department_id) as total_departments'),
                DB::raw('SUM(ie.ttl) as total_target'),
                DB::raw('SUM(ie.adj) as total_actual'),
                DB::raw('IFNULL(SUM(ie.ttl) / NULLIF(COUNT(DISTINCT d.department_id), 0), 0) as target'),
                DB::raw('IFNULL(SUM(ie.adj) / NULLIF(COUNT(DISTINCT d.department_id), 0), 0) as actual')
            )
            ->get()
            ->map(function ($item) {
                return [
                    'x' => $item->x,
                    'actual' => round($item->actual, 2),
                    'target' => round($item->target, 2),
                ];
            });


        $departments = DB::table('department')->select('department_id', 'department_name')->get();
        return view('pages.dashboard-admin', compact(
            'departments',
            'selectedDepartment',
            'departmentName',
            'selectedYear',
            'selectedMonth',
            'selectedMonthName',
            'months',
            'isAdmin',
            'isDirector',
            'isDivision',
            'chartData0',
            'chartData1',
            'chartData2',
            'chartData3',
            'totalActual',
            'gapTo100',
            'topGap'
        ));
    }
}
