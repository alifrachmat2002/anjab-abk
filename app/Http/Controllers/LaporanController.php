<?php

namespace App\Http\Controllers;

use App\Models\Ajuan;
use App\Models\JabatanDiajukan;
use App\Models\JabatanTugasTambahan;
use App\Models\UnitKerja;
use Illuminate\Http\Request;

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
