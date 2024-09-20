<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    {{-- Bootstrap CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />

    {{-- bootstrap js --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</head>

<body class="p-4">
    <h1 class="text-center mb-3">Laporan ABK Periode {{ $anjab->tahun }}</h1>
    <table class="table table-bordered mb-5">
        <tbody>
            @foreach ($unitkerjas as $unit)
                <tr class="table-secondary">
                    <th>{{ $unit->nama }}</th>
                </tr>
                <tr>
                    <td colspan="" class="p-0 m-0">
                        <div class="">
                            <table class="table table-bordered mb-0 pb-0">
                                <thead>
                                    <tr>
                                        <th class="table-">Nama Jabatan</th>
                                        <th style="width: 10%">B</th>
                                        <th style="width: 10%">K</th>
                                        <th style="width: 10%">+/-</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($unit->unsur->jabatanTugasTambahan as $tutam)
                                        <tr>
                                            <td>{{ $tutam->nama }}</td>
                                            <td>1</td>
                                            <td>1</td>
                                            <td>0</td>
                                        </tr>
                                        @foreach ($tutam->AbkJabatan as $abk)
                                            <tr>
                                                <td class="ms-3">
                                                    <p class="m-0 p-0 ms-4">{{ $abk->jabatan->nama }}</p>
                                                </td>
                                                <td>{{ $abk->kebutuhan_pegawai }}</td>
                                                <td>0</td>
                                                <td>0</td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <h1 class="text-center mb-3">Rekapitulasi Kebutuhan Jabatan</h1>
    <table class="table table-bordered mb-0 pb-0">
        <thead class="table-secondary">
            <tr>
                <th class="table-">Nama Jabatan</th>
                <th style="width: 10%">B</th>
                <th style="width: 10%">K</th>
                <th style="width: 10%">+/-</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($jabatans as $jabatan)
                <tr>
                    <td class="">
                        {{ $jabatan->nama_jabatan }}
                    </td>
                    <td>{{ $jabatan->total_kebutuhan }}</td>
                    <td>0</td>
                    <td>0</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
