<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BahanKerjaDiajukan extends Model
{
    use HasFactory;

    protected $table = 'bahan_kerja_diajukan';
    protected $guarded = ['id'];
}
