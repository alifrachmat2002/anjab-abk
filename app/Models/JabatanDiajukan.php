<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class JabatanDiajukan extends Model
{
    use HasFactory;
    use HasRecursiveRelationships;

    public function getParentKeyName()
    {
        return 'parent_id';
    }

    // table name is 'jabatan_diajukan'
    protected $table = 'jabatan_diajukan';

    protected $guarded = ['id'];

    // check if there is an ajuan draft
    public static function is_draft_exist()
    {
        // if model does not have instance, return false
        // if model has instance and its latest instance's 'ajuan_id' is not null, return false
        // if model has instance and its latest instance's 'ajuan_id' is null, return true
        // return JabatanDiajukan::count() == 0 ? false : JabatanDiajukan::latest()->first()->ajuan_id == null;

        return JabatanDiajukan::where('ajuan_id', null)->exists();
    }

    public function jenisJabatan()
    {
        return $this->belongsTo(JenisJabatan::class);
    }

    public function pendidikanFormal()
    {
        return $this->hasMany(PendidikanFormalDiajukan::class);
    }

    public function pendidikanPelatihan()
    {
        return $this->hasMany(PendidikanPelatihanDiajukan::class);
    }

    public function pengalaman()
    {
        return $this->hasMany(PengalamanDiajukan::class);
    }

    public function uraianTugas()
    {
        return $this->hasMany(UraianTugasDiajukan::class);
    }

    public function bahanKerja()
    {
        return $this->hasMany(BahanKerjaDiajukan::class);
    }

    public function perangkatKerja()
    {
        return $this->hasMany(PerangkatKerjaDiajukan::class);
    }

    public function tanggungJawab()
    {
        return $this->hasMany(TanggungJawabDiajukan::class);
    }

    public function wewenang()
    {
        return $this->hasMany(WewenangDiajukan::class);
    }

    public function korelasiJabatan()
    {
        return $this->hasMany(KorelasiJabatanDiajukan::class);
    }

    public function risikoBahaya()
    {
        return $this->hasMany(RisikoBahayaDiajukan::class);
    }

    public function jabatanUnsur()
    {
        return $this->hasMany(JabatanUnsurDiajukan::class, 'jabatan_diajukan_id');
    }

    public function detailAbk()
    {
        return $this->hasMany(DetailAbk::class);
    }

    public function abkJabatan() {
        return $this->hasMany(AbkJabatan::class,'jabatan_id');
    }

    public function unsurs()
    {
        return $this->belongsToMany(Unsur::class, 'jabatan_unsur_diajukans', 'jabatan_diajukan_id', 'unsur_id');
    }

    public function bakatKerja()
    {
        return $this->hasMany(BakatKerjaJabatanDiajukan::class);
    }

    public function temperamenKerja()
    {
        return $this->hasMany(TemperamenKerjaJabatanDiajukan::class);
    }

    public function minatKerja()
    {
        return $this->hasMany(MinatKerjaJabatanDiajukan::class);
    }

    public function fungsiPekerjaan()
    {
        return $this->hasMany(FungsiPekerjaanJabatanDiajukan::class);
    }

    public function upayaFisik()
    {
        return $this->hasMany(UpayaFisikJabatanDiajukan::class);
    }

    // two functions below are created to access $jabatan->catatan directly
    public function revisiTerbaruTanpaVerifikasi()
    {
        return $this->hasOne(JabatanDirevisi::class, 'jabatan_diajukan_id')
        ->whereNull('verifikasi_id')
        ->latest();
    }
    public function getCatatanRevisiTerbaru() {
        return $this->revisiTerbaruTanpaVerifikasi->catatan;
    }

    public function catatanAjuan() {
        return $this->hasMany(JabatanDirevisi::class, 'jabatan_diajukan_id')->whereHas('verifikasi')->latest();
    }
}
