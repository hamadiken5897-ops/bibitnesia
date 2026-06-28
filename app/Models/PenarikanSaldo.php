<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenarikanSaldo extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_penarikan';

    protected $fillable = [
        'user_id',
        'role',
        'nama_bank',
        'no_rekening',
        'nama_pemilik_rekening',
        'jumlah_penarikan',
        'status',
        'tgl_pengajuan',
        'tgl_selesai',
        'catatan_admin',
        'payout_id',
    ];

    protected $casts = [
        'tgl_pengajuan' => 'datetime',
        'tgl_selesai' => 'datetime',
        'jumlah_penarikan' => 'decimal:2',
    ];
}
