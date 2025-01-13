<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;
    protected $guarded = [];
    protected $fillable = [
        'name', 'role', 'email', 'password', 'tipe_pelanggan', 'marketing', 'jenis_institusi', 'no_hp', 'marketing_id', 'address'
    ];

    protected $hidden = ['password', 'remember_token'];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function hasRole($role)
    {
        return $this->role === $role; // Check if the user's role matches the given role
    }
}
