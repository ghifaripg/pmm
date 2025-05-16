<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Department;
use App\Models\Director;
use App\Models\Division;

class UserController extends Controller
{


    public function showAll()
    {
        $currentUser = Auth::user();

        $isAdmin = DB::table('re_user_department')
            ->where('user_id', $currentUser->id)
            ->where('department_role', 'admin')
            ->exists();

        $isDirector = $currentUser->role === 'director';
        $isDivision = $currentUser->role === 'division';
        $isDepartment = $currentUser->role === 'department';

        if (!$isAdmin && $isDirector && $isDivision) {
            return redirect('/dashboard')->with('error', 'Unauthorized access.');
        }

        $usersQuery = DB::table('users')
            ->leftJoin('department', 'users.department_id', '=', 'department.department_id')
            ->leftJoin('division', 'users.division_id', '=', 'division.division_id')
            ->leftJoin('director', 'users.director_id', '=', 'director.director_id')
            ->select(
                'users.id',
                'users.nama',
                'users.username',
                'users.role',
                DB::raw("
            CASE
                WHEN users.role = 'department' THEN department.department_name
                WHEN users.role = 'division' THEN division.division_name
                WHEN users.role = 'director' THEN director.director_name
                ELSE NULL
            END AS unit_kerja_name
        ")
            );


        if ($isDepartment) {
            $usersQuery->where('users.department_id', $currentUser->department_id);
        }

        if ($isDirector) {
            // Step 1: Get director_id from `users` table
            $directorId = DB::table('director')
                ->where('director_id', $currentUser->director_id)
                ->value('director_id');

            if ($directorId) {
                // Step 2: Get all departments and divisions under this director
                $departmentIds = Department::where('director_id', $directorId)->pluck('department_id')->toArray();
                $divisionIds = Division::where('director_id', $directorId)->pluck('division_id')->toArray();

                // Step 3: Get users who belong to those departments or divisions
                $usersQuery->where(function ($query) use ($departmentIds, $divisionIds) {
                    $query->whereIn('users.department_id', $departmentIds)
                        ->orWhereIn('users.division_id', $divisionIds);
                });
            } else {
                // No match in director table
                $usersQuery->whereRaw('1 = 0');
            }
        }

        if ($isDivision) {
            // Step 1: Get director_id from `users` table
            $divisionId = DB::table('division')
                ->where('division_id', $currentUser->division_id)
                ->value('division_id');

            if ($divisionId) {
                // Step 2: Get all departments and divisions under this director
                $departmentIds = Department::where('division_id', $divisionId)->pluck('department_id')->toArray();

                // Step 3: Get users who belong to those departments or divisions
                $usersQuery->where(function ($query) use ($departmentIds) {
                    $query->whereIn('users.department_id', $departmentIds);
                });
            } else {
                // No match in director table
                $usersQuery->whereRaw('1 = 0');
            }
        }
        $users = $usersQuery->get();

        return view('pages.user', [
            'users' => $users,
            'isAdmin' => $isAdmin,
            'isDirector' => $isDirector,
            'isDivision' => $isDivision,
        ]);
    }



    public function delete($id)
    {
        DB::table('users')->where('id', '=', $id)->delete();
        return redirect('/user')->with('success', 'User deleted successfully.');
    }

    public function edit($id)
    {
        $currentUserId = Auth::id();

        // Check if current user is Admin of any department
        $isAdmin = DB::table('re_user_department')
            ->where('user_id', $currentUserId)
            ->where('department_role', 'admin')
            ->exists();

        if (!$isAdmin) {
            return redirect('/dashboard')->with('error', 'Unauthorized access.');
        }

        // Fetch user to edit
        $user = DB::table('users')
            ->leftJoin('re_user_department', 'users.id', '=', 're_user_department.user_id')
            ->select('users.*', 're_user_department.department_role')
            ->where('users.id', $id)
            ->first();


        // Fetch all departments, directors, and divisions using Eloquent
        $departments = Department::select('department_id', 'department_name')->get();
        $directors = Director::all();
        $divisions = Division::all();

        // Get current role of the user being edited
        $currentRole = DB::table('re_user_department')
            ->where('user_id', $id)
            ->value('department_role');

        // Check roles of the current (logged-in) user
        $loggedInUser = DB::table('users')->where('id', $currentUserId)->first();
        $isDirector = $loggedInUser->role === 'director';
        $isDivision = $loggedInUser->role === 'division';

        return view('pages.edit-user', [
            'user' => $user,
            'departments' => $departments,
            'directors' => $directors,
            'divisions' => $divisions,
            'isAdmin' => $isAdmin,
            'roles' => ['Admin', 'User'],
            'currentRole' => $currentRole,
            'isDirector' => $isDirector,
            'isDivision' => $isDivision,
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
