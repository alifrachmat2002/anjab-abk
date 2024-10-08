@extends('layouts.main')

@section('container')
    <div class="">
        @if (Route::currentRouteName() == 'anjab.jabatan.edit.2')
            {{ Breadcrumbs::render('isi-detail-jabatan', $jabatan) }}
        @else
            {{ Breadcrumbs::render('edit-ajuan-anjab-jabatan-2', $ajuan, $jabatan) }}
        @endif
    </div>
    <div class="mb-3">
        <h1 class="fw-light fs-4 d-inline nav-item">Ubah Informasi Jabatan | {{ $jabatan->nama }}</h1>
    </div>
    <div class="card dropdown-divider mb-4"></div>
    <div class="mb-3">
        <a href="{{ route('anjab.jabatan.edit.1', $jabatan) }}" class="btn btn-sm btn-secondary align-baseline"><i
                data-feather="chevron-left"></i>Kembali</a>
    </div>
    <div class="alert alert-info alert-dismissible fade show">
        <div class="alert-heading d-flex justify-content-between">
            <div class="d-flex">
                <img width="20px" data-feather="info" class="m-0 p-0 me-2"></img>
                <p class="m-0 p-0">Perhatian</p>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <hr>
        <p class="m-0 p-0">Silahkan isi Kualifikasi Jabatan dengan informasi yang benar.</p>
    </div>
    <div class="" id="kualifikasi">
        {{-- <table class="table 
                table-bordered w-75" id="pendidikan_formal">
            <caption class="caption-top fs-6 text-black">Kualifikasi Jabatan | Pendidikan Formal</caption>
            <thead class="table-primary">
                <th>No</th>
                <th>Jenjang</th>
                <th>Jurusan</th>
                <th>Aksi</th>
            </thead>
            <tbody>
                @foreach ($jabatan->pendidikanFormals as $pendidikan)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $pendidikan->jenjang }}</td>
                        <td>{{ $pendidikan->jurusan }}</td>
                        <td class="d-flex gap-1">
                            <form
                                action="{{ route('anjab.jabatan.pendidikan.delete', ['jabatan' => $jabatan->id, 'pendidikan' => $pendidikan->id]) }}"
                                method="POST">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="kualifikasi_jabatan_id" value="{{ $jabatan->id }}">
                                <button type="submit" class="btn btn-danger">
                                    <img width="20px" data-feather="trash"></img>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <form action="{{ route('anjab.jabatan.pendidikan.store', ['jabatan' => $jabatan->id]) }}"
                        method="POST">
                        @csrf
                        <td></td>
                        <input type="hidden" name="kualifikasi_jabatan_id" value="{{ $jabatan->id }}">
                        <td>
                            <select name="jenjang" class="form-select" id="">
                                <option value="" selected>Pilih Jenjang Pendidikan</option>
                                <option value="SMA">SMA</option>
                                <option value="D3">D-3</option>
                                <option value="D4">D-4</option>
                                <option value="S1">S-1</option>
                                <option value="S2">S-2</option>
                                <option value="S3">S-3</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="jurusan" class="form-control" placeholder="Masukkan Jurusan">
                        </td>
                        <td>
                            <button type="submit" class="btn btn-primary">
                                <i data-feather="plus"></i>
                                Tambah
                            </button>
                        </td>
                    </form>
                </tr>
            </tbody>
        </table> --}}
        <livewire:pendidikan-formal :jabatan="$jabatan" />
        {{-- <table class="table 
                            table-bordered w-75" id="pengalaman">
            <caption class="caption-top">Kualifikasi Jabatan | Pengalaman</caption>
            <thead class="table-primary">
                <th>No</th>
                <th>Nama Pengalaman</th>
                <th>Lama Pengalaman (Tahun)</th>
                <th>Aksi</th>
            </thead>
            <tbody>
                @foreach ($jabatan->pengalamans as $pengalaman)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $pengalaman->nama }}</td>
                        <td>{{ $pengalaman->lama }}</td>
                        <td class="d-flex gap-1">
                            <form
                                action="{{ route('anjab.jabatan.pengalaman.delete', ['jabatan' => $jabatan->id, 'pengalaman' => $pengalaman->id]) }}"
                                method="POST">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="kualifikasi_jabatan_id"
                                    value="{{ $jabatan->id }}">
                                <button type="submit" class="btn btn-danger">
                                    <img width="20px" data-feather="trash"></img>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <form action="{{ route('anjab.jabatan.pengalaman.store', ['jabatan' => $jabatan->id]) }}"
                        method="POST">
                        @csrf
                        <input type="hidden" name="kualifikasi_jabatan_id" value="{{ $jabatan->id }}">
                        <td></td>
                        <td><input name="nama" type="text" class="form-control"
                                placeholder="Masukkan Nama Pengalaman">
                        </td>
                        <td><input name="lama" type="text" class="form-control"
                                placeholder="Masukkan Lama Pengalaman">
                        </td>
                        <td>
                            <button type="submit" class="btn btn-primary">
                                <i data-feather="plus"></i>
                                Tambah
                            </button>
                        </td>
                    </form>
                </tr>
            </tbody>
        </table> --}}
        <livewire:pengalaman-table :jabatan="$jabatan" />
        {{-- <table class="table 
                            table-bordered w-75" id="pelatihan">
            <caption class="caption-top"> Kualifikasi Jabatan | Pelatihan</caption>
            <thead class="table-primary">
                <th>No</th>
                <th>Jenis Pelatihan</th>
                <th>Aksi</th>
            </thead>
            <tbody>
                @foreach ($jabatan->pendidikanPelatihans as $pelatihan)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $pelatihan->nama }}</td>
                        <td class="d-flex gap-1">
                            <form
                                action="{{ route('anjab.jabatan.pelatihan.delete', ['jabatan' => $jabatan->id, 'pelatihan' => $pelatihan->id]) }}"
                                method="POST">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="kualifikasi_jabatan_id"
                                    value="{{ $jabatan->id }}">
                                <button type="submit" class="btn btn-danger">
                                    <img width="20px" data-feather="trash"></img>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <form action="{{ route('anjab.jabatan.pelatihan.store', ['jabatan' => $jabatan->id]) }}"
                        method="POST">
                        @csrf
                        <input type="hidden" name="kualifikasi_jabatan_id" value="{{ $jabatan->id }}">
                        <td></td>
                        <td class="d-flex justify-content-between">
                            <input name="nama" type="text" class="form-control w-50"
                                placeholder="Masukkan Nama Pelatihan">

                        </td>
                        <td>
                            <button type="submit" class="btn btn-primary">
                                <i data-feather="plus"></i>
                                Tambah
                            </button>
                        </td>
                    </form>
                </tr>
            </tbody>
        </table> --}}
        <livewire:pelatihan-table :jabatan="$jabatan" />
    </div>
    <livewire:uraian-tugas-table :jabatan="$jabatan" />
    <livewire:bahan-kerja-table :jabatan="$jabatan" />

    <livewire:perangkat-kerja-table :jabatan="$jabatan" />
    <livewire:tanggungjawab-table :jabatan="$jabatan" />
    <livewire:wewenang-table :jabatan="$jabatan" />
    <livewire:korelasi-jabatan-table :jabatan="$jabatan" />
    <livewire:risiko-table-table :jabatan="$jabatan" />
    <form
        action="
    {{-- Check if current route is edit jabatan inside ajuan or before ajuan is created --}}
    @if (Route::currentRouteName() == 'anjab.jabatan.edit.2') {{ route('anjab.jabatan.update.2', ['jabatan' => $jabatan]) }}
    @elseif (Route::currentRouteName() == 'anjab.ajuan.jabatan.edit.2')
      {{ route('anjab.ajuan.jabatan.update.2', ['jabatan' => $jabatan, 'ajuan' => $ajuan]) }} @endif
