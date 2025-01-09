<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HargaPenawaran extends Model
{
    use HasFactory;

    protected $table = 'harga_penawarans';

    protected $fillable = [
        'penawaran_id',
        'product_id',
        'price',
        'qty',
    ];

    public function product()
    {
        //Invoice reference ke table customers
        return $this->belongsTo(Product::class);
    }
}
