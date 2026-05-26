<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Komplain extends Model
{
    protected $table = 'komplains';
    protected $primaryKey = 'id_komplain';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_komplain',
        'id_user',
        'id_pesanan',
        'id_terlapor',
        'judul_laporan',
        'deskripsi_laporan',
        'bukti_foto',
        'status',
        'catatan_admin',
    ];

    /**
     * Boot function to auto-generate string ID.
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id_komplain)) {
                $model->id_komplain = 'KMP-' . strtoupper(Str::random(10));
            }
        });
    }

    // Relasi ke Pelapor (User)
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    // Relasi ke Pesanan
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan', 'id_pesanan');
    }

    // Relasi ke Terlapor (User)
    public function terlapor()
    {
        return $this->belongsTo(User::class, 'id_terlapor', 'id_user');
    }
}
