<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\KontrakManajemenExport;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class KontrakController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect('/kontrak')->with('error', 'Akses tidak diizinkan');
        }

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

        return view('pages.form-kontrak', compact(
            'sasaranGrouped',
            'sasaranStrategis',
            'selectedYear',
            'isAdmin',
            'isDirector',
            'isDivision'
        ));
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

        return view('pages.kontrak', compact(
            'sasaranGrouped',
            'selectedYear',
            'isAdmin',
            'isDirector',
            'isDivision',
        ));
    }

    public function editKpi($id)
    {
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
        $kpi = DB::table('form_kontrak_manajemen')->where('id', $id)->first();
        return view('pages.edit-kpi', compact('kpi','isAdmin', 'isDirector', 'isDivision'));
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
        $iku = DB::table('form_iku')
            ->join('sasaran_strategis', 'form_iku.sasaran_id', '=', 'sasaran_strategis.id')
            ->where('form_iku.id', $id)
            ->select('form_iku.*', 'sasaran_strategis.name as sasaran_name')
            ->first();

        if (!$iku) {
            return redirect()->route('progres.index')->with('error', 'IKU not found.');
        }

        return view('pages.iku.detail', compact('iku', 'isAdmin', 'isDirector', 'isDivision'));
    }

    public function exportKontrakManajemen(Request $request)
    {
        $year = $request->query('year', date('Y'));
        $export = new KontrakManajemenExport($year);

        return $export->export($request);
    }

    // Penajabaran
    public function showPenjabaran(Request $request)
    {
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

        $selectedYear = $request->query('year', date('Y'));
        $kontrak_id = 'KM_' . $selectedYear;

        $penjabaranData = DB::table('penjabaran_strategis as p')
            ->join('form_kontrak_manajemen as fkm', 'p.form_id', '=', 'fkm.id')
            ->join('sasaran_strategis as ss', 'fkm.sasaran_id', '=', 'ss.id')
            ->where('fkm.kontrak_id', $kontrak_id)
            ->select(
                'p.id as penjabaran_id',
                'ss.name as sasaran_name',
                'fkm.kpi_name',
                'fkm.target',
                'fkm.satuan',
                'p.proses_bisnis',
                'p.strategis',
                'p.pic'
            )
            ->get();

        $grouped = [];
        $letters = range('A', 'Z');
        $counter = 0;

        foreach ($penjabaranData as $item) {
            $sasaranName = $item->sasaran_name;
            if (!isset($grouped[$sasaranName])) {
                $grouped[$sasaranName] = [
                    'letter' => $letters[$counter] ?? '-',
                    'name' => $sasaranName,
                    'kpis' => [],
                ];
                $counter++;
            }

            $grouped[$sasaranName]['kpis'][] = $item;
        }

        return view('pages.penjabaran', [
            'selectedYear' => $selectedYear,
            'sasaranGrouped' => $grouped,
            'isAdmin' => $isAdmin,
            'isDirector' => $isDirector,
            'isDivision' => $isDivision,
        ]);
    }

    public function checkOrCreatePenjabaran(Request $request)
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

        return redirect()->route('form-penjabaran', ['year' => $selectedYear]);
    }

    public function showForm(Request $request)
    {

        $user = Auth::user();
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
        if (Auth::user()->role !== 'admin' && ($isDirector || $isDivision)) {
            return redirect('/penjabaran')->with('error', 'Akses tidak diizinkan');
        }
        $departmentId = $user->department_id;
        $selectedYear = (int) $request->query('year', date('Y'));
        $kontrak_id = 'KM_' . $selectedYear;

        // Get all sasaran
        $sasaranMap = DB::table('sasaran_strategis')
            ->where('kontrak_id', $kontrak_id)
            ->pluck('name', 'id');

        // Get form_kontrak
        $formKontrak = DB::table('form_kontrak_manajemen')
            ->where('kontrak_id', $kontrak_id)
            ->get()
            ->map(function ($form) use ($sasaranMap) {
                $form->sasaran_name = $sasaranMap[$form->sasaran_id] ?? '-';
                return $form;
            });

        $groupedFormKontrak = $formKontrak->groupBy('sasaran_name');

        // Get penjabaran
        $penjabaran = DB::table('penjabaran_strategis')
            ->whereIn('form_id', $formKontrak->pluck('id'))
            ->get()
            ->groupBy('form_id');

        $combinedData = [];
        $letter = 'A';

        foreach ($groupedFormKontrak as $sasaranName => $forms) {
            foreach ($forms as $form) {
                $penjabaranItems = $penjabaran->get($form->id, collect());

                $combinedData[] = [
                    'letter' => $letter,
                    'sasaran_name' => $sasaranName,
                    'form' => $form,
                    'penjabaran' => $penjabaranItems,
                ];
            }
            $letter++;
        }

        return view('pages.form-penjabaran', compact(
            'selectedYear',
            'kontrak_id',
            'combinedData',
            'groupedFormKontrak',
            'isAdmin',
            'isDirector',
            'isDivision',
        ));
    }

    public function storePenjabaran(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'form_id' => 'required|exists:form_kontrak_manajemen,id',
            'sasaran' => 'required|string|max:255',
            'target' => 'nullable|string|max:255',
            'satuan' => 'nullable|string|max:50',
            'proses' => 'nullable|string|max:255',
            'strategis' => 'nullable|string',
            'pic' => 'nullable|string|max:250',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Insert data into penjabaran_strategis table
        DB::table('penjabaran_strategis')->insert([
            'form_id' => $request->input('form_id'),
            'proses_bisnis' => $request->input('proses'),
            'strategis' => $request->input('strategis'),
            'pic' => $request->input('pic'),
        ]);

        return redirect()->back()->with('success', 'Penjabaran Strategis berhasil disimpan!');
    }

    public function editPenjabaran($id)
    {
        $user = Auth::user();
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
        $penjabaran = DB::table('penjabaran_strategis')->where('id', $id)->first();

        $kpi = DB::table('form_kontrak_manajemen')
            ->where('id', $penjabaran->form_id)
            ->first(['kpi_name', 'target', 'satuan']);

        return view('pages.edit-penjabaran', compact('penjabaran', 'kpi','isAdmin', 'isDirector', 'isDivision'));
    }

    public function updatePenjabaran(Request $request, $id)
    {
        DB::table('penjabaran_strategis')
            ->where('id', $id)
            ->update([
                'proses_bisnis' => $request->input('proses_bisnis'),
                'strategis'     => $request->input('strategis'),
                'pic'           => $request->input('pic'),
            ]);

        return redirect()->back()->with('success', 'Data updated successfully.');
    }

    public function deletePenjabaran($id)
    {
        DB::table('penjabaran_strategis')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Data deleted successfully.');
    }
}
