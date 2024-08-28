<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unsur extends Model
{
    use HasFactory;

    protected $table = 'unsur';

    protected $fillable = ['nama'];

    public function unitKerja()
    {
        return $this->hasMany(UnitKerja::class);
    }

    public function jabatanTugasTambahan()
    {
        return $this->hasMany(JabatanTugasTambahan::class);
    }
}
