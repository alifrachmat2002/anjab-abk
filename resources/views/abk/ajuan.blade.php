@extends('layouts.main')


@section('container')
    <div class="">
        {{ Breadcrumbs::render('lihat-ajuan-abk', $abk) }}
    </div>
    <div class="card-head mb-3">
        <h1 class="fw-light fs-4 d-inline nav-item">Analisis Beban Kerja Periode {{ $abk->tahun }}</h1>
    </div>
    <div class="card dropdown-divider mb-3"></div>
    <a href="{{ route('abk.ajuans') }}" class="btn btn-primary header1 mb-3"><i data-feather="arrow-left"></i> Kembali</a>
    <table class="table table-striped table-bordered">
        <thead>
            <th class="fw-semibold text-muted">No</th>
            <th class="fw-semibold text-muted">Unit Kerja/Lembaga/Sekolah</th>
            <th class="fw-semibold text-muted">Aksi</th>
            @can('make abk')
                <th class="fw-semibold text-muted">Status</th>
                <th class="fw-semibold text-muted">Catatan Perbaikan</th>
            @endcan

        </thead>
        <tbody>
            @foreach ($abk_unit_kerja as $abk_unit)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <div class="d-flex justify-content-between">
                            <p>{{ $abk_unit->unitKerja[0]->nama }}</p>
                            {{-- create edit and lihat button group --}}
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <a href="{{ route('abk.unitkerja.show', ['abk' => $abk, 'unit_kerja' => $abk_unit->unitKerja->first()->id]) }}"
                                    class="btn btn-outline-primary">Lihat</a>
                                {{-- @if ($abk_unit->)
                                    
                                @endif --}}
                            </div>
                        </div>
                    </td>
                    <td>
                        @if (
                                !empty($abk_unit->latest_verificator()) &&
                                    !empty($abk_unit->next_verificator()) &&
                                    $abk_unit->latest_verificator() != auth()->user()->getRoleNames()->first() &&
                                    $abk_unit->next_verificator()->role->name == auth()->user()->getRoleNames()->first())
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal"
                                        data-bs-target="#modalRevisi{{ $loop->index }}" data-ajuan="{{ $abk_unit->id }}">Revisi</button>
                                </div>
                            @else
                                {{-- if current verificator HAS accepted/rejected the ajuan, show them that they accepted/rejected the ajuan  --}}

                                @if (!empty($abk_unit->latest_verifikasi_by_current_user))
                                    @if ($abk_unit->latest_verifikasi_by_current_user->is_approved)
                                        <p class="badge text-bg-success">Anda sudah menerima Ajuan ini</p>
                                        @if (!empty($abk_unit->next_verificator()))
                                            <div class="alert alert-info w-100">
                                                <div class="alert-heading d-flex">
                                                    <img width="20px" data-feather="clock" class="m-0 p-0 me-2"></img>
                                                    <p class="m-0 p-0">Menunggu Diperiksa</p>
                                                </div>
                                                <hr>
                                                <p class="m-0 p-0">
                                                    {{ $abk_unit->next_verificator()->role->name ?? '' }}
                                                </p>
                                            </div>
                                        @endif
                                    @else
                                        <span class="badge text-bg-danger">Anda sudah merevisi Ajuan ini</span>
                                    @endif
                                @endif
                            @endif
                    </td>
                </tr>
                
                {{-- Modal Revisi Start --}}
                    <div class="modal fade" tabindex="-1" id="modalRevisi{{ $loop->index }}">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Beri Catatan dan Minta Revisi (Semua Jabatan)</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('abk.ajuan.revisi', ['abk' => $abk_unit,'abkparent' => $abk]) }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <input type="text" name="ajuan_id" id="inputAjuan"
                                            value="{{ old('ajuan_id') ?? $abk_unit->id }}">
                                        <label for="catatan" class="form-label">Berikan Catatan tentang ajuan untuk
                                            diperbaiki</label>
                                        <textarea class="form-control @error('catatan') is-invalid @enderror" name="catatan" id="catatan" cols="30"
                                            rows="10"></textarea>
                                        @error('catatan')
                                            <label for="catatan" class="invalid-feedback">{{ $message }}</label>
                                        @enderror
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-secondary" data-bs-dismiss="modal">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                {{-- Modal Revisi End --}}
            @endforeach

        </tbody>
    </table>
    <form action="{{ route('abk.ajuan.parent.revisi', ['abk' => $abk]) }}" method="POST">
        @csrf
        <button class="btn btn-danger mb-3" type="submit"><i class="fa-solid fa-arrow-left"></i>
            Simpan Revisi dan Kembali</button>
    </form>
    
@endsection
