<?php

namespace App\Http\Controllers;

use App\Models\BakatKerja;
use App\Models\Jabatan;
use App\Models\KondisiLingkunganKerja;
use App\Models\MinatKerja;
use App\Models\SyaratBakat;
use App\Models\SyaratFungsi;
use App\Models\SyaratJabatan;
use App\Models\SyaratMinat;
use App\Models\SyaratTemperamen;
use App\Models\SyaratUpaya;
use App\Models\UnitKerja;
use App\Models\UpayaFisik;
use App\Models\JenisJabatan;
use Illuminate\Http\Request;
use App\Models\FungsiPekerjaan;
use App\Models\TemperamenKerja;
use App\Http\Requests\CreateJabatanRequest;
use App\Models\Ajuan;
use App\Models\BakatKerjaJabatanDiajukan;
use App\Models\FungsiPekerjaanJabatanDiajukan;
use App\Models\JabatanDiajukan;
use App\Models\JabatanDirevisi;
use App\Models\JabatanUnsurDiajukan;
use App\Models\MinatKerjaJabatanDiajukan;
use App\Models\TemperamenKerjaJabatanDiajukan;
use App\Models\Unsur;
use App\Models\UpayaFisikJabatanDiajukan;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Illuminate\Support\Facades\Route;

class JabatanController extends Controller
{
    public function index()
    {
        $title = 'Data Jabatan';
        $jabatans = Jabatan::all();
        $jenisJabatan = JenisJabatan::all();
        $unitKerjas = UnitKerja::all();
        $buttons = ['tambah-jabatan-bawahan', 'ubah-informasi-jabatan'];

        return view('anjab.jabatan', compact('title', 'jabatans', 'jenisJabatan', 'unitKerjas', 'buttons'));
    }

    public function show(Ajuan $ajuan, Jabatan $jabatan)
    {
        return view('anjab.jabatan.show', [
            'title' => 'Form Informasi Jabatan',
            'ajuan' => $ajuan,
            'jabatan' => $jabatan,
            'bakat_kerjas' => BakatKerja::all(),
            'unit_kerjas' => UnitKerja::all(),
            'jenis_jabatan' => JenisJabatan::all(),
            'temperamens' => TemperamenKerja::all(),
            'upaya_fisiks' => UpayaFisik::all(),
            'fungsi_pekerjaans' => FungsiPekerjaan::all(),
        ]);
    }

    public function store(CreateJabatanRequest $request)
    {
        // don't create jabatan if it already exists
        // instead, add the unsurs that are not already in the database
        // if jabatan exists, get the instance
        if (JabatanDiajukan::where('nama', $request['nama'])->where('ajuan_id', null)->exists()) {
            // get jabatan instance of the same name
            $jabatan = JabatanDiajukan::where('nama', $request['nama'])->where('ajuan_id', null)->first();
        } else {
            // if jabatan instance does not exist yet, create a new one
            $jabatan = JabatanDiajukan::create($request->all());
        }

        // based on the input, create instances of JabatanUnsurDiajukan
        // if user selected all unsur, create instances for all unsurs
        if ($request['unsur_id'] == 'Semua Unsur') {
            $unsurs = Unsur::all();
            foreach ($unsurs as $unsur) {
                // if the unsurs already exists in the database, skip
                if ($jabatan->jabatanUnsur?->where('unsur_id', $unsur->id)->count() > 0) {
                    continue;
                }
                JabatanUnsurDiajukan::create([
                    'jabatan_diajukan_id' => $jabatan->id,
                    'unsur_id' => $unsur->id,
                ]);
            }
        } else {
            // if user selected specific unsurs, create instances for those unsurs
            foreach ($request['unsur_id'] as $unsurId) {
                // if the unsurs already exists in the database, skip
                if ($jabatan->jabatanUnsur->where('unsur_id', $unsurId)->count() > 0) {
                    continue;
                }
                JabatanUnsurDiajukan::create([
                    'jabatan_diajukan_id' => $jabatan->id,
                    'unsur_id' => $unsurId,
                ]);
            }
        }

        return back()->with('success', 'Data Jabatan ' . $jabatan->nama . ' berhasil Ditambahkan');
    }

