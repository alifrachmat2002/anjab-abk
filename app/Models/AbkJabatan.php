<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbkJabatan extends Model
{
    use HasFactory;

    public $table = 'abk_jabatan';

    protected $guarded = ['id'];

    public function abk()
    {
        return $this->belongsTo(Ajuan::class, 'abk_id');
    }

    public function jabatan()
    {
        return $this->belongsTo(JabatanDiajukan::class, 'jabatan_id');
    }

    public function jabatanTugasTambahan()
    {
        return $this->belongsTo(JabatanTugasTambahan::class,'jabatan_tutam_id');
    }

    public function detailAbk()
    {
        return $this->hasMany(DetailAbk::class);
    }

    public function revisiTerbaruTanpaVerifikasi()
    {
        return $this->hasOne(JabatanDirevisi::class, 'abk_jabatan_id')
        ->whereNull('verifikasi_id')
        ->latest();
    }

    public function catatanAjuan() {
        return $this->hasMany(JabatanDirevisi::class, 'abk_jabatan_id')->whereHas('verifikasi')->latest();
    }
}
