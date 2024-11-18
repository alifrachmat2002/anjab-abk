<?php

use Livewire\Volt\Component;
use App\Models\RisikoBahayaDiajukan;

new class extends Component {
    public $jabatan;
    public $bahaya_fisik;
    public $penyebab;
    public $editedRisikoBahaya = null;
    public $editedBahayaFisik = null;
    public $editedPenyebab = null;

    
    public function mount($jabatan) {
        $this->jabatan = $jabatan;
        $this->bahaya_fisik = '';
        $this->penyebab = '';
    }

    public function addRisikoBahaya() {
        $validated = $this->validate([
            'bahaya_fisik' => 'required|string',
            'penyebab' => 'required|string',
        ]);

        $this->jabatan->RisikoBahaya()->create($validated);

        $this->reset('bahaya_fisik', 'penyebab');
    }

    public function deleteRisikoBahaya(RisikoBahayaDiajukan $risikoBahaya) {
        $risikoBahaya->delete();
    
    }
    public function editRisikoBahaya(RisikoBahayaDiajukan $risikoBahaya) {
        $this->editedRisikoBahaya = $risikoBahaya->id;
        $this->editedBahayaFisik = $risikoBahaya->bahaya_fisik;
        $this->editedPenyebab = $risikoBahaya->penyebab;
    }

    public function storeRisikoBahaya(RisikoBahayaDiajukan $risikoBahaya) {
        // dd('test');
        $validated = $this->validate([
            'editedBahayaFisik' => 'required|string',
            'editedPenyebab' => 'required|string',
        ]);
        
        $risikoBahaya->update([
            'bahaya_fisik' => $validated['editedBahayaFisik'],
            'penyebab' => $validated['editedPenyebab'],
        ]);
        $this->reset('editedRisikoBahaya', 'editedBahayaFisik', 'editedPenyebab');
    }
}; ?>

<div class="mb-3">
    <label for="risiko_bahaya" class="form-label">Risiko Bahaya</label>
    <table class="table 
                table-bordered" id="risiko_bahaya">
        <thead class="table-danger">
            <th style="width: 50px;">No</th>
            <th>Risiko bahaya</th>
            <th>Penyebab</th>
            <th style="width: 125px;">Aksi</th>
        </thead>
        <tbody>
            @foreach ($jabatan->risikoBahaya as $risikoBahaya)
                @if ($editedRisikoBahaya === $risikoBahaya->id)
                    <form >
                        @csrf
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>    
                                <input name="nama" type="text" class="form-control @error('editedBahayaFisik') is-invalid @enderror" placeholder="Masukkan Perangkat Kerja" wire:model="editedBahayaFisik">
                                @error('editedBahayaFisik')
                                    <label class="invalid-feedback form-label" for="editedBahayaFisik" >{{ $message }}</label>
                                @enderror
                            </td>
                            <td>
                                <input name="penggunaan" type="text" class="form-control @error('editedPenyebab') is-invalid @enderror" placeholder="Masukkan Perangkat Kerja" wire:model="editedPenyebab">
                                @error('editedPenyebab')
                                    <label class="invalid-feedback form-label" for="editedPenyebab" >{{ $message }}</label>
                                @enderror
                            </td>
                            <td>
                                <button type="submit" class="btn btn-primary" wire:click="storeRisikoBahaya({{ $risikoBahaya }})"><i class="fa-solid fa-floppy-disk"></i>
                                    Simpan</button>
                            </td>
                        </tr>
                    </form>
                @else
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $risikoBahaya->bahaya_fisik }}</td>
                        <td>{{ $risikoBahaya->penyebab }}</td>
                        <td class="d-flex gap-1">
                            <button wire:click="editRisikoBahaya({{ $risikoBahaya }})" class="btn btn-warning">
                                <i class="fa-solid fa-edit"></i>
                            </button>
                            <button wire:click="deleteRisikoBahaya({{ $risikoBahaya }})" type="submit" class="btn btn-danger">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @endif
            @endforeach
            <tr>
                <form wire:submit="addRisikoBahaya()" method="POST">
                    @csrf
                    <td></td>
                    <td>
                        <input name="bahaya_fisik" type="text" class="form-control"
                            placeholder="Masukkan Risiko Bahaya" wire:model="bahaya_fisik">
                    </td>
                    <td>
                        <input name="penyebab" type="text" class="form-control" placeholder="Masukkan Penyebab" wire:model="penyebab">
                    </td>
                    <td><button type="submit" class="btn btn-primary"><i class="fa-solid fa-plus"></i>
                        Tambah</button></td>
                </form>
            </tr>
        </tbody>
    </table>
</div>
