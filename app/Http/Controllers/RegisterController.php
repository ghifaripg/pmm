<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

        $bisnisTerkait = DB::table('bisnis_terkait')->get();

        return view('authentication.register-department', compact('departments', 'bisnisTerkait'));
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:users,username',
            'nama' => 'required|string|max:255|unique:users,nama',
            'password' => 'required|string|confirmed',
            'department_id' => 'nullable|exists:department,department_id',
            'department_role' => 'required|in:Admin,User',
        ]);

        // Create user
        $user = User::create([
            'username' => $validated['username'],
            'nama' => $validated['nama'],
            'password' => Hash::make($validated['password']),
            'department_id' => $validated['department_id'] ?? null,
        ]);

        // Insert department role
        if ($validated['department_id']) {
            DB::table('re_user_department')->insert([
                'user_id' => $user->id,
                'department_id' => $validated['department_id'],
                'department_role' => $validated['department_role'],
            ]);
        }

        return redirect()->back()->with('success', 'Registration successful!');
    }

    public function registerDepartment(Request $request)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'department_name' => 'required|string|max:255|unique:department,department_name',
            'department_username' => 'required|string|max:255',
            'bisnis_terkait' => 'array|nullable', // Bisnis terkait can be an array
            'bisnis_terkait.*' => 'exists:bisnis_terkait,id', // Make sure each selected bisnis_terkait exists
        ]);

        // Start a transaction to ensure data consistency
        DB::beginTransaction();

        try {
            // Insert the department data into the 'department' table
            $departmentId = DB::table('department')->insertGetId([
                'department_name' => $validated['department_name'],
                'department_username' => $validated['department_username'],
            ]);

            // If there are 'bisnis_terkait' to associate, insert them into the pivot table
            if (!empty($validated['bisnis_terkait'])) {
                $pivotData = [];
                foreach ($validated['bisnis_terkait'] as $bisnisId) {
                    $pivotData[] = [
                        'department_id' => $departmentId,
                        'bisnis_terkait_id' => $bisnisId,
                    ];
                }

                // Insert data into 're_bisnis_department' pivot table
                DB::table('re_bisnis_department')->insert($pivotData);
            }

            // Commit the transaction
            DB::commit();

            // Redirect back with success message
            return redirect()->back()->with('success', 'Department registration successful!');
        } catch (\Exception $e) {
            // Rollback the transaction if something goes wrong
            DB::rollBack();

            // Log the error for debugging (optional)
            Log::error('Department registration failed: ' . $e->getMessage());

            // Redirect back with error message
            return redirect()->back()->with('error', 'Failed to register department. Please try again.');
        }
    }
}
