<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanans';
    protected $primaryKey = 'id_pesanan';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_pesanan',
        'id_user',
        'tanggal_pesanan',
        'total_harga',
        'status_pesanan',
        'catatan',
        'id_detail_pesanan',
        'tgl_konfirmasi',
    ];

    // Relasi ke user (pembeli)
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    // Relasi ke detail pesanan (nanti bisa dibuat tabel detail_pesanans)
    public function detailPesanan()
    {
        return $this->hasOne(Detail_pesanan::class, 'id_detail_pesanan', 'id_detail_pesanan');
    }

    // Relasi ke produk
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

}
