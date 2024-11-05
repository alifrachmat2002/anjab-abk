@extends('layouts.main')

@section('container')
    <div class="">
        {{ Breadcrumbs::render('edit-ajuan-abk-jabatan', $abk, $unit_kerja, $jabatan) }}
    </div>
    <div class="card-head mb-3">
        <h1 class="fw-light fs-4 d-inline nav-item">Edit Analisis Beban Kerja {{ $jabatan->nama }}</h1>
    </div>
    <hr>
    <label for="uraian_tugas_table" class="form-label">Uraian Tugas</label>
    <div class="mb-4" id="uraian_tugas_table">
        <table class=" table table-bordered" id="tabelTugas">
            <thead class="table-light">
                <tr>
                    <th class="fw-semibold text-muted" scope="col ">Uraian Tugas</th>
                    <th class="fw-semibold text-muted" scope="col ">Hasil Kerja</th>
                    <th class="fw-semibold text-muted" scope="col ">Jumlah Hasil Kerja</th>
                    <th class="fw-semibold text-muted" scope="col ">Waktu Penyelesaian Tugas</th>
                    <th class="fw-semibold text-muted" scope="col ">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($abk_jabatan->detailAbk as $detail)
                    <tr>
                        <form
                            action="{{ route('abk.detail_abk.store', [
                                'detail_abk' => $detail->id, 'abk_jabatan' => $abk_jabatan->id,
                            ]) }}"
                            method="POST">
                            @csrf
                            @method('put')
                            <td>
                                {{ $detail->uraianTugasDiajukan->nama_tugas }}
                            </td>
                            <td class="">
                                {{-- create a text input and labelfor "hasil kerja" --}}
                                <input type="text" class="form-control @error('hasil_kerja') is-invalid
                                @enderror" name="hasil_kerja" id="hasil_kerja"
                                    value="{{ $detail->hasil_kerja }}" required>
                                @error('hasil_kerja')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>                                    
                                @enderror
                            </td>
                            <td class="">
                                <input type="number" class="form-control" name="jumlah_hasil_kerja" id="jumlah_hasil_kerja"
                                    value="{{ old('jumlah_hasil_kerja') ?? $detail->jumlah_hasil_kerja }}" required>
                            </td>
                            <td class="d-flex gap-1">
                                    <input type="number" class="form-control" name="waktu_penyelesaian" id="waktu_penyelesaian" value="{{ old('waktu_penyelesaian') ?? '' }}"
                                        placeholder="{{ $detail->waktu_penyelesaian >= 60 ? $detail->waktu_penyelesaian / 60  : $detail->waktu_penyelesaian }}"  required>
                                    <select class="form-select" name="satuan_waktu" id="" required>
                                        <option value="">Pilih satuan waktu</option>
                                        <option value="jam" @selected($detail->waktu_penyelesaian >= 60)>Jam</option>
                                        <option value="menit" @selected($detail->waktu_penyelesaian < 60)>Menit</option>
                                    </select>
                            </td>
                            <td class="text-center">
                                <button type="submit" class="btn btn-primary"><i data-feather="save"></i> Simpan</button>
                            </td>
                        </form>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mb-3">
        <h2 class="fs-5">Perhitungan Jumlah Kebutuhan Pegawai</h2>

        <div class="col-md-6">
            <div class="row">
                <div class="col">Total Waktu Penyelesaian Tugas (WPT)</div>
                <div class="col">{{ $wpt >= 60 ? $wpt / 60  . " jam" : $wpt . " menit" }}</div>
            </div>
            <div class="row">
                <div class="col">Total Waktu Kerja Efektif</div>
                <div class="col">1250 jam</div>
            </div>
            <div class="row">
                <div class="col">Jumlah Kebutuhan Pegawai</div>
                <div class="col">{{ $kebutuhan_pegawai }} orang</div>
            </div>
        </div>
    </div>
    <div class="">
        <a href="{{ route('abk.unitkerja.edit', ['abk' => $abk, 'unit_kerja' => $unit_kerja]) }}" class="btn btn-primary header1"><img src="" alt=""
                data-feather="arrow-left" width="20px"> Kembali</a>
    </div>
@endsection
