@extends('layouts.main')

@section('container')
    <div class="container mb-3 d-flex flex-column align-items-center p-3">
        <h1 class="display-3 mb-5"> Selamat Datang, {{ Auth::user()->name }}</h1>
        <div class="btn-group">
            @can('make anjab')
                <a href="{{ route('anjab.ajuan.create') }}" class="btn btn-outline-primary">Buat Ajuan Analisis Jabatan Baru</a>
                <a href="{{ route('anjab.ajuan.index') }}" class="btn btn-outline-primary">Lihat Daftar Ajuan Analisis jabatan</a>
            @endcan
            @can('verify anjab')
                <a href="{{ route('anjab.ajuan.index') }}" class="btn btn-outline-primary">Lihat Daftar Ajuan Analisis
                    jabatan</a>
            @endcan
            @if (auth()->user()->can('make abk') || auth()->user()->can('verify abk'))
                <a href="{{ route('abk.ajuans') }}" class="btn btn-outline-primary">Lihat Daftar Ajuan Analisis Beban
                    Kerja</a>
            @endif
        </div>

    </div>
    <div class="row m-3">
        <div class="col">
            <h2>Grafik Jabatan Berdasarkan Jenis</h2>
            <img src="/assets/pie-chart.svg" alt="Grafik" width="250" />
        </div>
        <div class="col">
            <div class="row">
                <div class="col py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="fs-5">Total Jabatan Struktural</h3>
                        <p class="fs-4">27</p>
                    </div>
                    <div class="bg-primary w-100 rounded-pill" style="height: 5px"></div>
                </div>
            </div>
            <div class="row">
                <div class="col py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="fs-5">Total Jabatan Pelaksana</h3>
                        <p class="fs-4">27</p>
                    </div>
                    <div class="bg-danger w-100 rounded-pill" style="height: 5px"></div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="row">
                <div class="col py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="fs-5">Total Jabatan Fungsional</h3>
                        <p class="fs-4">27</p>
                    </div>
                    <div class="bg-warning w-100 rounded-pill" style="height: 5px"></div>
                </div>
            </div>
            <div class="row">
                <div class="col py-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="fs-5">Total Pegawai</h3>
                        <p class="fs-4">27</p>
                    </div>
                    <div class="bg-success w-100 rounded-pill" style="height: 5px"></div>
                </div>
            </div>
        </div>
    </div>
@endsection
