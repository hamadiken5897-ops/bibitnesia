<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesan extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_pengirim',
        'id_penerima',
        'id_produk',
        'isi_pesan',
        'is_read',
    ];

    public function pengirim()
    {
        return $this->belongsTo(User::class, 'id_pengirim', 'id_user');
    }

    public function penerima()
    {
        return $this->belongsTo(User::class, 'id_penerima', 'id_user');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }
}