    public function edit(JabatanDiajukan $jabatan)
    {
        $title = 'Form Informasi Jabatan';
        $bakat_kerjas = BakatKerja::all();
        $unit_kerjas = UnitKerja::all();
        $jenis_jabatan = JenisJabatan::all();
        $temperamens = TemperamenKerja::all();
        $upaya_fisiks = UpayaFisik::all();
        $fungsi_pekerjaans = FungsiPekerjaan::all();

        return view('anjab.jabatan.edit', compact('title', 'jabatan', 'bakat_kerjas', 'unit_kerjas', 'jenis_jabatan', 'temperamens', 'upaya_fisiks', 'fungsi_pekerjaans'));
    }

    public function edit1(JabatanDiajukan $jabatan)
    {
        $title = 'Form Informasi Jabatan';
        $bakat_kerjas = BakatKerja::all();
        $unit_kerjas = UnitKerja::all();
        $jenis_jabatan = JenisJabatan::all();
        $temperamens = TemperamenKerja::all();
        $upaya_fisiks = UpayaFisik::all();
        $fungsi_pekerjaans = FungsiPekerjaan::all();

        return view('anjab.jabatan.edit.step-1', compact('title', 'jabatan', 'bakat_kerjas', 'unit_kerjas', 'jenis_jabatan', 'temperamens', 'upaya_fisiks', 'fungsi_pekerjaans'));
    }

    public function update1(Request $request, JabatanDiajukan $jabatan)
    {
        $jabatan->update($request->all());

        return redirect()
            ->route('anjab.jabatan.edit.2', ['jabatan' => $jabatan])
            ->with('success', 'Data Jabatan berhasil Diubah');
    }

    public function edit2(JabatanDiajukan $jabatan)
    {
        $title = 'Form Informasi Jabatan';
        $jabatans = JabatanDiajukan::orderBy('nama')->get();
        $bakatKerja = BakatKerja::all();
        $unitKerja = UnitKerja::all();
        $jenisJabatan = JenisJabatan::all();
        $temperamen = TemperamenKerja::all();
        $upayaFisik = UpayaFisik::all();
        $fungsiPekerjaan = FungsiPekerjaan::all();
        $minatKerja = MinatKerja::all();

        // get necessary data for checkboxes
        // checkboxes are checked if the data is found in the database
        $checkedBakatKerja = BakatKerjaJabatanDiajukan::where('jabatan_diajukan_id', $jabatan->id)
            ->get()
            ->pluck('bakat_kerja_id')
            ->toArray();
        $checkedTemperamenKerja = TemperamenKerjaJabatanDiajukan::where('jabatan_diajukan_id', $jabatan->id)
            ->get()
            ->pluck('temperamen_kerja_id')
            ->toArray();
        $checkedMinatKerja = MinatKerjaJabatanDiajukan::where('jabatan_diajukan_id', $jabatan->id)
            ->get()
            ->pluck('minat_kerja_id')
            ->toArray();
        $checkedUpayaFisik = UpayaFisikJabatanDiajukan::where('jabatan_diajukan_id', $jabatan->id)
            ->get()
            ->pluck('upaya_fisik_id')
            ->toArray();
        $checkedFungsiPekerjaan = FungsiPekerjaanJabatanDiajukan::where('jabatan_diajukan_id', $jabatan->id)
            ->get()
            ->pluck('fungsi_pekerjaan_id')
            ->toArray();

        return view('anjab/jabatan/edit/step-2', compact('title', 'jabatans', 'jabatan', 'bakatKerja', 'unitKerja', 'jenisJabatan', 'temperamen', 'upayaFisik', 'fungsiPekerjaan', 'minatKerja', 'checkedBakatKerja', 'checkedTemperamenKerja', 'checkedMinatKerja', 'checkedUpayaFisik', 'checkedFungsiPekerjaan'));
    }

