<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProgresController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $isAdmin = DB::table('re_user_department')
            ->where('user_id', Auth::id())
            ->where('department_role', 'admin')
            ->exists();

        $department = DB::table('department')
            ->where('department_id', $user->department_id)
            ->first();

        if (!$department) {
            return redirect('/dashboard')->with('error', 'Department not found.');
        }

        $departmentUsername = $department->department_username;

        $progresData = DB::table('progres')
            ->join('iku', 'progres.iku_id', '=', 'iku.iku_id')
            ->select(
                'progres.id',
                'progres.iku_id',
                'iku.department_name as nama_department',
                'iku.tahun as tahun',
                'progres.status',
                'progres.need_discussion',
                'progres.meeting_date',
                'progres.notes'
            );

        if ($user->id !== 1) {
            $progresData->where('iku.iku_id', 'LIKE', "%{$departmentUsername}%");
        }

        $progresData = $progresData->paginate(5);

        return view('pages.progres', compact('progresData', 'isAdmin'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'year' => 'required|integer',
        ]);

        $userId = Auth::id();
        $selectedYear = $request->year;

        $existingProgres = DB::table('progres')
            ->where('user_id', $userId)
            ->where('year', $selectedYear)
            ->exists();

        if ($existingProgres) {
            return redirect()->route('progres')->with('error', 'Anda sudah memiliki progres untuk tahun ini.');
        }

        DB::table('progres')->insert([
            'user_id' => $userId,
            'name' => $request->name,
            'year' => $selectedYear,
            'status' => 'pending',
            'need_discussion' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('progres')->with('success', 'Progres berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();

        if ($user->id !== 1) {
            return redirect()->route('progres')->with('error', 'Unauthorized action.');
        }

        $request->validate([
            'status' => 'required|in:pending,accept,reject',
            'need_discussion' => 'boolean',
            'meeting_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        DB::table('progres')->where('id', $id)->update([
            'status' => $request->status,
            'need_discussion' => $request->need_discussion ?? 0,
            'meeting_date' => $request->meeting_date,
            'notes' => $request->notes,
        ]);

        return redirect()->route('progres.index')->with('success', 'Progres berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = Auth::user();

        if ($user->id !== 1) {
            return redirect()->route('progres')->with('error', 'Unauthorized action.');
        }

        DB::table('progres')->where('id', $id)->delete();
        return redirect()->route('progres')->with('success', 'Progres berhasil dihapus.');
    }
}
