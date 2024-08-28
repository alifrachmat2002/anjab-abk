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
                                    <a href="{{ route('abk.unitkerja.show', ['abk' => $ajuan->parent, 'unit_kerja' => auth()->user()->unitKerja]) }}"
                                        class="btn btn-outline-primary">Lihat</a>
                                    @if (
                                        $ajuan->next_verificator()->role->name == 'Operator Unit Kerja')
                                        <a href="{{ route('abk.unitkerja.edit', ['anjab' => $ajuan->anjab->first(), 'abk' => $ajuan]) }}"
                                            class="btn btn-outline-secondary">Edit</a>
                                    @endif
                                @endcan
                            </div>
                        </div>
                    </td>

                    @can('make abk')
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
                            @if ($ajuan->approved_verificator()->count() && $ajuan->approved_verificator)
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
                            @if ($ajuan->next_verificator() && $ajuan->next_verificator()->role->name != 'Operator Unit Kerja')
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
                    @elsecan('verify abk')
                        <td>
                            <p>{{ $ajuan->created_at }}</p>
                        </td>
                        @if ($ajuan->approvedAbkCount() == $ajuan->abk->count())
                            <td>
                                {{-- check if current verificator HAS NOT accept/reject the ajuan YET, show "Terima" and "Revisi" buttons --}}
                                @if (
                                    !empty($ajuan->latest_verificator()) &&
                                        !empty($ajuan->next_verificator()) &&
                                        $ajuan->latest_verificator() != auth()->user()->getRoleNames()->first() &&
                                        $ajuan->next_verificator()->role->name == auth()->user()->getRoleNames()->first())
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <a href="{{ route('abk.unitkerja.show', ['anjab' => $ajuan->anjab->first()->id, 'abk' => $ajuan->id]) }}"
                                            class="btn btn-outline-primary">Lihat</a>
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
                        @else
                            <td>
                                <p>Aksi belum dapat dilakukan.</p>
                                <p>({{ $ajuan->approvedAbkCount() }} dari {{ $ajuan->abk->count() }} ajuan ABK unit kerja
                                    disetujui)
                                </p>
                            </td>
                        @endif
                    @endcan

                    <td>
                        {{-- check if latest verification has catatan and catatan is not from current user, if true show the catatan --}}
                        @if ($ajuan->verifikasi->count() > 0)
                            @if ($ajuan->latest_verifikasi()->catatan)
                                <p>Catatan dari {{ $ajuan->latest_verifikasi()->user->name }}
                                    ({{ $ajuan->latest_verifikasi()->user->getRolenames()->first() }})
                                </p>
                                <p>{{ $ajuan->latest_verifikasi()->created_at }}</p>
                                <hr>
                                <p>{{ $ajuan->latest_verifikasi()->catatan }}</p>
                            @else
                                <p>Tidak ada catatan.</p>
                            @endif
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
                                <form action="{{ route('abk.ajuan.verifikasi', ['abk' => $ajuan->id]) }}" method="POST">
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
                            <form action="{{ route('abk.ajuan.revisi', ['abk' => $ajuan->id]) }}" method="POST">
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
        </tbody>
    </table>
    {{-- make a kembali button --}}
    <a href="{{ route('home') }}" class="btn btn-primary header1"><i data-feather="arrow-left"></i> Kembali</a>


@endsection
