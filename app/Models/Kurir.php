<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kurir extends Model
{
    protected $table = 'kurirs';
    protected $primaryKey = 'id_kurir';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_kurir',
        'id_user',
        'nama_pt',
        'tipe_kendaraan',
        'status_kurir',
        'daerah',
    ];

    // Relasi ke tabel users
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
