<?php

use Livewire\Volt\Component;
use App\Models\WewenangDiajukan;

new class extends Component {
    public $jabatan;
    public $nama;
    public $editedWewenang = null;
    public $editedNama = '';

    public function mount($jabatan) {
        $this->jabatan = $jabatan;
        $this->nama = '';

    }

    public function addWewenang() {
        $validated = $this->validate([
            'nama' => 'required|string'
        ]);

        $this->jabatan->wewenang()->create($validated);

        $this->reset('nama');
    }

    public function deleteWewenang(WewenangDiajukan $wewenang) {
        $wewenang->delete();
    
    }

    public function editWewenang(WewenangDiajukan $wewenang) {
        $this->editedWewenang = $wewenang->id;
        $this->editedNama = $wewenang->nama;
    }

    public function storeWewenang(WewenangDiajukan $wewenang) {
        // dd('test');
        $validated = $this->validate([
            'editedNama' => 'required',
        ]);
        
        $wewenang->update([
            'nama' => $validated['editedNama'],
        ]);
        $this->reset('editedWewenang', 'editedNama');
    }
}; ?>

<div class="mb-3">
    <label for="wewenang" class="form-label">Wewenang</label>
    <table class="table table-bordered" id="wewenang">
        <thead class="table-info">
            <th style="width: 50px;">No</th>
            <th>Wewenang</th>
            <th style="width: 125px;">Aksi</th>
        </thead>
        <tbody>
            @foreach ($jabatan->wewenang as $wewenang)
                @if ($editedWewenang == $wewenang->id)
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
                            <button type="submit" class="btn btn-primary"  wire:click="storeWewenang({{ $wewenang }})"><i class="fa-solid fa-floppy-disk"></i>
                                Simpan</button>
                        </td>
                    </tr>
                @else
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="d-flex justify-content-between">
                            <p>{{ $wewenang->nama }}</p>
                        </td>
                        <td>
                            <button type="submit" class="btn btn-warning" wire:click="editWewenang({{ $wewenang }})">
                                <i class="fa-solid fa-edit"></i>
                            </button>
                            <button type="submit" class="btn btn-danger" wire:click="deleteWewenang({{ $wewenang }})">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @endif
            @endforeach
            <tr>
                <form wire:submit="addWewenang" method="POST">
                    @csrf
                    <td></td>
                    <td class="d-flex justify-content-between">
                        <input name="nama" type="text" class="form-control"
                            placeholder="Masukkan Wewenang" wire:model="nama">
                    </td>
                    <td>
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Tambah</button>
                    </td>
                </form>
            </tr>
        </tbody>
    </table>
</div>
