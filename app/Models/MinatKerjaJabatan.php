<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MinatKerjaJabatan extends Model
{
    use HasFactory;

    protected $table = 'minat_kerja_jabatan';

    protected $guarded = ['id'];
}
