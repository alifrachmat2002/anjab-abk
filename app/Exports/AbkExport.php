<?php

namespace App\Exports;

use App\Models\AbkJabatan;
use App\Models\JabatanDiajukan;
use App\Models\UnitKerja;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;

class AbkExport implements FromView
{
    private $abkIds;
    private $anjabId;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($abkIds, $anjabId)
    {
        $this->abkIds = $abkIds;
        $this->anjabId = $anjabId;
    }

    public function view(): View
    {
        $unitkerjas = UnitKerja::with([
            // include unitKerja's unsur relationship
            'unsur' => [
                // include unsur's jabatanTugasTambahan relationship
                'jabatanTugasTambahan' => [
                    // include jabatanTugasTambahan's AbkJabatan relationship
                    'AbkJabatan' => function ($query) {
                        $query->with('jabatan:id,nama')->whereIn('abk_id', $this->abkIds);
                    }   
                    ]
                ]
                ])->get();
        $jabatans = AbkJabatan::select('jabatan_diajukan.nama as nama_jabatan', 
                             DB::raw('SUM(abk_jabatan.kebutuhan_pegawai) as total_kebutuhan'),
                             )
             ->whereIn('abk_id',$this->abkIds)
             ->join('jabatan_diajukan', 'abk_jabatan.jabatan_id', '=', 'jabatan_diajukan.id')
             ->groupBy('abk_jabatan.jabatan_id', 'jabatan_diajukan.nama')
             ->orderBy('jabatan_diajukan.nama','asc')
             ->get();


        return view('laporan.abks-download', compact('unitkerjas','jabatans'));
    }

}
