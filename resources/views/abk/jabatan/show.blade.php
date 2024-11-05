@extends('layouts.main')

@section('container')
    <div class="">
        {{ Breadcrumbs::render('ajuan-abk-jabatan', $abk, $unit_kerja, $jabatan) }}
    </div>
    <div class="card-head mb-3">
        <h1 class="fw-light fs-4 d-inline nav-item">Analisis Beban Kerja {{ $jabatan->nama }}</h1>
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
                    <th class="fw-semibold text-muted" scope="col ">Waktu Penyelesaian</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($detail_abk as $detail)
                    <tr>
                        <td class="">
                            {{ $detail->uraianTugasDiajukan->nama_tugas }}
                        </td>
                        <td class="">
                            {{ $detail->hasil_kerja }}
                        </td>
                        <td class="">
                            {{ $detail->jumlah_hasil_kerja }}
                        </td>
                        <td class="">
                            {{ $detail->waktu_penyelesaian >= 60 ? $detail->waktu_penyelesaian / 60  . " jam" : $detail->waktu_penyelesaian . " menit" }}
                        </td>
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
        <a  href="{{ route('abk.unitkerja.show', ['unit_kerja' => $unit_kerja, 'abk' => $abk]) }}" class="btn btn-primary header1"><img
                src="" alt="" data-feather="arrow-left" width="20px"> Kembali</a>
    </div>
@endsection
