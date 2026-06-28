<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanKurir extends Model
{
    use HasFactory;

    protected $table = 'laporan_kurirs';

    protected $fillable = [
        'id_kurir',
        'id_pesanan',
        'jumlah',
    ];

    public function kurir()
    {
        return $this->belongsTo(Kurir::class, 'id_kurir', 'id_kurir');
    }

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan', 'id_pesanan');
    }
}
