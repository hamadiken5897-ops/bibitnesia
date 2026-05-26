<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\Banned;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AuthController extends Controller
{
    // 🔹 Tampilkan halaman login
    public function showLogin()
    {
        return view('auth.login', ['title' => 'Login']);
    }

    // 🔹 Proses login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:6'],
        ]);
        //dd(Auth::attempt($credentials)); // ⬅ TEST 1
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            // Cek apakah akun di-banned
            if ($user->status_akun === 'BANNED') {
                $ban = Banned::where('id_user', $user->id_user)
                    ->orderBy('tgl_banned', 'desc')
                    ->first();

                if ($ban) {
                    if ($ban->status === 'SEMENTARA' && $ban->tgl_berakhir && Carbon::parse($ban->tgl_berakhir)->isPast()) {
                        // Masa banned berakhir, pulihkan status akun dan hapus data banned
                        $user->update(['status_akun' => 'AKTIF']);
                        $ban->delete();
                    } else {
                        // Banned masih aktif, keluarkan user
                        Auth::logout();
                        $request->session()->invalidate();
                        $request->session()->regenerateToken();

                        $alasan = $ban->alasan;
                        $waktuBanned = $ban->status === 'PERMANEN' 
                            ? 'Permanen' 
                            : 'sampai ' . Carbon::parse($ban->tgl_berakhir)->format('d M Y H:i');

                        throw ValidationException::withMessages([
                            'email' => "Akun Anda diblokir ($waktuBanned). Alasan: $alasan",
                        ]);
                    }
                } else {
                    // Default fallback jika data banned tidak ditemukan di tabel banneds
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    throw ValidationException::withMessages([
                        'email' => 'Akun Anda telah ditangguhkan (Banned).',
                    ]);
                }
            }

            $request->session()->regenerate();
            \App\Models\User::where('id_user', $user->id_user)
                ->update(['terakhir_login' => now()]);


            // arahkan sesuai role
            return match ($user->role) {
                'admin' => redirect()->route('admin.dashboard'),
                'penjual' => redirect()->route('penjual.dashboard'),
                'kurir' => redirect()->route('kurir.dashboard'),
                default => redirect()->route('portal'),
            };
        }

        throw ValidationException::withMessages([
            'email' => 'Email atau password salah.',
        ]);
    }

    // 🔹 Tampilkan halaman register
    public function showRegister()
    {
        return view('auth.register', ['title' => 'Daftar']);
    }

    // 🔹 Proses register user baru
    public function register(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|min:3|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'no_telepon' => 'required|string|max:25',
            // 'alamat' dihapus dari validasi karena opsional
        ]);

        // simpan ke database
        User::create([
            //'id_user' => 'USR' . strtoupper(Str::random(5)),
            'nama' => e($validated['nama']),
            'email' => $validated['email'],
           'password' => Hash::make($validated['password']), // wajib Hash::make
            'no_telepon' => $validated['no_telepon'],
            'alamat' => null, // bisa diisi nanti di profil
            'role' => 'pembeli', // default role
            'tanggal_daftar' => Carbon::now()->toDateString(),
            'status_akun' => 'AKTIF',
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    // 🔹 Halaman lupa password
    public function showForgotPassword()
    {
        return view('auth.forgot-password', ['title' => 'Lupa Password']);
    }

    // 🔹 Kirim link reset password
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['success' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    // 🔹 Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Anda telah logout.');
    }
}
