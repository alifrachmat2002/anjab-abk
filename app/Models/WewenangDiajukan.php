<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WewenangDiajukan extends Model
{
    use HasFactory;

    protected $table = 'wewenang_diajukan';
    protected $guarded = ['id'];
}
