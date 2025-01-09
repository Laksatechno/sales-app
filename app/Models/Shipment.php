<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;

    protected $fillable = ['sale_id', 'delivery_date', 'arrival_date', 'photo_proof'];

    public function statuses()
    {
        return $this->hasMany(ShipmentStatus::class);
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}
