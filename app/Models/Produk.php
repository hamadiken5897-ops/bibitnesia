<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produks';
    protected $primaryKey = 'id_produk';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_produk',
        'id_penjual',
        'nama_produk',
        'kategori',
        'deskripsi',
        'foto_produk',
        'status',
        'stok',
        'harga',
    ];

    // Relasi ke tabel Penjual
    public function penjual()
    {
        return $this->belongsTo(Penjual::class, 'id_penjual', 'id_penjual');
    }
}
