<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wewenang extends Model
{
    use HasFactory;

    protected $table = 'wewenang';
    protected $guarded = ['id'];

    public function jabatan() {
        return $this->belongsTo(JabatanDiajukan::class);
    }
}
