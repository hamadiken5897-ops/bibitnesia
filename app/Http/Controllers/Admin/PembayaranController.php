<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function index()
    {
        $data = Pembayaran::orderBy('created_at', 'desc')->get();
        return view('admin.manajemen.pembayaran.index', compact('data'));
    }

    public function create()
    {
        return view('admin.manajemen.pembayaran.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_pembayaran' => 'required|unique:pembayarans',
            'id_pesanan' => 'required',
            'metode_pembayaran' => 'required',
            'total_bayar' => 'required|numeric',
            'tanggal_pembayaran' => 'required|date',
        ]);

        Pembayaran::create($request->all());

        return redirect()->route('admin.pembayaran.index')
            ->with('success', 'Pembayaran berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $data = Pembayaran::findOrFail($id);
        return view('admin.manajemen.pembayaran.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $data = Pembayaran::findOrFail($id);

        $request->validate([
            'id_pesanan' => 'required',
            'metode_pembayaran' => 'required',
            'total_bayar' => 'required|numeric',
            'tanggal_pembayaran' => 'required|date',
        ]);

        $data->update($request->all());

        return redirect()->route('admin.pembayaran.index')
            ->with('success', 'Data pembayaran berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Pembayaran::destroy($id);

        return redirect()->route('admin.pembayaran.index')
            ->with('success', 'Pembayaran berhasil dihapus!');
    }
}
