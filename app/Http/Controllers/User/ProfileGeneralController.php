<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileGeneralController extends Controller
{
    /**
     * Tampilkan profil sendiri
     */
    public function showOwn()
    {
        $user = auth()
            ->user()
            ->load(['penjual.provinsi', 'kurir.provinsi']);
        $isOwner = true;

        return view('profile.show', compact('user', 'isOwner'));
    }

    /**
     * Tampilkan profil user lain
     */
    public function show($userId)
    {
        $user = User::with(['penjual.provinsi', 'kurir.provinsi'])->findOrFail($userId);
        $isOwner = auth()->check() && auth()->id() === $user->id_user;

        return view('profile.show', compact('user', 'isOwner'));
    }

    /**
     * Update profil user
     */
    public function update(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',

            'profile_image' => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $user = auth()->user();

        $user->update([
            'nama' => $request->nama,
            'no_telepon' => $request->no_telepon,
            'deskripsi' => $request->deskripsi,
        ]);

        if ($request->hasFile('profile_image')) {
            $userCode = $user->id_user;

            // Pastikan folder user ada
            Storage::disk('public')->makeDirectory("profiles/{$userCode}");

            // Hapus avatar lama
            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }

            // Simpan avatar
            $path = $request->file('profile_image')->storeAs("profiles/{$userCode}", 'avatar.jpg', 'public');

            // Simpan path ke DB
            $user->update([
                'profile_image' => $path,
            ]);
        }

        return back()->with('success', 'Profil berhasil diperbarui');
    }
}
