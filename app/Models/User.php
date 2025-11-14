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

    public function getAuthIdentifierName()
    {
        return 'id_user';
    }

    public function getAuthIdentifier()
    {
        return $this->attributes['id_user'];
    }
}