    public function update2(Request $request, JabatanDiajukan $jabatan)
    {
        // loop through $request->input('kondisiLingkunganKerja') and put them all inside $kondisi
        $kondisi = [];
        foreach ($request->input('kondisiLingkunganKerja') as $key => $value) {
            $kondisi[$key] = $value;
            $jabatan->$key = $value;
            $jabatan->save();
        }

        $jabatan->keterampilan = $request->input('keterampilan');
        $jabatan->save();

        // UPDATING SYARAT BAKAT
        // delete SyaratBakat instances with syarat_jabatan_id = $syaratJabatan->id
        // loop through $request->input('bakatKerja') and create new SyaratBakat instances
        // this is done so that when user uncheck an input, the data is deleted from the database
        BakatKerjaJabatanDiajukan::where('jabatan_diajukan_id', $jabatan->id)->delete();
        $bakatKerja = $request->input('bakatKerja');
        if ($bakatKerja) {
            foreach ($bakatKerja as $bakatKerjaId) {
                BakatKerjaJabatanDiajukan::create([
                    'jabatan_diajukan_id' => $jabatan->id,
                    'bakat_kerja_id' => $bakatKerjaId,
                ]);
            }
        }

        // UPDATING SYARAT TEMPERAMEN
        // delete SyaratTemperamen instances with syarat_jabatan_id = $syaratJabatan->id
        // loop through $request->input('temperamenKerja') and create new SyaratTemperamen instances
        // this is done so that when user uncheck an input, the data is deleted from the database
        TemperamenKerjaJabatanDiajukan::where('jabatan_diajukan_id', $jabatan->id)->delete();
        $temperamenKerja = $request->input('temperamenKerja');
        if ($temperamenKerja) {
            foreach ($temperamenKerja as $temperamenKerjaId) {
                TemperamenKerjaJabatanDiajukan::create([
                    'jabatan_diajukan_id' => $jabatan->id,
                    'temperamen_kerja_id' => $temperamenKerjaId,
                ]);
            }
        }

        // UPDATING SYARAT MINAT
        // delete MinatKerja instances with syarat_jabatan_id = $syaratJabatan->id
        // loop through $request->input('minatKerja') and create new MinatKerja instances
        // this is done so that when user uncheck an input, the data is deleted from the database
        MinatKerjaJabatanDiajukan::where('jabatan_diajukan_id', $jabatan->id)->delete();
        $minatKerja = $request->input('minatKerja');
        if ($minatKerja) {
            foreach ($minatKerja as $minatKerjaId) {
                MinatKerjaJabatanDiajukan::create([
                    'jabatan_diajukan_id' => $jabatan->id,
                    'minat_kerja_id' => $minatKerjaId,
                ]);
            }
        }

        // UPDATING SYARAT UPAYA
        // delete UpayaFisik instances with jabatan_diajukan_id = $syaratJabatan->id
        // loop through $request->input('upayaFisik') and create new UpayaFisik instances
        // this is done so that when user uncheck an input, the data is deleted from the database
        UpayaFisikJabatanDiajukan::where('jabatan_diajukan_id', $jabatan->id)->delete();
        $upayaFisik = $request->input('upayaFisik');
        if ($upayaFisik) {
            foreach ($upayaFisik as $upayaFisikId) {
                UpayaFisikJabatanDiajukan::create([
                    'jabatan_diajukan_id' => $jabatan->id,
                    'upaya_fisik_id' => $upayaFisikId,
                ]);
            }
        }

        // Updating Kondisi Fisik
        $jabatan->jenis_kelamin = $request->input('jenis_kelamin');
        $jabatan->umur = $request->input('umur');
        $jabatan->tinggi_badan = $request->input('tinggi_badan');
        $jabatan->berat_badan = $request->input('berat_badan');
        $jabatan->postur_badan = $request->input('postur_badan');
        $jabatan->penampilan = $request->input('penampilan');
        $jabatan->save();

        // UPDATING SYARAT FUNGSI
        // delete FungsiPekerjaan instances with jabatan_diajukan_id = $syaratJabatan->id
        // loop through $request->input('fungsiPekerjaan') and create new FungsiPekerjaan instances
        // this is done so that when user uncheck an input, the data is deleted from the database
        FungsiPekerjaanJabatanDiajukan::where('jabatan_diajukan_id', $jabatan->id)->delete();
        $fungsiPekerjaan = $request->input('fungsiPekerjaan');
        if ($fungsiPekerjaan) {
            foreach ($fungsiPekerjaan as $fungsiPekerjaanId) {
                FungsiPekerjaanJabatanDiajukan::create([
                    'jabatan_diajukan_id' => $jabatan->id,
                    'fungsi_pekerjaan_id' => $fungsiPekerjaanId,
                ]);
            }
        }

        // return redirect()->route('anjab.ajuan.create')->with('success', 'Data Jabatan berhasil Diubah');
        return redirect(route('anjab.ajuan.create'))->with('success', 'Data Jabatan ' . $jabatan->nama . ' berhasil Diubah');
    }

    public function anjabMakeCatatan(Request $request, JabatanDiajukan $jabatan) {
    // dd(url()->previous() . "#form $jabatan->nama .$jabatan->id");
      JabatanDirevisi::create([
            'jabatan_diajukan_id' => $jabatan->id,
            'catatan' => request('catatan')
        ]);

        return redirect()->back()->with('success', 'Catatan berhasil ditambahkan');
    }
}
