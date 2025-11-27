<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request; //router folder controller admin
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $users = User::when($search, function ($query) use ($search) {
            $query
                ->where('nama', 'LIKE', "%$search%")
                ->orWhere('id_user', 'LIKE', "%$search%")
                ->orWhere('email', 'LIKE', "%$search%");
        })
            ->orderBy('nama')
            ->get();

        return view('admin.manajemen.user', compact('users'));
    }
    // =========================================================
    // SHOW
    // =========================================================
    public function show($id)
    {
        $user = User::where('id_user', $id)->firstOrFail();

        return view('admin.manajemen.User.show', compact('user'));
    }

    // =========================================================
    // CREATE
    // =========================================================
    public function create()
    {
        return view('admin.manajemen.User.create');
    }

    // =========================================================
    // STORE
    // =========================================================
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:5',
            'no_telepon' => 'nullable',
            'alamat' => 'nullable',
            'role' => 'required',
            'status_akun' => 'required',
        ]);

        User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_telepon' => $request->no_telepon,
            'alamat' => $request->alamat,
            'role' => $request->role,
            'status_akun' => $request->status_akun,
            'tanggal_daftar' => now()->toDateString(),
            'terakhir_login' => null,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan!');
    }

    // =========================================================
    // EDIT
    // =========================================================
    public function edit($id)
    {
        $user = User::where('id_user', $id)->firstOrFail();
        return view('admin.manajemen.User.edit', compact('user'));
    }

    // =========================================================
    // UPDATE
    // =========================================================
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'email' => "required|email|unique:users,email,$id,id_user",
            'no_telepon' => 'nullable',
            'alamat' => 'nullable',
            'role' => 'required',
            'status_akun' => 'required',
        ]);

        $user = User::where('id_user', $id)->firstOrFail();

        $data = [
            'nama' => $request->nama,
            'email' => $request->email,
            'no_telepon' => $request->no_telepon,
            'alamat' => $request->alamat,
            'role' => $request->role,
            'status_akun' => $request->status_akun,
        ];

        // Jika password diisi, update password
        if (!empty($request->password)) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui!');
    }

    // Controller User AJAX
    public function search(Request $request)
    {
        //if (!$request->ajax()) {
        //    abort(404);
        //}

        $search = $request->search;
        $sort = $request->sort;
        $role = $request->role;
        $status = $request->status;

        $users = User::query();

        if ($search) {
            $users->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('id_user', 'like', "%$search%");
            });
        }

        if ($role) {
            $users->where('role', $role);
        }

        if ($sort) {
            $users->orderBy('id_user', $sort);
        }

        if ($status) {
            $users->where('status_akun', $status);
        }

        $users = $users->get();

        return response()->json([
            'html' => view('admin.manajemen.User.table', compact('users'))->render(),
        ]);
    }
}
