<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banned extends Model
{
    protected $table = 'banneds';
    protected $primaryKey = 'id_banned';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_banned',
        'id_user',
        'status',
        'tgl_banned',
        'tgl_berakhir',
        'alasan',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
