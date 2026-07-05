<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Helpers\BlowfishCipher;

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

    protected function noRekening(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => BlowfishCipher::decrypt($value),
            set: fn ($value) => BlowfishCipher::encrypt($value),
        );
    }
}
