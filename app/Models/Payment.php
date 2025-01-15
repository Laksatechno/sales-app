<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'sales_id', // ID penjualan
        'photo',    // Path atau URL gambar bukti pembayaran
        'pph',      // Path atau URL file PPH
        'ppn',      // Path atau URL file PPN
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sales_id');
    }
}


