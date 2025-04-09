<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\KontrakManajemenExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class KontrakController extends Controller
{
    public function index(Request $request)
{
    if (Auth::id() !== 1) {
        return redirect('/kontrak')->with('error', 'Akses tidak diizinkan');
    }

    $selectedYear = $request->query('year', date('Y'));
    $kontrak_id = 'KM_' . $selectedYear;

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

    return view('pages.form-kontrak', compact('sasaranGrouped', 'sasaranStrategis', 'selectedYear'));
}


    public function checkOrCreateKontrak(Request $request)
    {
        $selectedYear = $request->input('year', date('Y'));
        $kontrak_id = 'KM_' . $selectedYear;

        $kontrak = DB::table('kontrak_manajemen')->where('kontrak_id', $kontrak_id)->first();

        if (!$kontrak) {
            DB::table('kontrak_manajemen')->insert([
                'kontrak_id' => $kontrak_id,
                'year' => $selectedYear,
            ]);
        }

        return redirect()->route('form-kontrak', ['year' => $selectedYear]);
    }

    public function storeSasaran(Request $request)
    {
        $selectedYear = $request->input('year', date('Y'));

        $validated = $request->validate([
            'sasaran_name' => 'required|string|max:255',
        ]);

        $kontrak_id = 'KM_' . $selectedYear;

        DB::table('sasaran_strategis')->insert([
            'kontrak_id' => $kontrak_id,
            'name' => $request->sasaran_name,
        ]);

        return redirect()->back()->with('success', 'Sasaran Strategis berhasil ditambahkan!');
    }

    public function storeKpi(Request $request)
    {
        $validated = $request->validate([
            'sasaran_id' => 'required|exists:sasaran_strategis,id',
            'kpi_name' => 'required|string|max:255',
            'target' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
            'milestone' => 'nullable|string|max:255',
            'esgc' => 'required|in:E,S,G,C',
            'polaritas' => 'required|in:maximize,minimize',
            'bobot' => 'required|numeric|min:0|max:100',
            'du' => 'required|in:O,R,S',
            'dk' => 'required|in:O,R,S',
            'do' => 'required|in:O,R,S',
        ]);

        DB::table('form_kontrak_manajemen')->insert([
            'sasaran_id' => $request->input('sasaran_id'),
            'kpi_name' => $request->input('kpi_name'),
            'target' => $request->input('target'),
            'satuan' => $request->input('satuan'),
            'milestone' => $request->input('milestone'),
            'esgc' => $request->input('esgc'),
            'polaritas' => $request->input('polaritas'),
            'bobot' => $request->input('bobot'),
            'du' => $request->input('du'),
            'dk' => $request->input('dk'),
            'do' => $request->input('do'),
        ]);

        return redirect()->back()->with('success', 'KPI successfully added.');
    }

    public function showKontrak(Request $request)
{
    $selectedYear = $request->query('year', date('Y'));
    $kontrak_id = 'KM_' . $selectedYear;

    $sasaranStrategis = DB::table('sasaran_strategis')
    ->where('kontrak_id', $kontrak_id)
    ->get();


    $kpiData = DB::table('form_kontrak_manajemen')
        ->join('sasaran_strategis', 'form_kontrak_manajemen.sasaran_id', '=', 'sasaran_strategis.id')
        ->where('sasaran_strategis.kontrak_id', $kontrak_id)
        ->select('form_kontrak_manajemen.*', 'sasaran_strategis.name as sasaran_name', 'sasaran_strategis.id as sasaran_id')
        ->get();

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

    return view('pages.kontrak', compact('sasaranGrouped', 'selectedYear'));
    }

    public function editKpi($id)
    {
        $kpi = DB::table('form_kontrak_manajemen')->where('id', $id)->first();
        return view('pages.edit-kpi', compact('kpi'));
    }

    public function updateKpi(Request $request, $id)
    {
        $validated = $request->validate([
            'sasaran_id' => 'required|exists:sasaran_strategis,id',
            'kpi_name' => 'required|string|max:255',
            'target' => 'required|string|max:255',
            'satuan' => 'required|string|max:255',
            'milestone' => 'nullable|string|max:255',
            'esgc' => 'required|in:E,S,G,C',
            'polaritas' => 'required|in:maximize,minimize',
            'bobot' => 'required|numeric|min:0|max:100',
            'du' => 'required|in:O,R,S',
            'dk' => 'required|in:O,R,S',
            'do' => 'required|in:O,R,S',
        ]);

        DB::table('form_kontrak_manajemen')
            ->where('id', $id)
            ->update([
                'sasaran_id' => $request->sasaran_id,
                'kpi_name' => $request->kpi_name,
                'target' => $request->target,
                'satuan' => $request->satuan,
                'milestone' => $request->milestone,
                'esgc' => $request->esgc,
                'polaritas' => $request->polaritas,
                'bobot' => $request->bobot,
                'du' => $request->du,
                'dk' => $request->dk,
                'do' => $request->do,
            ]);

        return redirect()->route('form-kontrak', ['year' => $request->query('year', date('Y'))])
            ->with('success', 'KPI updated successfully!');
    }

    public function deleteKpi($id)
    {
        DB::table('form_kontrak_manajemen')->where('id', $id)->delete();
        return redirect()->route('form-kontrak')->with('success', 'KPI deleted successfully!');
    }

    public function deleteSasaran($id)
    {
        DB::table('sasaran_strategis')->where('id', $id)->delete();
        return redirect()->route('form-kontrak')->with('success', 'Sasaran deleted successfully!');
    }

    public function detail($id)
    {
    $iku = DB::table('form_iku')
        ->join('sasaran_strategis', 'form_iku.sasaran_id', '=', 'sasaran_strategis.id')
        ->where('form_iku.id', $id)
        ->select('form_iku.*', 'sasaran_strategis.name as sasaran_name')
        ->first();

    if (!$iku) {
        return redirect()->route('progres.index')->with('error', 'IKU not found.');
    }

    return view('pages.iku.detail', compact('iku'));
    }

    public function exportKontrakManajemen(Request $request)
    {
        $year = $request->query('year', date('Y'));
        $export = new KontrakManajemenExport($year);

        return $export->export($request);
    }


}
