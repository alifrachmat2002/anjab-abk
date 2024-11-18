<?php

use Livewire\Volt\Component;
use App\Models\PendidikanFormalDiajukan;


new class extends Component {
    public $jabatan;
    public $pendidikanFormal = [];
    public $id;
    public $jenjang = '';
    public $jurusan = '';
    public $editedPendidikan = null;
    public $editedJenjang = '';
    public $editedJurusan = '';

    public function mount($jabatan)
    {
        $this->jabatan = $jabatan;
        // $this->pendidikanFormal = $pendidikanformal;
        $this->id = $this->jabatan->id;
    }

      public function addPendidikanFormal()
    {
        $validated = $this->validate([
            'jenjang' => 'required',
            'jurusan' => 'required',
        ]);
        $this->jabatan->pendidikanFormal()->create($validated);
        $this->reset(['jenjang', 'jurusan']);
    }

    public function deletePendidikan(PendidikanFormalDiajukan $pendidikan)
    {
        $pendidikan->delete();
    }

    public function editPendidikan(PendidikanFormalDiajukan $pendidikan) {
        $this->editedPendidikan = $pendidikan->id;
        $this->editedJenjang = $pendidikan->jenjang;
        $this->editedJurusan = $pendidikan->jurusan;
    }

    public function storePendidikan(PendidikanFormalDiajukan $pendidikan) {
        $validated = $this->validate([
            'editedJenjang' => 'required',
            'editedJurusan' => 'required',
        ]);
        
        $pendidikan->update([
            'jenjang' => $validated['editedJenjang'],
            'jurusan' => $validated['editedJurusan'],
        ]);
        $this->reset('editedPendidikan', 'editedJenjang', 'editedJurusan');
    }
}; ?>

<div>
    <table class="table 
                    table-bordered" id="pendidikan_formal">
        <caption class="caption-top fs-6">Kualifikasi Jabatan | Pendidikan Formal</caption>
        <thead class="table-primary">
            <th style="width: 50px;">No</th>
            <th>Jenjang</th>
            <th>Jurusan</th>
            <th style="width: 125px;">Aksi</th>
        </thead>
        <tbody>
            @foreach ($this->jabatan->pendidikanFormal as $pendidikan)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    @if ($editedPendidikan === $pendidikan->id)
                    <form action="" wire:submit="storePendidikan({{ $pendidikan }})">
                        @csrf
                        <td>
                            <select name="jenjang" class="form-select @error('editedJenjang') is-invalid @enderror" id="jenjang" wire:model.change="editedJenjang">
                                <option value="" >Pilih Jenjang Pendidikan</option>
                                <option value="SMA" >SMA</option>
                                <option value="D3" >D-3</option>
                                <option value="D4" >D-4</option>
                                <option value="S1" >S-1</option>
                                <option value="S2" >S-2</option>
                                <option value="S3" >S-3</option>
                            </select>
                            @error('editedJenjang')
                                <label class="invalid-feedback form-label" for="jenjang" >{{ $message }}</label>
                            @enderror   
                        </td>
                        <td>
                            <input class="form-control @error('editedJenjang') is-invalid @enderror" type="text" name="jurusan" wire:model.change="editedJurusan" id="jurusan" value="{{ $pendidikan->jurusan }}">
                            @error('editedJurusan')
                                <label class="invalid-feedback form-label" for="jurusan" >{{ $message }}</label>
                            @enderror   
                        </td>
                        <td>
                            <button type="submit" class="btn btn-primary" wire:click="storePendidikan({{ $pendidikan }})">
                                <i class="fa-solid fa-floppy-disk"></i>
                                Simpan
                            </button>
                        </td>
                    </form>
                        @else
                            <td>{{ $pendidikan->jenjang }}</td>
                            <td>{{ $pendidikan->jurusan }}</td>
                            <td class="d-flex gap-1">
                                <button type="submit" class="btn btn-warning" wire:click="editPendidikan({{ $pendidikan }})">
                                    <i class="fa-solid fa-edit"></i>
                                </button>
                                <button type="submit" class="btn btn-danger" wire:click="deletePendidikan({{ $pendidikan->id }})">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </td>
                        @endif
                    
                </tr>
            @endforeach
            <tr>
                <td></td>
                <form action="" wire:submit="addPendidikanFormal">
                    @csrf
                    <td>
                        <select name="jenjang" class="form-select" id="" wire:model="jenjang">
                            <option value="" selected>Pilih Jenjang Pendidikan</option>
                            <option value="SMA">SMA</option>
                            <option value="D3">D-3</option>
                            <option value="D4">D-4</option>
                            <option value="S1">S-1</option>
                            <option value="S2">S-2</option>
                            <option value="S3">S-3</option>
                        </select>
                    </td>
                    <td>
                        <input type="text" name="jurusan" class="form-control" placeholder="Masukkan Jurusan"
                            wire:model="jurusan">
                    </td>
                    <td>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-plus"></i>
                            Tambah
                        </button>
                    </td>
                </form>
                </form>
            </tr>
        </tbody>
    </table>
</div>
