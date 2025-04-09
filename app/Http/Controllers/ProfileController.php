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

    $department = DB::table('department')
        ->where('department_id', $user->department_id)
        ->value('department_name');

    return view('pages.profile', [
        'name' => $user->nama,
        'username' => $user->username,
        'created_at' => $user->created_at->format('Y-m-d H:i:s'),
        'department' => $department ?? 'No Department',
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
