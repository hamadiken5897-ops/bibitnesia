<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengaturanPembayaran extends Model
{
    use HasFactory;

    protected $table = 'pengaturan_pembayarans';

    protected $fillable = [
        'bank_name',
        'bank_account',
        'bank_owner',
        'ewallet_name',
        'ewallet_phone',
        'ewallet_owner',
        'midtrans_is_active',
        'midtrans_server_key',
        'midtrans_client_key',
        'biaya_layanan_persen',
        'card_theme',
    ];

    protected $casts = [
        'midtrans_is_active' => 'boolean',
        'biaya_layanan_persen' => 'decimal:2',
    ];
}
