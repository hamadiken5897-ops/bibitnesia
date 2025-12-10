<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Keranjang;

class KeranjangController extends Controller
{
    public function index()
    {
        // ambil data keranjang untuk user yg login, termasuk relasi produk
        $keranjang = Keranjang::where('user_id', auth()->id())
            ->with('produk') // relasi produk harus ada di model Keranjang
            ->get();

        // pastikan view yang dipanggil sesuai struktur folder
        return view('marketplace.keranjang', compact('keranjang'));
    }

    public function add(Request $request)
    {
        $item = Keranjang::where('user_id', auth()->id())
            ->where('produk_id', $request->produk_id)
            ->first();

        if ($item) {
            $item->qty += 1;
            $item->save();
        } else {
            Keranjang::create([
                'user_id' => auth()->id(),
                'produk_id' => $request->produk_id,
                'qty' => 1,
            ]);
        }

        return back()->with('success', 'Berhasil ditambahkan ke keranjang!');
    }

    public function update(Request $request, $id)
    {
        Keranjang::where('id', $id)->update(['qty' => $request->qty]);
        return back()->with('success', 'Keranjang diperbarui!');
    }

    public function delete($id)
    {
        Keranjang::where('id', $id)->delete();
        return back()->with('success', 'Produk dihapus!');
    }
}
