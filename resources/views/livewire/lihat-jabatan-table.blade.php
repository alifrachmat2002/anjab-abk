<?php

use Livewire\Volt\Component;
use App\Models\JabatanDiajukan;
use App\Models\Unsur;

new class extends Component {
    public $search = '';
    public $ajuan = '';
    public $jabatans = '';
    public $unsurs = '';
    public $mode;

    public function with()
    {
        if ($this->search) {
            $this->jabatans = JabatanDiajukan::where('ajuan_id', $this->ajuan->id)
                ->where('nama', 'like', '%' . $this->search . '%')
                ->get();
        }
        return [
            'jabatans' => $this->jabatans,
        ];
    }
}; ?>

<div>
    <div class="form-floating mb-3 w-25 ">
        <input type="email" wire:model.live="search" class="form-control" id="search" placeholder="Cari Jabatan">
        <label for="search">Cari Nama Jabatan</label>
    </div>
    @if (empty($this->search))
        <table class="table table-bordered">
            <thead>
                <th class="text-muted d-flex justify-content-between">
                    Unsur
                    @can('verify anjab')
                        {{--  show buttons only when next verificator is the current user  --}}
                        @if ($this->ajuan->next_verificator()->role->name == auth()->user()->getRoleNames()->first())
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal"
                                    data-bs-target="#modalTerima">Terima Semua Jabatan</button>
                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal"
                                    data-bs-target="#modalRevisi">Revisi Semua Jabatan</button>
                            </div>
                        @endif
                    @endcan
                </th>
                </th>
            </thead>
            <tbody>
                @foreach ($unsurs as $unsur)
                    <tr wire:key="{{ $unsur->id }}" class="table-secondary">
                        <td colspan="2">
                            <button class="btn btn-link p-0" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapse{{ $unsur->id }}" aria-expanded="false"
                                aria-controls="collapse{{ $unsur->id }}">
                                <i class="fa-solid fa-chevron-down me-2"></i>
                            </button>
                            {{ $unsur->nama }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="p-0">
                            <div class="collapse" id="collapse{{ $unsur->id }}">
                                <table class="table mb-0 table-bordered table-striped">
                                    <thead>
                                        <th class="fw-semibold text-muted">Kode</th>
                                        <th class="fw-semibold text-muted d-flex">Jabatan
                                        </th>
                                        <th class="fw-semibold text-muted">Catatan</th>
                                    </thead>
                                    @forelse ($unsur->jabatanDiajukan->where('ajuan_id', $this->ajuan->id) as $jabatan)
                                        <tr wire:key="{{ $jabatan->id }}">
                                            <td>{{ $jabatan->kode == null ? 'N/A' : $jabatan->kode }}</td>
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    <div class="d-flex gap-1"
                                                        style="margin-left: {{ 25 * $jabatan->depth }}px">
                                                        @if ($jabatan->depth > 0)
                                                            <i class="fa-solid fa-chevron-right mt-1"></i>
                                                        @endif
                                                        <p class="" href="/anjab/analisis-jabatan/create"
                                                            style="">
                                                            {{ $jabatan->nama }}
                                                        </p>
                                                    </div>
                                                    <div
                                                        class="div @can('verify anjab')
                                                        @if (
                                                            !empty($ajuan->latest_verificator()) &&
                                                                !empty($ajuan->next_verificator()) &&
                                                                $ajuan->latest_verificator() != auth()->user()->getRoleNames()->first() &&
                                                                $ajuan->next_verificator()->role->name == auth()->user()->getRoleNames()->first())
                                                            d-flex w-50
                                                        @endif
                                                    @endcan">
                                                        @can('verify anjab')
                                                            @if (
                                                                !empty($ajuan->latest_verificator()) &&
                                                                    !empty($ajuan->next_verificator()) &&
                                                                    $ajuan->latest_verificator() != auth()->user()->getRoleNames()->first() &&
                                                                    $ajuan->next_verificator()->role->name == auth()->user()->getRoleNames()->first())
                                                                <form
                                                                    action="{{ route('anjab.jabatan.makeCatatan', ['jabatan' => $jabatan]) }}"
                                                                    class="d-flex flex-grow-1 me-2" method="POST"
                                                                    id="form{{ $jabatan->id }}">
                                                                    @csrf
                                                                    <div class="flex-grow-1 me-1">
                                                                        <input name="catatan"
                                                                            class="form-control form-control-sm w-100 border-black"
                                                                            type="text"
                                                                            placeholder="{{ $jabatan->revisiTerbaruTanpaVerifikasi->catatan ?? 'Berikan Catatan Perbaikan' }}"
                                                                            aria-label=".form-control-sm example"
                                                                            style="width: 150px;">
                                                                    </div>
                                                                    <div class="">
                                                                        <button type="submit"
                                                                            class="btn btn-sm btn-warning"><i
                                                                                class="fa-regular fa-save"></i></button>
                                                                    </div>
                                                                </form>
                                                            @endif
                                                        @endcan
                                                        <div class="">
                                                            <a href="{{ route('anjab.ajuan.jabatan.show', ['ajuan' => $this->ajuan, 'jabatan' => $jabatan->id, 'id' => '']) }}"
                                                                class="btn btn-sm btn-primary ms-auto add-button"><i
                                                                    class="fa-regular fa-eye"></i>
                                                                Lihat</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="overflow-y-scroll"
                                                    style="line-height: 1.5em; max-height: calc(1.5em * 4);">
                                                    @forelse ($jabatan->catatanAjuan as $catatan)
                                                        <p class="m-0 p-0 text-muted">Catatan oleh
                                                            {{ $catatan->verifikasi?->user->name }}</p>
                                                        <p class="m-0 p-0 text-muted">
                                                            {{ $catatan->verifikasi->created_at }}</p>
                                                        <p class="m-0 p-0">{{ $catatan->catatan }}</p>
                                                        <hr>
                                                    @empty
                                                        <p class="m-0 p-0 text-muted">Tidak ada catatan.</p>
                                                    @endforelse
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">Unsur ini Belum memiliki Jabatan</td>
                                        </tr>
                                    @endforelse
                                </table>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <table class="table mb-3 table-bordered table-striped">
            <thead>
                <th class="fw-semibold text-muted"style="width: 10%">Kode</th>
                <th class="fw-semibold text-muted d-flex">Jabatan
                </th>
                <th class="fw-semibold text-muted">Catatan</th>
            </thead>
            @forelse ($jabatans as $jabatan)
                <tr wire:key="{{ $jabatan->id }}">
                    <td>{{ $jabatan->kode == null ? 'N/A' : $jabatan->kode }}</td>
                    <td>
                        <div class="d-flex justify-content-between">
                            <div class="d-flex gap-1" style="margin-left: {{ 25 * $jabatan->depth }}px">
                                @if ($jabatan->depth > 0)
                                    <i class="fa-solid fa-chevron-right mt-1"></i>
                                @endif
                                <p class="" href="/anjab/analisis-jabatan/create" style="">
                                    {{ $jabatan->nama }}
                                </p>
                            </div>
                            <div
                                class="div @can('verify anjab')
                                                        @if (
                                                            !empty($ajuan->latest_verificator()) &&
                                                                !empty($ajuan->next_verificator()) &&
                                                                $ajuan->latest_verificator() != auth()->user()->getRoleNames()->first() &&
                                                                $ajuan->next_verificator()->role->name == auth()->user()->getRoleNames()->first())
                                                            d-flex w-50
                                                        @endif
                                                    @endcan">
                                @can('verify anjab')
                                    @if (
                                        !empty($ajuan->latest_verificator()) &&
                                            !empty($ajuan->next_verificator()) &&
                                            $ajuan->latest_verificator() != auth()->user()->getRoleNames()->first() &&
                                            $ajuan->next_verificator()->role->name == auth()->user()->getRoleNames()->first())
                                        <form action="{{ route('anjab.jabatan.makeCatatan', ['jabatan' => $jabatan]) }}"
                                            class="d-flex flex-grow-1 me-2" method="POST" id="form{{ $jabatan->id }}">
                                            @csrf
                                            <div class="flex-grow-1 me-1">
                                                <input name="catatan"
                                                    class="form-control form-control-sm w-100 border-black" type="text"
                                                    placeholder="{{ $jabatan->revisiTerbaruTanpaVerifikasi->catatan ?? 'Berikan Catatan Perbaikan' }}"
                                                    aria-label=".form-control-sm example" style="width: 150px;">
                                            </div>
                                            <div class="">
                                                <button type="submit" class="btn btn-sm btn-warning"><i
                                                        class="fa-regular fa-save"></i></button>
                                            </div>
                                        </form>
                                    @endif
                                @endcan
                                <div class="">
                                    <a href="{{ route('anjab.ajuan.jabatan.show', ['ajuan' => $this->ajuan, 'jabatan' => $jabatan->id, 'id' => '']) }}"
                                        class="btn btn-sm btn-primary ms-auto add-button"><i
                                            class="fa-regular fa-eye"></i>
                                        Lihat</a>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="overflow-y-scroll" style="line-height: 1.5em; max-height: calc(1.5em * 4);">
                            @forelse ($jabatan->catatanAjuan as $catatan)
                                <p class="m-0 p-0 text-muted">Catatan oleh
                                    {{ $catatan->verifikasi->user->name }}</p>
                                <p class="m-0 p-0 text-muted">
                                    {{ $catatan->verifikasi->created_at }}</p>
                                <p class="m-0 p-0">{{ $catatan->catatan }}</p>
                                <hr>
                            @empty
                                <p class="m-0 p-0 text-muted">Tidak ada catatan.</p>
                            @endforelse
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">Jabatan Tidak Ditemukan</td>
                </tr>
            @endforelse
        </table>
    @endif
    {{-- Modal Terima Start --}}
    <div class="modal fade " tabindex="-1" id="modalTerima">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Terima Ajuan?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Ajuan yang sudah diterima tidak akan bisa diubah lagi dan akan diteruskan ke tingkat
                        verifikasi
                        berikutnya.</p>
                </div>
                <div class="modal-footer">
                    <form action="{{ route('anjab.ajuan.verifikasi', ['ajuan' => $this->ajuan->id]) }}"
                        method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary">Ya</button>
                    </form>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
                </div>
            </div>
        </div>
    </div>
    @can('verify anjab')
        @if (
            !empty($ajuan->latest_verificator()) &&
                !empty($ajuan->next_verificator()) &&
                $ajuan->latest_verificator() != auth()->user()->getRoleNames()->first() &&
                $ajuan->next_verificator()->role->name == auth()->user()->getRoleNames()->first())
            <form action="{{ route('anjab.ajuan.jabatan.revisi', ['ajuan' => $this->ajuan]) }}" method="POST">
                @csrf
                <button {{-- href="{{ route('anjab.ajuan.index') }}" --}} class="btn btn-danger mb-3" type="submit"><i class="fa-solid fa-arrow-left"></i> 
                    Simpan Revisi dan Kembali</button>
            </form>
        @endif
    @endcan
    {{-- Modal Terima End --}}
    {{-- Modal Revisi Start --}}
    <div class="modal fade" tabindex="-1" id="modalRevisi">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Beri Catatan dan Minta Revisi (Semua Jabatan)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('anjab.ajuan.revisi') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="text" name="ajuan_id" id="inputAjuan"
                            value="{{ old('ajuan_id') ?? $this->ajuan->id }}">
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
</div>
