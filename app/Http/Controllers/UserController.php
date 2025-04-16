<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function showAll()
    {
        $isAdmin = DB::table('re_user_department')
            ->where('user_id', Auth::id())
            ->where('department_role', 'admin')
            ->exists();

        if (!$isAdmin) {
            return redirect('/dashboard')->with('error', 'Unauthorized access.');
        }

        $users = DB::table('users')
            ->leftJoin('department', 'users.department_id', '=', 'department.department_id')
            ->select('users.id', 'users.nama', 'users.username', 'department.department_name')
            ->get();

        return view('pages.user', ['users' => $users, 'isAdmin' => $isAdmin]);
    }


    public function delete($id)
    {
        DB::table('users')->where('id', '=', $id)->delete();
        return redirect('/user')->with('success', 'User deleted successfully.');
    }

    public function edit($id)
    {
        if (Auth::id() !== 1) {
            return redirect('/dashboard')->with('error', 'Unauthorized access.');
        }

        $user = DB::table('users')->find($id);
        $departments = DB::table('department')->select('department_id', 'department_name')->get();

        return view('pages.edit-user', ['user' => $user, 'departments' => $departments]);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'username' => 'nullable|string|max:255',
            'full_name' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:6',
            'department_id' => 'required|integer|exists:department,department_id',
        ]);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }

        DB::table('users')->where('id', $id)->update($data);

        return redirect('/users')->with('success', 'User updated successfully');
    }
}
