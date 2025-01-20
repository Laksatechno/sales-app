<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'stock'];

    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class);
    }

    public function sale(){
        return $this->belongsTo(Sale::class);
    }
}
