@extends('layouts.main')

@section('container')
<div class="">
            {{ Breadcrumbs::render('isi-informasi-umum', $jabatan) }}
</div>
<div class="mb-3">
    <h1 class="fw-light fs-4 d-inline nav-item">Ubah Informasi Jabatan | {{ $jabatan->nama }}</h1>
</div>
<div class="card dropdown-divider mb-4"></div>
<div class="mb-3">
    <a href="{{ route('anjab.ajuan.create') }}" class="btn btn-sm btn-secondary align-baseline"><i
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
        <p class="m-0 p-0">Silahkan Isi Nama Jabatan, Jenis Jabatan, dan Ikhtisar Jabatan dengan informasi yang benar.</p>
</div>
<div class="mb-3">
    <label for="nama" id="nama" class="form-label">Nama Jabatan</label>
    <input type="text" class="form-control" id="nama" value="{{ $jabatan->nama ?? '' }}">
</div>
<div class="mb-3">
    <label for="jenis_jabatan" class="form-label">Jenis Jabatan</label>
    <select class="form-select" name="jenis_jabatan_id" id="jenis_jabatan">
        @foreach ($jenis_jabatan as $jenis)
            <option value="{{ $jenis->id }}" @if ($jenis->id == old('jenis_jabatan_id')) selected @endif>
                {{ $jenis->nama }}</option>
        @endforeach
    </select>
</div>
<div class="mb-3">
    <label for="ikhtisar" class="form-label">Ikhtisar Jabatan</label>
    <textarea class="form-control" placeholder="Masukkan Ikhtisar" id="ikhtisar" style="height:100px"></textarea>
</div>
<label for="prestasi" class="form-label text-capitalize">prestasi</label>
<input type="text" class="form-control " id="prestasi">

<label for="kelas_jabatan" class="form-label text-capitalize">kelas jabatan</label>
<div class="mb-3">
    <select class="form-select" id="kelas_jabatan">
        @for ($i = 1; $i <= 8; ++$i)
            <option value="{{ $i }}">{{ $i }}</option>
        @endfor
    </select>
</div>
<div class="d-flex justify-content-end">
    <a href="{{ route('anjab.jabatan.edit.step-two', $jabatan) }}" class="btn header1 btn-primary"><i data-feather="save"></i> Simpan dan Lanjutkan</a>
</div>
@endsection