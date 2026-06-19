<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProdukController extends Controller
{
    private function getPenjual()
    {
        $penjual = auth()->user()->penjual;

        if (!$penjual) {
            abort(403, 'Akun ini bukan penjual');
        }

        return $penjual;
    }

    public function index()
    {
        $penjual = $this->getPenjual();

        $produks = Produk::where('id_penjual', $penjual->id_penjual)->get();
        return view('penjual.produk.index', compact('produks'));
    }

    public function create()
    {
        $this->getPenjual();
        return view('penjual.produk.create');
    }

    public function store(Request $request)
    {
        $penjual = $this->getPenjual();

        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'kategori' => 'required|in:Tanaman_hias,sayur,buah',
            'deskripsi' => 'required|string',
            'stok' => 'required|integer|min:0',
            'harga' => 'required|integer|min:0',
            'foto_produk1' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'foto_produk2' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'foto_produk3' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $validated['id_produk'] = 'PRD-' . Str::random(10);
        $validated['id_penjual'] = $penjual->id_penjual;
        $validated['status'] = $validated['stok'] > 0 ? 'tersedia' : 'habis';

        foreach (['foto_produk1', 'foto_produk2', 'foto_produk3'] as $foto) {
            if ($request->hasFile($foto)) {
                $validated[$foto] = $request->file($foto)->store('produks', 'public');
            }
        }

        Produk::create($validated);

        return redirect()->route('penjual.produk')->with('success', 'Produk berhasil ditambahkan');
    }

    public function edit(Produk $produk)
    {
        $penjual = $this->getPenjual();

        if ($produk->id_penjual !== $penjual->id_penjual) {
            abort(403);
        }

        return view('penjual.produk.edit', compact('produk'));
    }

    public function update(Request $request, Produk $produk)
    {
        $penjual = $this->getPenjual();

        if ($produk->id_penjual !== $penjual->id_penjual) {
            abort(403);
        }

        $validated = $request->validate([
            'nama_produk' => 'required|string|max:255',
            'kategori' => 'required',
            'deskripsi' => 'required',
            'harga' => 'required|integer|min:0',
            'stok' => 'required|integer|min:0',

            // ⬇️ FOTO UTAMA TIDAK WAJIB DI EDIT
            'foto_produk1' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'foto_produk2' => 'nullable|image|max:2048',
            'foto_produk3' => 'nullable|image|max:2048',
        ]);

        // 🔥 HAPUS FOTO DULU
        if ($request->remove_foto2 == 1 && $produk->foto_produk2) {
            Storage::disk('public')->delete($produk->foto_produk2);
            $validated['foto_produk2'] = null;
        }

        if ($request->remove_foto3 == 1 && $produk->foto_produk3) {
            Storage::disk('public')->delete($produk->foto_produk3);
            $validated['foto_produk3'] = null;
        }

        // 🔥 HANDLE UPLOAD
        foreach (['foto_produk1', 'foto_produk2', 'foto_produk3'] as $foto) {
            if ($request->hasFile($foto)) {
                if ($produk->$foto) {
                    Storage::disk('public')->delete($produk->$foto);
                }
                $validated[$foto] = $request->file($foto)->store('produks', 'public');
            }
        }

        $validated['status'] = $validated['stok'] > 0 ? 'tersedia' : 'habis';

        $produk->update($validated);

        return redirect()->route('penjual.produk')->with('success', 'Produk berhasil diperbarui');
    }

    public function destroy(Produk $produk)
    {
        $penjual = $this->getPenjual();

        if ($produk->id_penjual !== $penjual->id_penjual) {
            abort(403);
        }

        foreach (['foto_produk1', 'foto_produk2', 'foto_produk3'] as $foto) {
            if ($produk->$foto && Storage::disk('public')->exists($produk->$foto)) {
                Storage::disk('public')->delete($produk->$foto);
            }
        }

        $produk->delete();

        return redirect()->route('penjual.produk')->with('success', 'Produk berhasil dihapus');
    }
}
