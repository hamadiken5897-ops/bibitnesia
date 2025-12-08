<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanMitra extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_mitra';
    protected $primaryKey = 'id_pengajuan';
    public $incrementing = true;

    // Jika PK bukan string, maka tipe integer tetap default
    protected $keyType = 'int';

    protected $fillable = [
        'id_user',
        'role_pengajuan',
        'ktp',
        'foto_selfie',
        'no_rekening',
        'alamat',
        'no_hp',
        'sim',
        'stnk',
        'skck',
        'foto_kendaraan',
        'tipe_kendaraan',
        'merek_kendaraan',
        'foto_kebun',
        'portofolio',
        'deskripsi',
        'alamat_kebun',
        'status',
        'catatan_admin',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    // --- RELASI ---
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'id_user', 'id_user');
    }
}

