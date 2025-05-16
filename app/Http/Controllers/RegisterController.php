<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Director;
use App\Models\Division;

class RegisterController extends Controller
{
    public function showForm()
    {
        $userId = Auth::id();
        $user = Auth::user();

        $directors = Director::with(['divisions.departments', 'departments'])->get();

        return view('authentication.sign-up', compact('userId', 'user', 'directors'));
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:users,username',
            'nama' => 'required|string|max:255|unique:users,nama',
            'password' => 'required|string|confirmed',
            'unit_kerja' => 'required|string',
            'department_role' => 'in:Admin,User',
        ]);

        // Extract unit kerja type and id
        [$type, $id] = explode('_', $validated['unit_kerja']);

        // Prepare data
        $data = [
            'username' => $validated['username'],
            'nama' => $validated['nama'],
            'password' => Hash::make($validated['password']),
            'department_id' => null,
            'division_id' => null,
            'director_id' => null,
            'role' => strtolower($type),
        ];

        if ($type === 'department') {
            $data['department_id'] = $id;
        } elseif ($type === 'division') {
            $data['division_id'] = $id;
        } elseif ($type === 'director') {
            $data['director_id'] = $id;
        }

        // Create user
        $user = User::create($data);

        // If department, insert into re_user_department
        if ($type === 'department') {
            DB::table('re_user_department')->insert([
                'user_id' => $user->id,
                'department_id' => $id,
                'department_role' => $validated['department_role'],
            ]);
        }

        return redirect()->back()->with('success', 'Registration successful!');
    }

    public function showRegis()
    {
        if (Auth::id() !== 1) {
            return redirect('/dashboard')->with('error', 'Unauthorized access.');
        }

        $departments = Department::all();
        $directors = Director::all();
        $divisions = Division::all();

        return view('authentication.register-department', compact('departments', 'directors', 'divisions'));
    }

    public function registerDepartment(Request $request)
    {
        $request->validate([
            'role_type' => 'required|in:director,division,department',
            'username' => 'required|string|max:255',
        ]);

        $role = $request->role_type;

        if ($role === 'director') {
            $request->validate([
                'director_name' => 'required|string|max:255|unique:director,director_name',
            ]);

            Director::create([
                'director_name' => $request->director_name,
                'director_username' => $request->username,
            ]);

            return redirect()->back()->with('success', 'Director registration successful!');
        }

        if ($role === 'division') {
            $request->validate([
                'director_select' => 'required|exists:director,director_id',
                'division_name' => 'required|string|max:255|unique:division,division_name',
            ]);

            $director = Director::find($request->director_select);

            Division::create([
                'division_name' => $request->division_name,
                'division_username' => $request->username,
                'director_id' => $director->director_id,
            ]);

            return redirect()->back()->with('success', 'Division registration successful!');
        }

        if ($role === 'department') {
            $request->validate([
                'director_select' => 'required|exists:director,director_id',
                'division_select' => 'required',
                'department_name' => 'required|string|max:500|unique:department,department_name',
            ]);

            $director = Director::find($request->director_select);

            $departmentData = [
                'department_name' => $request->department_name,
                'department_username' => $request->username,
                'director_id' => $director->director_id,
            ];

            // If selected division is not "-", set division_id
            if ($request->division_select !== '-') {
                $division = Division::find($request->division_select);
                if (!$division) {
                    return redirect()->back()->with('error', 'Selected division not found.');
                }
                $departmentData['division_id'] = $division->division_id;
            }

            Department::create($departmentData);

            return redirect()->back()->with('success', 'Department registration successful!');
        }

        return redirect()->back()->with('error', 'Invalid role selected.');
    }
}
