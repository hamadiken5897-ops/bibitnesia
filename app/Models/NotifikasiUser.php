<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class NotifikasiUser extends Model
{
    // Nama tabel sesuai migration
    protected $table = 'notifikasi_user';

    // Nama primary key sesuai migration
    protected $primaryKey = 'id_notif';

    // Kalau primary key bukan incrementing integer (ini increment), tapi tetap aman:
    public $incrementing = true;
    protected $keyType = 'int';

    // Fillable fields untuk mass assignment
    protected $fillable = [
        'id_user',
        'judul',
        'pesan',
        'redirect_url',
        'is_read',
    ];

    // Casts
    protected $casts = [
        'is_read' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationship ke user (sesuaikan nama PK pada users model - saya asumsikan primary key di users adalah `id_user`)
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'id_user', 'id_user');
    }

    /**
     * Tandai notifikasi sebagai sudah dibaca
     */
    public function markAsRead()
    {
        $this->is_read = true;
        $this->save();
    }

    /**
     * Helper: ambil URL redirect penuh (fallback ke root jika kosong)
     */
    public function getRedirectUrlAttribute($value)
    {
        return $value ?: url('/');
    }
}
