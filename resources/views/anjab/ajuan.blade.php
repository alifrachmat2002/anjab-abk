@extends('layouts.main')

@section('container')
    <div class="mb-3">
        {{ Breadcrumbs::render('lihat-ajuan-anjab',$ajuan) }}
    </div>
    <div class="card-head mb-3">
        <h1 class="fw-light fs-4 ">Ajuan Analisis Jabatan Periode {{ $ajuan->tahun }}</h1>
    </div>
    <div class="card dropdown-divider mb-3"></div>
    <a href="{{ route('anjab.ajuan.index') }}" class="btn btn-primary header1 mb-3"><i data-feather="arrow-left"></i> Kembali</a>

    <livewire:lihat-jabatan-table :ajuan="$ajuan" :unsurs="$unsurs" :jabatans="$jabatans"/>
    {{-- <livewire:jabatan-table/> --}}


    
@endsection
@section('scripts')
    @if ($errors->any())
        {{-- BANG ERROR --}}
        <script>
            const myModal = document.getElementById('modalRevisi');
            const bootstrapModal = new bootstrap.Modal(myModal);
            bootstrapModal.show();
        </script>
    @endif
@endsection
