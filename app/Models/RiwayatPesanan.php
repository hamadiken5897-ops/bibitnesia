<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatPesanan extends Model
{
    use HasFactory;

    protected $table = 'riwayat_pesanans';
    protected $fillable = ['id_pesanan', 'status', 'deskripsi'];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan', 'id_pesanan');
    }
}
