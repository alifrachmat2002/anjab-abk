<?php

use Livewire\Volt\Component;
use App\Models\TanggungJawabDiajukan;

new class extends Component {
    public $jabatan;
    public $nama = '';
    public $editedTanggungJawab = null;
    public $editedNama = '';

    public function mount($jabatan) {
        $this->jabatan = $jabatan;
        }

    public function addTanggungJawab() {
        $validated = $this->validate([
            'nama' => 'required|string'
        ]);

        $this->jabatan->tanggungJawab()->create($validated);

        $this->reset('nama');
    }

    public function deleteTanggungJawab(TanggungJawabDiajukan $tanggungjawab) {
        $tanggungjawab->delete();
    
    }

    public function editTanggungJawab(TanggungJawabDiajukan $tanggungJawab) {
        $this->editedTanggungJawab = $tanggungJawab->id;
        $this->editedNama = $tanggungJawab->nama;
    }

    public function storeTanggungJawab(TanggungJawabDiajukan $tanggungJawab) {
        // dd('test');
        $validated = $this->validate([
            'editedNama' => 'required',
        ]);
        
        $tanggungJawab->update([
            'nama' => $validated['editedNama'],
        ]);
        $this->reset('editedTanggungJawab', 'editedNama');
    }
}; ?> 

<div class="mb-3">
    <label for="tanggung_jawab" class="form-label">Tanggung Jawab</label>
    <table class="table table-bordered" id="tanggung_jawab">
        <thead class="table-info">
            <th style="width: 50px;">No</th>
            <th>Uraian Tanggung Jawab</th>
            <th style="width: 125px;">Aksi</th>
        </thead>
        <tbody>
            @foreach ($jabatan->tanggungJawab as $tanggungJawab)
                @if ($editedTanggungJawab == $tanggungJawab->id)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="">
                            <input name="nama" type="text" class="form-control @error('editedNama') is-invalid @enderror"
                                placeholder="Masukkan Uraian Tugas" wire:model="editedNama" id="editedNama">
                            @error('editedNama')
                                <label class="invalid-feedback form-label" for="editedNama" >{{ $message }}</label>
                            @enderror  
                        </td>
                        <td>
                            <button type="submit" class="btn btn-primary"  wire:click="storeTanggungJawab({{ $tanggungJawab }})"><i class="fa-solid fa-floppy-disk"></i>
                                Simpan</button>
                        </td>
                    </tr>
                @else
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td class="d-flex justify-content-between">
                        <p>{{ $tanggungJawab->nama }}</p>
                    </td>
                    <td>
                        <button type="submit" class="btn btn-warning" wire:click="editTanggungJawab({{ $tanggungJawab }})">
                            <i class="fa-solid fa-edit"></i>
                        </button>
                        <button wire:click="deleteTanggungJawab({{ $tanggungJawab }})" class="btn btn-danger">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @endif
            @endforeach
            <tr>
                <form wire:submit="addTanggungJawab" method="POST">
                    @csrf
                    <td></td>
                    <td class="d-flex justify-content-between">
                        <input name="nama" type="text" class="form-control"
                            placeholder="Masukkan Tanggung Jawab" wire:model="nama">
                    </td>
                    <td>
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-plus"></i>
                        Tambah</button>
                    </td>
                </form>
            </tr>
        </tbody>
    </table>
</div>
