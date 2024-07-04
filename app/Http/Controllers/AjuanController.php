<?php

namespace App\Http\Controllers;

use App\Models\Ajuan;
use App\Models\AjuanJabatan;
use App\Models\Eselon;
use App\Models\Golongan;
use App\Models\Jabatan;
use App\Models\JenisJabatan;
use App\Models\UnitKerja;
use App\Models\Verifikasi;
use Illuminate\Http\Request;

class AjuanController extends Controller
{
    public function anjabIndex()
    {
        $title = 'Ajuan Jabatan';
        $ajuans = Ajuan::where('jenis', 'anjab')->get();

        return view('anjab.ajuans', compact('title', 'ajuans'));
    }

    public function anjabCreate()
    {
        $title = 'Buat Ajuan Baru';
        $jabatans = Jabatan::all();
        $jenisJabatan = JenisJabatan::all();
        $eselon = Eselon::all();
        $golongan = Golongan::all();
        $unitKerjas = UnitKerja::all();
        $buttons = ['tambah-jabatan-bawahan', 'ubah-informasi-jabatan'];
        return view('anjab.buat-ajuan', compact('title', 'jabatans', 'jenisJabatan', 'eselon', 'golongan', 'unitKerjas', 'buttons'));
    }

    public function anjabStore(Request $request)
    {
        Ajuan::create([
            'tahun' => now()->year,
            'status' => 'diajukan',
            'jenis' => 'anjab'
        ]);

        $jabatans = $request->input('jabatans');
        foreach ($jabatans as $jabatan_id) {
            AjuanJabatan::create([
                'ajuan_id' => Ajuan::latest()->first()->id,
                'jabatan_id' => $jabatan_id
            ]);
        }

        return redirect()->route('anjab.ajuan.index')->with('success', 'Ajuan Jabatan berhasil diajukan');
    }
}
