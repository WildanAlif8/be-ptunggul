<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;
    protected $table = 'pelanggans';

    protected $fillable = [
        'kode',
        'nama',
        'domisili',
        'jenis_kelamin',
        'alamat',
    ];


    public function penjualan()
    {
        return $this->hasMany(Penjualan::class);
    }
}
