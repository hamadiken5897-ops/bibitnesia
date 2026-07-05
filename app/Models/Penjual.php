<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Helpers\BlowfishCipher;

class Penjual extends Model
{
    protected $table = 'penjuals'; // 
    protected $primaryKey = 'id_penjual';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_penjual',
        'id_user',
        'id_provinsi',
        'nama_penjual',
        'no_teleponPJ',
        'alamatPJ',
        'tanggal_daftar',
        'status_verifikasi',
        'tgl_verifikasi',
        'deskripsi_pj',
        'rating',
        'saldo',
        'nama_bank',
        'no_rekening',
        'nama_pemilik_rekening',
        'ewallet_name',
        'ewallet_phone',
        'ewallet_owner',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    // Relasi ke Provinsi
    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class, 'id_provinsi', 'id_provinsi');
    }

    // ✅ TAMBAHKAN INI - Relasi ke Produk
    public function produk()
    {
        return $this->hasMany(Produk::class, 'id_penjual', 'id_penjual');
    }

    protected function noRekening(): Attribute
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

    protected function noTeleponPj(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => BlowfishCipher::decrypt($value),
            set: fn ($value) => BlowfishCipher::encrypt($value),
        );
    }

    protected function alamatPj(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => BlowfishCipher::decrypt($value),
            set: fn ($value) => BlowfishCipher::encrypt($value),
        );
    }
}