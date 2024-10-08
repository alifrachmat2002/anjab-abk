<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TanggungJawab extends Model
{
    use HasFactory;

    protected $table = 'tanggung_jawab';
    protected $guarded = ['id'];

    public function jabatan() {
        return $this->belongsTo(JabatanDiajukan::class);
    }
}
