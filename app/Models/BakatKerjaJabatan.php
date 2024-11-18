<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BakatKerjaJabatan extends Model
{
    use HasFactory;

    protected $table = 'bakat_kerja_jabatan';

    protected $guarded = ['id'];
}
