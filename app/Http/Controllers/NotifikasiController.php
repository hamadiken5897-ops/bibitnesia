<?php

namespace App\Http\Controllers;

use App\Models\NotifikasiUser;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function index(Request $request)
    {
        $notifikasi = NotifikasiUser::where('id_user', auth()->user()->id_user)
            ->orderBy('created_at', 'desc')
            ->get();

        // dari mana user datang (portal, marketplace, dashboard, dll)
        $backUrl = $request->get('from', url()->previous());

        return view('notifikasi.index', [
            'notifikasi' => $notifikasi,
            'backUrl' => $backUrl,
        ]);
    }

    public function readAll()
    {
        NotifikasiUser::where('id_user', auth()->user()->id_user)->update(['is_read' => 1]);

        return back()->with('success', 'Semua notifikasi ditandai sebagai dibaca.');
    }

    public function markAsRead()
    {
        $this->is_read = 1;
        $this->save();
    }

    public function delete($id)
    {
        NotifikasiUser::where('id_user', auth()->user()->id_user)
            ->where('id', $id)
            ->delete();

        return back()->with('success', 'Notifikasi berhasil dihapus.');
    }

    public function deleteAll()
    {
        NotifikasiUser::where('id_user', auth()->user()->id_user)->delete();

        return back()->with('success', 'Semua notifikasi berhasil dihapus.');
    }
}
