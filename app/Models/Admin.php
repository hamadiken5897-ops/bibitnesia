<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = 'admins';
    protected $primaryKey = 'id_admin';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_admin',
        'id_user',
        'jabatan',
        'tgl_bergabung',
        'status_admin',
    ];

    // Relasi ke model User
    public function user()
    {
        //return $this->belongsTo(User::class, 'id_user', 'id_user');
        return $this->belongsTo(\App\Models\User::class, 'id_user');
    }

    public function getJabatanAliasAttribute()
    {
        return match ($this->jabatan) {
            'admin' => 'Administrator',
            'super_admin' => 'Super Administrator',
            default => 'Tidak Diketahui'
        };
    }
}
