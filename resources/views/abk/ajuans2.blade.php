@extends('layouts.main')

@section('container')
    <div class="">
        {{ Breadcrumbs::render('daftar-ajuan-abk') }}
    </div>
    <div class="card-head mb-3">
        <h1 class="fw-light fs-4 d-inline nav-item">Daftar Ajuan Beban Kerja</h1>
    </div>
    @if (auth()->user()->roles[0]->name == 'Admin Kepegawaian')
        @if (Request::has('abk'))
            <div class="alert alert-info alert-dismissible fade show">
                <div class="alert-heading d-flex justify-content-between">
                    <div class="d-flex">
                        <img width="20px" data-feather="info" class="m-0 p-0 me-2"></img>
                        <p class="m-0 p-0">Perhatian</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <hr>
                <p class="m-0 p-0">Untuk membuat ajuan ABK, silahkan tekan tombol 'Buat Ajuan ABK'</p>
            </div>
        @endif
        <div class="alert alert-dismissible alert-success fade show">
            <p class="m-0">Ajuan berhasil disimpan!</p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card dropdown-divider mb-3"></div>

    <table class="table table-striped table-bordered table-responsive">
        <thead>
            <tr>
                <th style="width: 10%">No</th>
                <th>Periode</th>
                @can('make abk')
                    <th>Status</th>
                @elsecan('verify abk')
                    <th>Diajukan Tanggal</th>
                    <th>Aksi</th>
                @endcan
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ajuans as $ajuan)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td class="w-25">
                        <div class="d-flex flex-column justify-content-between">
                            <p>{{ $ajuan->tahun }} </p>
                            <div class="btn-group" role="group" aria-label="Basic example">
                                @can('make abk')
                                    <a href="{{ route('abk.unitkerja.show', ['anjab' => $ajuan->anjab->first(), 'abk' => $ajuan]) }}"
                                        class="btn btn-outline-primary">Lihat</a>
                                    @if (
                                        $ajuan->latest_verificator() != 'Operator Unit Kerja' &&
                                            ($ajuan->next_verificator()->role->name == 'Manajer Unit Kerja' ||
                                                $ajuan->next_verificator()->role->name == 'Manajer Tata Usaha'))
                                        <a href="{{ route('abk.unitkerja.edit', ['anjab' => $ajuan->anjab->first(), 'abk' => $ajuan]) }}"
                                            class="btn btn-outline-secondary">Edit</a>
                                    @endif
                                @endcan
                            </div>
                        </div>
                    </td>
                    @can('make abk')
                    @elsecan('verify abk')
                        <td>
                            <p>{{ $ajuan->created_at }}</p>
                        </td>
                        @if ($ajuan->approvedAbkCount() == $ajuan->children()->count() || $ajuan->hasChildrenAbkCheckedByUser())
                            <td>
                                {{-- check if current verificator HAS NOT accept/reject the ajuan YET, show "Terima" and "Revisi" buttons --}}
                                @if (
                                    !empty($ajuan->next_verificator()) &&
                                        $ajuan->latest_verificator() != auth()->user()->getRoleNames()->first() &&
                                        $ajuan->next_verificator()->role->name == auth()->user()->getRoleNames()->first())
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <a href="{{ route('abk.ajuan.show', ['abk' => $ajuan->id]) }}"
                                            class="btn btn-outline-primary">Lihat</a>
                                        @if (!$ajuan->hasChildrenRejectedByUser())
                                            <button type="button" class="btn btn-outline-success" data-bs-toggle="modal"
                                        data-bs-target="#modalTerima{{ $loop->index }}">Terima</button>
                                        @endif
                                    </div>
                                @else
                                    {{-- if current verificator HAS accepted/rejected the ajuan, show them that they accepted/rejected the ajuan  --}}
                                    @if (!empty($ajuan->latest_verifikasi_by_current_user))
                                        @if ($ajuan->latest_verifikasi_by_current_user->is_approved)
                                        <div class="d-flex justify-content-between align-items-center">
                                            <p class="badge text-bg-success m-0">Anda sudah menerima Ajuan ini</p>
                                            <a href="{{ route('abk.ajuan.show', ['abk' => $ajuan->id]) }}"
                                            class="btn btn-outline-primary">Lihat</a>
                                        </div>
                                            @if (!empty($ajuan->next_verificator()))
                                                <div class="alert alert-info w-100">
                                                    <div class="alert-heading d-flex">
                                                        <img width="20px" data-feather="clock" class="m-0 p-0 me-2"></img>
                                                        <p class="m-0 p-0">Menunggu Diperiksa</p>
                                                    </div>
                                                    <hr>
                                                    <p class="m-0 p-0">
                                                        {{ $ajuan->next_verificator()->role->name ?? '' }}
                                                    </p>
                                                </div>
                                            @endif
                                        @else
                                            <span class="badge text-bg-danger">Anda sudah merevisi Ajuan ini</span>
                                            <a href="{{ route('abk.ajuan.show', ['abk' => $ajuan->id]) }}"
                                            class="btn btn-outline-primary">Lihat</a>
                                        @endif
                                    @endif
                                @endif
                            </td>
                        @else
                            <td>
                                <p>Aksi belum dapat dilakukan.</p>
                                <p>({{ $ajuan->approvedAbkCount() }} dari {{ $ajuan->children()->count() }} ajuan ABK unit
                                    kerja
                                    disetujui)
                                </p>
                            </td>
                        @endif
                    @endcan
                    <td>
                        {{-- check if latest verification has catatan and catatan is not from current user, if true show the catatan --}}
                        @if ($ajuan->latest_verifikasi() && $ajuan->latest_verifikasi()->catatan)
                            <p>Catatan dari {{ $ajuan->latest_verifikasi()->user->name }}
                                ({{ $ajuan->latest_verifikasi()->user->getRolenames()->first() }})
                            </p>
                            <p>{{ $ajuan->latest_verifikasi()->created_at }}</p>
                            <hr>
                            <p>{{ $ajuan->latest_verifikasi()->catatan }}</p>
                            <ul>
                                @foreach ($ajuan->latest_verifikasi()->jabatanDirevisi as $jabatan)
                                    <li>{{ $jabatan->jabatan_direvisi }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p>Tidak ada catatan.</p>
                        @endif
                    </td>
                </tr>

                {{-- Modals are placed here so that it can pass $ajuan->id when the buttons are clicked --}}
                {{-- Modal Terima Start --}}
                <div class="modal fade" tabindex="-1" id="modalTerima{{ $loop->index }}">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Terima Ajuan?</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Ajuan yang sudah diterima tidak akan bisa diubah lagi dan akan diteruskan ke tingkat
                                    verifikasi
                                    berikutnya.</p>
                            </div>
                            <div class="modal-footer">
                                <form action="{{ route('abk.ajuan.parent.verifikasi', ['abk' => $ajuan->id]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">Ya</button>
                                </form>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Modal Terima End --}}
                {{-- Modal Revisi Start --}}
                <div class="modal fade" tabindex="-1" id="modalRevisi">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Beri Catatan dan Minta Revisi</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form action="{{ route('abk.ajuan.revisi', ['abk' => $ajuan->id]) }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <input type="text" name="ajuan_id" id="inputAjuan" value="{{ old('ajuan_id') }}">
                                    <label for="catatan" class="form-label">Berikan Catatan tentang ajuan untuk
                                        diperbaiki</label>
                                    <textarea
                                        class="form-control mb-1 @error('catatan')
                            is-invalid
                        @enderror"
                                        name="catatan" id="catatan" cols="30" rows="10"></textarea>
                                    @error('catatan')
                                        <label for="catatan" class="invalid-feedback">{{ $message }}</label>
                                    @enderror
                                    <label for="select2" class="form-label mt-1">Pilih Jabatan apa saja yang perlu
                                        diperbaiki
                                    </label>
                                    <div class="w-100 border" id="select2">
                                        <select class="select2 form-select" id="select2input" style="width: 100%"
                                            multiple="multiple" name="jabatan_direvisi[]" placeholder="Pilih Jabatan">

                                        </select>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="Semua Jabatan"
                                            id="semuaJabatanCheckbox" name="semua_jabatan_revisi">
                                        <label class="form-check-label" for="semuaJabatanCheckbox">
                                            Pilih Semua Jabatan
                                        </label>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                {{-- Modal Revisi End --}}
            @endforeach
        </tbody>
    </table>

    {{-- make a kembali button --}}
    <a href="{{ route('home') }}" class="btn btn-primary header1"><i data-feather="arrow-left"></i> Kembali</a>


