<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class EvaluasiController extends Controller
{
    public function showEvaluasi(Request $request)
    {
        $user = Auth::user();

        $isAdmin = DB::table('re_user_department')
            ->where('user_id', Auth::id())
            ->where('department_role', 'admin')
            ->exists();

        $departmentName = DB::table('department')
            ->where('department_id', $user->department_id)
            ->value('department_username');

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

        $selectedMonth = (int) $selectedMonth;
        $selectedMonthName = $months[$selectedMonth] ?? 'Unknown';

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
            ip.point_name AS sub_point_name
        FROM iku_evaluations ie
        LEFT JOIN users u ON ie.user_id = u.id
        LEFT JOIN department d ON u.department_id = d.department_id
        LEFT JOIN form_iku fi ON ie.iku_id = fi.id
        LEFT JOIN isi_iku isi ON fi.isi_iku_id = isi.id
        LEFT JOIN iku_point ip ON ie.point_id = ip.id
        WHERE ie.year = ?
          AND ie.month = ?
          AND u.department_id = ?
        ORDER BY fi.id, ie.id ASC
    ", [$selectedYear, $selectedMonth, $user->department_id]);

        return view('pages.evaluasi', compact(
            'departmentName',
            'selectedYear',
            'months',
            'selectedMonth',
            'selectedMonthName',
            'evaluations',
            'isAdmin'
        ));
    }

    public function index(Request $request)
    {
        $nama = Auth::user()->nama;
        $department_id = Auth::user()->department_id;
        $user = Auth::user();

        $isAdmin = DB::table('re_user_department')
            ->where('user_id', Auth::id())
            ->where('department_role', 'admin')
            ->exists();

        $departmentName = DB::table('department')
            ->where('department_id', $user->department_id)
            ->value('department_username');

        $selectedYear = $request->query('year', date('Y'));
        $selectedMonth = $request->query('month', date('n'));

        $selectedMonth = (int) $selectedMonth;
        $selectedYear = (int) $selectedYear;

        $kontrak_id = 'KM_' . $selectedYear;

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
            ip.point_name AS sub_point_name
        FROM iku_evaluations ie
        LEFT JOIN users u ON ie.user_id = u.id
        LEFT JOIN department d ON u.department_id = d.department_id
        LEFT JOIN form_iku fi ON ie.iku_id = fi.id
        LEFT JOIN isi_iku isi ON fi.isi_iku_id = isi.id
        LEFT JOIN iku_point ip ON ie.point_id = ip.id
        WHERE ie.year = ?
          AND ie.month = ?
          AND u.department_id = ?
        ORDER BY fi.id, ie.id ASC
    ", [$selectedYear, $selectedMonth, $user->department_id]);

        $iku_ikuIdentifier = 'IKU' . str_replace(' ', '_', $departmentName) . '_' .  $selectedYear;

        $sasaranStrategis = DB::table('sasaran_strategis')
            ->where('kontrak_id', $kontrak_id)
            ->get();

        $ikus = DB::table('form_iku')
            ->join('isi_iku', 'form_iku.isi_iku_id', '=', 'isi_iku.id')
            ->where('form_iku.iku_id', $iku_ikuIdentifier)
            ->whereRaw('form_iku.version = (SELECT MAX(version) FROM form_iku WHERE iku_id = form_iku.iku_id)')
            ->select(
                'form_iku.*',
                'isi_iku.iku',
                'isi_iku.proker',
                'isi_iku.pj',
                'form_iku.iku_atasan',
                'form_iku.sasaran_id',
                'form_iku.is_multi_point',
                'form_iku.base',
                'form_iku.stretch',
                'form_iku.bobot',
                'form_iku.satuan',
                'form_iku.polaritas'
            )
            ->get();

        $ikuPoints = DB::table('iku_point')->get()->groupBy('form_iku_id');

        $sasaranGrouped = [];
        $number = 1;

        foreach ($sasaranStrategis as $sasaran) {
            $sasaranGrouped[$sasaran->id] = [
                'number' => $number,
                'perspektif' => $sasaran->name,
                'ikus' => [],
            ];
            $number++;
        }

        foreach ($ikus as $iku) {
            $iku->points = $ikuPoints->get($iku->id, collect());

            if (isset($sasaranGrouped[$iku->sasaran_id])) {
                $sasaranGrouped[$iku->sasaran_id]['ikus'][] = $iku;
            }
        }

        return view('pages.form-evaluasi', compact(
            'selectedYear',
            'selectedMonth',
            'sasaranGrouped',
            'sasaranStrategis',
            'ikus',
            'ikuPoints',
            'months',
            'selectedMonth',
            'selectedMonthName',
            'evaluations',
            'isAdmin'
        ));
    }


    public function store(Request $request)
    {
        $userId = Auth::id();
        $ikuId = $request->input('selected_iku_id');
        $pointId = $request->input('selected_sub_points');
        $year = $request->input('year');
        $month = $request->input('month');

        $polaritas = $request->input('polaritas');
        $bobot = $request->input('bobot');
        $satuan = $request->input('satuan');
        $base = $request->input('base');
        $targetBulanIni = $request->input('target_bulan_ini');
        $targetSdBulanIni = $request->input('target_sdbulan_ini');
        $realisasiBulanIni = $request->input('realisasi_bulan_ini');
        $realisasiSdBulanIni = $request->input('realisasi_sdbulan_ini');
        $percentTarget = $request->input('percent_target');
        $percentYear = $request->input('percent_year');
        $ttl = $request->input('ttl');
        $adj = $request->input('adj');
        $penyebabTidakTercapai = $request->input('penyebab_tidak_tercapai');
        $programKerja = $request->input('program_kerja');

        DB::insert("
            INSERT INTO iku_evaluations (
                user_id, iku_id, point_id, year, month, polaritas, bobot, satuan, base,
                target_bulan_ini, target_sdbulan_ini, realisasi_bulan_ini, realisasi_sdbulan_ini,
                percent_target, percent_year, ttl, adj, penyebab_tidak_tercapai, program_kerja, created_at, updated_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
        ", [
            $userId,
            $ikuId,
            $pointId,
            $year,
            $month,
            $polaritas,
            $bobot,
            $satuan,
            $base,
            $targetBulanIni,
            $targetSdBulanIni,
            $realisasiBulanIni,
            $realisasiSdBulanIni,
            $percentTarget,
            $percentYear,
            $ttl,
            $adj,
            $penyebabTidakTercapai,
            $programKerja
        ]);

        return redirect()->back()->with('success', 'Evaluation saved successfully.');
    }

    public function edit($id)
    {
        $selectedYear = request()->query('year', date('Y'));
        $selectedMonth = request()->query('month', date('n'));

        $eval = DB::table('iku_evaluations')
            ->join('form_iku', 'iku_evaluations.iku_id', '=', 'form_iku.id')
            ->join('isi_iku', 'form_iku.isi_iku_id', '=', 'isi_iku.id')
            ->select(
                'iku_evaluations.*',
                'isi_iku.iku as iku_name'
            )
            ->where('iku_evaluations.id', $id)
            ->first();

        return view('pages.edit-evaluasi', compact('eval', 'selectedYear', 'selectedMonth'));
    }


    public function update(Request $request, $id)
    {
        DB::table('iku_evaluations')
            ->where('id', $id)
            ->update([
                'polaritas' => $request->polaritas,
                'bobot' => $request->bobot,
                'satuan' => $request->satuan,
                'base' => $request->base,
                'target_bulan_ini' => $request->target_bulan_ini,
                'target_sdbulan_ini' => $request->target_sdbulan_ini,
                'realisasi_bulan_ini' => $request->realisasi_bulan_ini,
                'realisasi_sdbulan_ini' => $request->realisasi_sdbulan_ini,
                'percent_target' => $request->percent_target,
                'percent_year' => $request->percent_year,
                'ttl' => $request->ttl,
                'adj' => $request->adj,
                'penyebab_tidak_tercapai' => $request->penyebab_tidak_tercapai,
                'program_kerja' => $request->program_kerja
            ]);

        return redirect()->route('form-evaluasi')->with('success', 'Data updated successfully.');
    }

    public function destroy($id, Request $request)
    {
        DB::table('iku_evaluations')->where('id', $id)->delete();

        $redirectUrl = $request->input('redirect_url', route('form-evaluasi'));

        return redirect($redirectUrl)->with('success', 'Data berhasil dihapus.');
    }
}
