@extends('layouts.main')

@section('container')
    <div class="">
        {{ Breadcrumbs::render('petajabatan-unitkerja', $anjab, $unit) }}
    </div>
    <div class="card-head mb-3">
        <h1 class="fw-light fs-4 d-inline nav-item">Peta Jabatan Anjab ABK {{ $anjab->tahun }} {{ ' ' . $unit->nama }}</h1>
    </div>
    <hr>
    <a href="{{ route('laporan.petajabatan',['anjab' => $anjab]) }}" class="btn btn-primary header1 mb-3"><i data-feather="arrow-left"></i> Kembali</a>
    <pre class="mermaid text-center" id="tree">
        graph TD
        {{ $mermaidCode }}
    </pre>
@endsection

@section('scripts')
    <script>
        mermaid.initialize({
            startOnLoad: true,
            theme: 'neutral',
            flowchart: {
                curve: 'bumpY'
            },
            securityLevel: 'loose'
        });
    </script>
@endsection
