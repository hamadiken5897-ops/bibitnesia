<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Keranjang;

class KeranjangController extends Controller
{
    public function index()
    {
        // Ambil semua item keranjang berdasarkan user login
        $keranjang = Keranjang::where('user_id', auth()->id())
            ->with('produk')
            ->get();

        return view('marketplace.keranjang.index', [
            'keranjang' => $keranjang
        ]);
    }

    public function add(Request $request)
    {
        // Cek apakah produk sudah ada dalam keranjang user
        $item = Keranjang::where('user_id', auth()->id())
            ->where('produk_id', $request->produk_id)
            ->first();

        if ($item) {
            // Jika sudah ada, tambahkan qty
            $item->qty += 1;
            $item->save();
        } else {
            // Jika belum ada, buat baru
            Keranjang::create([
                'user_id'   => auth()->id(),
                'produk_id' => $request->produk_id,
                'qty'       => 1,
            ]);
        }

        return back()->with('success', 'Berhasil ditambahkan ke keranjang!');
    }

    public function update(Request $request, $id)
    {
        Keranjang::where('id', $id)->update([
            'qty' => $request->qty
        ]);

        return back()->with('success', 'Keranjang diperbarui!');
    }

    public function delete($id)
    {
        Keranjang::where('id', $id)->delete();

        return back()->with('success', 'Produk dihapus dari keranjang!');
    }
}
