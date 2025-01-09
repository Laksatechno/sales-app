<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penawaran extends Model
{
    use HasFactory;

    protected $table = 'penawarans';

    protected $fillable = [
        'customer',
        'product_id',
        'address',
        'perihal',
        'no_hp',
        'user_id'
    ];
}
