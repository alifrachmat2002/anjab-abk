<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FungsiPekerjaan extends Model
{
    use HasFactory;

    protected $table = 'fungsi_pekerjaan';
    protected $guarded = ['id'];
}
