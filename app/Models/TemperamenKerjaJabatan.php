<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemperamenKerjaJabatan extends Model
{
    use HasFactory;

    protected $table = 'temperamen_kerja_jabatan';

    protected $guarded = ['id'];
}
