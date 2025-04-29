<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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

        // Get departments WITH their related bisnis_terkait
        $departments = DB::table('department')
            ->leftJoin('re_bisnis_department', 'department.department_id', '=', 're_bisnis_department.department_id')
            ->leftJoin('bisnis_terkait', 're_bisnis_department.bisnis_terkait_id', '=', 'bisnis_terkait.id')
            ->select(
                'department.department_id',
                'department.department_name',
                'department.department_username',
                DB::raw('GROUP_CONCAT(bisnis_terkait.name SEPARATOR ", ") as bisnis_names')
            )
            ->groupBy('department.department_id', 'department.department_name', 'department.department_username')
            ->get();

        return view('pages.department', [
            'name' => $user->nama,
            'username' => $user->username,
            'created_at' => $user->created_at->format('Y-m-d H:i:s'),
            'departments' => $departments,
            'isAdmin' => $isAdmin,
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
            'isAdmin' => $isAdmin
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
        // 1. Delete old links
        DB::table('re_bisnis_department')->where('department_id', $id)->delete();

        // 2. Insert new links if any bisnis selected
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
