<div class="modal fade" tabindex="-1" id="modalJabatan">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambahkan Jabatan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('anjab.jabatan.store') }}" method="POST" id="jabatanForm">
                    @csrf
                    <input type="hidden" name="parent_id" id="parent_id" value="{{ old('parent_id') }}">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Jabatan</label>
                        <input type="text" class="form-control @error('nama') is-invalid @enderror"
                            id="nama" name="nama" placeholder="Masukkan Nama Jabatan"
                            value="{{ old('nama') }}">
                        @error('nama')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="jenis_jabatan_id" class="form-label">Jenis Jabatan</label>
                        <select class="form-select @error('jenis_jabatan_id') is-invalid @enderror" name="jenis_jabatan_id" id="jenis_jabatan_id">
                            <option value="">Pilih Jenis Jabatan</option>
                            @foreach ($jenisJabatan as $jenis)
                                <option value="{{ $jenis->id }}" @if ($jenis->id == old('jenis_jabatan_id')) selected @endif>
                                    {{ $jenis->nama }}</option>
                            @endforeach
                        </select>
                        @error('jenis_jabatan_id')
                            <label for="jenis_jabatan_id" class="invalid-feedback">{{ $message }}</label>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="kode" class="form-label">Kode</label>
                        <input type="text" class="form-control @error('kode') is-invalid @enderror" id="kode"
                            name="kode" placeholder="Masukkan Kode Jabatan" value="">
                        @error('kode')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="unsur" class="form-label">Unsur</label>
                        <select class="form-select @error('unsur_id') is-invalid @enderror" id="unsur"
                            name="unsur_id">
                            <option value="">Pilih Unsur yang membawahi Jabatan</option>
                            @foreach ($unsurs as $unsur)
                                @if ($unsur != 'Wakil Rektor')
                                    <option value="{{ $unsur->id }}">{{ $unsur->nama }}</option>
                                @endif
                            @endforeach
                        </select>
                        @error('unsur_id')
                            <label for="unsur_id" class="invalid-feedback">{{ $message }}</label>
                        @enderror
                    </div>
                    <div class="">
                        <button class="btn btn-primary header1" type="submit" id="submitJabatan"><i class="fa-solid fa-plus"></i>
                            Tambah
                            Jabatan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
