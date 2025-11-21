<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\User; 

class ProdukController extends Controller
{
    // Tampilkan semua produk
    public function index()
    {
        $produks = Produk::with('penjual')->latest()->paginate(10);
        return view('admin.manajemen.produk', compact('produks'));
    }

    // Form tambah produk
    public function create()
    {
        return view('admin.produk.create');
    }

    // Simpan produk baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'kategori' => 'required|in:Tanaman_hias,sayur,buah',
            'deskripsi' => 'required|string',
            'foto_produk' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'stok' => 'required|integer|min:0',
            'harga' => 'required|integer|min:0',
            'status' => 'required|in:tersedia,habis',
        ]);

        $data = $validated;
        $data['id_penjual'] = auth()->users()->id_user;

        // Upload foto jika ada
        if ($request->hasFile('foto_produk')) {
            $file = $request->file('foto_produk');
            $filename = 'produk_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('produks', $filename, 'public');
            $data['foto_produk'] = $path;
        }

        Produk::create($data);

        return redirect()->route('admin.produk.index')
            ->with('success', 'Produk berhasil ditambahkan!');
    }

    // Tampilkan detail produk
    public function show($id)
    {
        $produk = Produk::with('penjual')->findOrFail($id);
        return view('admin.produk.show', compact('produk'));
    }

    // Form edit produk
    public function edit($id)
    {
        $produk = Produk::findOrFail($id);
        return view('admin.produk.edit', compact('produk'));
    }

    // Update produk
    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'kategori' => 'required|in:Tanaman_hias,sayur,buah',
            'deskripsi' => 'required|string',
            'foto_produk' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'stok' => 'required|integer|min:0',
            'harga' => 'required|integer|min:0',
            'status' => 'required|in:tersedia,habis',
        ]);

        // Upload foto baru jika ada
        if ($request->hasFile('foto_produk')) {
            // Hapus foto lama
            if ($produk->foto_produk && Storage::disk('public')->exists($produk->foto_produk)) {
                Storage::disk('public')->delete($produk->foto_produk);
            }

            $file = $request->file('foto_produk');
            $filename = 'produk_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('produks', $filename, 'public');
            $validated['foto_produk'] = $path;
        }

        $produk->update($validated);

        return redirect()->route('admin.produk.index')
            ->with('success', 'Produk berhasil diperbarui!');
    }

    // Hapus produk
    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);

        // Hapus foto jika ada
        if ($produk->foto_produk && Storage::disk('public')->exists($produk->foto_produk)) {
            Storage::disk('public')->delete($produk->foto_produk);
        }

        $produk->delete();

        return redirect()->route('admin.produk.index')
            ->with('success', 'Produk berhasil dihapus!');
    }
}
