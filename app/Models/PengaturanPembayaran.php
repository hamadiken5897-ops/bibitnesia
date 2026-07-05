<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Helpers\BlowfishCipher;

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

    protected function midtransServerKey(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => BlowfishCipher::decrypt($value),
            set: fn ($value) => BlowfishCipher::encrypt($value),
        );
    }

    protected function midtransClientKey(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => BlowfishCipher::decrypt($value),
            set: fn ($value) => BlowfishCipher::encrypt($value),
        );
    }

    protected function bankAccount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => BlowfishCipher::decrypt($value),
            set: fn ($value) => BlowfishCipher::encrypt($value),
        );
    }

    protected function ewalletPhone(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => BlowfishCipher::decrypt($value),
            set: fn ($value) => BlowfishCipher::encrypt($value),
        );
    }
}
