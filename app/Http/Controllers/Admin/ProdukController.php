<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\NotifikasiUser;

class ProdukController extends Controller
{
    /**
     * Tampilkan semua produk dengan filter
     */
    public function index(Request $request)
    {
        // Query dasar dengan eager loading
        $query = Produk::with(['penjual.user', 'penjual.provinsi']);

        // Filter search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_produk', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter kategori
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Ambil data dengan pagination
        $produk = $query->orderBy('created_at', 'desc')->paginate(10);

        // Debug (hapus setelah berhasil)
        Log::info('Produk Query', [
            'total' => $produk->total(),
            'count' => $produk->count(),
            'filters' => $request->all()
        ]);

        return view('admin.manajemen.produk.index', compact('produk'));
    }

    /**
     * Detail produk - Route Model Binding otomatis
     */
    public function show(Produk $produk)
    {
        // Load relasi
        $produk->load(['penjual.user', 'penjual.provinsi']);
        
        Log::info('Show Produk', ['id' => $produk->id_produk]);
        
        return view('admin.manajemen.Produk.show', compact('produk'));
    }

    /**
     * Form edit produk
     */
    public function edit(Produk $produk)
    {
        return view('admin.produk.edit', compact('produk'));
    }

    /**
     * Update produk (untuk ubah status)
     */
    public function update(Request $request, Produk $produk)
    {
        try {
            $validated = $request->validate([
                'status' => 'required|in:tersedia,tidak_tersedia,habis,hidden',
                'alasan_admin' => 'nullable|string'
            ]);

            $produk->update([
                'status' => $validated['status'],
                'alasan_admin' => $validated['alasan_admin'] ?? null,
            ]);

            // Jika di-hidden, kirim notifikasi
            if ($validated['status'] === 'hidden') {
                NotifikasiUser::create([
                    'id_user' => $produk->penjual->id_user,
                    'judul' => 'Produk Disembunyikan',
                    'pesan' => "Produk Anda '{$produk->nama_produk}' telah disembunyikan oleh Admin. Alasan: " . ($validated['alasan_admin'] ?? 'Melanggar ketentuan'),
                    'redirect_url' => route('penjual.produk.edit', $produk->id_produk)
                ]);
            }

            Log::info('Produk Status Updated', [
                'id' => $produk->id_produk,
                'new_status' => $validated['status']
            ]);

            return redirect()->back()
                ->with('success', 'Status produk berhasil diperbarui!');
                
        } catch (\Exception $e) {
            Log::error('Update Status Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal memperbarui status: ' . $e->getMessage());
        }
    }

    public function destroy(Request $request, Produk $produk)
    {
        try {
            $validated = $request->validate([
                'alasan_admin' => 'required|string'
            ]);

            Log::info('Hapus (Sembunyikan) Produk', ['id' => $produk->id_produk]);

            $produk->update([
                'status' => 'dihapus_admin',
                'alasan_admin' => $validated['alasan_admin'],
                'tgl_dihapus_admin' => now(),
            ]);

            NotifikasiUser::create([
                'id_user' => $produk->penjual->id_user,
                'judul' => 'Produk Dihapus Admin',
                'pesan' => "Produk Anda '{$produk->nama_produk}' telah dihapus/diblokir oleh Admin. Alasan: {$validated['alasan_admin']}. Produk akan dihapus secara permanen dari sistem dalam 7 hari.",
                'redirect_url' => route('penjual.produk.edit', $produk->id_produk)
            ]);
            
            Log::info('Produk Status Changed to dihapus_admin Successfully');

            return redirect()->route('admin.produk.index')
                ->with('success', 'Produk berhasil disembunyikan/dihapus oleh admin!');
                
        } catch (\Exception $e) {
            Log::error('Delete Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menyembunyikan/menghapus produk: ' . $e->getMessage());
        }
    }
}