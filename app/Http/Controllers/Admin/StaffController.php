<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    // List all admins
    public function index()
    {
        // Get all users who have role 'admin'
        $staffs = User::with('admin')->where('role', 'admin')->orderBy('nama')->get();

        return view('admin.teams.staff.index', compact('staffs'));
    }

    // Show form to create new admin (only accessible by super_admin via middleware)
    public function create()
    {
        return view('admin.teams.staff.create');
    }

    // Store new admin
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:5',
            'jabatan' => 'required|in:admin,super_admin',
        ]);

        // Create User
        $user = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_telepon' => '-',
            'alamat' => '-',
            'deskripsi' => '-',
            'role' => 'admin',
            'status_akun' => 'AKTIF',
            'tanggal_daftar' => now()->toDateString(),
            'terakhir_login' => null,
        ]);

        // Create Admin record
        // Generate random id_admin, you could use a better pattern if required.
        $id_admin = 'ADM-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        
        Admin::create([
            'id_admin' => $id_admin,
            'id_user' => $user->id_user,
            'jabatan' => $request->jabatan,
            'tgl_bergabung' => now()->toDateString(),
            'status_admin' => 'AKTIF',
        ]);

        // Log aktivitas
        \App\Models\AdminLog::log('Menambahkan akun admin baru: ' . $user->nama);

        return redirect()->route('admin.staff.index')->with('success', 'Staff admin berhasil ditambahkan!');
    }
}
