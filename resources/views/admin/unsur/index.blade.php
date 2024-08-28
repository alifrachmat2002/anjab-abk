@extends('layouts.main')

@section('container')
    <div class="mb-3">
        {{-- {{ Breadcrumbs::render('user-dashboard') }} --}}
    </div>
    <div class="card-head mb-3">
        <h1 class="fw-light fs-4 d-inline nav-item">Daftar Unsur</h1>
    </div>
    <hr>
    @if (Session::has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Sukses!</strong> {{ Session::get('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="mb-3">
        <a href="{{ route('admin.unsur.create') }}" class="btn btn-primary mb-3"><i
                data-feather="plus"></i>Tambah</a>
        <table class="table table-striped table-bordered">
            <thead>
                <th>No</th>
                <th>Nama</th>
                <th>Aksi</th>
            </thead>
            <tbody>
                @foreach ($unsurs as $unsur)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $unsur->nama }}</td>
                        <td>
                            <a href="{{ route('admin.unsur.edit', ['unsur' => $unsur->id]) }}"
                                type="button" class="btn btn-warning"><i data-feather="edit"></i></a>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                data-bs-target="#modalHapusUnsur{{ $unsur->id }}"><i
                                    data-feather="trash"></i></button>
                            <div class="modal fade" tabindex="-1" id="modalHapusUnsur{{ $unsur->id }}">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Hapus Unsur?</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Unsur yang sudah dihapus tidak akan bisa dikembalikan lagi.</p>
                                        </div>
                                        <div class="modal-footer">
                                            <form
                                                action="{{ route('admin.unsur.destroy', ['unsur' => $unsur->id]) }}"
                                                method="POST">
                                                @csrf
                                                @method('delete')
                                                <input type="hidden" value="{{ $unsur->id }}">
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
