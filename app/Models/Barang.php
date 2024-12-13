<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barangs';

    protected $fillable = [
        'kode',
        'nama',
        'kategori',
        'harga',
    ];

    public function items()
    {
        return $this->hasMany(ItemPenjualan::class);
    }
}
