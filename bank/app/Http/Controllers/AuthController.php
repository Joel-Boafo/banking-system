<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function register()
    {
        return view('auth.register');
    }

    public function loginPost(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (!auth()->attempt($credentials)) {
            return redirect()->route('auth.login')->with('error', 'Invalid credentials');
        }

        return redirect()->route('home')->with('status', 'Logged in successfully');
    }

    public function registerPost(RegisterRequest $request)
    {

        $existingUser = User::where('first_name', $request->first_name)->andWhere('last_name', $request->last_name)->first();

        if ($existingUser) {
            return redirect()->route('auth.register')->with('error', 'User already exists');
        }

        $request->validated();

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($request->password !== $request->confirm_password) {
            return redirect()->route('auth.register')->with('error', 'Passwords do not match');
        }

        if (!$user) {
            return redirect()->route('auth.register')->with('error', 'Something went wrong');
        }

        return redirect()->route('auth.login')->with('status', 'User created successfully');
    }

    public function logout(Request $request)
    {
        auth()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('auth.login')->with('status', 'Logged out successfully');
    }
}
