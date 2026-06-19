<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamConversation extends Model
{
    use HasFactory;

    protected $table = 'team_conversations';

    protected $fillable = [
        'id_user',
        'pesan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
