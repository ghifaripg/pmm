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
        $currentUserId = Auth::id();

        $isAdmin = DB::table('re_user_department')
            ->where('user_id', $currentUserId)
            ->where('department_role', 'admin')
            ->exists();

        if (!$isAdmin) {
            return redirect('/dashboard')->with('error', 'Unauthorized access.');
        }

        // Get the current user's department
        $currentDepartmentId = DB::table('users')
            ->where('id', $currentUserId)
            ->value('department_id');

        $usersQuery = DB::table('users')
            ->leftJoin('department', 'users.department_id', '=', 'department.department_id')
            ->select('users.id', 'users.nama', 'users.username', 'department.department_name');

        if ($currentUserId != 1) {
            $usersQuery->where('users.department_id', $currentDepartmentId);
        }

        $users = $usersQuery->get();

        return view('pages.user', ['users' => $users, 'isAdmin' => $isAdmin]);
    }

    public function delete($id)
    {
        DB::table('users')->where('id', '=', $id)->delete();
        return redirect('/user')->with('success', 'User deleted successfully.');
    }

    public function edit($id)
    {
        $currentUserId = Auth::id();

        $isAdmin = DB::table('re_user_department')
            ->where('user_id', $currentUserId)
            ->where('department_role', 'admin')
            ->exists();

        if (!$isAdmin) {
            return redirect('/dashboard')->with('error', 'Unauthorized access.');
        }

        $user = DB::table('users')->find($id);
        $departments = DB::table('department')->select('department_id', 'department_name')->get();

        // Get the current role of the user being edited
        $currentRole = DB::table('re_user_department')
            ->where('user_id', $id)
            ->value('department_role');

        $roles = ['Admin', 'User'];

        return view('pages.edit-user', [
            'user' => $user,
            'departments' => $departments,
            'isAdmin' => $isAdmin,
            'roles' => $roles,
            'currentRole' => $currentRole
        ]);
    }

    public function update(Request $request, $id)
{
    $validated = $request->validate([
        'username' => 'nullable|string|max:255',
        'nama' => 'nullable|string|max:255',
        'password' => 'nullable|string|min:6',
        'department_id' => 'required|integer|exists:department,department_id',
        'department_role' => 'required|in:Admin,User',
    ]);

    $userData = [
        'username' => $validated['username'],
        'nama' => $validated['nama'],
        'department_id' => $validated['department_id'],
    ];

    if (!empty($validated['password'])) {
        $userData['password'] = Hash::make($validated['password']);
    }

    DB::table('users')->where('id', $id)->update($userData);

    DB::table('re_user_department')
        ->where('user_id', $id)
        ->update([
            'department_id' => $validated['department_id'],
            'department_role' => $validated['department_role'],
        ]);

    return redirect('/users')->with('success', 'User updated successfully');
}

}
