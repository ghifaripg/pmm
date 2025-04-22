<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    public function showForm()
    {
        $userId = Auth::id();

        $departments = DB::table('department')->get();

        return view('authentication.sign-up', compact('departments'));
    }


    public function showRegis()
    {
        if (Auth::id() !== 1) {
            return redirect('/dashboard')->with('error', 'Unauthorized access.');
        }

        $departments = Department::all();

        return view('authentication.register-department', compact('departments'));
    }

    public function register(Request $request)
    {

        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:users,username',
            'nama' => 'required|string|max:255|unique:users,nama',
            'password' => 'required|string|confirmed',
            'department_id' => 'nullable|exists:department,department_id',
        ]);

        User::create([
            'username' => $validated['username'],
            'nama' => $validated['nama'],
            'password' => Hash::make($validated['password']),
            'department_id' => $validated['department_id'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Registration successful!');
    }



    public function registerDepartment(Request $request)
    {
        $validated = $request->validate([
            'department_name' => 'required|string|max:255|unique:department,department_name',
            'department_username' => 'required|string|max:255',
        ]);

        Department::create([
            'department_name' => $validated['department_name'],
            'department_username' => $validated['department_username'],
        ]);

        return redirect()->back()->with('success', 'Department registration successful!');
    }
}
