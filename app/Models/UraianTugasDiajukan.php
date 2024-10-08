<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UraianTugasDiajukan extends Model
{
    use HasFactory;

    protected $table = 'uraian_tugas_diajukan';
    protected $guarded = ['id'];

    public function detailAbk()
    {
        return $this->hasMany(DetailAbk::class);
    }
}
