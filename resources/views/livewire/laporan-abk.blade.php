<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Computed;
use App\Models\UnitKerja;

new class extends Component {
    public $unitkerjas;
    public $selectedUnitKerja;
    public $abk;
    public $tutams;

    #[Computed]
    public function selectedUnit()
    {
        return UnitKerja::where('id', $this->selectedUnitKerja)->first();
    }

    #[Computed]
    public function selectedTutam()
    {
        return $this->tutams->where('unsur_id', $this->selectedUnit->unsur_id);
    }
}; ?>

<div>
    <label for="jabatan" class="form-label">Pilih Berdasarkan Unit Kerja</label>
    <select name="jabatan" id="jabatan" wire:model.change="selectedUnitKerja" class="form-select mb-3">
        <option value="">Pilih Unit Kerja</option>
        @foreach ($this->unitkerjas ?? [] as $unit)
            <option value={{ $unit->id }}>{{ $unit->nama }}</option>
        @endforeach
    </select>
    @if ($this->selectedUnitKerja)
        <table class="table table-bordered ">
            <thead>
                <tr>
                    <th>Nama Jabatan</th>
                    <th style="width: 10%">B</th>
                    <th style="width: 10%">K</th>
                    <th style="width: 10%">+/-</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($this->selectedTutam as $tutam)
                    <tr>
                        <td>{{ $tutam->nama }}</td>
                        <td>1</td>
                        <td>1</td>
                        <td>0</td>
                    </tr>
                    @foreach ($tutam->AbkJabatan as $abk)
                        <tr>
                            <td class="ms-3"><p class="m-0 p-0 ms-4">{{ $abk->jabatan->nama }}</p></td>
                            <td>{{ $abk->kebutuhan_pegawai }}</td>
                            <td>0</td>
                            <td>0</td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    @endif
</div>
