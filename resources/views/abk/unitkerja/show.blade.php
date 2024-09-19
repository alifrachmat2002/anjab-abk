@extends('layouts.main')

@section('container')
    <div class="">
        {{ Breadcrumbs::render('lihat-ajuan-abk-unitkerja', $abkunit, $unit_kerja) }}
    </div>
    <div class="card-head mb-3">
        <h1 class="fw-light fs-4 d-inline nav-item">Analisis Beban Kerja {{ $abk->tahun }} {{ $unit_kerja->nama }}</h1>
    </div>
    <div class="card dropdown-divider mb-4"></div>
    <div class="mb-3">
        @if (auth()->user()->roles[0]->name == 'Admin Kepegawaian' || auth()->user()->roles[0]->name == 'Wakil Rektor 2')
            <a href="{{ route('abk.ajuan.show', ['abk' => $abk]) }}" class="btn btn-primary header1"><img src="" alt=""
                    data-feather="arrow-left" width="20px"> Kembali</a>
        @else
            <a href="{{ route('abk.ajuans') }}"  class="btn btn-primary header1"><img src="" alt=""
                    data-feather="arrow-left" width="20px"> Kembali</a>
        @endif
    </div>
    <livewire:lihat-jabatan-abk-table :tutams="$tutams" :abk="$abkunit" :abkparent="$abk" :unit="$unit_kerja" />
@endsection
