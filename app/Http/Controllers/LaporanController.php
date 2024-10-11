<?php

namespace App\Http\Controllers;

use App\Exports\AbkExport;
use App\Models\AbkJabatan;
use App\Models\Ajuan;
use App\Models\JabatanDiajukan;
use App\Models\JabatanTugasTambahan;
use App\Models\UnitKerja;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel as Excel;

class LaporanController extends Controller
{
    public function index()
    {
        $title = 'Laporan';
        $ajuans = Ajuan::where('jenis', 'anjab')
            ->where('is_approved', true)
            ->whereHas('abk', function ($query) {
                $query->where('is_approved', true);
            })
            ->get();

        return view('laporan.index', compact('title', 'ajuans'));
    }

    public function showAnjab($tahun, Ajuan $anjab)
    {
        $title = 'Laporan Analisis Jabatan' . $anjab->tahun;
        $jabatans = JabatanDiajukan::where('ajuan_id', $anjab->id)->get();

        return view('laporan.anjab', compact('title', 'anjab', 'jabatans'));
    }

    public function showLaporanAnjab($tahun, Ajuan $anjab)
    {
        $title = 'Laporan Jabatan' . $anjab->tahun;
        $jabatans = JabatanDiajukan::where('ajuan_id', $anjab->id)->get();

        return view('laporan.anjabs', compact('title', 'jabatans'));
    }

    public function downloadLaporanAnjab($tahun, Ajuan $anjab)
    {
        $title = 'Laporan Jabatan' . $anjab->tahun;
        $jabatans = JabatanDiajukan::where('ajuan_id', $anjab->id)->get();

        $pdf = Pdf::loadView('laporan.anjabs', compact('title', 'jabatans'));


        return $pdf->download('laporan-anjab-' . $anjab->tahun . '-' . $anjab->id . '.pdf');
    }

    public function showABK($tahun, Ajuan $anjab)
    {
        $title = 'Laporan Jabatan' . $anjab->tahun;
        $abk = $anjab->abk;
        $jabatans = JabatanDiajukan::where('ajuan_id', $anjab->id)->get();
        $unitKerjas = UnitKerja::all();
        $tutams = JabatanTugasTambahan::with('AbkJabatan')->get();

        return view('laporan.abk', compact('title', 'anjab', 'jabatans', 'abk', 'unitKerjas', 'tutams'));
    }

    public function showLaporanABK($tahun, Ajuan $anjab)
    {
        $title = 'Laporan Jabatan' . $anjab->tahun;
        $abkIds = $anjab->abk->pluck('id');
        $unitkerjas = UnitKerja::with([
            // include unitKerja's unsur relationship
            'unsur' => [
                // include unsur's jabatanTugasTambahan relationship
                'jabatanTugasTambahan' => [
                    // include jabatanTugasTambahan's AbkJabatan relationship
                    'AbkJabatan' => function ($query) use ($abkIds) {
                        $query->with('jabatan:id,nama')->whereIn('abk_id', $abkIds);
                    },
                ],
            ],
        ])->get();
        $jabatans = AbkJabatan::select('jabatan_diajukan.nama as nama_jabatan', 
                             DB::raw('SUM(abk_jabatan.kebutuhan_pegawai) as total_kebutuhan'),
                             )
             ->whereIn('abk_id',$abkIds)
             ->join('jabatan_diajukan', 'abk_jabatan.jabatan_id', '=', 'jabatan_diajukan.id')
             ->groupBy('abk_jabatan.jabatan_id', 'jabatan_diajukan.nama')
             ->get();
        return view('laporan.abks', compact('title', 'anjab', 'unitkerjas','jabatans'));
    }

    public function downloadLaporanAbk($tahun, Ajuan $anjab)
    {
        try {
            $abkIds = $anjab->abk->pluck('id');
            return Excel::download(new AbkExport($abkIds, $anjab->id), 'abk.xlsx');
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public function indexPetaJabatan(Ajuan $anjab)
    {
        $title = 'Peta Jabatan';
        $abk = $anjab->abk;
        $rootTutams = JabatanTugasTambahan::where('parent_id', null)->get();

        $unitKerjas = UnitKerja::all();

        return view('laporan.petajabatan.index', compact('title', 'anjab', 'abk', 'rootTutams', 'unitKerjas'));
    }

    private function generateMermaidCodeUnit($jabatans, $parentId = null, $unsurId = null)
    {
        $code = '';
        foreach ($jabatans as $jabatan) {
            $currentId = preg_replace('/ /', '', $jabatan->nama) . $jabatan->id;
            $parent = $jabatan->parent ?? null;
            $parentId = $jabatan->parent_id ? preg_replace('/ /', '', $parent->nama) . $jabatan->parent_id : null;
            $code .= "{$currentId}[{$jabatan->nama}]\n";
            if ($parent) {
                $code .= "{$parentId}[{$parent->nama}]\n";
                $code .= "{$parentId} --> {$currentId}\n";
            }

            if ($jabatan->AbkJabatan->isNotEmpty()) {
                $abkJabatanId = 'abk' . $jabatan->AbkJabatan->first()->id;
                $code .= "{$abkJabatanId}[\"<table class='table table-bordered' style='background: transparent; border: none;'>
            <thead>
                <tr>
                    <td>Jabatan</td>
                    <td>B</td>
                    <td>K</td>
                    <td>+/-</td>
                </tr>
            </thead>
            <tbody>";

                foreach ($jabatan->AbkJabatan as $abkJabatan) {
                    $code .= "<tr>
                    <td>{$abkJabatan->jabatan->nama}</td>
                    <td>{$abkJabatan->kebutuhan_pegawai}</td>
                    <td>1</td>
                    <td>1</td>
                </tr>";
                }
                $code .= "</tbody></table>\"]:::customTableNode\n";
                $code .= "{$currentId} --> {$abkJabatanId}\n";
            }
        }
        $code .= "classDef customTableNode fill:#fff,stroke:none;\n";
        return $code;
    }

    public function showUnit(Ajuan $anjab, UnitKerja $unit)
    {
        $title = 'Peta Jabatan ' . $unit->nama;
        $unsur = $unit->unsur;
        $rootTutams = JabatanTugasTambahan::where('unsur_id', $unsur->id)->get();
        $mermaidCode = $this->generateMermaidCodeUnit($rootTutams, null, unsurId: $unsur->id);

        return view('laporan.petajabatan.showunit', compact('anjab', 'unit', 'title', 'mermaidCode'));
    }
}
