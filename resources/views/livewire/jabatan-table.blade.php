<?php

use Livewire\Volt\Component;
use App\Models\JabatanDiajukan;
use App\Models\Unsur;

new class extends Component {
    public $search = '';
    public $selectedJabatan;

    // RENDER JABATAN
    // renders jabatan based on search query
    // if search query is empty, renders all jabatan as tree
    // if search query is not empty, renders jabatan that matches the search query
    public function with()
    {
        if (empty($this->search)) {
            return [
                'jabatans' => JabatanDiajukan::where('ajuan_id', null)->with('uraianTugas')->tree()->get()->toTree(),
                'unsurs' => Unsur::with([
                    'jabatanDiajukan' => function ($query) {
                        $query->whereNull('jabatan_diajukan.ajuan_id');
                    },
                ])->get(),
            ];
        } else {
            return [
                'jabatans' => JabatanDiajukan::where('ajuan_id', null)
                    ->with('uraianTugas')
                    ->where('nama', 'like', '%' . $this->search . '%')
                    ->get(),
            ];
        }
    }

    // DELETE JABATAN
    // assigns jabatan to be deleted to $selectedJabatan
    public function deleteJabatan(JabatanDiajukan $jabatan)
    {
        $this->selectedJabatan = $jabatan;
        // dd($this->selectedJabatan->nama);
    }

    // DESTROY JABATAN
    // deletes selected jabatan in $selectedJabatan from database
    public function destroyJabatan()
    {
        $this->selectedJabatan->delete();
    }
}; ?>

<div>
    <div class="" wire:ignore>
        <div class="modal fade" tabindex="-1" id="deleteJabatanModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">  
                        <h5 class="modal-title">Hapus Jabatan?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah anda yakin menghapus jabatan {{ $this->selectedJabatan }} ? Jabatan yang sudah dihapus
                            tidak bisa dikembalikan lagi.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" wire:click="destroyJabatan"
                            data-bs-dismiss="modal">Ya</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-floating mb-3 w-25 ">
        <input type="email" wire:model.live="search" class="form-control" id="search" placeholder="Cari Jabatan">
        <label for="search">Cari Nama Jabatan</label>
    </div>
    
    @if (empty($this->search))
        <table class="table table-bordered">
            <thead>
                <th class="text-muted d-flex">
                    Unsur
                    <button class="btn btn-sm btn-success ms-auto add-button" data-bs-toggle="modal"
                        data-bs-target="#modalJabatan" id="addButton" data-bs-atasan=""><i class="fa-solid fa-plus"></i>
                        Tambah Jabatan</button>
                </th>
                
            </thead>
            <tbody>
                @foreach ($unsurs as $unsur)
                    <tr wire:key="{{ $unsur->nama }}" class="table-secondary">
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
                                    </thead>
                                    @forelse ($unsur->jabatanDiajukan as $jabatan)
                                        <tr wire:key="{{ $jabatan->id }}">
                                            <td>{{ $jabatan->kode == null ? 'N/A' : $jabatan->kode }}</td>
                                            <td class="d-flex justify-content-between">
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
                                                <div class="div">
                                                    @php
                                                        $isIncomplete = false;
                                                        $requiredInformations = [
                                                            'uraianTugas',
                                                            'risikoBahaya',
                                                            'tanggungJawab',
                                                            'wewenang',
                                                            'bahanKerja',
                                                            'perangkatKerja',
                                                            'korelasiJabatan',
                                                            'bakatKerja',
                                                            'temperamenKerja',
                                                            'minatKerja',
                                                            'fungsiPekerjaan',
                                                            'upayaFisik',
                                                            'pendidikanFormal',
                                                            'pendidikanPelatihan',
                                                            'pengalaman',
                                                        ];
                                                        foreach ($requiredInformations as $information) {
                                                            if ($jabatan->$information->count() == 0) {
                                                                $isIncomplete = true;
                                                                break;
                                                            }
                                                        }
                                                    @endphp
                                                    @if ($isIncomplete)
                                                        <span class="badge text-bg-warning">Informasi Jabatan Belum
                                                            Lengkap</span>
                                                    @endif
                                                    <a href="{{ route('anjab.jabatan.edit.1', ['jabatan' => $jabatan->id]) }}"
                                                        class="btn btn-sm btn-primary ms-auto add-button "><i
                                                            class="fa-solid fa-edit"></i>
                                                        Ubah
                                                        Informasi Jabatan</a>
                                                    <button data-bs-target="#deleteJabatanModal" data-bs-toggle="modal"
                                                        class="btn btn-danger btn-sm"
                                                        wire:click="deleteJabatan({{ $jabatan }})"><i
                                                            class="fa-solid fa-trash"></i> Hapus
                                                        Jabatan</button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center">Unsur ini Belum memiliki Jabatan</td>
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
        <table class="table mb-0 table-bordered table-striped">
            <thead>
                <th class="fw-semibold text-muted"style="width: 10%">Kode</th>
                <th class="fw-semibold text-muted d-flex">Jabatan
                </th>
            </thead>
            @forelse ($jabatans as $jabatan)
                <tr wire:key="{{ $jabatan->id }}">
                    <td>{{ $jabatan->kode == null ? 'N/A' : $jabatan->kode }}</td>
                    <td class="d-flex justify-content-between">
                        <div class="d-flex gap-1" style="margin-left: {{ 25 * $jabatan->depth }}px">
                            @if ($jabatan->depth > 0)
                                <i class="fa-solid fa-chevron-right mt-1"></i>
                            @endif
                            <p class="" href="/anjab/analisis-jabatan/create" style="">
                                {{ $jabatan->nama }}
                            </p>
                        </div>
                        <div class="div">
                            @php
                                $isIncomplete = false;
                                $requiredInformations = [
                                    'uraianTugas',
                                    'risikoBahaya',
                                    'tanggungJawab',
                                    'wewenang',
                                    'bahanKerja',
                                    'perangkatKerja',
                                    'korelasiJabatan',
                                    'bakatKerja',
                                    'temperamenKerja',
                                    'minatKerja',
                                    'fungsiPekerjaan',
                                    'upayaFisik',
                                    'pendidikanFormal',
                                    'pendidikanPelatihan',
                                    'pengalaman',
                                ];
                                foreach ($requiredInformations as $information) {
                                    if ($jabatan->$information->count() == 0) {
                                        $isIncomplete = true;
                                        break;
                                    }
                                }
                            @endphp
                            @if ($isIncomplete)
                                <span class="badge text-bg-warning">Informasi Jabatan Belum
                                    Lengkap</span>
                            @endif
                            <a href="{{ route('anjab.jabatan.edit.1', ['jabatan' => $jabatan->id]) }}"
                                class="btn btn-sm btn-primary ms-auto add-button "><i class="fa-solid fa-edit"></i>
                                Ubah
                                Informasi Jabatan</a>
                            <button data-bs-target="#deleteJabatanModal" data-bs-toggle="modal"
                                class="btn btn-danger btn-sm" wire:click="deleteJabatan({{ $jabatan }})"><i
                                    class="fa-solid fa-trash"></i> Hapus
                                Jabatan</button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" class="text-center">Jabatan Tidak Ditemukan</td>
                </tr>
            @endforelse
        </table>
    @endif
</div>
