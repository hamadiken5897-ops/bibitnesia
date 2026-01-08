<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengiriman extends Model
{
    protected $table = 'pengiriman';
    protected $primaryKey = 'id_pengiriman';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_pengiriman',
        'id_pesanan',
        'id_kurir',          // 🔥 BUKAN "kurir"
        'no_resi',
        'alamat_tujuan',
        'tanggal_pengiriman',
        'estimasi_tiba',
        'status_pengiriman',
    ];

    // Relasi ke Pesanan
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan', 'id_pesanan');
    }

    // 🔥 RELASI KE KURIR
    public function kurir()
    {
        return $this->belongsTo(Kurir::class, 'id_kurir', 'id_kurir');
    }
}
