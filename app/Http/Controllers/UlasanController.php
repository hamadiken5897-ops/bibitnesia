<?php

namespace App\Http\Controllers;

use App\Models\Ulasan;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class UlasanController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'nullable|string'
        ]);

        $id_user = auth()->user()->id_user;

        // Cek apakah user pernah membeli produk ini
        $hasPurchased = Pesanan::where('id_user', $id_user)
            ->whereHas('detailPesanan', function ($q) use ($request) {
                $q->where('id_produk', $request->produk_id);
            })->exists();

        if (!$hasPurchased) {
            return back()->with('error', 'Anda harus membeli produk ini terlebih dahulu untuk memberikan ulasan.');
        }

        // Cek apakah user sudah pernah mengulas produk ini (opsional, kita batasi 1 ulasan per user per produk)
        $existing = Ulasan::where('id_user', $id_user)
            ->where('id_produk', $request->produk_id)
            ->first();

        if ($existing) {
            // Update ulasan
            $existing->update([
                'rating' => $request->rating,
                'komentar' => $request->komentar
            ]);
            return back()->with('success', 'Ulasan Anda berhasil diperbarui!');
        }

        // Buat ulasan baru
        Ulasan::create([
            'id_user' => $id_user,
            'id_produk' => $request->produk_id,
            'rating' => $request->rating,
            'komentar' => $request->komentar
        ]);

        return back()->with('success', 'Terima kasih! Ulasan Anda berhasil ditambahkan.');
    }
}
