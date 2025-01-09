<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KondisiPenawaran extends Model
{
    use HasFactory;

    protected $table = 'kondisi_penawarans';

    protected $fillable = [
        'penawaran_id',
        'kondisi',
    ];
}
