<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FungsiPekerjaanJabatan extends Model
{
    use HasFactory;

    protected $table = 'fungsi_pekerjaan_jabatan';

    protected $guarded = ['id'];
}
