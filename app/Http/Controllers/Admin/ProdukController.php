<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    // Tampilkan semua produk (monitoring)
    public function index()
    {
        $produks = Produk::with('penjual')->latest()->paginate(10);
        return view('admin.manajemen.produk', compact('produks'));
    }

    // Detail produk
    public function show($id)
    {
        $produk = Produk::with('penjual')->findOrFail($id);
        return view('admin.produk.show', compact('produk'));
    }

    // Edit (misalnya status / hide)
    public function edit($id)
    {
        $produk = Produk::findOrFail($id);
        return view('admin.produk.edit', compact('produk'));
    }

    // Update (TANPA upload ulang foto)
    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:tersedia,habis,hidden',
        ]);

        $produk->update($validated);

        return redirect()->route('admin.produk.index')
            ->with('success', 'Produk berhasil diperbarui');
    }

    // Hapus produk (opsional hard delete)
    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);

        foreach (['foto_produk1', 'foto_produk2', 'foto_produk3'] as $foto) {
            if ($produk->$foto && Storage::disk('public')->exists($produk->$foto)) {
                Storage::disk('public')->delete($produk->$foto);
            }
        }

        $produk->delete();

        return redirect()->route('admin.produk.index')
            ->with('success', 'Produk dihapus');
    }
}
