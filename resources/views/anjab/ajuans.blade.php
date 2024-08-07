@extends('layouts.main')

@section('container')
    <div class="">
        {{ Breadcrumbs::render('ajuan-analisis-jabatan') }}
    </div>
    <div class="card-head mb-3">
        <h1 class="fw-light fs-4 d-inline nav-item">Daftar Ajuan Analisis Jabatan</h1>
    </div>
    @can('make ajuan')
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
    @endcan
    <div class="card dropdown-divider mb-3"></div>
    @can('make ajuan')
        <a type="button" class="btn-primary btn mb-3" href="{{ route('anjab.ajuan.create') }}"><i data-feather="plus"></i> Buat
            Ajuan Baru</a>
    @endcan
    <table class="table table-striped table-bordered table-responsive">
        <thead>
            <tr>
                <th style="width: 10%">No</th>
                <th>Periode</th>
                @can('make ajuan')
                    <th>Status</th>
                @elsecan('verify ajuan')
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
                                @can('make ajuan')
                                    <a href="{{ route('anjab.ajuan.show', $ajuan->tahun) }}"
                                        class="btn btn-outline-primary">Lihat</a>
                                    @if (!$ajuan->latest_verifikasi()->is_approved && $ajuan->next_verificator()->role->name == 'Admin Kepegawaian')
                                        <a href="{{ route('anjab.ajuan.edit', ['tahun' => $ajuan->tahun, 'id' => $ajuan->id]) }}"
                                            class="btn btn-outline-primary">Edit</a>
                                    @endif
                                    @if ($ajuan->is_approved())
                                        <form action="{{ route('abk.ajuan.store', ['ajuan' => $ajuan->id]) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-success" aria-disabled="true">Buat
                                                Ajuan ABK</button>
                                        </form>
                                    @endif
                                @endcan
                            </div>
                        </div>
                    </td>
                    @can('make ajuan')
                        <td class="w-25">
                            {{-- check if latest verification exists, if exists and latest verification is not approved, show alert warning --}}
                            @if (!empty($ajuan->latest_verifikasi()) && !$ajuan->latest_verifikasi()->is_approved)
                                <div class="alert alert-warning w-100">
                                    <div class="alert-heading d-flex">
                                        <img width="20px" data-feather="alert-triangle" class="m-0 p-0 me-2"></img>
                                        <p class="m-0 p-0">Perlu Perbaikan</p>
                                    </div>
                                    <hr>
                                    <p class="m-0 p-0">{{ $ajuan->latest_verificator() }}</p>
                                </div>
                            @endif

                            {{-- if someone has verified the ajuan, display alert success --}}
                            @if ($ajuan->approved_verificator()->count())
                                <div class="alert alert-success w-100">
                                    <div class="alert-heading d-flex">
                                        <img width="20px" data-feather="check-circle" class="m-0 p-0 me-2"></img>
                                        <p class="m-0 p-0">Disetujui</p>
                                    </div>
                                    <hr>
                                    <ul>
                                        @foreach ($ajuan->approved_verificator() as $verificator)
                                            <li>
                                                {{ $verificator->role->name }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            {{-- if there is still someone to verify, display alert info --}}
                            @if ($ajuan->next_verificator() && $ajuan->next_verificator()->role->name != 'Admin Kepegawaian')
                                <div class="alert alert-info w-100">
                                    <div class="alert-heading d-flex">
                                        <img width="20px" data-feather="clock" class="m-0 p-0 me-2"></img>
                                        <p class="m-0 p-0">Menunggu Diperiksa</p>
                                    </div>
                                    <hr>
                                    <p class="m-0 p-0">
                                        {{ $ajuan->next_verificator()->role->name }}
                                    </p>
                                </div>
                            @endif
                        </td>
                    @elsecan('verify ajuan')
                        <td>
                            <p>{{ $ajuan->created_at }}</p>
                        </td>
                        <td>

                            {{-- check if current verificator HAS NOT accept/reject the ajuan YET, show "Terima" and "Revisi" buttons --}}
                            @if (
                                !empty($ajuan->latest_verificator()) &&
                                    !empty($ajuan->next_verificator()) &&
                                    $ajuan->latest_verificator() != auth()->user()->getRoleNames()->first() &&
                                    $ajuan->next_verificator()->role->name == auth()->user()->getRoleNames()->first())
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <a href="{{ route('anjab.ajuan.show', $ajuan) }}" class="btn btn-outline-primary">Lihat</a>
                                    <button type="button" class="btn btn-outline-success" data-bs-toggle="modal"
                                        data-bs-target="#modalTerima{{ $loop->index }}">Terima</button>
                                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal"
                                        data-bs-target="#modalRevisi{{ $loop->index }}">Revisi</button>
                                </div>
                            @else
                                {{-- if current verificator HAS accepted/rejected the ajuan, show them that they accepted/rejected the ajuan  --}}

                                @if (!empty($ajuan->latest_verifikasi_by_current_user()))
                                    @if ($ajuan->latest_verifikasi_by_current_user()->is_approved)
                                        <p class="badge text-bg-success">Anda sudah menerima Ajuan ini</p>
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
                                    @endif
                                @endif
                            @endif
                        </td>
                    @endcan
                    <td>
                        {{-- check if latest verification has catatan and catatan is not from current user, if true show the catatan --}}
                        @if ($ajuan->latest_verifikasi()->catatan)
                            <p>Catatan dari {{ $ajuan->latest_verifikasi()->verificator->name }}
                                ({{ $ajuan->latest_verifikasi()->verificator->getRolenames()->first() }})
                            </p>
                            <p>{{ $ajuan->latest_verifikasi()->created_at }}</p>
                            <hr>
                            <p>{{ $ajuan->latest_verifikasi()->catatan }}</p>
                        @else
                            <p>Tidak ada catatan.</p>
                        @endif
                    </td>
                    {{-- <td>{{  ? <p class="bad"></p> : "Revisi" }}</td> --}}
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
                                <form action="{{ route('anjab.ajuan.verifikasi', ['ajuan' => $ajuan->id]) }}"
                                    method="POST">
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
                <div class="modal fade" tabindex="-1" id="modalRevisi{{ $loop->index }}">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Beri Catatan dan Minta Revisi</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form action="{{ route('anjab.ajuan.revisi', ['ajuan' => $ajuan->id]) }}" method="POST">
                                @csrf
                                <div class="modal-body">
                                    <label for="catatan" class="form-label">Berikan Catatan tentang ajuan untuk
                                        diperbaiki</label>
                                    <textarea class="form-control" name="catatan" id="catatan" cols="30" rows="10"></textarea>
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

            {{-- <tr>
                <td>1</td>
                <td class="w-25">
                    <div class="d-flex flex-column justify-content-between">
                        <p>2024</p>
                        <div class="btn-group" role="group" aria-label="Basic example">
                            @can('make ajuan')
                                <a href="{{ route('abk.ajuan.create', ['periode' => now()->year]) }}"
                                    class="btn btn-outline-success" aria-disabled="true">Buat Ajuan ABK</a>
                            @endcan
                        </div>
                    </div>
                </td>
                @can('make ajuan')
                    <td class="w-25">
                        <div class="alert alert-success w-100">
                            <div class="alert-heading d-flex">
                                <img width="20px" data-feather="check-circle" class="m-0 p-0 me-2"></img>
                                <p class="m-0 p-0">Disetujui</p>
                            </div>
                            <hr>
                            <p class="m-0 p-0">Manajer Tata Usaha/Kepegawaian</p>
                        </div>
                        <div class="alert alert-info w-100">
                            <div class="alert-heading d-flex">
                                <img width="20px" data-feather="clock" class="m-0 p-0 me-2"></img>
                                <p class="m-0 p-0">Menunggu Diperiksa</p>
                            </div>
                            <hr>
                            <p class="m-0 p-0">Kepala Biro, Wakil Dekan 2, Sekretaris Lembaga</p>
                        </div>
                    </td>
                    <td>Tidak ada catatan.</td>
                @elsecan('verify ajuan')
                    <td>
                        <p>{{ now()->format('d-m-Y') }}</p>
                    </td>
                    <td>
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <a href="{{ route('anjab.ajuan', $ajuan) }}" class="btn btn-outline-primary">Lihat</a>
                            <button type="button" class="btn btn-outline-success" data-bs-toggle="modal"
                                data-bs-target="#modalTerima">Terima</button>
                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal"
                                data-bs-target="#modalRevisi">Revisi</button>
                        </div>
                    </td>
                @endcan
            </tr>
            <tr>
                <td>1</td>
                <td class="w-25">
                    <div class="d-flex flex-column justify-content-between">
                        <p>2024</p>
                        <div class="btn-group" role="group" aria-label="Basic example">
                            @can('make ajuan')
                                <a href="{{ route('anjab.ajuan', $ajuan->id) }}" class="btn btn-outline-primary">Lihat</a>
                                <a href="{{ route('anjab.ajuan.edit', $ajuan->id) }}"
                                    class="btn btn-outline-primary">Edit</a>
                                <a href="{{ route('abk.ajuan.create', ['periode' => now()->year]) }}"
                                    class="btn btn-outline-success" aria-disabled="true">Buat Ajuan ABK</a>
                            @endcan
                        </div>
                    </div>
                </td>
                @can('make ajuan')
                    <td class="">
                        <div class="alert alert-warning w-100">
                            <div class="alert-heading d-flex">
                                <img width="20px" data-feather="alert-triangle" class="m-0 p-0 me-2"></img>
                                <p class="m-0 p-0">Perlu Perbaikan</p>
                            </div>
                            <hr>
                            <p class="m-0 p-0">Kepala Biro, Wakil Dekan 2, Sekretaris Lembaga</p>
                        </div>

                    </td>
                    <td>
                        <ul>
                            <li>Lorem ipsum dolor doloran</li>
                            <li>Lorem ipsum dolor doloran</li>
                            <li>Lorem ipsum dolor doloran</li>
                        </ul>
                    </td>
                @elsecan('verify ajuan')
                    <td>
                        <p>{{ now()->format('d-m-Y') }}</p>
                    </td>
                    <td>
                        <div class="btn-group" role="group" aria-label="Basic example">
                            <a href="{{ route('anjab.ajuan', $ajuan) }}" class="btn btn-outline-primary">Lihat</a>
                            <button type="button" class="btn btn-outline-success" data-bs-toggle="modal"
                                data-bs-target="#modalTerima">Terima</button>
                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal"
                                data-bs-target="#modalRevisi">Revisi</button>
                        </div>
                    </td>
                @endcan
            </tr> --}}
        </tbody>
    </table>
    {{-- make a kembali button --}}
    <a href="{{ route('home') }}" class="btn btn-primary header1"><i data-feather="arrow-left"></i> Kembali</a>


@endsection
