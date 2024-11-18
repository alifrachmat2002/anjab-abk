<?php

use Livewire\Volt\Component;
use App\Models\PerangkatKerjaDiajukan;

new class extends Component {
    public $jabatan;
    public $nama = '';
    public $penggunaan = '';
    public $editedPerangkatKerja = null;
    public $editedNama = null;
    public $editedPenggunaan = null;
    
    public function mount($jabatan) {
        $this->jabatan = $jabatan;
    }

    public function addPerangkatKerja() {
        $validated = $this->validate([
            'nama' => 'required|string',
            'penggunaan' => 'required|string'
            ]);

        $this->jabatan->perangkatKerja()->create($validated);

        $this->reset('nama','penggunaan');
    }

    public function deletePerangkatKerja(PerangkatKerjaDiajukan $perangkatKerja) {
        $perangkatKerja->delete();
    
    }

    public function editPerangkatKerja(PerangkatKerjaDiajukan $perangkatKerja) {
        $this->editedPerangkatKerja = $perangkatKerja->id;
        $this->editedNama = $perangkatKerja->nama;
        $this->editedPenggunaan = $perangkatKerja->penggunaan;
    }

    public function storePerangkatKerja(PerangkatKerjaDiajukan $perangkatKerja) {
        // dd('test');
        $validated = $this->validate([
            'editedNama' => 'required|string',
            'editedPenggunaan' => 'required|string',
        ]);
        
        $perangkatKerja->update([
            'nama' => $validated['editedNama'],
            'penggunaan' => $validated['editedPenggunaan'],
        ]);
        $this->reset('editedPerangkatKerja', 'editedNama', 'editedPenggunaan');
    }
}; ?>

<div class="mb-3">
    <label for="perangkat_kerja" class="form-label">Perangkat Kerja</label>
    <table class="table table-bordered" id="PerangkatKerja">
        <thead class="table-secondary">
            <th style="width: 50px;">No</th>
            <th>Perangkat Kerja</th>
            <th>Penggunaan dalam pekerjaan</th>
            <th style="width: 125px;">Aksi</th>
        </thead>
        <tbody>
            @foreach ($jabatan->perangkatKerja as $perangkatKerja)
                @if ($editedPerangkatKerja === $perangkatKerja->id)
                    <form >
                        @csrf
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <input name="nama" type="text" class="form-control @error('editedNama') is-invalid @enderror" placeholder="Masukkan Perangkat Kerja" wire:model="editedNama">
                                @error('editedNama')
                                    <label class="invalid-feedback form-label" for="editedNama" >{{ $message }}</label>
                                @enderror
                            </td>
                            <td>
                                <input name="penggunaan" type="text" class="form-control @error('editedPenggunaan') is-invalid @enderror" placeholder="Masukkan Perangkat Kerja" wire:model="editedPenggunaan">
                                @error('editedPenggunaan')
                                    <label class="invalid-feedback form-label" for="editedPenggunaan" >{{ $message }}</label>
                                @enderror
                            </td>
                            <td><button type="submit" class="btn btn-primary" wire:click="storePerangkatKerja({{ $perangkatKerja }})"><i class="fa-solid fa-floppy-disk"></i>
                                    Simpan</button>
                            </td>
                        </tr>
                    </form>
                @else
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $perangkatKerja->nama }}</td>
                        <td>{{ $perangkatKerja->penggunaan }}</td>
                        <td class="d-flex gap-1">
                            <button wire:click="editPerangkatKerja({{ $perangkatKerja }})" class="btn btn-warning">
                                <i class="fa-solid fa-edit"></i>
                            </button>
                            <button wire:click="deletePerangkatKerja({{ $perangkatKerja }})" class="btn btn-danger">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @endif
                
            @endforeach
            <tr>
                <form wire:submit="addPerangkatKerja()">
                    @csrf
                    <td></td>
                    <td>
                        <input name="nama" type="text" class="form-control" placeholder="Masukkan Perangkat Kerja" wire:model="nama">
                    </td>
                    <td>
                        <input name="penggunaan" type="text" class="form-control" placeholder="Masukkan Perangkat Kerja" wire:model="penggunaan">
                    </td>
                    <td><button type="submit" class="btn btn-primary"><i class="fa-solid fa-plus"></i>
                            Tambah</button></td>
                </form>
            </tr>
        </tbody>
    </table>
</div>