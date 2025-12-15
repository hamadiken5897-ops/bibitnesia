<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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
        'stok',
        'harga',
        'status',
        'foto_produk1',
        'foto_produk2',
        'foto_produk3',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
    // Relasi ke tabel Penjual
    public function penjual()
    {
        return $this->belongsTo(Penjual::class, 'id_penjual', 'id_penjual');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->id_produk) {
                $model->id_produk = 'PRD-' . Str::random(10);
            }
        });
    }
}
