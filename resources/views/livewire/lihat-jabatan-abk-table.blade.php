<?php

use Livewire\Volt\Component;
use App\Models\ABKJabatan;

new class extends Component {
    public $tutams;
    public $abk;
    public $unit;
    public $search;
    public $jabatans;
    public $abkparent;

    public function with()
    {
        if ($this->search) {
            $this->jabatans = ABKJabatan::with('jabatanTugasTambahan')
                ->where('abk_id', $this->abk->id)
                ->whereHas('jabatan', function ($query) {
                    $query->where('nama', 'like', '%' . $this->search . '%');
                })
                ->get();
        }
        return ['jabatans' => $this->jabatans];
    }
}; ?>

<div>
    <div class="form-floating mb-3 w-25 ">
        <input type="email" wire:model.live="search" class="form-control" id="search" placeholder="Cari Jabatan">
        <label for="search">Cari Nama Jabatan</label>
    </div>
    @if ($this->search)
        <table class="table mb-3 table-bordered table-striped">
            <thead>
                <th class="fw-semibold text-muted">Kode</th>
                <th class="fw-semibold text-muted d-flex">Jabatan
                </th>
            </thead>
            @forelse ($jabatans as $jabatan)
                <tr wire:key="{{ $jabatan->id }}">
                    <td>{{ $jabatan->kode == null ? 'N/A' : $jabatan->kode }}</td>
                    <td>
                        <div class="d-flex justify-content-between">
                            <div class="">
                                <p>{{ $jabatan->jabatan->nama }}</p>
                                <span class="text-muted">(Dibawahi oleh
                                    {{ $jabatan->jabatanTugasTambahan->nama }})</span>
                            </div>
                            <div
                                class="@can('verify abk')
                                            @if (
                                                !empty($this->abk->latest_verificator()) &&
                                                    !empty($this->abk->next_verificator()) &&
                                                    $this->abk->latest_verificator() != auth()->user()->getRoleNames()->first() &&
                                                    $this->abk->next_verificator()->role->name == auth()->user()->getRoleNames()->first())
                                                d-flex w-50
                                            @endif
                                        @endcan">
                                @can('verify abk')
                                    @if (
                                        !empty($this->abk->latest_verificator()) &&
                                            !empty($this->abk->next_verificator()) &&
                                            $this->abk->latest_verificator() != auth()->user()->getRoleNames()->first() &&
                                            $this->abk->next_verificator()->role->name == auth()->user()->getRoleNames()->first())
                                        <form action="{{ route('abk.jabatan.makecatatan', ['abk_jabatan' => $jabatan]) }}"
                                            class="d-flex flex-grow-1 me-2" method="POST">
                                            @csrf
                                            <div class="flex-grow-1 me-1">
                                                <input name="catatan"
                                                    class="form-control form-control-sm w-100 border-black" type="text"
                                                    placeholder=" {{ $jabatan->revisiTerbaruTanpaVerifikasi->catatan ?? 'Berikan Catatan Perbaikan' }}"
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
                                    <a href="{{ route('abk.jabatan.show', ['abk' => $this->abkparent, 'unit_kerja' => $this->unit, 'abk_jabatan' => $jabatan]) }}"
                                        class="btn btn-sm btn-primary ms-auto"><i class="fa-regular fa-eye"></i> Lihat
                                        </a>
                                </div>
                            </div>
                        </div>
                    </td>
                <tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center">Jabatan Tidak Ditemukan.</td>
                </tr>
            @endforelse
        </table>
    @else
        <table class="table table-bordered">
            <thead>
                <th class="text-muted d-flex justify-content-between">
                    Jabatan Tugas Tambahan
                    @can('verify abk')
                        {{--  show buttons only when next verificator is the current user  --}}
                        {{-- {{ $this->abk->next_verificator()->role->name }} --}}
                        @if ($this->abk->next_verificator()->role->name == auth()->user()->getRoleNames()->first())
                            <div class="btn-group" role="group" aria-label="Basic example">
                                @if (!auth()->user()->hasAnyRole(['Wakil Rektor 2', 'Admin Kepegawaian']))
                                    <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal"
                                    data-bs-target="#modalTerima">Terima Semua Jabatan</button>
                                @endif
                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal"
                                    data-bs-target="#modalRevisi">Revisi Semua Jabatan</button>
                            </div>
                        @endif
                    @endcan
                </th>

            </thead>
            <tbody>
                @foreach ($tutams as $tutam)
                    <tr class="table-secondary">
                        <td colspan="2">
                            <button class="btn btn-link p-0" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapse{{ $tutam->id }}" aria-expanded="false"
                                aria-controls="collapse{{ $tutam->id }}">
                                <i class="fa-solid fa-chevron-down me-2"></i>
                            </button>
                            {{ $tutam->nama }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="p-0">
                            <div class="collapse" id="collapse{{ $tutam->id }}">
                                <table class="table mb-0 table-bordered table-striped">
                                    <thead>
                                        <th class="fw-semibold text-muted">Kode</th>
                                        <th class="fw-semibold text-muted d-flex">Jabatan
                                        <th class="fw-semibold text-muted">Catatan Revisi
                                        </th>
                                    </thead>
                                    @forelse ($tutam->abkJabatan->where('abk_id',$this->abk->id) as $abkjabatan)
                                        <tr wire:key="{{ $abkjabatan->id }}">
                                            <td>{{ $abkjabatan->jabatan->kode == null ? 'N/A' : $abkjabatan->jabatan->kode }}
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    <p>{{ $abkjabatan->jabatan->nama }}</p>
                                                    <div
                                                        class="@can('verify abk')
                                                        @if (
                                                            !empty($this->abk->latest_verificator()) &&
                                                                !empty($this->abk->next_verificator()) &&
                                                                $this->abk->latest_verificator() != auth()->user()->getRoleNames()->first() &&
                                                                $this->abk->next_verificator()->role->name == auth()->user()->getRoleNames()->first())
                                                            d-flex w-50
                                                        @endif
                                                    @endcan">
                                                        @can('verify abk')
                                                            @if (
                                                                !empty($this->abk->latest_verificator()) &&
                                                                    !empty($this->abk->next_verificator()) &&
                                                                    $this->abk->latest_verificator() != auth()->user()->getRoleNames()->first() &&
                                                                    $this->abk->next_verificator()->role->name == auth()->user()->getRoleNames()->first())
                                                                <form
                                                                    action="{{ route('abk.jabatan.makecatatan', ['abk_jabatan' => $abkjabatan]) }}"
                                                                    class="d-flex flex-grow-1 me-2" method="POST"
                                                                    id="form{{ $abkjabatan->id }}">
                                                                    @csrf
                                                                    <div class="flex-grow-1 me-1">
                                                                        <input name="catatan"
                                                                            class="form-control form-control-sm w-100 border-black"
                                                                            type="text"
                                                                            placeholder=" {{ $abkjabatan->revisiTerbaruTanpaVerifikasi->catatan ?? 'Berikan Catatan Perbaikan' }}"
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
                                                            <a href="{{ route('abk.jabatan.show', ['abk' => $abkparent, 'unit_kerja' => $this->unit, 'abk_jabatan' => $abkjabatan]) }}"
                                                                class="btn btn-sm btn-primary ms-auto"><i
                                                                    class="fa-regular fa-eye"></i> Lihat</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="w-25">
                                                <div class="overflow-y-scroll"
                                                    style="line-height: 1.5em; max-height: calc(1.5em * 4);">
                                                    @forelse ($abkjabatan->catatanAjuan as $catatan)
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
                                        <tr>

                                        @empty
                                        <tr>
                                            <td colspan="3" class="text-center">Jabatan Bawahan belum disusun.</td>
                                        </tr>
                                    @endforelse
                                </table>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
    @can('verify abk')
        @if (
            !empty($this->abk->latest_verificator()) &&
                !empty($this->abk->next_verificator()) &&
                $this->abk->latest_verificator() != auth()->user()->getRoleNames()->first() &&
                $this->abk->next_verificator()->role->name == auth()->user()->getRoleNames()->first())
            <form action="{{ route('abk.jabatan.revisi', ['abk' => $this->abk]) }}" method="POST">
                @csrf
                <button {{-- href="{{ route('anjab.ajuan.index') }}" --}} class="btn btn-danger" type="submit"><i class="fa-solid fa-arrow-left"></i>
                    Simpan Revisi dan Kembali</button>
            </form>
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
                        <form action="{{ route('abk.ajuan.verifikasi', ['abk' => $this->abk->id]) }}" method="POST">
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
                        <h5 class="modal-title">Beri Catatan dan Minta Revisi (Semua Jabatan)</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('abk.ajuan.revisi', ['abk' => $this->abk]) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <input type="text" name="ajuan_id" id="inputAjuan"
                                value="{{ old('ajuan_id') ?? $this->abk->id }}" hidden>
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
    @endcan
</div>
