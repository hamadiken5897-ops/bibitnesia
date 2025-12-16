<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provinsi extends Model
{
    use HasFactory;

    protected $table = 'provinsis'; // Nama tabel di database
    
    protected $primaryKey = 'id_provinsi'; // PRIMARY KEY harus sesuai dengan foreign key di PengajuanMitra
    
    public $incrementing = true;
    
    protected $keyType = 'int';
    
    protected $fillable = [
        'nama_provinsi',
    ];
}