<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;
    protected $fillable = ['invoice_number', 'customer_id','user_customer_id', 'user_id', 'total','tax', 'tax_status', 'due_date', 'total', 'status', 'diskon'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function users()
    {
        return $this->belongsTo(User::class, 'user_customer_id');
    }

    public function marketing()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function details()
    {
        return $this->hasMany(SaleDetail::class);
    }

    public function shipment()
    {
        return $this->hasOne(Shipment::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'sales_id');
    }

}
