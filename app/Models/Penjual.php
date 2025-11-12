<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjual extends Model
{
    protected $table = 'penjuals';
    protected $primaryKey = 'id_penjual';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_penjual',
        'id_user',
        'nama_penjual',
        'no_teleponPJ',
        'alamatPJ',
        'tanggal_daftar',
        'status_verifikasi',
        'tgl_verifikasi',
        'deskripsi_pj',
        'rating',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
