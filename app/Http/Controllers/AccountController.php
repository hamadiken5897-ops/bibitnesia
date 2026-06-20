<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alamat;
use App\Models\Provinsi;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    /**
     * Show Profile Settings Page
     */
    public function profile()
    {
        $user = Auth::user();
        return view('account.profile', compact('user'));
    }

    /**
     * Update Password - Step 1: Send OTP
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'password' => 'required|string|min:8|confirmed',
        ];

        // Jika user tidak menggunakan google_id, maka current_password wajib diisi
        if (empty($user->google_id)) {
            $rules['current_password'] = 'required';
        }

        $request->validate($rules);

        // Jika user mengisi current_password (walau opsional untuk akun google), kita tetap cek validitasnya
        if ($request->filled('current_password')) {
            if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password lama tidak sesuai.']);
            }
        }

        // Generate OTP
        $otp = sprintf("%06d", mt_rand(1, 999999));
        \App\Models\User::where('id_user', $user->id_user)->update([
            'otp_code' => $otp,
            'otp_expires_at' => \Carbon\Carbon::now()->addMinutes(10),
        ]);

        try {
            \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\OtpMail($otp));
        } catch (\Exception $e) {}

        session(['new_password' => $request->password, 'require_password_otp' => true]);
        if (app()->environment('local')) {
            session(['dev_otp_code' => $otp]);
        }

        return back()->with('success', 'OTP telah dikirim ke email Anda. Silakan verifikasi untuk mengubah password.');
    }

    /**
     * Update Password - Step 2: Verify OTP and save
     */
    public function verifyPasswordOtp(Request $request)
    {
        $request->validate([
            'otp_code' => 'required|string|size:6',
        ]);

        $user = Auth::user();

        if ($user->otp_code !== $request->otp_code) {
            session(['require_password_otp' => true]);
            return back()->withErrors(['otp_code' => 'Kode OTP salah.']);
        }

        if (\Carbon\Carbon::now()->isAfter($user->otp_expires_at)) {
            session(['require_password_otp' => true]);
            return back()->withErrors(['otp_code' => 'Kode OTP sudah kedaluwarsa.']);
        }

        // Update password
        \App\Models\User::where('id_user', $user->id_user)->update([
            'password' => \Illuminate\Support\Facades\Hash::make(session('new_password')),
            'otp_code' => null,
            'otp_expires_at' => null,
        ]);

        session()->forget(['new_password', 'require_password_otp', 'dev_otp_code']);

        return back()->with('success', 'Password berhasil diperbarui!');
    }

    /**
     * Show Addresses Settings Page
     */
    public function alamat()
    {
        $user = Auth::user();
        $alamats = Alamat::where('id_user', $user->id_user)->get();
        $provinsis = Provinsi::all();
        
        return view('account.alamat', compact('user', 'alamats', 'provinsis'));
    }

    /**
     * Store new Address
     */
    public function storeAlamat(Request $request)
    {
        $request->validate([
            'nama_penerima' => 'required|string|max:255',
            'no_telepon'    => 'required|string|max:20',
            'id_provinsi'   => 'required|exists:provinsis,id_provinsi',
            'kota'          => 'required|string|max:255',
            'kecamatan'     => 'nullable|string|max:255',
            'kode_pos'      => 'nullable|string|max:20',
            'detail_alamat' => 'required|string',
        ]);

        $user = Auth::user();
        $isUtama = $request->has('is_utama') ? true : false;

        // If it's the first address, or if is_utama is true, make sure others are false
        if ($isUtama || $user->alamats()->count() == 0) {
            $isUtama = true;
            $user->alamats()->update(['is_utama' => false]);
        }

        Alamat::create([
            'id_user'       => $user->id_user,
            'nama_penerima' => $request->nama_penerima,
            'no_telepon'    => $request->no_telepon,
            'id_provinsi'   => $request->id_provinsi,
            'kota'          => $request->kota,
            'kecamatan'     => $request->kecamatan,
            'kode_pos'      => $request->kode_pos,
            'detail_alamat' => $request->detail_alamat,
            'is_utama'      => $isUtama,
        ]);

        return back()->with('success', 'Alamat berhasil ditambahkan!');
    }

    /**
     * Update Address
     */
    public function updateAlamat(Request $request, $id)
    {
        $request->validate([
            'nama_penerima' => 'required|string|max:255',
            'no_telepon'    => 'required|string|max:20',
            'id_provinsi'   => 'required|exists:provinsis,id_provinsi',
            'kota'          => 'required|string|max:255',
            'kecamatan'     => 'nullable|string|max:255',
            'kode_pos'      => 'nullable|string|max:20',
            'detail_alamat' => 'required|string',
        ]);

        $alamat = Alamat::findOrFail($id);
        
        // Ensure user owns this address
        if ($alamat->id_user !== Auth::id()) {
            abort(403);
        }

        $isUtama = $request->has('is_utama') ? true : false;

        if ($isUtama && !$alamat->is_utama) {
            Auth::user()->alamats()->update(['is_utama' => false]);
        }

        // If trying to un-set primary, but it's the only one, keep it primary
        if (!$isUtama && $alamat->is_utama && Auth::user()->alamats()->count() == 1) {
            $isUtama = true;
        }

        $alamat->update([
            'nama_penerima' => $request->nama_penerima,
            'no_telepon'    => $request->no_telepon,
            'id_provinsi'   => $request->id_provinsi,
            'kota'          => $request->kota,
            'kecamatan'     => $request->kecamatan,
            'kode_pos'      => $request->kode_pos,
            'detail_alamat' => $request->detail_alamat,
            'is_utama'      => $isUtama,
        ]);

        return back()->with('success', 'Alamat berhasil diperbarui!');
    }

    /**
     * Set Primary Address
     */
    public function setUtamaAlamat($id)
    {
        $alamat = Alamat::findOrFail($id);

        if ($alamat->id_user !== Auth::id()) {
            abort(403);
        }

        Auth::user()->alamats()->update(['is_utama' => false]);
        $alamat->update(['is_utama' => true]);

        return back()->with('success', 'Alamat utama berhasil diubah!');
    }

    /**
     * Delete Address
     */
    public function deleteAlamat($id)
    {
        $alamat = Alamat::findOrFail($id);

        if ($alamat->id_user !== Auth::id()) {
            abort(403);
        }

        $wasUtama = $alamat->is_utama;
        $alamat->delete();

        // If we deleted the primary address, set the newest one as primary
        if ($wasUtama) {
            $newUtama = Auth::user()->alamats()->latest()->first();
            if ($newUtama) {
                $newUtama->update(['is_utama' => true]);
            }
        }

        return back()->with('success', 'Alamat berhasil dihapus!');
    }
}
