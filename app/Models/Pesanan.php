<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Provinsi;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Helpers\BlowfishCipher;

class Pesanan extends Model
{
    protected $table = 'pesanans';
    protected $primaryKey = 'id_pesanan';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_pesanan',
        'id_user',
        'kode_invoice', // ← Tambahkan ini jika ada
        'tanggal_pesanan',
        'total_harga',
        'status_pesanan',
        'alamat', // 🔥
        'provinsi',
        'catatan',
        'tgl_konfirmasi',
        // 'id_detail_pesanan', // ← HAPUS INI! Tidak perlu ada di tabel pesanans
    ];

    // Relasi ke user (pembeli)
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    // Relasi ke detail pesanan (ONE TO MANY, bukan hasOne!)
    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class, 'id_pesanan', 'id_pesanan');
        // ↑ hasMany karena 1 pesanan bisa punya banyak item
        // ↑ Gunakan 'id_pesanan' sebagai foreign key
    }

    public function riwayat()
    {
        return $this->hasMany(RiwayatPesanan::class, 'id_pesanan', 'id_pesanan')->orderBy('created_at', 'desc');
    }

    public function provinsiRelasi()
    {
        return $this->belongsTo(Provinsi::class, 'provinsi', 'id_provinsi');
    }

    public function pengiriman()
    {
        return $this->hasOne(
            \App\Models\Pengiriman::class,
            'id_pesanan',
            'id_pesanan'
        );
    }

    public function pembayaran()
    {
        return $this->hasOne(\App\Models\Pembayaran::class, 'id_pesanan', 'id_pesanan');
    }

    protected function alamat(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => BlowfishCipher::decrypt($value),
            set: fn ($value) => BlowfishCipher::encrypt($value),
        );
    }
}
