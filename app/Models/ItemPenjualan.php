<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemPenjualan extends Model
{
    use HasFactory;
    protected $table = 'item_penjualans';
    protected $fillable = [
        'nota',
        'kode_barang',
        'qty'
    ];


    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'nota', 'nota');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'kode_barang', 'kode');
    }
}
