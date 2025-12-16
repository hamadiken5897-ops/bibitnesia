<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provinsi extends Model
{
    protected $table = 'provinsis';
    protected $primaryKey = 'id_provinsi';

    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'kode_provinsi',
        'nama_provinsi',
    ];
}
