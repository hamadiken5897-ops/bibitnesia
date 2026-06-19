<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminLog extends Model
{
    use HasFactory;

    protected $table = 'admin_logs';

    protected $fillable = [
        'id_user',
        'aktivitas',
    ];

    // Relasi ke tabel users
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /**
     * Helper statis untuk mencatat log aktivitas admin
     */
    public static function log($aktivitas)
    {
        if (auth()->check()) {
            self::create([
                'id_user' => auth()->user()->id_user,
                'aktivitas' => $aktivitas,
            ]);
        }
    }
}
