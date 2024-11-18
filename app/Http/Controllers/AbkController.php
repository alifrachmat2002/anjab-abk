<?php

namespace App\Http\Controllers;

use App\Models\AbkAnjab;
use App\Models\AbkJabatan;
use App\Models\AbkUnitKerja;
use App\Models\Ajuan;
use App\Models\AjuanUnitKerja;
use App\Models\BahanKerja;
use App\Models\BakatKerjaJabatan;
use App\Models\DetailAbk;
use App\Models\FungsiPekerjaanJabatan;
use App\Models\Jabatan;
use App\Models\JabatanDiajukan;
use App\Models\JabatanDirevisi;
use App\Models\JabatanTugasTambahan;
use App\Models\JabatanUnsur;
use App\Models\KorelasiJabatan;
use App\Models\MinatKerjaJabatan;
use App\Models\PendidikanFormal;
use App\Models\PendidikanPelatihan;
use App\Models\Pengalaman;
use App\Models\PerangkatKerja;
use App\Models\RisikoBahaya;
use App\Models\Role;
use App\Models\RoleVerifikasi;
use App\Models\TanggungJawab;
use App\Models\TemperamenKerjaJabatan;
use App\Models\UnitKerja;
use App\Models\UpayaFisikJabatan;
use App\Models\UraianTugas;
use App\Models\UraianTugasDiajukan;
use App\Models\User;
use App\Models\Verifikasi;
use App\Models\Wewenang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AbkController extends Controller
{
    public function index()
    {
        $title = 'Daftar Ajuan ABK';

        if (auth()->user()->hasRole('Admin Kepegawaian')) {
            $ajuans = Ajuan::isRoot()->where('jenis', 'abk')->get();
            return view('abk.ajuans2', compact('title', 'ajuans'));
        } elseif (auth()->user()->hasRole('Wakil Rektor 2')) {
            $previousVerificatorId = Role::where('name', 'Admin Kepegawaian')->first()->id;
            $ajuans = Ajuan::abk_for_verificator_after($previousVerificatorId);
            return view('abk.ajuans2', compact('title', 'ajuans'));
        }

        if (auth()->user()->hasRole('Operator Unit Kerja')) {
            $ajuans = Ajuan::where('jenis', 'abk')
                ->whereHas('abkUnitKerja', function ($query) {
                    $query->where('unit_kerja_id', auth()->user()->unit_kerja_id);
                })
                ->get();

            return view('abk.ajuans', compact('title', 'ajuans'));
        }

        if (auth()->user()->hasRole('Manajer Unit Kerja') || auth()->user()->hasRole('Manajer Tata Usaha')) {
            $previousVerificatorId = Role::where('name', 'Operator Unit Kerja')->first()->id;
        } elseif (auth()->user()->hasRole('Kepala Unit Kerja')) {
            $previousVerificatorId = Role::where('name', 'Manajer Unit Kerja')->first()->id;
        } elseif (auth()->user()->hasRole('Wakil Dekan 2')) {
            $previousVerificatorId = Role::where('name', 'Manajer Tata Usaha')->first()->id;
        }
        $ajuans = Ajuan::abk_for_verificator_after($previousVerificatorId);

        return view('abk.ajuans', compact('title', 'ajuans'));
    }

    public function createAjuan()
    {
        return view('abk.buat-ajuan', [
            'title' => 'Buat Ajuan ABK',
            'jabatans' => JabatanDiajukan::all(),
        ]);
    }

    public function storeAjuan(Ajuan $anjab)
    {
        // create a parent ABK
        $parentAbk = Ajuan::create([
            'tahun' => now()->year,
            'jenis' => 'abk',
        ]);

        // define who can verify the parent ABK
        RoleVerifikasi::create([
            'ajuan_id' => $parentAbk->id,
            'role_id' => Role::where('name', 'Admin Kepegawaian')->first()->id,
            'is_approved' => false,
        ]);
        RoleVerifikasi::create([
            'ajuan_id' => $parentAbk->id,
            'role_id' => Role::where('name', 'Wakil Rektor 2')->first()->id,
            'is_approved' => false,
        ]);

        // get all unique unit kerja from the jabatan
        $unitKerjas = UnitKerja::all();
        // for each unit kerja,
        // create ABK with current year as 'tahun' and abk as 'jenis'
        foreach ($unitKerjas as $unitKerja) {
            $abk = Ajuan::create([
                'parent_id' => $parentAbk->id,
                'tahun' => now()->year,
                'jenis' => 'abk',
            ]);

            // also create instance of abk_anjab to map which ones are the abk for an anjab
            AbkAnjab::create([
                'abk_id' => $abk->id,
                'anjab_id' => $anjab->id,
            ]);

            // also create instance of abk_unit_kerja to map which unit kerja the abk is for
            AbkUnitKerja::create([
                'abk_id' => $abk->id,
                'unit_kerja_id' => $unitKerja->id,
            ]);

            // also create role verifikasi for each abk based on unsur of the unit kerja
            if (in_array($unitKerja->unsur->nama, ['Lembaga', 'Badan', 'Biro', 'Direktorat', 'Unit Pelaksana Teknis', 'Kantor', 'Satuan Pengawas Internal', 'Dewan Penasihat Universitas'])) {
                // create roles that can verify the ajuan
                RoleVerifikasi::create([
                    'ajuan_id' => $abk->id,
                    'role_id' => Role::where('name', 'Operator Unit Kerja')->first()->id,
                    'is_approved' => false,
                ]);
                RoleVerifikasi::create([
                    'ajuan_id' => $abk->id,
                    'role_id' => Role::where('name', 'Manajer Unit Kerja')->first()->id,
                    'is_approved' => false,
                ]);
                RoleVerifikasi::create([
                    'ajuan_id' => $abk->id,
                    'role_id' => Role::where('name', 'Kepala Unit Kerja')->first()->id,
                    'is_approved' => false,
                ]);
                RoleVerifikasi::create([
                    'ajuan_id' => $abk->id,
                    'role_id' => Role::where('name', 'Admin Kepegawaian')->first()->id,
                    'is_approved' => false,
                ]);
                RoleVerifikasi::create([
                    'ajuan_id' => $abk->id,
                    'role_id' => Role::where('name', 'Wakil Rektor 2')->first()->id,
                    'is_approved' => false,
                ]);
            }

            if ($unitKerja->unsur->nama == 'Fakultas/Sekolah') {
                // create roles that can verify the ajuan
                RoleVerifikasi::create([
                    'ajuan_id' => $abk->id,
                    'role_id' => Role::where('name', 'Operator Unit Kerja')->first()->id,
                    'is_approved' => false,
                ]);
                RoleVerifikasi::create([
                    'ajuan_id' => $abk->id,
                    'role_id' => Role::where('name', 'Manajer Tata Usaha')->first()->id,
                    'is_approved' => false,
                ]);
                RoleVerifikasi::create([
                    'ajuan_id' => $abk->id,
                    'role_id' => Role::where('name', 'Wakil Dekan 2')->first()->id,
                    'is_approved' => false,
                ]);
                RoleVerifikasi::create([
                    'ajuan_id' => $abk->id,
                    'role_id' => Role::where('name', 'Admin Kepegawaian')->first()->id,
                    'is_approved' => false,
                ]);
                RoleVerifikasi::create([
                    'ajuan_id' => $abk->id,
                    'role_id' => Role::where('name', 'Wakil Rektor 2')->first()->id,
                    'is_approved' => false,
                ]);
            }
        }

        return redirect()->route('abk.ajuans');
    }

    public function showAjuan(Ajuan $abk)
    {
        $title = 'Ajuan ABK';
        $abk_unit_kerja = $abk->children;
        $periode = $abk->tahun;

        return view('abk.ajuan', compact('title', 'abk', 'abk_unit_kerja'));
    }

    public function showUnitKerja(Ajuan $abk, UnitKerja $unit_kerja)
    {
        $title = 'Lihat Informasi ABK';
        $abkunit = Ajuan::where('jenis', 'abk')
            ->where('parent_id', $abk->id)
            ->whereHas('abkUnitKerja', function ($query) use ($unit_kerja) {
                $query->where('unit_kerja_id', $unit_kerja->id);
            })
            ->get()
            ->first();
        $tutams = JabatanTugasTambahan::with('AbkJabatan')
            ->where('unsur_id', $unit_kerja->unsur_id)
            ->where('jenis_jabatan_id', '>', '3')
            ->get();
        // $jabatans = $abkunit->abkJabatan;

        return view('abk.unitkerja.show', compact('title', 'abk', 'unit_kerja', 'tutams', 'abk', 'abkunit'));
    }

    public function editUnitKerja(Ajuan $abk, UnitKerja $unit_kerja)
    {
        $title = 'Edit Informasi ABK';
        $abkunit = Ajuan::where('jenis', 'abk')
            ->where('parent_id', $abk->id)
            ->whereHas('abkUnitKerja', function ($query) use ($unit_kerja) {
                $query->where('unit_kerja_id', $unit_kerja->id);
            })
            ->get()
            ->first();
        $tutams = JabatanTugasTambahan::where('unsur_id', $unit_kerja->unsur_id)
            ->where('jenis_jabatan_id', '>', '3')
            ->get();
        $jabatans = JabatanDiajukan::whereHas('jabatanUnsur', function ($query) use ($unit_kerja, $abkunit) {
            $query->where('unsur_id', $unit_kerja->unsur_id)->where('ajuan_id', $abkunit->anjab->first()->id);
        })->get();
        return view('abk.unitkerja.edit', compact('title', 'abkunit', 'unit_kerja', 'tutams', 'jabatans', 'abk'));
    }

    public function storeAbkJabatan(Request $request, Ajuan $abk)
    {
        $validated = $request->validate([
            'jabatan_id' => 'required|exists:jabatan_diajukan,id',
            'jabatan_tutam_id' => 'required|exists:jabatan_tugas_tambahan,id',
        ]);

        $abkJabatan = AbkJabatan::create([
            'abk_id' => $request->abk_id,
            'jabatan_id' => $request->jabatan_id,
            'jabatan_tutam_id' => $request->jabatan_tutam_id,
        ]);

        // also create detail ABK instance for each uraian tugas the jabatan in $abkJabatan
        foreach ($abkJabatan->jabatan->uraianTugas as $uraianTugas) {
            DetailAbk::create([
                'ajuan_id' => $request->abk_id,
                'abk_jabatan_id' => $abkJabatan->id,
                'uraian_tugas_diajukan_id' => $uraianTugas->id,
            ]);
        }

        return redirect()->back()->with('success', 'Jabatan berhasil ditambahkan');
    }

    public function createJabatan(JabatanDiajukan $jabatan)
    {
        return view('abk.jabatan.create', [
            'jabatan' => $jabatan,
            'title' => 'Buat Informasi Beban Kerja',
        ]);
    }

    public function showJabatan(Ajuan $abk, UnitKerja $unit_kerja, AbkJabatan $abk_jabatan)
    {
        $title = 'Lihat Informasi ABK';
        $jabatan = $abk_jabatan->jabatan;
        $detail_abk = DetailAbk::where('abk_jabatan_id', $abk_jabatan->id)->get();
        $wpt = $abk_jabatan->total_waktu_penyelesaian_tugas;
        $kebutuhan_pegawai = $abk_jabatan->kebutuhan_pegawai;

        return view('abk.jabatan.show', compact('title', 'abk', 'jabatan', 'wpt', 'kebutuhan_pegawai', 'detail_abk', 'unit_kerja'));
    }

    public function editJabatan(Ajuan $abk, UnitKerja $unit_kerja, AbkJabatan $abk_jabatan)
    {
        $title = 'Edit Informasi ABK';
        $uraians = $abk_jabatan->jabatan->uraianTugas;
        $jabatan = $abk_jabatan->jabatan;
        $wpt = $abk_jabatan->total_waktu_penyelesaian_tugas;
        $kebutuhan_pegawai = $abk_jabatan->kebutuhan_pegawai;

        return view('abk.jabatan.edit', compact('title', 'unit_kerja', 'abk', 'jabatan', 'uraians', 'wpt', 'abk_jabatan', 'kebutuhan_pegawai'));
    }

    public function storeDetailAbk(Request $request, DetailAbk $detail_abk, AbkJabatan $abk_jabatan)
    {
        // calculate wpt by checking the satuan_waktu from uraian tugas, and converting the waktu_penyelesaian to MINUTES
        $request->validate([
            'hasil_kerja' => 'required|string|max:75',
        ]);
        $waktu_penyelesaian = $request->satuan_waktu == 'jam' ? $request->waktu_penyelesaian * 60 : $request->waktu_penyelesaian;

        $detail_abk->update([
            'hasil_kerja' => $request->hasil_kerja,
            'jumlah_hasil_kerja' => $request->jumlah_hasil_kerja,
            'waktu_penyelesaian' => $waktu_penyelesaian,
        ]);

        // calculate total wpt in MINUTES by summing the multiplication of waktu_penyelesaian and jumlah_hasil_kerja from detail abk
        $total_wpt = DetailAbk::where('abk_jabatan_id', $abk_jabatan->id)->selectRaw('SUM(waktu_penyelesaian * jumlah_hasil_kerja) as total_value')
            ->value('total_value');

        // calculate kebutuhan pegawai by dividing total wpt with numbers of working MINUTES in a year
        $kebutuhan_pegawai_calculated = ceil($total_wpt / 75000);

        // update the total wpt and kebutuhan pegawai in abk_jabatan
        $abk_jabatan->update([
            'total_waktu_penyelesaian_tugas' => $total_wpt,
            'kebutuhan_pegawai' => $kebutuhan_pegawai_calculated,
        ]);

        return redirect()->back()->with('success', 'Detail ABK berhasil disimpan');
    }

    public function updateAjuan(Ajuan $abk)
    {
        Verifikasi::create([
            'ajuan_id' => $abk->id,
            'user_id' => auth()->user()->id,
            'is_approved' => true,
            'catatan' => null,
        ]);

        RoleVerifikasi::where('ajuan_id', $abk->id)
            ->where('role_id', auth()->user()->roles->first()->id)
            ->update(['is_approved' => true]);

        return redirect()->route('abk.ajuans')->with('success', 'Ajuan ABK berhasil disimpan');
    }

    public function abkParentVerifikasi(Ajuan $abk)
    {
        // When user accepts the ajuan, verification instance is created,
        // and is_approved in RoleVerifikasi is set to true
        $userIsWakilRektor = auth()->user()->hasRole('Wakil Rektor 2');
        Verifikasi::create([
            'ajuan_id' => $abk->id,
            'user_id' => auth()->user()->id,
            'is_approved' => true,
            'catatan' => null,
        ]);
        RoleVerifikasi::where('ajuan_id', $abk->id)
            ->where('role_id', auth()->user()->roles->first()->id)
            ->update(['is_approved' => true]);

        if ($userIsWakilRektor) {
            $abk->update(['is_approved' => true]);

            $this->updateJabatanReferensi();
        }

        foreach ($abk->children as $child) {
            Verifikasi::create([
                'ajuan_id' => $child->id,
                'user_id' => auth()->user()->id,
                'is_approved' => true,
                'catatan' => null,
            ]);
            RoleVerifikasi::where('ajuan_id', $child->id)
                ->where('role_id', auth()->user()->roles->first()->id)
                ->update(['is_approved' => true]);

            if ($userIsWakilRektor) {
                $child->update(['is_approved' => true]);
            }
        }

        return redirect()->route('abk.ajuans')->with('success', 'Verifikasi berhasil');
    }

    public function abkVerifikasi(Ajuan $abk)
    {
        // When user accepts the ajuan, verification instance is created,
        // and is_approved in RoleVerifikasi is set to true
        Verifikasi::create([
            'ajuan_id' => $abk->id,
            'user_id' => auth()->user()->id,
            'is_approved' => true,
            'catatan' => null,
        ]);
        RoleVerifikasi::where('ajuan_id', $abk->id)
            ->where('role_id', auth()->user()->roles->first()->id)
            ->update(['is_approved' => true]);

        $abk_parent = $abk->parent;
        if ($abk_parent->approvedAbkCount() == $abk_parent->children->count()) {
            Verifikasi::create([
                'ajuan_id' => $abk_parent->id,
                'user_id' => User::where('name', 'Superadmin')->first()->id,
                'is_approved' => true,
                'catatan' => null,
            ]);
            RoleVerifikasi::where('ajuan_id', $abk_parent->id)
                ->where('role_id', Role::where('name', 'Admin Kepegawaian')->first()->id)
                ->update(['is_approved' => false]);
        }

        return redirect()->route('abk.ajuans')->with('success', 'Verifikasi berhasil');
    }

    // When user rejects the ajuan, verification instance is created,
    // is_approved in RoleVerifikasi from the previous role is set to false
    // and is_approved in RoleVerifikasi from the current role is also set to false
    public function abkRevisi(Ajuan $abk, Request $request)
    {
        $abk = Ajuan::where('jenis', 'abk')->find(request('ajuan_id'));
        $validated = $request->validate([
            'catatan' => 'required|string',
        ]);
        // Create a new verification instance
        $verifikasi = Verifikasi::create([
            'ajuan_id' => $abk->id,
            'user_id' => auth()->user()->id,
            'is_approved' => false,
            'catatan' => $validated['catatan'],
        ]);

        // Get all role ids that can verify the ajuan
        $role_verifikasi = RoleVerifikasi::where('ajuan_id', $abk->id)->get();
        $verificatorIds = $role_verifikasi->pluck('role_id')->toArray();

        // Get the role id of the previous verificator
        if ($abk->parent_id == null) {
            $previousVerificatorRoleId = $verificatorIds[array_search(auth()->user()->roles->first()->id, $verificatorIds) - 1];
        } else {
            $previousVerificatorRoleId = $verificatorIds[array_search(auth()->user()->roles->first()->id, $verificatorIds) - 1];
        }

        // Set is_approved in RoleVerifikasi from the previous role to false
        RoleVerifikasi::where('ajuan_id', $abk->id)
            ->where('role_id', $previousVerificatorRoleId)
            ->update(['is_approved' => false]);

        // Set is_approved in RoleVerifikasi from the current role to false
        RoleVerifikasi::where('ajuan_id', $abk->id)
            ->where('role_id', auth()->user()->roles->first()->id)
            ->update(['is_approved' => false]);

        // create JabatanDirevisi instance to store all the jabatans
        foreach ($abk->abkJabatan as $abk_jabatan) {
            JabatanDirevisi::create([
                'verifikasi_id' => $verifikasi->id,
                'abk_jabatan_id' => $abk_jabatan->id,
                'catatan' => $validated['catatan'],
            ]);
        }

        if (request()->has('abkparent')) {
            return redirect()
                ->route('abk.ajuan.show', ['abk' => request('abkparent')])
                ->with('success', 'Revisi berhasil');
        }

        return redirect()->route('abk.ajuans')->with('success', 'Revisi berhasil');
    }

    public function abkMakeCatatan(AbkJabatan $abk_jabatan, Request $request)
    {
        $validated = $request->validate([
            'catatan' => 'string',
        ]);
        JabatanDirevisi::create([
            'abk_jabatan_id' => $abk_jabatan->id,
            'catatan' => $validated['catatan'],
        ]);

        return redirect()->back()->with('success', 'Catatan berhasil disimpan');
    }

    public function abkRevisiJabatan(Ajuan $abk, Request $request)
    {
        // Create a new verification instance
        $verifikasi = Verifikasi::create([
            'ajuan_id' => $abk->id,
            'user_id' => auth()->user()->id,
            'is_approved' => false,
        ]);

        // Get all role ids that can verify the ajuan
        $role_verifikasi = RoleVerifikasi::where('ajuan_id', $abk->id)->get();
        $verificatorIds = $role_verifikasi->pluck('role_id')->toArray();

        // Get the role id of the previous verificator
        if ($abk->parent_id == null) {
            $previousVerificatorRoleId = $verificatorIds[array_search(auth()->user()->roles->first()->id, $verificatorIds) - 1];
        } else {
            $previousVerificatorRoleId = $verificatorIds[array_search(auth()->user()->roles->first()->id, $verificatorIds) - 1];
        }

        // Set is_approved in RoleVerifikasi from the previous role to false
        RoleVerifikasi::where('ajuan_id', $abk->id)
            ->where('role_id', $previousVerificatorRoleId)
            ->update(['is_approved' => false]);

        // Set is_approved in RoleVerifikasi from the current role to false
        RoleVerifikasi::where('ajuan_id', $abk->id)
            ->where('role_id', auth()->user()->roles->first()->id)
            ->update(['is_approved' => false]);

        // update JabatanDirevisi instance to store all the jabatans that require revisions
        $jabatanDirevisi = JabatanDirevisi::where('verifikasi_id', null)
            ->whereNotNull('abk_jabatan_id')
            ->update(['verifikasi_id' => $verifikasi->id]);

        return redirect()->route('abk.ajuans')->with('success', 'Revisi berhasil');
    }

    public function abkParentRevisi(Ajuan $abk)
    {
        $previousVerificatorRoleId = $abk->latest_verifikasi()->user->roles->first()->id;
        $verifikasi = Verifikasi::create([
            'ajuan_id' => $abk->id,
            'user_id' => auth()->user()->id,
            'is_approved' => false,
        ]);
        // Set is_approved in RoleVerifikasi from the previous role to false
        RoleVerifikasi::where('ajuan_id', $abk->id)
            ->where('role_id', $previousVerificatorRoleId)
            ->update(['is_approved' => false]);

        // Set is_approved in RoleVerifikasi from the current role to false
        RoleVerifikasi::where('ajuan_id', $abk->id)
            ->where('role_id', auth()->user()->roles->first()->id)
            ->update(['is_approved' => false]);

        return redirect()->route('abk.ajuans')->with('success', 'Revisi berhasil');
    }

    public function getJabatanABK()
    {
        $ajuans = AbkJabatan::where('abk_id', request('ajuan'))
            ->with(['jabatan', 'jabatanTugasTambahan'])
            ->get();
        $response = [];
        foreach ($ajuans as $ajuan) {
            $response[] = [
                'jabatan' => $ajuan->jabatan->nama,
                'jabatan_tutam' => $ajuan->jabatanTugasTambahan->nama,
            ];
        }
        return response()->json(['jabatans' => $response]);
    }

    public function getJabatanABKParent()
    {
        $parent = Ajuan::find(request('ajuan'))->children->pluck('id');
        $ajuans = AbkJabatan::whereIn('abk_id', $parent)
            ->with(['jabatan', 'jabatanTugasTambahan'])
            ->get();
        $response = [];
        foreach ($ajuans as $ajuan) {
            $response[] = [
                'jabatan' => $ajuan->jabatan->nama,
                'jabatan_tutam' => $ajuan->jabatanTugasTambahan->nama,
                'unit_kerja' => $ajuan->abk->unitKerja[0]->nama,
            ];
        }
        return response()->json(['jabatans' => $response]);
    }

    private function updateJabatanReferensi()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        PendidikanFormal::truncate();
        PendidikanPelatihan::truncate();
        Pengalaman::truncate();
        UraianTugas::truncate();
        BahanKerja::truncate();
        PerangkatKerja::truncate();
        TanggungJawab::truncate();
        Wewenang::truncate();
        KorelasiJabatan::truncate();
        RisikoBahaya::truncate();
        JabatanUnsur::truncate();
        BakatKerjaJabatan::truncate();
        TemperamenKerjaJabatan::truncate();
        MinatKerjaJabatan::truncate();
        FungsiPekerjaanJabatan::truncate();
        UpayaFisikJabatan::truncate();

        Jabatan::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $latestAjuanId = JabatanDiajukan::max('ajuan_id');
        $jabatanDiajukan = JabatanDiajukan::with([
            'pendidikanFormal',
            'pendidikanPelatihan',
            'pengalaman',
            'uraianTugas',
            'bahanKerja',
            'perangkatKerja',
            'tanggungJawab',
            'wewenang',
            'korelasiJabatan',
            'risikoBahaya',
            'jabatanUnsur',
            'bakatKerja',
            'temperamenKerja',
            'minatKerja',
            'fungsiPekerjaan',
            'upayaFisik',
        ])->where('ajuan_id', $latestAjuanId)->get();

        foreach ($jabatanDiajukan as $jabatanDiajukan) {
            $jabatan = Jabatan::create([
                'parent_id' => $jabatanDiajukan->parent_id,
                'nama' => $jabatanDiajukan->nama,
                'kode' => $jabatanDiajukan->kode,
                'ikhtisar' => $jabatanDiajukan->ikhtisar,
                'prestasi' => $jabatanDiajukan->prestasi,
                'jenis_kelamin' => $jabatanDiajukan->jenis_kelamin,
                'umur' => $jabatanDiajukan->umur,
                'tinggi_badan' => $jabatanDiajukan->tinggi_badan,
                'postur_badan' => $jabatanDiajukan->postur_badan,
                'penampilan' => $jabatanDiajukan->penampilan,
                'keterampilan' => $jabatanDiajukan->keterampilan,
                'berat_badan' => $jabatanDiajukan->berat_badan,
                'getaran' => $jabatanDiajukan->getaran,
                'suhu' => $jabatanDiajukan->suhu,
                'suara' => $jabatanDiajukan->suara,
                'penerangan' => $jabatanDiajukan->penerangan,
                'letak' => $jabatanDiajukan->letak,
                'tempat' => $jabatanDiajukan->tempat,
                'udara' => $jabatanDiajukan->udara,
                'keadaan_ruangan' => $jabatanDiajukan->keadaan_ruangan,
                'uraian_tugas' => $jabatanDiajukan->uraian_tugas,
                'unsur_id' => $jabatanDiajukan->unsur_id,
            ]);

            if ($jabatanDiajukan->pendidikanFormal) {
                foreach ($jabatanDiajukan->pendidikanFormal as $pendidikanFormal) {
                    PendidikanFormal::create([
                        'jabatan_id' => $jabatan->id,
                        'jenjang' => $pendidikanFormal->jenjang,
                        'jurusan' => $pendidikanFormal->jurusan,
                    ]);
                }
            }

            if ($jabatanDiajukan->pendidikanPelatihan) {
                foreach ($jabatanDiajukan->pendidikanPelatihan as $pendidikanPelatihan) {
                    PendidikanPelatihan::create([
                        'jabatan_id' => $jabatan->id,
                        'nama' => $pendidikanPelatihan->nama,
                    ]);
                }
            }

            if ($jabatanDiajukan->pengalaman) {
                foreach ($jabatanDiajukan->pengalaman as $pengalaman) {
                    Pengalaman::create([
                        'jabatan_id' => $jabatan->id,
                        'nama' => $pengalaman->nama,
                        'lama' => $pengalaman->lama,
                    ]);
                }
            }

            if ($jabatanDiajukan->uraianTugas) {
                foreach ($jabatanDiajukan->uraianTugas as $uraianTugas) {
                    UraianTugas::create([
                        'jabatan_id' => $jabatan->id,
                        'nama_tugas' => $uraianTugas->nama_tugas,
                    ]);
                }
            }

            if ($jabatanDiajukan->bahanKerja) {
                foreach ($jabatanDiajukan->bahanKerja as $bahanKerja) {
                    BahanKerja::create([
                        'jabatan_id' => $jabatan->id,
                        'nama' => $bahanKerja->nama,
                        'penggunaan' => $bahanKerja->penggunaan,
                    ]);
                }
            }

            if ($jabatanDiajukan->perangkatKerja) {
                foreach ($jabatanDiajukan->perangkatKerja as $perangkatKerja) {
                    PerangkatKerja::create([
                        'jabatan_id' => $jabatan->id,
                        'nama' => $perangkatKerja->nama,
                        'penggunaan' => $perangkatKerja->penggunaan,
                    ]);
                }
            }

            if ($jabatanDiajukan->tanggungJawab) {
                foreach ($jabatanDiajukan->tanggungJawab as $tanggungJawab) {
                    TanggungJawab::create([
                        'jabatan_id' => $jabatan->id,
                        'nama' => $tanggungJawab->nama,
                    ]);
                }
            }

            if ($jabatanDiajukan->wewenang) {
                foreach ($jabatanDiajukan->wewenang as $wewenang) {
                    Wewenang::create([
                        'jabatan_id' => $jabatan->id,
                        'nama' => $wewenang->nama,
                    ]);
                }
            }

            if ($jabatanDiajukan->korelasiJabatan) {
                foreach ($jabatanDiajukan->korelasiJabatan as $korelasiJabatan) {
                    KorelasiJabatan::create([
                        'jabatan_id' => $jabatan->id,
                        'jabatan_relasi_id' => $korelasiJabatan->jabatan_relasi_id,
                        'dalam_hal' => $korelasiJabatan->dalam_hal,
                    ]);
                }
            }

            if ($jabatanDiajukan->risikoBahaya) {
                foreach ($jabatanDiajukan->risikoBahaya as $risikoBahaya) {
                    RisikoBahaya::create([
                        'jabatan_id' => $jabatan->id,
                        'bahaya_fisik' => $risikoBahaya->bahaya_fisik,
                        'penyebab' => $risikoBahaya->penyebab,
                    ]);
                }
            }

            if ($jabatanDiajukan->jabatanUnsur) {
                foreach ($jabatanDiajukan->jabatanUnsur as $jabatanUnsur) {
                    JabatanUnsur::create([
                        'jabatan_id' => $jabatan->id,
                        'unsur_id' => $jabatanUnsur->unsur_id,
                    ]);
                }
            }

            if ($jabatanDiajukan->bakatKerja) {
                foreach ($jabatanDiajukan->bakatKerja as $bakatKerja) {
                    BakatKerjaJabatan::create([
                        'jabatan_id' => $jabatan->id,
                        'bakat_kerja_id' => $bakatKerja->bakat_kerja_id,
                    ]);
                }
            }

            if ($jabatanDiajukan->temperamenKerja) {
                foreach ($jabatanDiajukan->temperamenKerja as $temperamenKerja) {
                    TemperamenKerjaJabatan::create([
                        'jabatan_id' => $jabatan->id,
                        'temperamen_kerja_id' => $temperamenKerja->temperamen_kerja_id,
                    ]);
                }
            }

            if ($jabatanDiajukan->minatKerja) {
                foreach ($jabatanDiajukan->minatKerja as $minatKerja) {
                    MinatKerjaJabatan::create([
                        'jabatan_id' => $jabatan->id,
                        'minat_kerja_id' => $minatKerja->minat_kerja_id,
                    ]);
                }
            }

            if ($jabatanDiajukan->fungsiPekerjaan) {
                foreach ($jabatanDiajukan->fungsiPekerjaan as $fungsiPekerjaan) {
                    FungsiPekerjaanJabatan::create([
                        'jabatan_id' => $jabatan->id,
                        'fungsi_pekerjaan_id' => $fungsiPekerjaan->fungsi_pekerjaan_id,
                    ]);
                }
            }

            if ($jabatanDiajukan->upayaFisik) {
                foreach ($jabatanDiajukan->upayaFisik as $upayaFisik) {
                    UpayaFisikJabatan::create([
                        'jabatan_id' => $jabatan->id,
                        'upaya_fisik_id' => $upayaFisik->upaya_fisik_id,
                    ]);
                }
            }
        }
    }
}
