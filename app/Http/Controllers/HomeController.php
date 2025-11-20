<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Ambil semua admin kecuali user yang sedang login
        $admins = \App\Models\Admin::with('user.file')
            ->whereHas('user', function($query) use ($user) {
                $query->where('id_user', '!=', $user->id_user);
            })
            ->limit(3) // Tampilkan 3 admin saja
            ->get();
    
        return view('admin.dashboard', compact('user', 'admins'));
    }
}
