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
