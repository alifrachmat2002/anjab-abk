@extends('layouts.main')

@section('container')
    <div class="mb-3">
        {{-- {{ Breadcrumbs::render('user-dashboard') }} --}}
    </div>
    <div class="card-head mb-3">
        <h1 class="fw-light fs-4 d-inline nav-item">Daftar Tugas Tambahan</h1>
    </div>
    <hr>
    @if (Session::has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Sukses!</strong> {{ Session::get('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <a href="{{ route('admin.unit-kerja.create') }}" class="btn btn-primary mb-3"><i data-feather="plus"></i>Tambah</a>
    <livewire:admin.unit-kerja-table />
@endsection
