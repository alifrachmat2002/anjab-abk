@extends('layouts.main')

@section('container')
    <div class="">
        {{ Breadcrumbs::render('edit-ajuan-abk-unitkerja', $abkunit, $unit_kerja) }}
    </div>
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="card-head mb-3">
        <h1 class="fw-light fs-4 d-inline nav-item">Edit Analisis Beban Kerja {{ $abkunit->tahun }} {{ $unit_kerja->nama }}
        </h1>
    </div>
    <div class="card dropdown-divider mb-3"></div>
    <a href="{{ route('abk.ajuans') }}" class="btn btn-primary header1 mb-3"><i data-feather="arrow-left"></i> Kembali</a>
    <div class="">
        <livewire:edit-jabatan-abk-table :tutams="$tutams" :abk="$abkunit" :jabatans="$jabatans" :unit="$unit_kerja" />
        {{-- Modal Tambah Jabatan Start --}}
        <div class="modal fade" tabindex="-1" id="modalTambahJabatan">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambahkan Jabatan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('abk.abk-jabatan.store', ['abk' => $abk]) }}" method="POST"
                            id="jabatanForm">
                            @csrf
                            <div class="mb-3">
                                <input type="integer" name="abk_id" value="{{ $abkunit->id }}" hidden>
                                <input type="integer" name="jabatan_tutam_id" value="{{ old('jabatan_tutam_id') ?? null }}" hidden id="inputTutamId">
                                <label for="nama" class="form-label">Supervisor</label>
                                <input type="text" name="nama_tutam"
                                    class="form-control @error('nama') is-invalid @enderror"
                                    placeholder="Masukkan Nama Jabatan" value="{{ old('nama_tutam')?? null }}" readonly
                                    id="inputNamaTutam">
                                @error('nama')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="jabatan_id" class="form-label">Jabatan
                                    Bawahan</label>
                                <select class="form-select @error('jabatan_id') is-invalid @enderror" name="jabatan_id"
                                    id="jabatan_id">
                                    <option value="">Pilih Jenis Jabatan</option>
                                    @foreach ($jabatans as $jabatan)
                                        <option value="{{ $jabatan->id }}"
                                            @if ($jabatan->id == old('jabatan_id')) selected @endif>
                                            {{ $jabatan->nama }}</option>
                                    @endforeach
                                </select>
                                @error('jabatan_id')
                                    <label for="jabatan_id" class="invalid-feedback">{{ $message }}</label>
                                @enderror
                            </div>
                            <div class="">
                                <button class="btn btn-primary header1" type="submit" id="submitJabatan"><i
                                        class="fa-solid fa-plus"></i>
                                    Tambah
                                    Jabatan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        {{-- Modal Tambah Jabatan End --}}
        <form action="{{ route('abk.ajuan.update', ['abk' => $abkunit]) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary header1 text-white"><i data-feather="save"></i> Simpan Ajuan
                ABK</button>
        </form>
    @endsection
    @section('scripts')
        @if ($errors->any())
            <script>
                const modal = document.getElementById('modalTambahJabatan');

                console.log('error kaka')
                const bootstrapModal = new bootstrap.Modal(modal);
                bootstrapModal.show();
            </script>
        @endif
        <script>
            const modal = document.getElementById('modalTambahJabatan');

            modal.addEventListener('show.bs.modal', event => {
                const btn = event.relatedTarget;

                let tutamId = btn.getAttribute('data-tutam-id');
                let namaTutam = btn.getAttribute('data-nama-tutam');

                const inputTutamId = document.getElementById('inputTutamId');
                const inputNamaTutam = document.getElementById('inputNamaTutam');

                inputTutamId.value = tutamId;
                inputNamaTutam.value = namaTutam;

            })
        </script>
    @endsection
