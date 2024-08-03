<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemperamenKerja extends Model
{
    use HasFactory;

    protected $table = 'temperamen_kerja';
    protected $guarded = ['id'];
}
