<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeamConversation;
use Illuminate\Http\Request;

class TeamConversationController extends Controller
{
    public function index()
    {
        // View utama chat
        return view('admin.teams.conversation.index');
    }

    public function fetchMessages()
    {
        // Ambil 50 pesan terakhir
        $messages = TeamConversation::with('user.file')
                        ->orderBy('created_at', 'asc') // Urutan lama ke baru agar scroll ke bawah
                        ->get();

        // Render HTML partial untuk di-inject via AJAX
        return view('admin.teams.conversation.partials.messages', compact('messages'))->render();
    }

    public function store(Request $request)
    {
        $request->validate([
            'pesan' => 'required|string|max:1000'
        ]);

        $message = TeamConversation::create([
            'id_user' => auth()->user()->id_user,
            'pesan' => $request->pesan,
        ]);

        // Log Aktivitas jika diperlukan (opsional, mungkin terlalu spam jika tiap chat di log)
        // \App\Models\AdminLog::log('Mengirim pesan di Team Conversation');

        return response()->json([
            'success' => true,
            'message' => 'Pesan terkirim'
        ]);
    }
}
