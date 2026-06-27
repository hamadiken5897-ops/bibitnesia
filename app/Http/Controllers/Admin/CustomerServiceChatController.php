<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PesanCS;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerServiceChatController extends Controller
{
    /**
     * Dapatkan daftar pengguna yang memiliki obrolan (Inbox list)
     */
    public function getInbox()
    {
        // Ambil pesan terakhir dari tiap user
        // Karena MySQL strict group by agak sulit dengan eloquent, kita bisa ambil semua pesan dan group by di collection
        // atau cari distinct user dari pesan_cs
        $usersWithChats = PesanCS::select('id_user')
            ->distinct()
            ->with(['user'])
            ->get()
            ->pluck('user');

        // Untuk setiap user, ambil pesan terakhir dan hitung unread admin
        $inbox = $usersWithChats->map(function ($user) {
            if (!$user) return null;
            $lastMessage = PesanCS::where('id_user', $user->id_user)->latest()->first();
            $unreadCount = PesanCS::where('id_user', $user->id_user)
                                  ->where('sender_role', 'user')
                                  ->where('is_read_admin', false)
                                  ->count();

            return [
                'id_user' => $user->id_user,
                'nama' => $user->nama,
                'profile_image' => $user->profile_image ? asset('storage/' . $user->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($user->nama) . '&background=27ae60&color=fff',
                'last_message' => $lastMessage ? $lastMessage->pesan : '',
                'last_time' => $lastMessage ? $lastMessage->created_at->diffForHumans() : '',
                'timestamp' => $lastMessage ? $lastMessage->created_at->timestamp : 0,
                'unread_count' => $unreadCount
            ];
        })->filter()->sortByDesc('timestamp')->values();

        return response()->json($inbox);
    }

    /**
     * Dapatkan riwayat chat dengan spesifik user
     */
    public function getChatHistory($id_user)
    {
        // Tandai terbaca oleh admin
        PesanCS::where('id_user', $id_user)
               ->where('sender_role', 'user')
               ->update(['is_read_admin' => true]);

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
     * Kirim balasan dari admin
     */
    public function sendMessage(Request $request, $id_user)
    {
        $request->validate([
            'pesan' => 'required|string'
        ]);

        $pesan = PesanCS::create([
            'id_user' => $id_user,
            'id_admin' => auth()->user()->id_user,
            'pesan' => $request->pesan,
            'sender_role' => 'admin',
            'is_read_admin' => true, // Admin sudah membacanya karena dia yang ngirim
            'is_read_user' => false
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
