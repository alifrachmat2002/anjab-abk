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
    <div class="mb-3">
        <a href="{{ route('admin.unit-kerja.create') }}" class="btn btn-primary mb-3"><i
                data-feather="plus"></i>Tambah</a>
        <table class="table table-striped table-bordered">
            <thead>
                <th>No</th>
                <th>Nama</th>
                <th>Unsur</th>
                <th>Aksi</th>
            </thead>
            <tbody>
                @foreach ($unitKerjas as $unitKerja)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $unitKerja->nama }}</td>
                        <td>
                            {{ $unitKerja->unsur->nama }}
                        </td>
                        <td>
                            <a href="{{ route('admin.unit-kerja.edit', ['unitKerja' => $unitKerja->id]) }}"
                                type="button" class="btn btn-warning"><i data-feather="edit"></i></a>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                data-bs-target="#modalHapusUnitKerja{{ $unitKerja->id }}"><i
                                    data-feather="trash"></i></button>
                            <div class="modal fade" tabindex="-1" id="modalHapusUnitKerja{{ $unitKerja->id }}">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Hapus Tugas Tambahan?</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Tugas Tambahan yang sudah dihapus tidak akan bisa dikembalikan lagi.</p>
                                        </div>
                                        <div class="modal-footer">
                                            <form
                                                action="{{ route('admin.unit-kerja.destroy', ['unitKerja' => $unitKerja->id]) }}"
                                                method="POST">
                                                @csrf
                                                @method('delete')
                                                <input type="hidden" value="{{ $unitKerja->id }}">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Tidak</button>
                                                <button type="submit" class="btn btn-primary">Ya</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
