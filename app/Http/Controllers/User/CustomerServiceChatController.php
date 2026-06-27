<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PesanCS;
use Illuminate\Http\Request;

class CustomerServiceChatController extends Controller
{
    /**
     * Tampilkan halaman chat CS untuk user
     */
    public function index()
    {
        return view('account.customer_service');
    }

    /**
     * Dapatkan riwayat pesan untuk user ini
     */
    public function getChatHistory()
    {
        $id_user = auth()->user()->id_user;

        // Tandai terbaca oleh user
        PesanCS::where('id_user', $id_user)
               ->where('sender_role', 'admin')
               ->update(['is_read_user' => true]);

        $chats = PesanCS::where('id_user', $id_user)->orderBy('created_at', 'asc')->get();

        $formatted = $chats->map(function ($chat) {
            return [
                'id_pesan_cs' => $chat->id_pesan_cs,
                'pesan' => $chat->pesan,
                'sender_role' => $chat->sender_role,
                'time' => $chat->created_at->format('H:i')
            ];
        })->values();

        return response()->json($formatted);
    }

    /**
     * User kirim pesan ke CS
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'pesan' => 'required|string'
        ]);

        $id_user = auth()->user()->id_user;

        $pesan = PesanCS::create([
            'id_user' => $id_user,
            'id_admin' => null, // null until replied by an admin (or just pooled)
            'pesan' => $request->pesan,
            'sender_role' => 'user',
            'is_read_admin' => false,
            'is_read_user' => true
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'id_pesan_cs' => $pesan->id_pesan_cs,
                'pesan' => $pesan->pesan,
                'sender_role' => $pesan->sender_role,
                'time' => $pesan->created_at->format('H:i')
            ]
        ]);
    }
}
