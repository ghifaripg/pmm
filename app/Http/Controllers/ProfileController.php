<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function profile()
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

    $department = DB::table('department')
        ->where('department_id', $user->department_id)
        ->value('department_name');

    $bisnisTerkait = DB::table('re_bisnis_department as rbd')
        ->join('bisnis_terkait as bt', 'rbd.bisnis_terkait_id', '=', 'bt.id')
        ->where('rbd.department_id', $user->department_id)
        ->pluck('bt.name');

    return view('pages.profile', [
        'name' => $user->nama,
        'username' => $user->username,
        'created_at' => $user->created_at->format('Y-m-d H:i:s'),
        'department' => $department ?? 'No Department',
        'isAdmin' => $isAdmin,
        'bisnisTerkait' => $bisnisTerkait,
        'isDirector' => $isDirector,
        'isDivision' => $isDivision,
    ]);
}


    public function updateUsername(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . Auth::id(),
        ]);

        $user = Auth::user();
        $user->username = $request->username;
        /** @var \App\Models\User $user **/
        $user->save();

        return redirect()->route('profile')->with('success', 'Username berhasil diperbarui!');
    }
}
