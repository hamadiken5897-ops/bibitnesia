<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login', ['title' => 'Login']);
    }
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:6']
        ]);
        if (
            session()->has('login_attempts') && session('login_attempts') >=
            5
        ) {
            return back()->withErrors([
                'email' => 'Terlalu banyak percobaan login. Silakan coba lagi
dalam 1 menit.'
            ]);
        }
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            session()->forget('login_attempts');
            return redirect()->intended('/dashboard')->with('success', 'Login
berhasil!');
        }
        session()->increment('login_attempts', 1);
        throw ValidationException::withMessages([
            'email' => 'Email atau password salah.'
        ]);
    }
    public function showRegister()
    {
        return view('auth.register', ['title' => 'Daftar']);
    }
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed'
        ]);
        User::create([
            'name' => e($validated['name']),
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);
        return redirect()->route('login')->with('success', 'Registrasi
berhasil. Silakan login.');
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Anda telah
logout.');
    }
}
