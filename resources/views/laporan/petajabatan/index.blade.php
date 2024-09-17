@extends('layouts.main')

@section('container')
    <div class="">
        {{ Breadcrumbs::render('petajabatan', $anjab) }}
    </div>
    <div class="card-head mb-3">
        <h1 class="fw-light fs-4 d-inline nav-item">Peta Jabatan Anjab ABK {{ $anjab->tahun }}</h1>
    </div>
    <hr>
    <a href="{{ route('laporan.index') }}" class="btn btn-primary header1 mb-3"><i data-feather="arrow-left"></i> Kembali</a>
    <livewire:list-unit-petajabatan-table :unitkerjas="$unitKerjas" :anjab="$anjab"/>
    {{-- <div class="mermaid" id="tree">
        graph TD
        {{ $mermaidCode }}
    </div> --}}
    {{-- <script type="module">
      import mermaid from 'https://cdn.jsdelivr.net/npm/mermaid@11/dist/mermaid.esm.min.mjs';
    </script> --}}
@endsection

@section('scripts')
    {{-- <script>
        mermaid.initialize({
            startOnLoad: false,
            theme: 'neutral',
            flowchart: {
                curve: 'bumpY'
            },
            securityLevel: 'loose'
        });
        await mermaid.run({
                querySelector: '.mermaid',
                theme: 'neutral',
            });
    </script> --}}
@endsection
