<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alamat extends Model
{
    use HasFactory;

    protected $table = 'alamats';

    protected $fillable = [
        'id_user',
        'nama_penerima',
        'no_telepon',
        'id_provinsi',
        'kota',
        'kecamatan',
        'kode_pos',
        'detail_alamat',
        'is_utama',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class, 'id_provinsi', 'id_provinsi');
    }
}
