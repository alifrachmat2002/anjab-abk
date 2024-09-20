<table cellpadding="3" style="border-collapse: collapse; width: 100%;">
    <thead>
        <tr>
            <th width="100" colspan="4" style="font-weight: bold; text-align: center;">Laporan ABK 2024
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($unitkerjas as $unit)
            <tr>
                <th colspan="4" style="background-color: #8EA9DB; font-weight: bold; border: 1px solid black;">
                    {{ $unit->nama }}</th>
            </tr>
            <tr style="background-color: #000; font-weight: bold;">
                <th width="70"
                    style="background-color: #bdbdbd; font-weight: bold; width: 70%; border: 1px solid black; ">Nama
                    Jabatan</th>
                <th width="10"
                    style="background-color: #bdbdbd; font-weight: bold; width: 10%; text-align: center; border: 1px solid black; ">
                    B</th>
                <th width="10"
                    style="background-color: #bdbdbd; font-weight: bold; width: 10%; text-align: center; border: 1px solid black;">
                    K</th>
                <th width="10"
                    style="background-color: #bdbdbd; font-weight: bold; width: 10%; text-align: center; border: 1px solid black;">
                    +/-</th>
            </tr>
            @foreach ($unit->unsur->jabatanTugasTambahan as $tutam)
                <tr style="background-color: #ededed;">
                    <td style="background-color: #dfe2e5; border: 1px solid black;">{{ $tutam->nama }}</td>
                    <td style="text-align: center; background-color: #dfe2e5; border: 1px solid black;">1</td>
                    <td style="text-align: center; background-color: #dfe2e5; border: 1px solid black;">1</td>
                    <td style="text-align: center; background-color: #dfe2e5; border: 1px solid black;">0</td>
                </tr>
                @foreach ($tutam->AbkJabatan as $abk)
                    <tr>
                        <td style="text-indent: 1%; border: 1px solid black;">{{ $abk->jabatan->nama }}</td>
                        <td style="text-align: center; border: 1px solid black;">{{ $abk->kebutuhan_pegawai }}</td>
                        <td style="text-align: center; border: 1px solid black;">0</td>
                        <td style="text-align: center; border: 1px solid black;">0</td>
                    </tr>
                @endforeach
            @endforeach
            <tr></tr>
        @endforeach
    </tbody>
</table>
<table>
    <thead>
        <tr>
            <th width="100" colspan="4" style="font-weight: bold; text-align: center;">Kebutuhan Pegawai Jabatan
            </th>
        </tr>
        <tr>
            <th width="100"
                style="background-color: #bdbdbd; font-weight: bold; width: 70%; border: 1px solid black; ">Nama Jabatan
            </th>
            <th width="10"
                style="background-color: #bdbdbd; font-weight: bold; width: 10%; text-align: center; border: 1px solid black; ">
                B</th>
            <th width="10"
                style="background-color: #bdbdbd; font-weight: bold; width: 10%; text-align: center; border: 1px solid black;">
                K</th>
            <th width="10"
                style="background-color: #bdbdbd; font-weight: bold; width: 10%; text-align: center; border: 1px solid black;">
                +/-</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($jabatans as $jabatan)
            <tr>
                <td style="border: 1px solid black;">{{ $jabatan->nama_jabatan }}</td>
                <td style="text-align: center; border: 1px solid black;">{{ $jabatan->total_kebutuhan }}</td>
                <td style="text-align: center; border: 1px solid black;">0</td>
                <td style="text-align: center; border: 1px solid black;">0</td>
            </tr>
        @endforeach
    </tbody>
</table>
