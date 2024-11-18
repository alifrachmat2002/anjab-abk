<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    use HasFactory;
    use \Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

    protected $guarded = ['id'];

    protected $table = 'jabatan';

    public function getParentKeyName()
    {
        return 'parent_id';
    }

    public function uraianTugas()
    {
        return $this->hasMany(UraianTugas::class);
    }

    public function unsurs()
    {
        return $this->belongsToMany(Unsur::class, 'jabatan_unsur', 'jabatan_id', 'unsur_id');
    }

    public function pendidikanFormal()
    {
        return $this->hasMany(PendidikanFormal::class);
    }

    public function pendidikanPelatihan()
    {
        return $this->hasMany(PendidikanPelatihan::class);
    }

    public function pengalaman()
    {
        return $this->hasMany(Pengalaman::class);
    }

    public function bahanKerja()
    {
        return $this->hasMany(BahanKerja::class);
    }

    public function perangkatKerja()
    {
        return $this->hasMany(PerangkatKerja::class);
    }

    public function tanggungJawab()
    {
        return $this->hasMany(TanggungJawab::class);
    }

    public function wewenang()
    {
        return $this->hasMany(Wewenang::class);
    }

    public function korelasiJabatan()
    {
        return $this->hasMany(KorelasiJabatan::class);
    }

    public function risikoBahaya()
    {
        return $this->hasMany(RisikoBahaya::class);
    }

    public function bakatKerja()
    {
        return $this->hasMany(BakatKerjaJabatan::class);
    }

    public function temperamenKerja()
    {
        return $this->hasMany(TemperamenKerjaJabatan::class);
    }

    public function minatKerja()
    {
        return $this->hasMany(MinatKerjaJabatan::class);
    }

    public function fungsiPekerjaan()
    {
        return $this->hasMany(FungsiPekerjaanJabatan::class);
    }

    public function upayaFisik()
    {
        return $this->hasMany(UpayaFisikJabatan::class);
    }
}
