<?php

namespace App\Http\Controllers;

use App\Models\Favorit;
use Illuminate\Http\Request;

class FavoritController extends Controller
{
    public function index()
    {
        $favorit = Favorit::where('id_user', auth()->user()->id_user)
            ->with('produk')
            ->get();

        return view('marketplace.favorit', compact('favorit'));
    }

    public function add(Request $request)
    {
        $cek = Favorit::where('id_user', auth()->user()->id_user)
            ->where('produk_id', $request->produk_id)
            ->first();

        if (!$cek) {
            Favorit::create([
                'id_user' => auth()->user()->id_user,
                'produk_id' => $request->produk_id
            ]);
        }

        return back()->with('success', 'Berhasil ditambahkan ke favorit!');
    }

    public function delete($id)
    {
        Favorit::where('id', $id)
            ->where('id_user', auth()->user()->id_user)
            ->delete();

        return back()->with('success', 'Berhasil menghapus dari favorit!');
    }
    public function toggle(Request $request)
    {
        $cek = Favorit::where('id_user', auth()->user()->id_user)
            ->where('produk_id', $request->produk_id)
            ->first();

        if ($cek) {
            $cek->delete();
            return response()->json(['status' => 'removed', 'message' => 'Dihapus dari favorit']);
        } else {
            Favorit::create([
                'id_user' => auth()->user()->id_user,
                'produk_id' => $request->produk_id
            ]);
            return response()->json(['status' => 'added', 'message' => 'Ditambahkan ke favorit']);
        }
    }
}
