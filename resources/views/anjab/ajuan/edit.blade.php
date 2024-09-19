@extends('layouts.main')

@section('container')
    <div class="">
        {{ Breadcrumbs::render('edit-ajuan-anjab', $ajuan) }}
    </div>
    <div class="card-head mb-3">
        <h1 class="fw-light fs-4 d-inline nav-item">Edit Ajuan Analisis Jabatan {{ $ajuan->tahun }}</h1>
    </div>
    <div class="card dropdown-divider mb-4"></div>
    <livewire:edit-jabatan-table :ajuan="$ajuan" :unsurs="$unsurs" :jabatans="$jabatans" mode="edit" />
    @include('anjab.partials.modaljabatan')
    <form action="{{ route('anjab.ajuan.update', ['ajuan' => $ajuan->id]) }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-primary header1 text-white"><i data-feather="save"></i>
            Simpan Ajuan Informasi Jabatan</button>
    </form>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Pilih Unsur yang membawahi jabatan",
                dropdownParent: $('#modalJabatan')
            });
        });
    </script>
    <script>
        const modal = document.getElementById('modalJabatan');

        modal.addEventListener('show.bs.modal', event => {
            console.log('NJIR DIPENCET');
            const btn = event.relatedTarget
            console.log(btn)

            const atasan = btn.getAttribute('data-bs-atasan')
            console.log(atasan)

            const inputAtasan = document.getElementById('parent_id');

            inputAtasan.value = atasan;
        })

        var unitKerja = document.getElementById('unit_kerja');

        select(unitKerja, {
            search: true
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Pilih Unsur yang membawahi jabatan",
                dropdownParent: $('#modalJabatan')
            });
        });
    </script>
    <script>
        const select2 = document.getElementById('select2input');

        const selectAllCheckbox = document.getElementById('semuaUnsurCheckbox');
        selectAllCheckbox.addEventListener('change', event => {
            if (event.target.checked) {
                select2.value = "Semua Unsur";
                select2.disabled = true;

            } else {
                select2.disabled = false;

            }
        })
    </script>
    @if ($errors->any())
        {{-- BANG ERROR --}}
        <script>
            const myModal = document.getElementById('modalJabatan');
            const bootstrapModal = new bootstrap.Modal(myModal);
            bootstrapModal.show();
        </script>
    @endif
@endsection
