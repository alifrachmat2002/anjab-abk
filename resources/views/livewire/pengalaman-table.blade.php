<?php

use Livewire\Volt\Component;
use App\Models\PengalamanDiajukan;



new class extends Component {
    public $jabatan;
    public $id;
    public $nama;
    public $lama;
    public $editedPengalaman = null;
    public $editedNama = '';
    public $editedLama = '';

    public function mount($jabatan)
    {
        $this->jabatan = $jabatan;
        $this->id = $this->jabatan->id;
        $this->nama = '';
        $this->lama = '';
    }

    public function addPengalaman()
    {
        $validated = $this->validate([
            'nama' => 'required',
            'lama' => 'required',
        ]);

        $this->jabatan->pengalaman()->create($validated);
        $this->reset(['nama', 'lama']);
    }

    public function deletePengalaman(PengalamanDiajukan $pengalaman)
    {
        $pengalaman->delete();
    }

    public function editPengalaman(PengalamanDiajukan $pengalaman) {
        $this->editedPengalaman = $pengalaman->id;
        $this->editedNama = $pengalaman->nama;
        $this->editedLama = $pengalaman->lama;
    }

    public function storePengalaman(PengalamanDiajukan $pengalaman) {
        // dd($Pengalaman, $this->editedJenjang, $this->editedJurusan);
        $validated = $this->validate([
            'editedNama' => 'required',
            'editedLama' => 'required',
        ]);
        
        $pengalaman->update([
            'nama' => $validated['editedNama'],
            'lama' => $validated['editedLama'],
        ]);
        $this->reset('editedPengalaman', 'editedNama', 'editedLama');
    }
}; ?>

<div>
    <table class="table table-bordered" id="pengalaman">
        <caption class="caption-top">Kualifikasi Jabatan | Pengalaman</caption>
        <thead class="table-primary">
            <th style="width: 50px;">No</th>
            <th>Nama Pengalaman</th>
            <th>Lama Pengalaman (Tahun)</th>
            <th style="width: 125px;">Aksi</th>
        </thead>
        <tbody>
            @foreach ($jabatan->pengalaman as $pengalaman)
                @if ($editedPengalaman === $pengalaman->id)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <input name="editedNama" type="text" class="form-control @error('editedNama') is-invalid @enderror" placeholder="Masukkan Nama Pengalaman" id="editedNama" wire:model="editedNama">
                            @error('editedNama')
                                <label class="invalid-feedback form-label" for="editedNama" >{{ $message }}</label>
                            @enderror   
                        </td>
                        <td><input name="editedLama" type="text" class="form-control @error('editedLama') is-invalid @enderror" placeholder="Masukkan Lama Pengalaman"
                                wire:model="editedLama" id="editedLama">
                            @error('editedLama')
                                <label class="invalid-feedback form-label" for="editedLama" >{{ $message }}</label>
                            @enderror   
                        </td>
                        <td>
                            <button type="button" class="btn btn-primary" wire:click="storePengalaman({{ $pengalaman }})">
                                <i class="fa-solid fa-save"></i>
                                Simpan
                            </button>
                        </td>
                    </tr>
                @else
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $pengalaman->nama }}</td>
                        <td>{{ $pengalaman->lama }}</td>
                        <td class="d-flex gap-1">
                            <button type="button" class="btn btn-warning" wire:click="editPengalaman({{ $pengalaman }})">
                                        <i class="fa-solid fa-edit"></i>
                                    </button>
                            <button type="button" class="btn btn-danger" wire:click="deletePengalaman({{ $pengalaman }})">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @endif
            @endforeach
            <tr>
                <form wire:submit="addPengalaman">
                    <td></td>
                    <td><input name="nama" type="text" class="form-control" placeholder="Masukkan Nama Pengalaman"
                            wire:model="nama">
                    </td>
                    <td><input name="lama" type="text" class="form-control" placeholder="Masukkan Lama Pengalaman"
                            wire:model="lama">
                    </td>
                    <td>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-plus"></i>
                            Tambah
                        </button>
                    </td>
                </form>
            </tr>
        </tbody>
    </table>
</div>
