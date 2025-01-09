<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'phone', 'address', 'email', 'tipe_pelanggan'];

    public function prices()
    {
        return $this->hasMany(CustomerProductPrice::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
