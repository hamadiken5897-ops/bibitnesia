<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banned;

class KomplainController extends Controller
{
    public function index()
    {
        $banneds = Banned::with('user')->latest('tgl_banned')->get();

        return view('admin.services.komplain', compact('banneds'));
    }
}
