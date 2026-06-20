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
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\DB;

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
        $user = User::where('email', $credentials['email'])->first();
        
        if ($user && Hash::check($credentials['password'], $user->password)) {

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
                        $alasan = $ban->alasan;
                        $waktuBanned = $ban->status === 'PERMANEN' 
                            ? 'Permanen' 
                            : 'sampai ' . Carbon::parse($ban->tgl_berakhir)->format('d M Y H:i');

                        throw ValidationException::withMessages([
                            'email' => "Akun Anda diblokir ($waktuBanned). Alasan: $alasan",
                        ]);
                    }
                } else {
                    throw ValidationException::withMessages([
                        'email' => 'Akun Anda telah ditangguhkan (Banned).',
                    ]);
                }
            }

            // Generate OTP
            $otp = sprintf("%06d", mt_rand(1, 999999));
            $user->update([
                'otp_code' => $otp,
                'otp_expires_at' => Carbon::now()->addMinutes(10),
            ]);

            try {
                Mail::to($user->email)->send(new OtpMail($otp));
            } catch (\Exception $e) {
                // Log exception if needed
            }

            // Put session for OTP Modal
            session(['otp_email' => $user->email, 'require_otp' => true]);
            if (app()->environment('local')) {
                session(['dev_otp_code' => $otp]);
            }
            
            return redirect()->route('login')->with('success', 'Kode OTP telah dikirim ke email Anda. Silakan masukkan kode OTP untuk melanjutkan.');
        }

        throw ValidationException::withMessages([
            'email' => 'Email atau password salah.',
        ]);
    }

    // 🔹 Verifikasi OTP
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp_code' => 'required|string|size:6',
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['otp_code' => 'Pengguna tidak ditemukan.'])->with(['require_otp' => true, 'otp_email' => $request->email]);
        }

        if ($user->otp_code !== $request->otp_code) {
            return back()->withErrors(['otp_code' => 'Kode OTP salah.'])->with(['require_otp' => true, 'otp_email' => $request->email]);
        }

        if (Carbon::now()->isAfter($user->otp_expires_at)) {
            return back()->withErrors(['otp_code' => 'Kode OTP sudah kedaluwarsa. Silakan minta ulang.'])->with(['require_otp' => true, 'otp_email' => $request->email]);
        }

        // OTP Valid, login
        $user->update([
            'otp_code' => null,
            'otp_expires_at' => null,
            'terakhir_login' => now(),
        ]);

        Auth::login($user, session('remember_login', false));
        $request->session()->regenerate();
        session()->forget(['otp_email', 'require_otp', 'remember_login']);

        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'penjual' => redirect()->route('penjual.dashboard'),
            'kurir' => redirect()->route('kurir.dashboard'),
            default => redirect()->route('portal'),
        };
    }

    // 🔹 Kirim ulang OTP
    public function resendOtp(Request $request)
    {
        $email = $request->email ?? session('otp_email');
        $user = User::where('email', $email)->first();

        if ($user) {
            $otp = sprintf("%06d", mt_rand(1, 999999));
            $user->update([
                'otp_code' => $otp,
                'otp_expires_at' => Carbon::now()->addMinutes(10),
            ]);

            try {
                Mail::to($user->email)->send(new OtpMail($otp));
            } catch (\Exception $e) {}

            session(['otp_email' => $user->email, 'require_otp' => true]);
            if (app()->environment('local')) {
                session(['dev_otp_code' => $otp]);
            }
            return back()->with('success', 'Kode OTP baru telah dikirim.');
        }

        return back()->withErrors(['email' => 'Gagal mengirim ulang OTP.']);
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
        $user = User::create([
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

        $otp = sprintf("%06d", mt_rand(1, 999999));
        $user->update([
            'otp_code' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(10),
        ]);

        try {
            Mail::to($user->email)->send(new OtpMail($otp));
        } catch (\Exception $e) { }

        session(['otp_email' => $user->email, 'require_otp' => true]);
        if (app()->environment('local')) {
            session(['dev_otp_code' => $otp]);
        }

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Kode OTP telah dikirim ke email Anda.');
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

        $token = Str::random(60);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['token' => Hash::make($token), 'created_at' => Carbon::now()]
        );

        $resetUrl = route('password.reset', ['token' => $token, 'email' => $request->email]);

        // Simulasikan pengiriman email dengan log atau Mailable sungguhan
        try {
            // Bisa menggunakan Mail::send(...) tapi untuk sekarang kita flash sukses
        } catch (\Exception $e) {}

        // Idealnya kirim ke email, tapi untuk kemudahan pengujian kita tampilkan link di session alert
        return back()->with('success', 'Tautan reset password berhasil dikirim. (Dalam mode dev, ini linknya: <a href="'.$resetUrl.'">Reset Link</a>)');
    }

    public function showResetForm(Request $request, $token)
    {
        return view('auth.reset-password', ['token' => $token, 'email' => $request->email, 'title' => 'Reset Password']);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
            'token' => 'required'
        ]);

        $resetRecord = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if (!$resetRecord || !Hash::check($request->token, $resetRecord->token)) {
            return back()->withErrors(['email' => 'Token reset password tidak valid atau sudah kedaluwarsa.']);
        }

        $user = User::where('email', $request->email)->first();
        if ($user) {
            $user->update([
                'password' => Hash::make($request->password)
            ]);
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return redirect()->route('login')->with('success', 'Password berhasil diubah. Silakan login.');
        }

        return back()->withErrors(['email' => 'Pengguna tidak ditemukan.']);
    }

    // 🔹 Google Login
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            $user = User::where('google_id', $googleUser->getId())
                        ->orWhere('email', $googleUser->getEmail())
                        ->first();

            if ($user) {
                if (empty($user->google_id)) {
                    $user->update(['google_id' => $googleUser->getId()]);
                }
                
                $user->update(['terakhir_login' => now()]);
                Auth::login($user);
                $request->session()->regenerate();
            } else {
                // Create new user if not exists
                $user = \App\Models\User::create([
                    'nama' => $googleUser->name,
                    'email' => $googleUser->email,
                    'password' => \Illuminate\Support\Facades\Hash::make(\Illuminate\Support\Str::random(16)),
                    'google_id' => $googleUser->id,
                    'role' => 'pembeli',
                    'tanggal_daftar' => now()->toDateString(),
                    'status_akun' => 'AKTIF',
                    'terakhir_login' => now(),
                    'no_telepon' => '-',
                    'alamat' => '-'
                ]);
                
                Auth::login($user);
                $request->session()->regenerate();
            }

            return match ($user->role) {
                'admin' => redirect()->route('admin.dashboard'),
                'penjual' => redirect()->route('penjual.dashboard'),
                'kurir' => redirect()->route('kurir.dashboard'),
                default => redirect()->route('portal'),
            };

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Google Login Error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Gagal login menggunakan Google: ' . $e->getMessage());
        }
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
