<?php

use Livewire\Volt\Component;
use App\Models\BahanKerjaDiajukan;

new class extends Component {
    public $jabatan;
    public $nama = '';
    public $penggunaan = '';
    public $editedBahanKerja = null;
    public $editedNama = null;
    public $editedPenggunaan = null;

    public function mount($jabatan)
    {
        $this->jabatan = $jabatan;
    }

    public function addBahanKerja() {
        $validated = $this->validate([
            'nama' => 'required|string',
            'penggunaan' => 'required|string'
        ]);

        $this->jabatan->bahanKerja()->create($validated);

        $this->reset('nama','penggunaan');
    }

    public function deleteBahanKerja(BahanKerjaDiajukan $bahanKerja) {
        $bahanKerja->delete();

    }

    public function editBahanKerja(BahanKerjaDiajukan $bahanKerja) {
        $this->editedBahanKerja = $bahanKerja->id;
        $this->editedNama = $bahanKerja->nama;
        $this->editedPenggunaan = $bahanKerja->penggunaan;
    }

    public function storeBahanKerja(BahanKerjaDiajukan $bahanKerja) {
        // dd($bahanKerja, $this->editedJenjang, $this->editedJurusan);
        $validated = $this->validate([
            'editedNama' => 'required',
            'editedPenggunaan' => 'required',
        ]);
        
        $bahanKerja->update([
            'nama' => $validated['editedNama'],
            'penggunaan' => $validated['editedPenggunaan'],
        ]);
        $this->reset('editedBahanKerja', 'editedNama', 'editedPenggunaan');
    }
}; ?>

<div class="mb-3">
    <label for="bahan_kerja" class="form-label">Bahan Kerja</label>
    <table class="table table-bordered" id="BahanKerja">
        <thead class="table-primary">
            <th style="width: 50px;">No</th>
            <th>Bahan Kerja</th>
            <th>Penggunaan dalam pekerjaan</th>
            <th style="width: 125px;">Aksi</th>
        </thead>
        <tbody>
            @foreach ($jabatan->bahanKerja as $bahanKerja)
                @if ($editedBahanKerja === $bahanKerja->id)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <input name="editedNama" type="text" class="form-control @error('editedNama') is-invalid @enderror" placeholder="Masukkan Nama Pengalaman" id="editedNama" wire:model="editedNama">
                            @error('editedNama')
                                <label class="invalid-feedback form-label" for="editedNama" >{{ $message }}</label>
                            @enderror   
                        </td>
                        <td><input name="editedPenggunaan" type="text" class="form-control @error('editedPenggunaan') is-invalid @enderror" placeholder="Masukkan Lama Pengalaman"
                                wire:model="editedPenggunaan" id="editedPenggunaan">
                            @error('editedPenggunaan')
                                <label class="invalid-feedback form-label" for="editedPenggunaan" >{{ $message }}</label>
                            @enderror   
                        </td>
                        <td>
                            <button type="button" class="btn btn-primary" wire:click="storeBahanKerja({{ $bahanKerja }})">
                                <i class="fa-solid fa-save"></i>
                                Simpan
                            </button>
                        </td>
                    </tr>
                @else
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $bahanKerja->nama }}</td>
                        <td>{{ $bahanKerja->penggunaan }}</td>
                        <td class="d-flex gap-1">
                            <button type="button" class="btn btn-warning" wire:click="editBahanKerja({{ $bahanKerja }})">
                                <i class="fa-solid fa-edit"></i>
                            </button>
                            <button wire:click="deleteBahanKerja({{ $bahanKerja }})" class="btn btn-danger">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @endif
            @endforeach
            <tr>
                <form wire:submit="addBahanKerja()" method="POST">
                    @csrf
                    <td></td>
                    <td>
                        <input name="nama" type="text" class="form-control" placeholder="Masukkan Bahan Kerja" wire:model="nama">
                    </td>
                    <td>
                        <input name="penggunaan" type="text" class="form-control" placeholder="Masukkan Penggunaan dalam pekerjaan" wire:model="penggunaan">
                    </td>
                    <td><button type="submit" class="btn btn-primary"><i class="fa-solid fa-plus"></i>
                            Tambah</button></td>
                </form>
            </tr>
        </tbody>
    </table>
</div>
