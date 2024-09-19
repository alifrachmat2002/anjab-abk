<?php

use Livewire\Volt\Component;
use App\Models\UnitKerja;


new class extends Component {
    public $unitkerjas;
    public $search;
    public $anjab;

    public function with() {
        if ($this->search) {
            return [
                'unit' => UnitKerja::where('nama','like','%' . $this->search . '%')->get()
            ];
        }
        return [];
    }

}; ?>

<div>
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        Silahkan Pilih Unit Kerja yang ingin dilihat Peta Jabatannya
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <div class="form-floating mb-3 w-25 ">
        <input type="email" wire:model.live="search" class="form-control" id="search" placeholder="Cari Jabatan">
        <label for="search">Cari Nama Unit Kerja</label>
    </div>
    <table class="table table-bordered table-striped w-75">
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th>Unit Kerja</th>
            </tr>
        </thead>
        <tbody>
            @if ($this->search)
                @foreach ($unit as $unitkerja)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <div class="d-flex justify-content-between">
                                {{ $unitkerja->nama }}
                                <a href="{{ route('laporan.petajabatan.unit', ['anjab' => $anjab, 'unit' => $unitkerja]) }}" class="btn btn-primary">Lihat</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @else
                @foreach ($this->unitkerjas as $unitkerja)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <div class="d-flex justify-content-between">
                                {{ $unitkerja->nama }}
                                 <a href="{{ route('laporan.petajabatan.unit', ['anjab' => $anjab, 'unit' => $unitkerja]) }}" class="btn btn-primary">Lihat</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @endif

        </tbody>
    </table>
</div>
