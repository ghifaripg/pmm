<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\IkuExport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class IkuController extends Controller
{
    public function showIku(Request $request)
    {
        $nama = Auth::user()->nama;
        $isAdmin = DB::table('re_user_department')
            ->where('user_id', Auth::id())
            ->where('department_role', 'admin')
            ->exists();
        $selectedYear = $request->query('year', date('Y'));
        $selectedVersion = $request->query('version', 1);
        $kontrak_id = 'KM_' . $selectedYear;
        $department_id = Auth::user()->department_id;

        $department = DB::table('department')
            ->where('department_id', $department_id)
            ->select('department_username')
            ->first();

        if (!$department || !isset($department->department_username)) {
            return back()->with('error', 'Department not found or missing department name');
        }

        $departmentName = (string) $department->department_username;
        $iku_ikuIdentifier = 'IKU' . str_replace(' ', '_', $departmentName) . '_' .  $selectedYear;

        // Get all versions for the selected IKU
        $versions = DB::table('form_iku')
            ->where('iku_id', $iku_ikuIdentifier)
            ->distinct()
            ->pluck('version');

        $sasaranStrategis = DB::table('sasaran_strategis')
            ->where('kontrak_id', $kontrak_id)
            ->get();

        $ikus = DB::table('form_iku')
            ->join('isi_iku', 'form_iku.isi_iku_id', '=', 'isi_iku.id')
            ->where('form_iku.iku_id', $iku_ikuIdentifier)
            ->where('form_iku.version', $selectedVersion)
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

        return view('pages.iku', compact(
            'nama',
            'sasaranStrategis',
            'sasaranGrouped',
            'ikuPoints',
            'selectedYear',
            'iku_ikuIdentifier',
            'versions',
            'selectedVersion',
            'isAdmin'
        ));
    }

    public function addVersion(Request $request)
    {
        $iku_id = $request->input('iku_id');

        $highestVersion = DB::table('form_iku')
            ->where('iku_id', $iku_id)
            ->max('version');

        $newVersion = $highestVersion + 1;

        // Get the latest version's data from form_iku
        $latestFormIkuData = DB::table('form_iku')
            ->where('iku_id', $iku_id)
            ->where('version', $highestVersion)
            ->get();

        // Loop through each form_iku record and duplicate it
        foreach ($latestFormIkuData as $formIku) {
            $isiIku = DB::table('isi_iku')
                ->where('id', $formIku->isi_iku_id)
                ->first();

            if ($isiIku) {
                $newIsiIkuId = DB::table('isi_iku')->insertGetId([
                    'iku' => $isiIku->iku,
                    'proker' => $isiIku->proker,
                    'pj' => $isiIku->pj,
                ]);

                $newFormIkuId = DB::table('form_iku')->insertGetId([
                    'iku_id' => $formIku->iku_id,
                    'sasaran_id' => $formIku->sasaran_id,
                    'version' => $newVersion,
                    'iku_atasan' => $formIku->iku_atasan,
                    'isi_iku_id' => $newIsiIkuId,
                    'target' => $formIku->target,
                    'is_multi_point' => $formIku->is_multi_point,
                    'base' => $formIku->base,
                    'stretch' => $formIku->stretch,
                    'satuan' => $formIku->satuan,
                    'polaritas' => $formIku->polaritas,
                    'bobot' => $formIku->bobot,
                ]);

                $ikuPoints = DB::table('iku_point')
                    ->where('form_iku_id', $formIku->id)
                    ->get();

                foreach ($ikuPoints as $ikuPoint) {
                    DB::table('iku_point')->insert([
                        'form_iku_id' => $newFormIkuId,
                        'point_name' => $ikuPoint->point_name,
                        'base' => $ikuPoint->base,
                        'stretch' => $ikuPoint->stretch,
                        'satuan' => $ikuPoint->satuan,
                        'polaritas' => $ikuPoint->polaritas,
                        'bobot' => $ikuPoint->bobot,
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Versi baru berhasil ditambahkan');
    }

    // Delete a version
    public function deleteVersion(Request $request, $iku_id, $version)
    {
        $formIkuRecords = DB::table('form_iku')
            ->where('iku_id', $iku_id)
            ->where('version', $version)
            ->get();

        foreach ($formIkuRecords as $formIku) {
            DB::table('iku_point')
                ->where('form_iku_id', $formIku->id)
                ->delete();

            DB::table('isi_iku')
                ->where('id', $formIku->isi_iku_id)
                ->delete();

            DB::table('form_iku')
                ->where('id', $formIku->id)
                ->delete();
        }

        return redirect()->back()->with('success', 'Versi berhasil dihapus');
    }

    public function checkOrCreateIku(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in');
        }

        $selectedYear = $request->input('year', date('Y'));
        $createdBy = Auth::user()->nama;
        $department_id = Auth::user()->department_id;

        $department = DB::table('department')
            ->where('department_id', $department_id)
            ->select('department_username')
            ->first();

        if (!$department || !isset($department->department_username)) {
            return back()->with('error', 'Department not found or missing department name');
        }

        $departmentName = (string) $department->department_username;
        $iku_id = 'IKU' . str_replace(' ', '_', $departmentName) . '_' .  $selectedYear;

        $iku = DB::table('iku')->where('iku_id', $iku_id)->first();

        if (!$iku) {
            DB::table('iku')->insert([
                'iku_id' => $iku_id,
                'tahun' => $selectedYear,
                'department_name' => $departmentName,
                'created_by' => $createdBy,
            ]);
        }

        return redirect()->route('form-iku', ['year' => $selectedYear]);
    }

    public function index(Request $request)
    {
        $nama = Auth::user()->nama;
        $isAdmin = DB::table('re_user_department')
            ->where('user_id', Auth::id())
            ->where('department_role', 'admin')
            ->exists();
        $selectedYear = $request->query('year', date('Y'));
        $selectedVersion = $request->query('version', 1);
        $kontrak_id = 'KM_' . $selectedYear;
        $department_id = Auth::user()->department_id;

        $department = DB::table('department')
            ->where('department_id', $department_id)
            ->select('department_username')
            ->first();

        if (!$department || !isset($department->department_username)) {
            return back()->with('error', 'Department not found or missing department name');
        }

        $departmentName = (string) $department->department_username;
        $iku_ikuIdentifier = 'IKU' . str_replace(' ', '_', $departmentName) . '_' .  $selectedYear;

        $sasaranStrategis = DB::table('sasaran_strategis')
            ->where('kontrak_id', $kontrak_id)
            ->get();

        // Filter data based on the selected version
        $ikus = DB::table('form_iku')
            ->join('isi_iku', 'form_iku.isi_iku_id', '=', 'isi_iku.id')
            ->where('form_iku.iku_id', $iku_ikuIdentifier)
            ->where('form_iku.version', $selectedVersion) // Filter by version
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

        return view('pages.form-iku', compact('nama',
        'sasaranStrategis',
        'sasaranGrouped',
        'ikuPoints',
        'selectedYear',
        'selectedVersion',
        'isAdmin'
    ));
    }

    public function storeIku(Request $request)
    {
        DB::beginTransaction();
        try {
            $selectedYear = $request->input('year', date('Y'));
            $selectedVersion = $request->input('version', 1);
            $userId = Auth::id();
            $departmentId = Auth::user()->department_id;

            $department = DB::table('department')
                ->where('department_id', $departmentId)
                ->select('department_username')
                ->first();

            if (!$department) {
                return redirect()->back()->with('error', 'Department not found.');
            }

            $departmentName = str_replace(' ', '_', $department->department_username);
            $ikuIdentifier = 'IKU' . $departmentName . '_' . $selectedYear;

            $ikuId = DB::table('isi_iku')->insertGetId([
                'iku' => $request->input('iku'),
                'proker' => $request->input('proker'),
                'pj' => $request->input('pj'),
            ]);

            $formIkuId = DB::table('form_iku')->insertGetId([
                'iku_id' => $ikuIdentifier,
                'sasaran_id' => $request->input('sasaran_id'),
                'version' => $selectedVersion,
                'iku_atasan' => $request->input('iku_atasan'),
                'isi_iku_id' => $ikuId,
                'target' => $request->input('target'),
                'base' => $request->input('single_base'),
                'stretch' => $request->input('single_stretch'),
                'satuan' => $request->input('single_satuan'),
                'polaritas' => $request->input('single_polaritas'),
                'bobot' => $request->input('single_bobot'),
            ]);

            if ($request->input('iku_type') === 'multiple' && $request->has('points')) {
                $ikuPoints = [];
                foreach ($request->input('points') as $point) {
                    $ikuPoints[] = [
                        'form_iku_id' => $formIkuId,
                        'point_name' => $point['name'],
                        'base' => $point['base'],
                        'stretch' => $point['stretch'],
                        'satuan' => $point['satuan'],
                        'polaritas' => $point['polaritas'],
                        'bobot' => $point['bobot'],
                    ];
                }

                DB::table('iku_point')->insert($ikuPoints);

                DB::table('form_iku')->where('id', $formIkuId)->update([
                    'is_multi_point' => 1,
                ]);
            }

            $existingProgres = DB::table('progres')
                ->where('iku_id', $ikuIdentifier)
                ->exists();

            if (!$existingProgres) {
                DB::table('progres')->insert([
                    'user_id' => $userId,
                    'iku_id' => $ikuIdentifier,
                    'status' => 'Pending',
                    'need_discussion' => null,
                    'meeting_date' => now(),
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'IKU successfully added.');
        } catch (\Exception $e) {
            DB::rollBack();
            dd('Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to add IKU: ' . $e->getMessage());
        }
    }
    // Delete the IKU
    public function deleteIku($id)
    {
        DB::table('form_iku')->where('id', $id)->delete();
        return redirect()->route('form-iku')->with('success', 'KPI deleted successfully!');
    }

    public function showDetail($ikuId)
    {
        preg_match('/\d{4}$/', $ikuId, $matches);
        $selectedYear = $matches[0] ?? null;

        if (!$selectedYear) {
            return back()->with('error', 'Invalid IKU ID format');
        }

        $isAdmin = DB::table('re_user_department')
            ->where('user_id', Auth::id())
            ->where('department_role', 'admin')
            ->exists();

        $ikus = DB::table('form_iku')
            ->join('isi_iku', 'form_iku.isi_iku_id', '=', 'isi_iku.id')
            ->join('sasaran_strategis', 'form_iku.sasaran_id', '=', 'sasaran_strategis.id')
            ->where('form_iku.iku_id', $ikuId)
            ->select(
                'form_iku.id as form_iku_id',
                'form_iku.*',
                'isi_iku.iku',
                'isi_iku.proker',
                'isi_iku.pj',
                'sasaran_strategis.id as sasaran_id',
                'sasaran_strategis.name as perspektif'
            )
            ->get();

        $ikuPoints = DB::table('iku_point')
            ->whereIn('form_iku_id', $ikus->pluck('form_iku_id'))
            ->get()
            ->groupBy('form_iku_id');

        $sasaranGrouped = [];
        $number = 1;

        foreach ($ikus as $iku) {
            $sasaranId = $iku->sasaran_id;

            if (!isset($sasaranGrouped[$sasaranId])) {
                $sasaranGrouped[$sasaranId] = (object) [
                    'number' => $number,
                    'perspektif' => $iku->perspektif,
                    'ikus' => []
                ];
                $number++;
            }

            $iku->points = $ikuPoints->get($iku->form_iku_id, collect());

            $sasaranGrouped[$sasaranId]->ikus[] = $iku;
        }

        return view('pages.detail', compact('sasaranGrouped', 'selectedYear', 'isAdmin'));
    }

    //Edit IKU
    public function editIku($id)
    {
        $iku = DB::table('form_iku')
            ->join('isi_iku', 'form_iku.isi_iku_id', '=', 'isi_iku.id')
            ->select(
                'form_iku.*',
                'isi_iku.iku',
                'isi_iku.proker',
                'isi_iku.pj'
            )
            ->where('form_iku.id', $id)
            ->first();


        if (!$iku) {
            abort(404, 'IKU not found');
        }

        $ikuPoints = DB::table('iku_point')
            ->where('form_iku_id', $iku->id)
            ->get();

        return view('pages.edit-iku', compact('iku', 'ikuPoints'));
    }

    // Update IKU
    public function updateIku(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'sasaran_id' => 'required|exists:sasaran_strategis,id',
                'iku_atasan' => 'nullable|string|max:500',
                'target' => 'nullable|string|max:500',
                'base' => 'nullable|string|max:500',
                'stretch' => 'nullable|string|max:500',
                'satuan' => 'nullable|string|max:500',
                'polaritas' => 'nullable|in:maximize,minimize',
                'bobot' => 'nullable|numeric|min:0|max:100',
                'iku' => 'required|string|max:500',
                'proker' => 'required|string',
                'pj' => 'required|string|max:500',
            ]);

            DB::table('form_iku')
                ->where('id', $id)
                ->update([
                    'sasaran_id' => $request->sasaran_id,
                    'iku_atasan' => $request->iku_atasan,
                    'target' => $request->target,
                    'base' => $request->base,
                    'stretch' => $request->stretch,
                    'satuan' => $request->satuan,
                    'polaritas' => $request->polaritas,
                    'bobot' => $request->bobot,
                ]);

            DB::table('isi_iku')
                ->where('id', function ($query) use ($id) {
                    $query->select('isi_iku_id')
                        ->from('form_iku')
                        ->where('id', $id);
                })
                ->update([
                    'iku' => $request->iku,
                    'proker' => $request->proker,
                    'pj' => $request->pj,
                ]);

            if ($request->has('points')) {
                foreach ($request->points as $pointId => $pointData) {
                    DB::table('iku_point')
                        ->where('id', $pointId)
                        ->update([
                            'point_name' => $pointData['point_name'],
                            'base' => $pointData['base'],
                            'stretch' => $pointData['stretch'],
                            'satuan' => $pointData['satuan'],
                            'polaritas' => $pointData['polaritas'],
                            'bobot' => $pointData['bobot'],
                        ]);
                }
            }

            return redirect()->route('form-iku', ['year' => $request->query('year', date('Y'))])
                ->with('success', 'IKU updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            dd('Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to add IKU: ' . $e->getMessage());
        }
    }

    //Export to Excel
    public function exportIku(Request $request)
    {
        $name = Auth::user()->name;
        $year = $request->query('year', date('Y'));

        $export = new IkuExport($year, $name, $request->all());

        return $export->export($request);
    }
}
