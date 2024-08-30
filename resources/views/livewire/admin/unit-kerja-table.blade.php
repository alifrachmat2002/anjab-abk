<?php

use Livewire\Volt\Component;
use App\Models\UnitKerja;   

new class extends Component {
    public $search = '';
    public $selectedUnitKerja;

    // RENDER unit kerja
    // renders unit kerja based on search query
    // if search query is empty, renders all unit kerja
    // if search query is not empty, renders unit kerja that matches the search query
    public function with()
    {
        if (empty($this->search)) {
            return [
                'unitKerjas' => UnitKerja::with('unsur')->get(),
            ];
        } else {
            return [
                'unitKerjas' => UnitKerja::where('nama', 'like', '%' . $this->search . '%')
                    ->orWhereHas('unsur', function ($query) {
                        $query->where('nama', 'like', '%' . $this->search . '%');
                    })
                    ->get(),
            ];
        }
    }

    // DELETE Unit Kerja
    // assigns Unit Kerja to be deleted to $selectedUnit Kerja
    public function deleteJabatan(UnitKerja $unitKerja)
    {
        $this->selectedUnitKerja = $unitKerja;
    }

    // DESTROY Unit Kerja
    // deletes selected unit kerja in $selectedUnitKerja from database
    public function destroyUnitKerja()
    {
        $this->selectedUnitKerja->delete();
    }
}; ?>

<div>
    <div class="form-floating mb-3 w-25 ">
        <input type="email" wire:model.live="search" class="form-control" id="search"
            placeholder="Cari Unit Kerja">
        <label for="search">Cari Unit Kerja</label>
    </div>

    @if (empty($this->search))
        <table class="table table-bordered table-striped">
            <thead>
                <th>No</th>
                <th>Nama</th>
                <th>Unsur</th>
                <th>Aksi</th>
            </thead>
            <tbody>
                @foreach ($unitKerjas as $unitKerja)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $unitKerja->nama }}</td>
                        <td>
                            {{ $unitKerja->unsur->nama }}
                        </td>
                        <td>
                            <a href="{{ route('admin.unit-kerja.edit', ['unitKerja' => $unitKerja->id]) }}"
                                type="button" class="btn btn-warning"><i class="fa-solid fa-edit"></i></a>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                data-bs-target="#modalHapusUnitKerja{{ $unitKerja->id }}"><i
                                    class="fa-solid fa-trash"></i></button>
                            <div class="modal fade" tabindex="-1" id="modalHapusUnitKerja{{ $unitKerja->id }}">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Hapus Tugas Tambahan?</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Tugas Tambahan yang sudah dihapus tidak akan bisa dikembalikan lagi.</p>
                                        </div>
                                        <div class="modal-footer">
                                            <form
                                                action="{{ route('admin.unit-kerja.destroy', ['unitKerja' => $unitKerja->id]) }}"
                                                method="POST">
                                                @csrf
                                                @method('delete')
                                                <input type="hidden" value="{{ $unitKerja->id }}">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Tidak</button>
                                                <button type="submit" class="btn btn-primary">Ya</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <table class="table mb-0 table-bordered table-striped">
            <thead>
                <th>No</th>
                <th>Nama</th>
                <th>Unsur</th>
                <th>Aksi</th>
            </thead>
            <tbody>
                @foreach ($unitKerjas as $unitKerja)
                    <tr wire:key="">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $unitKerja->nama }}</td>
                        <td>
                            {{ $unitKerja->unsur->nama }}
                        </td>
                        <td>
                            <a href="{{ route('admin.unit-kerja.edit', ['unitKerja' => $unitKerja->id]) }}"
                                type="button" class="btn btn-warning"><i class="fa-solid fa-edit"></i></a>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                data-bs-target="#modalHapusUnitKerja{{ $unitKerja->id }}"><i
                                    class="fa-solid fa-trash"></i></button>
                            <div class="modal fade" tabindex="-1" id="modalHapusUnitKerja{{ $unitKerja->id }}">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Hapus Tugas Tambahan?</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Tugas Tambahan yang sudah dihapus tidak akan bisa dikembalikan lagi.</p>
                                        </div>
                                        <div class="modal-footer">
                                            <form
                                                action="{{ route('admin.unit-kerja.destroy', ['unitKerja' => $unitKerja->id]) }}"
                                                method="POST">
                                                @csrf
                                                @method('delete')
                                                <input type="hidden" value="{{ $unitKerja->id }}">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Tidak</button>
                                                <button type="submit" class="btn btn-primary">Ya</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
