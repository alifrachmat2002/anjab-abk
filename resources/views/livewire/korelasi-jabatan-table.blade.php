<?php

use Livewire\Volt\Component;
use App\Models\UnitKerja;
use App\Models\JabatanDiajukan;
use App\Models\KorelasiJabatanDiajukan;

new class extends Component {
    public $jabatan;
    public $jabatanKorelasi;
    public $jabatan_relasi_id;
    public $dalam_hal;
    public $editedKorelasiJabatan = null;
    public $editedJabatanRelasi = '';
    public $editedDalamHal = '';

    public function mount($jabatan)
    {
        $this->jabatan = $jabatan;
        $this->nama = '';
        $this->jabatanKorelasi = JabatanDiajukan::all();
        $this->jabatan_relasi_id = null;
        $this->dalam_hal = '';
    }

    public function addKorelasiJabatan()
    {
        $validated = $this->validate([
            'jabatan_relasi_id' => 'required|string',
            'dalam_hal' => 'required|string',
        ]);

        $this->jabatan->korelasiJabatan()->create($validated);

        $this->reset('jabatan_relasi_id', 'dalam_hal');
    }

    public function deleteKorelasiJabatan(KorelasiJabatanDiajukan $korelasiJabatan)
    {
        $korelasiJabatan->delete();
    }

    public function editKorelasiJabatan(KorelasiJabatanDiajukan $korelasi) {
        $this->editedKorelasiJabatan = $korelasi->id;
        $this->editedJabatanRelasi = $korelasi->jabatan_relasi_id;
        $this->editedDalamHal = $korelasi->dalam_hal;
    }

    public function storeKorelasiJabatan(KorelasiJabatanDiajukan $korelasi) {
        $validated = $this->validate([
            'editedJabatanRelasi' => 'required',
            'editedDalamHal' => 'required',
        ]);
        
        $korelasi->update([
            'jenjang' => $validated['editedJabatanRelasi'],
            'jurusan' => $validated['editedDalamHal'],
        ]);
        $this->reset('editedKorelasiJabatan', 'editedJabatanRelasi', 'editedDalamHal');
    }
}; ?>

<div class="mb-3">
    <label for="korelasi_jabatan" class="form-label">Korelasi Jabatan</label>
    <table class="table table-bordered" id="korelasi_jabatan">
        <thead class="table-info">
            <th style="width: 50px;">No</th>
            <th>Jabatan</th>
            <th>Dalam Hal</th>
            <th style="width: 125px;">Aksi</th>
        </thead>
        <tbody>
            @foreach ($jabatan->korelasiJabatan as $korelasi)
            @if ($editedKorelasiJabatan === $korelasi->id)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <form wire:submit="storeKorelasiJabatan({{ $korelasi }})">
                        @csrf
                        <td>
                            <select name="" id="" class="form-select @error('editedJabatanRelasi') is-invalid @enderror" wire:model.change="editedJabatanRelasi">
                                <option value="">Pilih Jabatan Korelasi</option>
                                @foreach ($jabatanKorelasi as $jabatan)
                                    <option value="{{ $jabatan->id }}">{{ $jabatan->nama }}</option>
                                @endforeach
                            </select>
                            @error('editedJabatanRelasi')
                                <label class="invalid-feedback form-label" for="jenjang" >{{ $message }}</label>
                            @enderror 
                        </td>
                        <td>
                            <input type="text" class="form-control @error('editedDalamHal') is-invalid @enderror" placeholder="Masukkan Korelasi Jabatan"
                                wire:model.change="editedDalamHal">
                            @error('editedDalamHal')
                                <label class="invalid-feedback form-label" for="jenjang" >{{ $message }}</label>
                            @enderror 
                        </td>
                        <td>
                            <button type="submit" class="btn btn-primary" wire:click="storeKorelasiJabatan({{ $korelasi }})"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
                        </td>
                    </form>
                </tr>
            @else
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $korelasi->jabatanRelasi->nama }}</td>
                    <td>{{ $korelasi->dalam_hal }}</td>
                    <td>
                        <button class="btn btn-warning" wire:click="editKorelasiJabatan({{ $korelasi }})">
                            <i class="fa-solid fa-edit"></i>
                        </button>
                        <button class="btn btn-danger" wire:click="deleteKorelasiJabatan({{ $korelasi }})">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </td>
                </tr>
            @endif
            @endforeach
            <tr>
                <td></td>
                <form wire:submit="addKorelasiJabatan">
                    @csrf
                    <td>
                        <select name="" id="" class="form-select" wire:model="jabatan_relasi_id">
                            <option value="">Pilih Jabatan Korelasi</option>
                            @foreach ($jabatanKorelasi as $jabatan)
                                <option value="{{ $jabatan->id }}">{{ $jabatan->nama }}</option>
                            @endforeach
                        </select>
                        
                    </td>
                    <td>
                        <input type="text" class="form-control" placeholder="Masukkan Korelasi Jabatan"
                            wire:model="dalam_hal">
                    </td>
                    <td>
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Tambah</button>
                    </td>
                </form>
            </tr>
        </tbody>
    </table>
</div>
