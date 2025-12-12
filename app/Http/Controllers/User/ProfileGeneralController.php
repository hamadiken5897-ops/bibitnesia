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
        $user = auth()->user()->load(['penjual', 'kurir', 'file']);
        $isOwner = true;

        return view('profile.show', compact('user', 'isOwner'));
    }

    /**
     * Tampilkan profil user lain berdasarkan ID
     */
    public function show($userId)
    {
        $user = User::with(['penjual', 'kurir', 'file'])->findOrFail($userId);
        $isOwner = auth()->check() && auth()->id() == $userId;

        return view('profile.show', compact('user', 'isOwner'));
    }

    /**
     * Update profil user
     */
    public function update(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'no_telepon' => 'nullable|string|max:25',
            'alamat' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string|max:500',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = auth()->user();

        // Update data user
        $user->update([
            'nama' => $request->nama,
            'no_telepon' => $request->no_telepon,
            'alamat' => $request->alamat,
            'deskripsi' => $request->deskripsi,
        ]);

        // Upload foto profil jika ada
        if ($request->hasFile('profile_image')) {
            // Hapus file lama jika ada
            if ($user->file) {
                Storage::delete($user->file->path);
                $user->file->delete();
            }

            $file = $request->file('profile_image');
            $extension = $file->getClientOriginalExtension();
            $filename = $user->id_user . '_' . time() . '.' . $extension;
            $folder = 'profiles/' . $user->id_user;
            $path = $file->storeAs($folder, $filename, 'public');

            $user->file()->create([
                'alias' => 'foto-profil',
                'filename' => $filename,
                'path' => 'public/' . $path,
                'mime_type' => $file->getClientMimeType(),
                'size' => $file->getSize(),
                'fileable_type' => 'App\Models\User',
                'fileable_id' => $user->id_user,
            ]);
        }

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }
}