"
        method="POST">
        @csrf
        @method('PUT')
        <div class="my-3">
            <hr>
            <p>Kondisi Lingkungan Kerja</p>
            <div class="row">
                <div class="col-6">
                    {{-- every option will be given selected directive --}}
                    <label for="letak" class="form-label">Letak</label>
                    <select name="kondisiLingkunganKerja[letak]" id="letak" class="form-select mb-3">
                        <option value="">Pilih Kondisi Lingkungan Kerja</option>
                        <option value="dalam ruangan" @selected($jabatan->letak == 'dalam ruangan')>Dalam Ruangan</option>
                        <option value="luar ruangan" @selected($jabatan->letak == 'luar ruangan')>Luar Ruangan</option>
                    </select>
                    <label for="penerangan" class="form-label">Penerangan</label>
                    <select name="kondisiLingkunganKerja[penerangan]" id="penerangan" class="form-select mb-3">
                        <option value="">Pilih Kondisi Lingkungan Kerja</option>
                        <option value="redup" @selected($jabatan->penerangan == 'redup')>Redup</option>
                        <option value="terang" @selected($jabatan->penerangan == 'terang')>Terang</option>
                    </select>
                    <label for="suhu" class="form-label text-capitalize">suhu</label>
                    <select name="kondisiLingkunganKerja[suhu]" id="suhu" class="form-select mb-3">
                        <option value="">Pilih Kondisi Lingkungan Kerja</option>
                        <option value="panas" @selected($jabatan->suhu == 'panas')>Panas</option>
                        <option value="dingin" @selected($jabatan->suhu == 'dingin')>Dingin</option>
                    </select>
                    <label for="getaran" class="form-label text-capitalize">getaran</label>
                    <select name="kondisiLingkunganKerja[getaran]" id="getaran" class="form-select mb-3">
                        <option value="">Pilih Kondisi Lingkungan Kerja</option>
                        <option value="rendah" @selected($jabatan->getaran == 'rendah')>Rendah</option>
                        <option value="sedang" @selected($jabatan->getaran == 'sedang')>Sedang</option>
                        <option value="tinggi" @selected($jabatan->getaran == 'tinggi')>Tinggi</option>
                    </select>
                </div>
                <div class="col-6">
                    <label for="suara" class="form-label text-capitalize">suara</label>
                    <select name="kondisiLingkunganKerja[suara]" id="suara" class="form-select mb-3">
                        <option value="">Pilih Kondisi Lingkungan Kerja</option>
                        <option value="bising" @selected($jabatan->suara == 'bising')>Bising</option>
                        <option value="senyap" @selected($jabatan->suara == 'senyap')>Senyap</option>
                    </select>
                    <label for="keadaan_ruangan" class="form-label text-capitalize">keadaan ruangan</label>
                    <select name="kondisiLingkunganKerja[keadaan_ruangan]" id="keadaan_ruangan" class="form-select mb-3">
                        <option value="">Pilih Kondisi Lingkungan Kerja</option>
                        <option value="sesak" @selected($jabatan->keadaan_ruangan == 'sesak')>Sesak</option>
                        <option value="lega" @selected($jabatan->keadaan_ruangan == 'lega')>Lega</option>
                    </select>
                    <label for="udara" class="form-label text-capitalize">udara</label>
                    <select name="kondisiLingkunganKerja[udara]" id="udara" class="form-select mb-3">
                        <option value="">Pilih Kondisi Lingkungan Kerja</option>
                        <option value="kering" @selected($jabatan->udara == 'kering')>Lembab</option>
                        <option value="lembab" @selected($jabatan->udara == 'lembab')>Kering</option>
                    </select>
                    <label for="tempat" class="form-label text-capitalize">tempat</label>
                    <input type="text" class="form-control mb-3" id="tempat" name="kondisiLingkunganKerja[tempat]"
                        value="{{ $jabatan->tempat }}">
                </div>
            </div>
            <hr class="mb-3">
        </div>

        <label for="syarat_jabatan" class="form-label mb-4">Syarat Jabatan</label>
        <div class="" id="syarat_jabatan">
            {{-- create text input for keterampilan --}}
            <label for="keterampilan" class="text-capitalize form-label">keterampilan</label>
            <textarea name="keterampilan" id="keterampilan" rows="4" class="form-control mb-3">{{ $jabatan->keterampilan }}</textarea>
            {{-- create bakat kerja checkbox input, with options using options in /seeder/bakat_kerja.json --}}
            <label for="bakat_kerja" class="form-label">Bakat Kerja</label>
            <div class="mb-4" id="bakat_kerja">
                <div class="row">
                    @foreach ($bakatKerja as $bakat)
                        <div class="col-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="{{ $bakat->id }}"
                                    id="{{ $bakat->nama }}" name="bakatKerja[]" @checked(in_array($bakat->id, $checkedBakatKerja))>
                                <label class="form-check-label" for="{{ $bakat->nama }}">{{ $bakat->nama }}</label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <label for="temperamen" class="form-label">Temperamen Kerja</label>
            <div class="row mb-4" id="temperamen">
                @foreach ($temperamen as $temperamen)
                    <div class="col-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="{{ $temperamen->id }}"
                                id="{{ $temperamen->nama }}" name="temperamenKerja[]" @checked(in_array($temperamen->id, $checkedTemperamenKerja))>
                            <label class="form-check-label"
                                for="{{ $temperamen->nama }}">{{ $temperamen->nama }}</label>
                        </div>
                    </div>
                @endforeach
            </div>
            <label for="minat" class="form-label">Minat Kerja</label>
            <div class="row mb-4" id="minat">
                @foreach ($minatKerja as $minatKerja)
                    <div class="col-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="{{ $minatKerja->id }}"
                                id="{{ $minatKerja->nama }}" name="minatKerja[]" @checked(in_array($minatKerja->id, $checkedMinatKerja))>
                            <label class="form-check-label"
                                for="{{ $minatKerja->nama }}">{{ $minatKerja->nama }}</label>
                        </div>
                    </div>
                @endforeach
            </div>
            <label for="upaya_fisik" class="form-label">Upaya Fisik</label>
            <div class="row mb-4" id="upaya_fisik">
                @foreach ($upayaFisik as $upayaFisik)
                    <div class="col-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="{{ $upayaFisik->id }}"
                                id="{{ $upayaFisik->nama }}" name="upayaFisik[]" @checked(in_array($upayaFisik->id, $checkedUpayaFisik))>
                            <label class="form-check-label"
                                for="{{ $upayaFisik->nama }}">{{ $upayaFisik->nama }}</label>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="my-3">
                <p>Kondisi Fisik</p>
                <div class="row">
                    <div class="col-6">
                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                        <select name="jenis_kelamin" id="jenis_kelamin" class="form-select mb-3">
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="L" @selected($jabatan->jenis_kelamin == 'L')>Laki-Laki</option>
                            <option value="P" @selected($jabatan->jenis_kelamin == 'P')>Perempuan</option>
                        </select>

                        <label for="umur" class="form-label">Umur (Tahun)</label>
                        <input type="number" name="umur" class="form-control mb-3" id="umur"
                            value="{{ $jabatan->umur }}">

                        <label for="tinggi_badan" class="form-label text-capitalize">tinggi badan (sentimeter)</label>
                        <input type="number" name="tinggi_badan" class="form-control mb-3" id="tinggi_badan"
                            value="{{ $jabatan->tinggi_badan }}">
                    </div>
                    <div class="col-6">
                        <label for="berat_badan" class="form-label text-capitalize">berat badan (kilogram)</label>
                        <input type="number" name="berat_badan" class="form-control mb-3" id="berat_badan"
                            value="{{ $jabatan->berat_badan }}">

                        <label for="postur_badan" class="form-label text-capitalize">postur badan</label>
                        <input type="text" name="postur_badan" class="form-control mb-3" id="postur_badan"
                            value="{{ $jabatan->postur_badan }}">

                        <label for="penampilan" class="form-label text-capitalize">penampilan</label>
                        <input type="text" name="penampilan" class="form-control mb-3" id="penampilan"
                            value="{{ $jabatan->penampilan }}">
                    </div>
                </div>
            </div>
            <label for="fungsi_pekerjaan" class="form-label">Fungsi Pekerjaan</label>
            <div class="row mb-4" id="fungsi_pekerjaan">
                @foreach ($fungsiPekerjaan as $fungsiPekerjaan)
                    <div class="col-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="{{ $fungsiPekerjaan->id }}"
                                id="{{ $fungsiPekerjaan->nama }}" name="fungsiPekerjaan[]" @checked(in_array($fungsiPekerjaan->id, $checkedFungsiPekerjaan))>
                            <label class="form-check-label" for="{{ $fungsiPekerjaan->nama }}">
                                {{ $fungsiPekerjaan->kode . ' ' . $fungsiPekerjaan->nama }}</label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn header1 btn-primary"><i data-feather="save"></i>
                Simpan dan Kembali</button>
        </div>
    </form>
@endsection
