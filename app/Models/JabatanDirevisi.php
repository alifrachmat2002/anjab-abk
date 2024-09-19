<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JabatanDirevisi extends Model
{
    use HasFactory;

    protected $table = 'jabatan_direvisi';

    protected $guarded = ['id'];

    public function verifikasi() {
        return $this->belongsTo(Verifikasi::class);
    }
}
