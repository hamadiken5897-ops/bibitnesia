<?php

namespace App\Http\Controllers;

use App\Models\Pesan;
use App\Models\User;
use App\Models\Produk;
use Illuminate\Http\Request;

class PesanController extends Controller
{
    /**
     * Menampilkan daftar kontak/chat (Inbox)
     */
    public function index()
    {
        $userId = auth()->user()->id_user;

        // Ambil semua pesan di mana user adalah pengirim atau penerima
        // dan group by lawan bicara
        $pesans = Pesan::with(['pengirim', 'penerima', 'produk'])
            ->where('id_pengirim', $userId)
            ->orWhere('id_penerima', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        $kontaks = [];
        $unreadCount = 0;

        foreach ($pesans as $pesan) {
            $lawanBicaraId = $pesan->id_pengirim == $userId ? $pesan->id_penerima : $pesan->id_pengirim;

            if (!isset($kontaks[$lawanBicaraId])) {
                $kontaks[$lawanBicaraId] = [
                    'user' => $pesan->id_pengirim == $userId ? $pesan->penerima : $pesan->pengirim,
                    'pesan_terakhir' => $pesan,
                    'unread' => 0
                ];
            }

            // Hitung unread (hanya jika kita yang menerima pesan)
            if ($pesan->id_penerima == $userId && !$pesan->is_read) {
                $kontaks[$lawanBicaraId]['unread']++;
                $unreadCount++;
            }
        }

        // Tampilan khusus jika penjual atau pembeli (kita gunakan view yang sama, layout mungkin beda)
        $isPenjual = auth()->user()->role === 'penjual';
        $layout = $isPenjual ? 'layouts.penjual.penjual' : 'layouts.marketplace.main';

        return view('pesan.index', compact('kontaks', 'layout'));
    }

    /**
     * API untuk mendapatkan jumlah pesan belum dibaca (Notif Bubble)
     */
    public function getUnreadCount()
    {
        $count = Pesan::where('id_penerima', auth()->user()->id_user)
            ->where('is_read', false)
            ->count();
            
        return response()->json(['unread' => $count]);
    }

    /**
     * Menampilkan detail percakapan dengan satu user
     */
    public function show($lawanBicaraId)
    {
        $userId = auth()->user()->id_user;
        $lawanBicara = User::findOrFail($lawanBicaraId);

        // Tandai sudah dibaca
        Pesan::where('id_pengirim', $lawanBicaraId)
            ->where('id_penerima', $userId)
            ->update(['is_read' => true]);

        $pesans = Pesan::with(['produk'])
            ->where(function ($q) use ($userId, $lawanBicaraId) {
                $q->where('id_pengirim', $userId)->where('id_penerima', $lawanBicaraId);
            })
            ->orWhere(function ($q) use ($userId, $lawanBicaraId) {
                $q->where('id_pengirim', $lawanBicaraId)->where('id_penerima', $userId);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        $isPenjual = auth()->user()->role === 'penjual';
        $layout = $isPenjual ? 'layouts.penjual.penjual' : 'layouts.marketplace.main';

        return view('pesan.show', compact('pesans', 'lawanBicara', 'layout'));
    }

    /**
     * Memulai chat baru dari halaman produk (Tanya Penjual)
     */
    public function tanyaPenjual($id_produk)
    {
        $produk = Produk::with('penjual.user')->where('id_produk', $id_produk)->firstOrFail();
        
        if ($produk->penjual->id_user == auth()->user()->id_user) {
            return back()->with('error', 'Anda tidak dapat mengirim pesan ke diri sendiri.');
        }

        // Redirect ke detail chat dengan penjual, sambil membawa produk_id sebagai konteks
        return redirect()->route('pesan.show', [
            'id' => $produk->penjual->id_user,
            'produk_id' => $id_produk
        ]);
    }

    /**
     * Mengirim pesan
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_penerima' => 'required|string',
            'isi_pesan' => 'required|string',
            'id_produk' => 'nullable|string'
        ]);

        Pesan::create([
            'id_pengirim' => auth()->user()->id_user,
            'id_penerima' => $request->id_penerima,
            'id_produk' => $request->id_produk,
            'isi_pesan' => $request->isi_pesan,
            'is_read' => false
        ]);

        if ($request->ajax()) {
            return response()->json(['status' => 'success']);
        }

        return back();
    }
}
