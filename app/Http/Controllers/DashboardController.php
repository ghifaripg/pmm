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

        $chartData0 = DB::table('sasaran_strategis as ss')
        ->join('kontrak_manajemen as km', 'ss.kontrak_id', '=', 'km.kontrak_id')
        ->where('km.year', $selectedYear)
        ->orderBy('ss.position')
        ->select(
            'ss.name as x',
            DB::raw('RAND() * 30 as actual'),
            DB::raw('RAND() * 30 as target')
        )
        ->get()
        ->map(function ($item) {
            return [
                'x' => $item->x,
                'actual' => round($item->actual, 2),
                'target' => round($item->target, 2)
            ];
        });

    // Compute doughnut chart values
    $totalActual = round($chartData0->sum('actual'), 2);
    $gapTo100 = round(100 - $totalActual, 2);

    // Top 3 gap contributors
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

        $chartData1 = DB::table('department')
            ->select('department_username as x')
            ->where('department_id', '!=', 1)
            ->get();

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
            'chartData0',
            'chartData1',
            'totalActual',
            'gapTo100',
            'topGap'
        ));
    }
}
