<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('authentication.sign-in');
    }

    public function login(Request $request)
    {
    $credentials = $request->validate([
        'username' => 'required',
        'password' => 'required'
    ]);

    $user = User::where('username', $credentials['username'])->first();

    if (!$user || !Hash::check($credentials['password'], $user->password)) {
        return redirect()->back()->with('error', 'Username atau password salah');
    }

    Auth::login($user);
    return redirect('/dashboard')->with('success', 'Login Berhasil!');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/')->with('success', 'Log out Berhasil');
    }
}
