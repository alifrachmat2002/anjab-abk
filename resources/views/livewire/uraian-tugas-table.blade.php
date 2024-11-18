<?php

use Livewire\Volt\Component;
use App\Models\UraianTugasDiajukan;

new class extends Component {
    public $jabatan;
    public $uraian;
    public $editedUraianTugas = null;
    public $editedUraian = '';

    public function mount($jabatan) {
        $this->jabatan = $jabatan;
        $this->uraian = '';
    }

    public function addUraianTugas() {
        $this->validate([
            'uraian' => 'required|string'
        ]);

        $this->jabatan->uraianTugas()->create([
            'nama_tugas' => $this->uraian
        ]);

        $this->reset('uraian');
    
    }

    public function deleteUraianTugas(UraianTugasDiajukan $uraian) {
        $uraian->delete();
    }

    public function editUraianTugas(UraianTugasDiajukan $uraianTugas) {
        $this->editedUraianTugas = $uraianTugas->id;
        $this->editedUraian = $uraianTugas->nama_tugas;
    }

    public function storeUraianTugas(UraianTugasDiajukan $uraianTugas) {
        // dd('test');
        $validated = $this->validate([
            'editedUraian' => 'required',
        ]);
        
        $uraianTugas->update([
            'nama_tugas' => $validated['editedUraian'],
        ]);
        $this->reset('editedUraianTugas', 'editedUraian');
    }
    
}; ?>

<div class="mb-3">
    <table class="table table-bordered" id="uraian_tugas">
        <caption class="caption-top text-black">Uraian Tugas</caption>
        <thead class="table-info">
            <th style="width: 50px;">No</th>
            <th>Uraian Tugas</th>
            <th style="width: 125px;">Aksi</th>
        </thead>
        <tbody>
            @foreach ($jabatan->uraianTugas as $uraian)
                
                @if ($editedUraianTugas == $uraian->id)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="">
                            <input name="nama_tugas" type="text" class="form-control @error('editedUraian') is-invalid @enderror"
                                placeholder="Masukkan Uraian Tugas" wire:model="editedUraian" id="editedUraian">
                            @error('editedUraian')
                                <label class="invalid-feedback form-label" for="editedUraian" >{{ $message }}</label>
                            @enderror  
                        </td>
                        <td>
                            <button type="submit" class="btn btn-primary"  wire:click="storeUraianTugas({{ $uraian }})"><i class="fa-solid fa-floppy-disk"></i>
                                Simpan</button>
                        </td>
                    </tr>
                @else
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="d-flex justify-content-between">
                            <p>{{ $uraian->nama_tugas }}</p>
                        </td>
                        <td class="">
                            <button type="submit" class="btn btn-warning" wire:click="editUraianTugas({{ $uraian }})">
                                <i class="fa-solid fa-edit"></i>
                        </button>
                            <button type="button" class="btn btn-danger" wire:click="deleteUraianTugas({{ $uraian }})">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>   
                    </tr>
                @endif
            @endforeach
            <tr>
                <td></td>
                {{-- <form action="" wire:submit="addUraianTugas()"> --}}
                    @csrf
                    <td class="">
                        <input name="nama_tugas" type="text" class="form-control"
                            placeholder="Masukkan Uraian Tugas" wire:model="uraian">
                    </td>
                    <td>
                        <button type="submit" class="btn btn-primary " wire:click="addUraianTugas()"><i class="fa-solid fa-plus"></i>
                            Tambah
                        </button>
                    </td>
                {{-- </form> --}}
            </tr>
        </tbody>
    </table>
</div>
