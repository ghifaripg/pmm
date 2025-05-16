<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Department;
use App\Models\Division;
use App\Models\Director;


class DepartmentController extends Controller
{
    public function showDepartment()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in first.');
        }

        $isAdmin = DB::table('re_user_department')
            ->where('user_id', $user->id)
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

        // Fetch each unit kerja type separately
        $directors = Director::all()->map(function ($item) {
            return (object)[
                'id' => $item->director_id,
                'name' => $item->director_name,
                'username' => $item->director_username,
                'type' => 'Director',
                'atasan' => '-'
            ];
        });

        $divisions = Division::with('director')->get()->map(function ($item) {
            return (object)[
                'id' => $item->division_id,
                'name' => $item->division_name,
                'username' => $item->division_username,
                'type' => 'Division',
                'atasan' => optional($item->director)->director_name ?? '-'
            ];
        });

        $departments = Department::with(['division', 'director'])->get()->map(function ($item) {
            $atasan = [];

            if (!empty($item->division)) {
                $atasan[] = $item->division->division_name;
            }
            if (!empty($item->director)) {
                $atasan[] = $item->director->director_name;
            }

            return (object)[
                'id' => $item->department_id,
                'name' => $item->department_name,
                'username' => $item->department_username,
                'type' => 'Department',
                'atasan' => $atasan ? implode(' & ', $atasan) : '-'
            ];
        });

        // Merge all into one list and sort by type order
        $unitKerja = collect()
            ->merge($directors)
            ->merge($divisions)
            ->merge($departments)
            ->sortBy(function ($item) {
                return ['Director' => 0, 'Division' => 1, 'Department' => 2][$item->type] ?? 3;
            });

        return view('pages.department', [
            'name' => $user->nama,
            'username' => $user->username,
            'created_at' => $user->created_at->format('Y-m-d H:i:s'),
            'departments' => $unitKerja,
            'isAdmin' => $isAdmin,
            'isDirector' => $isDirector,
            'isDivision' => $isDivision,
        ]);
    }

    public function edit($id)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in first.');
        }

        $isAdmin = DB::table('re_user_department')
            ->where('user_id', $user->id)
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
        // Get the department
        $department = DB::table('department')->where('department_id', $id)->first();

        if (!$department) {
            return redirect()->back()->with('error', 'Department not found.');
        }

        // Get all bisnis_terkait
        $allBisnis = DB::table('bisnis_terkait')->get();

        // Get bisnis_terkait_ids already related to this department
        $selectedBisnisIds = DB::table('re_bisnis_department')
            ->where('department_id', $id)
            ->pluck('bisnis_terkait_id')
            ->toArray();

        return view('pages.edit-department', [
            'department' => $department,
            'allBisnis' => $allBisnis,
            'selectedBisnisIds' => $selectedBisnisIds,
            'isAdmin' => $isAdmin,
            'isDirector' => $isDirector,
            'isDivision' => $isDivision,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'department_name' => 'required|string|max:255',
            'department_username' => 'required|string|max:255',
            'bisnis_terkait' => 'nullable|array',
            'bisnis_terkait.*' => 'exists:bisnis_terkait,id',
        ]);

        // Update department main data
        DB::table('department')
            ->where('department_id', $id)
            ->update([
                'department_name' => $request->department_name,
                'department_username' => $request->department_username,
            ]);

        // Update bisnis terkait (pivot table)
        DB::table('re_bisnis_department')->where('department_id', $id)->delete();

        if ($request->has('bisnis_terkait')) {
            $data = [];
            foreach ($request->bisnis_terkait as $bisnisId) {
                $data[] = [
                    'department_id' => $id,
                    'bisnis_terkait_id' => $bisnisId,
                ];
            }
            DB::table('re_bisnis_department')->insert($data);
        }

        return redirect()->route('showDepartment')->with('success', 'Department updated successfully.');
    }

    public function destroy($id)
    {
        DB::table('department')->where('department_id', $id)->delete();

        return redirect()->back()->with('success', 'Department deleted successfully.');
    }
}
