@extends('layouts.main')

@section('container')
    <div class="mb-3">
        {{-- {{ Breadcrumbs::render('user-create') }} --}}
        <a href="{{ route('admin.unit-kerja.index') }}" class="btn btn-primary header1"><i data-feather="arrow-left"></i>
            Kembali</a>
    </div>
    {{-- alert success closable --}}
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="card-head mb-3">
        <h1 class="fw-light fs-4 d-inline nav-item">Tambah Unit Kerja</h1>
    </div>
    <hr>
    <div class="">
        <form action="{{ route('admin.unit-kerja.update', ['unitKerja' => $unitKerja]) }}" method="POST">
            @csrf
            @method('put')
            <div class="mb-3">
                <label class="form-label" for="nama">Nama Unit Kerja</label>
                <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" placeholder=""
                    name="nama" value="{{ $unitKerja->nama }}">
                @error('nama')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="unsur" class="form-label">Unsur</label>
                <select class="form-select" id="unsur" aria-label="Default select example" name="unsur_id">
                    <option selected>Pilih unsur</option>
                    @foreach ($unsurs as $unsur)
                        <option value="{{ $unsur->id }}" @selected($unsur->id == $unitKerja->unsur->id)>{{ $unsur->nama }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary header1 "><i data-feather="plus"></i>Simpan</button>
        </form>
    </div>
@endsection