@endsection
@section('scripts')
    @if ($errors->any())
        {{-- BANG ERROR --}}
        <script>
            const myModal = document.getElementById('modalRevisi');
            const bootstrapModal = new bootstrap.Modal(myModal);
            bootstrapModal.show();

            const select2 = document.getElementById('select2input');

            const inputAjuan = document.getElementById('inputAjuan');

            fetch(`{{ route('api.jabatanabk.parent') }}?ajuan=${inputAjuan.value}`)
                .then(response => response.json())
                .then(data => {
                    console.log(data.jabatans);
                    data.jabatans.map(jabatan => {
                        const option = document.createElement('option');
                        option.value = jabatan.unit_kerja.toUpperCase() + ' - ' + jabatan.jabatan +
                            ' (di bawahi oleh ' + jabatan.jabatan_tutam +
                            ')';
                        option.text = jabatan.unit_kerja.toUpperCase() + ' - ' + jabatan.jabatan +
                            ' (di bawahi oleh ' + jabatan.jabatan_tutam +
                            ')';
                        select2.appendChild(option);
                    })
                })

            const selectAllCheckbox = document.getElementById('semuaJabatanCheckbox');
            selectAllCheckbox.addEventListener('change', event => {
                if (event.target.checked) {
                    select2.value = "Semua Jabatan";
                    select2.disabled = true;

                } else {
                    select2.disabled = false;

                }
            })
        </script>
    @endif
    <script>
        const modalRevisi = document.getElementById('modalRevisi');
        modalRevisi.addEventListener('show.bs.modal', event => {
            console.log('NJIR DIPENCET');
            const btn = event.relatedTarget
            // console.log(btn)z

            const ajuan = btn.getAttribute('data-ajuan')
            // console.log(ajuan)

            const inputAjuan = document.getElementById('inputAjuan');

            inputAjuan.value = ajuan;

            const select2 = document.getElementById('select2input');
            console.log(select2)
            console.log(ajuan)

            // make a request to fetch jabatan diajukan save it to select2
            fetch(`{{ route('api.jabatanabk.parent') }}?ajuan=${ajuan}`)
                .then(response => response.json())
                .then(data => {
                    console.log(data.jabatans);
                    data.jabatans.map(jabatan => {
                        const option = document.createElement('option');
                        option.value = jabatan.unit_kerja.toUpperCase() + ' - ' + jabatan.jabatan +
                            ' (di bawahi oleh ' + jabatan.jabatan_tutam +
                            ')';
                        option.text = jabatan.unit_kerja.toUpperCase() + ' - ' + jabatan.jabatan +
                            ' (di bawahi oleh ' + jabatan.jabatan_tutam +
                            ')';
                        select2.appendChild(option);
                    })
                })

            const selectAllCheckbox = document.getElementById('semuaJabatanCheckbox');
            selectAllCheckbox.addEventListener('change', event => {
                if (event.target.checked) {
                    select2.value = "Semua Jabatan";
                    select2.disabled = true;

                } else {
                    select2.disabled = false;

                }
            })
        })

        $(document).ready(function() {
            $('.select2').select2({
                dropdownParent: '#modalRevisi'
            });
        });
    </script>
@endsection
