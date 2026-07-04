<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Favorit;
use App\Models\Pesanan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    // Dapatkan profil pengguna saat ini
    public function profile(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => $request->user()
        ]);
    }

    // Update profil
    public function updateProfile(Request $request)
    {
        $user = $request->user();
        
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'no_telepon' => 'nullable|string|max:20',
            'deskripsi' => 'nullable|string|max:500',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        $dataToUpdate = [
            'nama' => $request->nama,
            'no_telepon' => $request->no_telepon,
            'deskripsi' => $request->deskripsi,
        ];

        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($user->profile_image && \Storage::disk('public')->exists($user->profile_image)) {
                \Storage::disk('public')->delete($user->profile_image);
            }
            
            $path = $request->file('profile_image')->store('profiles', 'public');
            $dataToUpdate['profile_image'] = $path;
        }

        $user->update($dataToUpdate);

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui.',
            'data' => $user
        ]);
    }

    // Update password
    public function updatePassword(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json(['success' => false, 'message' => 'Password lama salah.'], 400);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diubah.'
        ]);
    }

    // Mendapatkan daftar favorit
    public function getFavorites(Request $request)
    {
        $user = $request->user();
        $favorites = Favorit::with(['produk.penjual', 'produk.ulasans.user'])->where('id_user', $user->id_user)->get();
        
        return response()->json([
            'success' => true,
            'data' => $favorites->pluck('produk')
        ]);
    }

    // Menambah/menghapus favorit
    public function toggleFavorite(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produks,id_produk'
        ]);

        $user = $request->user();
        $favorit = Favorit::where('id_user', $user->id_user)->where('produk_id', $request->produk_id)->first();

        if ($favorit) {
            $favorit->delete();
            return response()->json(['success' => true, 'message' => 'Dihapus dari favorit.', 'is_favorite' => false]);
        } else {
            Favorit::create([
                'id_user' => $user->id_user,
                'produk_id' => $request->produk_id
            ]);
            return response()->json(['success' => true, 'message' => 'Ditambahkan ke favorit.', 'is_favorite' => true]);
        }
    }

    // Mendapatkan riwayat pesanan
    public function getRiwayatPesanan(Request $request)
    {
        $user = $request->user();
        $pesanans = Pesanan::with(['detailPesanan.produk', 'pengiriman', 'pembayaran', 'riwayat'])
            ->where('id_user', $user->id_user)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $pesanans
        ]);
    }

    // Konfirmasi pesanan selesai
    public function selesaiPesanan(Request $request, $id)
    {
        $user = $request->user();
        $pesanan = Pesanan::where('id_user', $user->id_user)->where('id_pesanan', $id)->first();

        if (!$pesanan) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak ditemukan'
            ], 404);
        }

        if ($pesanan->status_pesanan !== 'Pesanan selesai' && $pesanan->status_pesanan !== 'Pesanan Selesai') {
            \DB::transaction(function () use ($pesanan) {
                $pesanan->update(['status_pesanan' => 'Pesanan Selesai']);

                \App\Models\RiwayatPesanan::create([
                    'id_pesanan' => $pesanan->id_pesanan,
                    'status' => 'Pesanan Selesai',
                    'deskripsi' => 'Pesanan telah dikonfirmasi selesai oleh pembeli.'
                ]);
            });
        }

        return response()->json([
            'success' => true,
            'message' => 'Pesanan berhasil diselesaikan'
        ]);
    }

    // Mendapatkan daftar provinsi
    public function getProvinsi(Request $request)
    {
        $provinsis = \App\Models\Provinsi::all();
        return response()->json([
            'success' => true,
            'data' => $provinsis
        ]);
    }

    // Mendapatkan daftar alamat
    public function getAlamat(Request $request)
    {
        $user = $request->user();
        $alamat = \App\Models\Alamat::with('provinsi')->where('id_user', $user->id_user)->get();
        return response()->json([
            'success' => true,
            'data' => $alamat
        ]);
    }

    // Menyimpan alamat baru
    public function saveAlamat(Request $request)
    {
        $user = $request->user();
        
        $validator = Validator::make($request->all(), [
            'nama_penerima' => 'required|string',
            'no_telepon' => 'required|string',
            'id_provinsi' => 'required|integer',
            'kota' => 'required|string',
            'kecamatan' => 'required|string',
            'kode_pos' => 'required|string',
            'detail_alamat' => 'required|string',
            'is_utama' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        if ($request->is_utama) {
            \App\Models\Alamat::where('id_user', $user->id_user)->update(['is_utama' => false]);
        }

        $alamat = \App\Models\Alamat::create([
            'id_user' => $user->id_user,
            'nama_penerima' => $request->nama_penerima,
            'no_telepon' => $request->no_telepon,
            'id_provinsi' => $request->id_provinsi,
            'kota' => $request->kota,
            'kecamatan' => $request->kecamatan,
            'kode_pos' => $request->kode_pos,
            'detail_alamat' => $request->detail_alamat,
            'is_utama' => $request->is_utama ?? false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Alamat berhasil ditambahkan',
            'data' => $alamat
        ]);
    }

    // Mendapatkan daftar notifikasi
    public function getNotifikasi(Request $request)
    {
        $user = $request->user();
        $notifikasi = \App\Models\NotifikasiUser::where('id_user', $user->id_user)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return response()->json([
            'success' => true,
            'data' => $notifikasi
        ]);
    }

    // Menandai notifikasi telah dibaca
    public function readNotifikasi(Request $request)
    {
        $user = $request->user();
        $id_notif = $request->id_notif;
        
        if ($id_notif) {
            \App\Models\NotifikasiUser::where('id_notif', $id_notif)
                ->where('id_user', $user->id_user)
                ->update(['is_read' => true]);
        } else {
            \App\Models\NotifikasiUser::where('id_user', $user->id_user)
                ->update(['is_read' => true]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi ditandai telah dibaca'
        ]);
    }
}
