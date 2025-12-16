<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

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
            ]);

            $produk->update($validated);

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

    /**
     * Hapus produk
     */
    public function destroy(Produk $produk)
    {
        try {
            Log::info('Delete Produk', ['id' => $produk->id_produk]);

            // Hapus foto jika ada
            $fotos = ['foto_produk1', 'foto_produk2', 'foto_produk3'];
            foreach ($fotos as $foto) {
                if ($produk->$foto && Storage::disk('public')->exists($produk->$foto)) {
                    Storage::disk('public')->delete($produk->$foto);
                    Log::info("Deleted {$foto}");
                }
            }

            $produk->delete();
            
            Log::info('Produk Deleted Successfully');

            return redirect()->route('admin.produk.index')
                ->with('success', 'Produk berhasil dihapus!');
                
        } catch (\Exception $e) {
            Log::error('Delete Error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menghapus produk: ' . $e->getMessage());
        }
    }
}