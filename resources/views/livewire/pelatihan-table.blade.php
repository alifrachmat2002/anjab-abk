<?php

use Livewire\Volt\Component;
use App\Models\PendidikanPelatihanDiajukan;

new class extends Component {
    public $jabatan;
    public $pelatihan;
    public $editedPelatihan = null;
    public $editedNama = '';

    public function mount($jabatan)
    {
        $this->jabatan = $jabatan;
        $this->pelatihan = '';
        $this->editedPelatihan = null;
        $this->editedNama = '';
        
    }

    public function addPelatihan()
    {
        $this->validate([
            'pelatihan' => 'required|string',
        ]);

        $this->jabatan->pendidikanPelatihan()->create([
            'nama' => $this->pelatihan,
        ]);

        $this->reset('pelatihan');
    }

    public function deletePelatihan(PendidikanPelatihanDiajukan $pelatihan)
    {
        $pelatihan->delete();
    }

    public function editPelatihan(PendidikanPelatihanDiajukan $pelatihan) {
        $this->editedPelatihan = $pelatihan->id;
        $this->editedNama = $pelatihan->nama;
    }

    public function storePelatihan(PendidikanPelatihanDiajukan $pelatihan) {
        // dd($pelatihan, $this->editedJenjang, $this->editedJurusan);
        $validated = $this->validate([
            'editedNama' => 'required',
        ]);
        
        $pelatihan->update([
            'nama' => $validated['editedNama'],
        ]);
        $this->reset('editedPelatihan', 'editedNama');
    }
}; ?>

<div>
    <table class="table table-bordered" id="pelatihan">
        <caption class="caption-top"> Kualifikasi Jabatan | Pelatihan</caption>
        <thead class="table-primary">
            <th style="width: 50px;">No</th>
            <th>Jenis Pelatihan</th>
            <th style="width: 125px;">Aksi</th>
        </thead>
        <tbody>
            @foreach ($jabatan->pendidikanPelatihan as $pelatihan)
                @if ($editedPelatihan == $pelatihan->id )
                    <tr>
                        <form action="">
                            @csrf
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <input name="editedNama" type="text" class="form-control @error('editedNama') is-invalid @enderror" placeholder="Masukkan Nama Pelatihan" wire:model="editedNama">
                                @error('editedNama')
                                    <label class="invalid-feedback form-label" for="editedNama" >{{ $message }}</label>
                                @enderror  
                            </td>
                            <td>
                                <button type="button" class="btn btn-primary" wire:click="storePelatihan({{ $pelatihan }})">
                                    <i class="fa-solid fa-save"></i>
                                    Simpan
                                </button>
                            </td>
                        </form>
                    </tr>
                @else
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $pelatihan->nama }}</td>
                        <td class="d-flex gap-1">
                            <button type="submit" class="btn btn-warning" wire:click="editPelatihan({{ $pelatihan }})">
                                    <i class="fa-solid fa-edit"></i>
                            </button>
                            <button wire:click="deletePelatihan({{ $pelatihan }})" class="btn btn-danger">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @endif
            @endforeach
            <tr>
                <td></td>
                <td class="d-flex justify-content-between">
                    <input name="nama" type="text" class="form-control w-100"
                        placeholder="Masukkan Nama Pelatihan" wire:model="pelatihan">
                </td>
                <td>
                    <button wire:click="addPelatihan" class="btn btn-primary">
                        <i class="fa-solid fa-plus"></i>
                        Tambah
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</div>
