<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Verifikasi extends Model
{
    use HasFactory;

    protected $table = 'verifikasi';
    protected $guarded = ['id'];

    public function ajuan()
    {
        return $this->belongsTo(Ajuan::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jabatanDirevisi() {
        // return $this->belongsToMany(JabatanDirevisi::class, 'jabatan_direvisi','verifikasi_id');
        return $this->hasMany(JabatanDirevisi::class);
    }
}
