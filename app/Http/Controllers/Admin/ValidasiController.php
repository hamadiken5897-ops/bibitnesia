<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banned;

class ValidasiController extends Controller
{
    public function index()
    {
        // contoh: hanya yang status = 'pending'
        $banneds = Banned::with('user')
            ->where('status', 'pending')
            ->orderBy('tgl_banned', 'desc')
            ->get();

        return view('admin.services.validasi', compact('banneds'));
    }
}
