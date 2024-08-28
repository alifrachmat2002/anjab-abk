<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\JabatanTugasTambahan;
use App\Models\JenisJabatan;
use App\Models\UnitKerja;
use App\Models\Unsur;
use Illuminate\Http\Request;

class SuperadminController extends Controller
{
    // Controller for tugas tambahan start
    public function tugasTambahanIndex()
    {
        $title = 'Dashboard Admin';
        $tutams = JabatanTugasTambahan::all();

        return view('admin.tugas-tambahan.index', compact('title', 'tutams'));
    }

    public function tugasTambahanCreate()
    {
        $title = 'Tambah Tugas Tambahan';
        $unsurs = Unsur::all();
        $jenisJabatans = JenisJabatan::all();

        return view('admin.tugas-tambahan.create', compact('title', 'unsurs', 'jenisJabatans'));
    }

    public function tugasTambahanStore(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|min:4|max:255',
            'kode' => 'required|min:1|max:255',
            'unsur_id' => 'required',
            'jenis_jabatan_id' => 'required',
        ]);
        
        JabatanTugasTambahan::create([
            'nama' => $validated['nama'],
            'unsur_id' => $validated['unsur_id'],
            'jenis_jabatan_id' => $validated['jenis_jabatan_id'],
            'kode' => $validated['kode'],
        ]);

        return redirect()->route('admin.tugas-tambahan.index')->with('success', 'Tugas Tambahan ' . $validated['nama'] . ' berhasil ditambahkan');
    }

    public function tugasTambahanEdit(JabatanTugasTambahan $tugasTambahan)
    {
        $title = 'Edit Tugas Tambahan';
        $unsurs = Unsur::all();
        $jenisJabatans = JenisJabatan::all();
        $tugasTambahan = JabatanTugasTambahan::with('unsur', 'jenisJabatan')->find($tugasTambahan->id);

        return view('admin.tugas-tambahan.edit', compact('title', 'tugasTambahan', 'unsurs', 'jenisJabatans'));
    }

    public function tugasTambahanUpdate(Request $request, JabatanTugasTambahan $tugasTambahan)
    {
        $validated = $request->validate([
            'nama' => 'required|min:4|max:255',
            'kode' => 'required|min:1|max:255',
            'unsur_id' => 'required',
            'jenis_jabatan_id' => 'required',
        ]);

        $tugasTambahan->update([
            'nama' => $validated['nama'],
            'unsur_id' => $validated['unsur_id'],
            'jenis_jabatan_id' => $validated['jenis_jabatan_id'],
            'kode' => $validated['kode'],
        ]);

        return redirect()->route('admin.tugas-tambahan.index')->with('success', 'Tugas Tambahan ' . $validated['nama'] . ' berhasil diubah');
    }

    public function tugasTambahanDestroy(JabatanTugasTambahan $tugasTambahan)
    {
        $tugasTambahan->delete();

        return redirect()->route('admin.tugas-tambahan.index')->with('success', 'Tugas Tambahan berhasil dihapus');
    }
    // Controller for tugas tambahan end

    // Controller for unsur start
    public function unsurIndex()
    {
        $title = 'Dashboard Admin';
        $unsurs = Unsur::all();

        return view('admin.unsur.index', compact('title', 'unsurs'));
    }

    public function unsurCreate()
    {
        $title = 'Tambah Unsur';

        return view('admin.unsur.create', compact('title'));
    }

    public function unsurStore(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|min:3|max:255',
        ]);
        
        Unsur::create([
            'nama' => $validated['nama'],
        ]);

        return redirect()->route('admin.unsur.index')->with('success', 'Unsur ' . $validated['nama'] . ' berhasil ditambahkan');
    }

    public function unsurEdit(Unsur $unsur)
    {
        $title = 'Edit Unsur';
        $unsur = Unsur::find($unsur->id);

        return view('admin.unsur.edit', compact('title', 'unsur'));
    }

    public function unsurUpdate(Request $request, Unsur $unsur)
    {
        $validated = $request->validate([
            'nama' => 'required|min:3|max:255',
        ]);

        $unsur->update([
            'nama' => $validated['nama'],
        ]);

        return redirect()->route('admin.unsur.index')->with('success', 'Unsur ' . $validated['nama'] . ' berhasil diubah');
    }

    public function unsurDestroy(Unsur $unsur)
    {
        $unsur->delete();

        return redirect()->route('admin.unsur.index')->with('success', 'Unsur berhasil dihapus');
    }
    // Controller for unsur end

    // Controller for unit kerja start
    public function unitKerjaIndex()
    {
        $title = 'Dashboard Admin';
        $unitKerjas = UnitKerja::all();

        return view('admin.unit-kerja.index', compact('title', 'unitKerjas'));
    }

    public function unitKerjaCreate()
    {
        $title = 'Tambah Unit Kerja';
        $unsurs = Unsur::all();

        return view('admin.unit-kerja.create', compact('title', 'unsurs'));
    }

    public function unitKerjaStore(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|min:3|max:255',
            'unsur_id' => 'required',
        ]);
        
        UnitKerja::create([
            'nama' => $validated['nama'],
            'unsur_id' => $validated['unsur_id'],
        ]);

        return redirect()->route('admin.unit-kerja.index')->with('success', 'Unit Kerja ' . $validated['nama'] . ' berhasil ditambahkan');
    }

    public function unitKerjaEdit(UnitKerja $unitKerja)
    {
        $title = 'Edit Unit Kerja';
        $unsurs = Unsur::all();
        $unitKerja = UnitKerja::with('unsur')->find($unitKerja->id);

        return view('admin.unit-kerja.edit', compact('title', 'unitKerja', 'unsurs'));
    }

    public function unitKerjaUpdate(Request $request, UnitKerja $unitKerja)
    {
        $validated = $request->validate([
            'nama' => 'required|min:3|max:255',
        ]);

        $unitKerja->update([
            'nama' => $validated['nama'],
        ]);

        return redirect()->route('admin.unit-kerja.index')->with('success', 'Unit Kerja ' . $validated['nama'] . ' berhasil diubah');
    }

    public function unitKerjaDestroy(UnitKerja $unitKerja)
    {
        $unitKerja->delete();

        return redirect()->route('admin.unit-kerja.index')->with('success', 'Unit Kerja berhasil dihapus');
    }
    // Controller for unit kerja end
}
