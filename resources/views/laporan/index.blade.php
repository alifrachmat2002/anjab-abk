@extends('layouts.main')

@section('container')
    <div class="mb-3">
        {{ Breadcrumbs::render('laporan') }}
    </div>
    <div class="card-head mb-3">
        <h1 class="fw-light fs-4 d-inline nav-item">Laporan Hasil Anjab ABK</h1>                
    </div>
    <hr>
    <a href="{{ route('home') }}" class="btn btn-primary header1 mb-3"><i data-feather="arrow-left"></i> Kembali</a>
    <div class="">
    <table class="table table-bordered table-striped mt-3 ">
        <thead>
            <tr>
                <th>No</th>
                <th class="">Periode</th>
                <th class="">Diajukan Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ajuans as $ajuan)
                @if ($ajuan->is_approved() == false )
                    @continue
                @endif
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td class="d-flex justify-content-between">
                        <p>{{ $ajuan->tahun }}</p>
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <a href="{{ route('laporan.anjab', ['tahun' => $ajuan->tahun, 'anjab' => $ajuan]) }}" class="btn btn-outline-primary">Lihat Laporan Anjab</a>
                            <a href="" class="btn btn-outline-primary">Lihat Laporan ABK</a>
                            <a href="{{ route('laporan.petajabatan',['anjab' => $ajuan]) }}" class="btn btn-outline-primary">Lihat Peta Jabatan</a>
                        </div>  
                    </td>
                    <td>
                        {{ $ajuan->created_at }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    </div>
@endsection