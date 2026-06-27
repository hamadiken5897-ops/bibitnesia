<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesanCS extends Model
{
    use HasFactory;

    protected $table = 'pesan_cs';
    protected $primaryKey = 'id_pesan_cs';

    protected $fillable = [
        'id_user',
        'id_admin',
        'pesan',
        'is_read_admin',
        'is_read_user',
        'sender_role',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'id_admin', 'id_user');
    }
}
