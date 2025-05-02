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
        $user = Auth::user();
        $departments = DB::table('department')->get();

        return view('authentication.sign-up', compact('departments', 'userId', 'user'));
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
        // Validate basic department fields
        $validated = $request->validate([
            'department_name' => 'required|string|max:255|unique:department,department_name',
            'department_username' => 'required|string|max:255',
            'bisnis_terkait' => 'array|nullable',
            'bisnis_terkait.*' => 'string|required', // accept both existing IDs and new_ IDs
        ]);

        DB::beginTransaction();

        try {
            // Insert department
            $departmentId = DB::table('department')->insertGetId([
                'department_name' => $validated['department_name'],
                'department_username' => $validated['department_username'],
            ]);

            $pivotData = [];

            if (!empty($validated['bisnis_terkait'])) {
                foreach ($validated['bisnis_terkait'] as $bisnisItem) {
                    if (str_starts_with($bisnisItem, 'new_')) {
                        // It's a new bisnis created by user
                        $newBisnisName = $request->input('new_bisnis_names.' . $bisnisItem);

                        if (!$newBisnisName) {
                            throw new \Exception("Invalid new bisnis name.");
                        }

                        // Insert new bisnis_terkait
                        $newBisnisId = DB::table('bisnis_terkait')->insertGetId([
                            'name' => $newBisnisName,
                        ]);

                        $pivotData[] = [
                            'department_id' => $departmentId,
                            'bisnis_terkait_id' => $newBisnisId,
                        ];
                    } else {
                        // It's an existing bisnis ID
                        $pivotData[] = [
                            'department_id' => $departmentId,
                            'bisnis_terkait_id' => $bisnisItem,
                        ];
                    }
                }

                // Insert into pivot table
                DB::table('re_bisnis_department')->insert($pivotData);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Department registration successful!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Department registration failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to register department. Please try again.');
        }
    }
}
