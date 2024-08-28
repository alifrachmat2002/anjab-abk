@extends('layouts.main')

@section('container')
    <div class="mb-3">
        {{-- {{ Breadcrumbs::render('user-create') }} --}}
        <a href="{{ route('admin.unsur.index') }}" class="btn btn-primary header1"><i data-feather="arrow-left"></i>
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
        <h1 class="fw-light fs-4 d-inline nav-item">Tambah Unsur</h1>
    </div>
    <hr>
    <div class="">
        <form action="{{ route('admin.unsur.update', ['unsur' => $unsur]) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label" for="nama">Unsur</label>
                <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" placeholder=""
                    name="nama" value="{{ $unsur->nama }}">
                @error('nama')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary header1 "><i data-feather="plus"></i>Simpan</button>
        </form>
    </div>
@endsection
