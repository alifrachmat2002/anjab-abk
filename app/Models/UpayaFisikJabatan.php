<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UpayaFisikJabatan extends Model
{
    use HasFactory;

    protected $table = 'upaya_fisik_jabatan';

    protected $guarded = ['id'];
}
