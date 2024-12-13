<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    protected $table = 'penjualans';

    protected $fillable = [
        'nota',
        'tgl',
        'subtotal',
        'kode_pelanggan'
    ];

    // Relasi ke Pelanggan
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'kode_pelanggan', 'kode');
    }

    // Relasi ke Barang melalui Item Penjualan
    public function items()
    {
        return $this->hasMany(ItemPenjualan::class);
    }
}
