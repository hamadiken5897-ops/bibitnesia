<?php

namespace App\Http\Controllers;

use App\Models\Favorit;
use Illuminate\Http\Request;

class FavoritController extends Controller
{
    public function index()
    {
        $favorit = Favorit::where('id_user', auth()->id())
            ->with('produk')
            ->get();

        return view('marketplace.favorit', compact('favorit'));
    }

    public function add(Request $request)
    {
        $cek = Favorit::where('id_user', auth()->id())
            ->where('produk_id', $request->produk_id)
            ->first();

        if (!$cek) {
            Favorit::create([
                'user_id' => auth()->id(),
                'produk_id' => $request->produk_id
            ]);
        }

        return back()->with('success', 'Berhasil ditambahkan ke favorit!');
    }

    public function delete($id)
    {
        Favorit::where('id', $id)
            ->where('user_id', auth()->id())
            ->delete();

        return back()->with('success', 'Berhasil menghapus dari favorit!');
    }
}
