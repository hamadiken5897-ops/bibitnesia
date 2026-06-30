<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Banned;
use Carbon\Carbon;

class AuthController extends Controller
{
    /**
     * Proses Login API
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah.'
            ], 401);
        }

        // Cek apakah akun di-banned
        if ($user->status_akun === 'BANNED') {
            $ban = Banned::where('id_user', $user->id_user)
                ->orderBy('tgl_banned', 'desc')
                ->first();

            if ($ban) {
                if ($ban->status === 'SEMENTARA' && $ban->tgl_berakhir && Carbon::parse($ban->tgl_berakhir)->isPast()) {
                    // Masa banned berakhir, pulihkan status akun
                    $user->update(['status_akun' => 'AKTIF']);
                    $ban->delete();
                } else {
                    $alasan = $ban->alasan;
                    return response()->json([
                        'success' => false,
                        'message' => "Akun Anda diblokir. Alasan: $alasan"
                    ], 403);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Akun Anda telah ditangguhkan (Banned).'
                ], 403);
            }
        }

        // Update terakhir login
        $user->update([
            'terakhir_login' => now(),
        ]);

        // Hapus token lama agar tidak menumpuk (opsional, tergantung kebijakan)
        $user->tokens()->delete();

        // Buat token baru
        $token = $user->createToken('mobile-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil.',
            'data' => [
                'token' => $token,
                'user' => [
                    'id_user' => $user->id_user,
                    'nama' => $user->nama ?? $user->nama_lengkap,
                    'email' => $user->email,
                    'role' => $user->role,
                ]
            ]
        ], 200);
    }

    /**
     * Proses Logout API
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil logout.'
        ], 200);
    }
}
