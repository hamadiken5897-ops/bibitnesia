<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanPenjual extends Model
{
    protected $table = 'laporan_penjual';
    protected $primaryKey = 'id_laporan';

    protected $fillable = [
        'id_penjual',
        'id_pesanan',
        'jumlah',
        'komisi'
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan', 'id_pesanan');
    }
}
