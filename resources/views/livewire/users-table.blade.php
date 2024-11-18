<?php

use function Livewire\Volt\{state,with};
use App\Models\User;

with([
    'users' => fn() => User::all()
]);

?>

<div>
    <table class="table table-striped table-bordered">
        <thead>
            <th style="width: 50px;">No</th>
            <th>Nama User</th>
            <th>Role</th>
            <th>User</th>
        </thead>
        <tbody>
            @foreach ($users as $user)
                @if ($user->id == auth()->user()->id)
                    @continue
                @endif
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $user->name }}</td>
                    <td>
                        {{ $user->getRoleNames()->first() }}
                    </td>
                    <td>
                        <button type="button" class="btn btn-warning"><i data-feather="edit"></i></button>
                        <button type="button" class="btn btn-danger" data-bs-target="#modalDelete" data-bs-toggle="modal"><i data-feather="trash"></i></button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

        {{-- <div class="modal fade" tabindex="-1" id="modalDelete">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Hapus User?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>User yang sudah dihapus tidak dapat dikembalikan lagi.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" wire:click>Ya</button>
                        <button type="button" class="btn btn-primary" class="btn-close" data-bs-dismiss="modal">Tidak</button>
                    </div>
                </div>
            </div>
        </div> --}}
    
</div>
