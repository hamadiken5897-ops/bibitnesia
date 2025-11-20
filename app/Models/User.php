<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $primaryKey = 'id_user';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    protected $rememberTokenName = 'remember_token';

    protected $fillable = [
        'id_user',
        'nama',
        'email',
        'password',
        'no_telepon',
        'alamat',
        'role',
        'tanggal_daftar',
        'status_akun',
        'terakhir_login',
    ];

    protected $hidden = ['password'];

    #// Bagian Generate ID USER Secara Terurut //
    // Event untuk auto-generate ID saat create
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id_user)) {
                $model->id_user = self::generateUserId();
            }
        });
    }

    /**
     * Generate ID User dengan format USR-0001, USR-0002, dst
     */
    private static function generateUserId()
    {
        // Ambil user terakhir
        $lastUser = self::orderBy('id_user', 'desc')->first();

        if (!$lastUser) {
            // Jika belum ada user, mulai dari USR-0001
            return 'USR-0001';
        }

        // Extract nomor dari ID terakhir
        // Contoh: USR-0001 → 0001 → 1
        preg_match('/USR-(\d+)/', $lastUser->id_user, $matches);
        
        if (isset($matches[1])) {
            $lastNumber = (int) $matches[1];
        } else {
            // Jika ID lama tidak match pattern, mulai dari 0
            $lastNumber = 0;
        }

        $newNumber = $lastNumber + 1;

        return 'USR-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    // ---------------------------------------------------------//
    
    public function getAuthIdentifierName()
    {
        return 'id_user';
    }

    public function getAuthIdentifier()
    {
        return $this->attributes['id_user'];
    }
    public function file()
    {
        return $this->morphOne(File::class, 'fileable');
    }
    public function admin()
    {
        return $this->hasOne(\App\Models\Admin::class, 'id_user');
    }
}
