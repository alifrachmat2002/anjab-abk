<?php

namespace Database\Seeders;

use App\Models\BakatKerja;
use App\Models\FungsiPekerjaan;
use App\Models\Jabatan;
use App\Models\KondisiLingkunganKerja;
use App\Models\KualifikasiJabatan;
use App\Models\MinatKerja;
use App\Models\PendidikanFormal;
use App\Models\SyaratBakat;
use App\Models\SyaratJabatan;
use App\Models\TemperamenKerja;
use App\Models\UpayaFisik;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data_fakultas_teknik = json_decode(file_get_contents(database_path('seeders/data/anjab/fakultas_teknik.json')), true);
        $data_spi = json_decode(file_get_contents(database_path('seeders/data/anjab/spi.json')), true);

        $data = array_merge($data_fakultas_teknik, $data_spi);

        foreach ($data as $data_jabatan) {
            $jabatan = Jabatan::create([
                'nama' => $data_jabatan['nama'],
                'kode' => $data_jabatan['kode'],
                'ikhtisar' => $data_jabatan['ikhtisar'],
                'prestasi' => $data_jabatan['prestasi'],
                'jenis_kelamin' => $data_jabatan['jenis_kelamin'],
                'umur' => $data_jabatan['umur'],
                'tinggi_badan' => $data_jabatan['tinggi_badan'],
                'penampilan' => $data_jabatan['penampilan'],
                'keterampilan' => $data_jabatan['keterampilan'],
                'berat_badan' => $data_jabatan['berat_badan'],
            ]);

            PendidikanFormal::create([
                'jabatan_id' => $jabatan->id,
                'jenjang' => 'S-1',
                'jurusan' => 'Manajemen'
            ]);

            if (isset($data_jabatan['tugas'])) {
                foreach ($data_jabatan['tugas'] as $uraian_tugas) {
                    $jabatan->uraianTugas()->create([
                        'jabatan_id' => $jabatan->id,
                        'nama_tugas' => $uraian_tugas['nama_tugas'],
                    ]);
                }
            }

            for ($i = 1; $i < BakatKerja::count(); $i++) {
                if (rand(0, 1)) {
                    $jabatan->bakatKerja()->create([
                        'jabatan_id' => $jabatan->id,
                        'bakat_kerja_id' => $i,
                    ]);
                }
            }

            for ($i = 1; $i < TemperamenKerja::count(); $i++) {
                if (rand(0, 1)) {
                    $jabatan->temperamenKerja()->create([
                        'jabatan_id' => $jabatan->id,
                        'temperamen_kerja_id' => $i,
                    ]);
                }
            }

            for ($i = 1; $i < MinatKerja::count(); $i++) {
                if (rand(0, 1)) {
                    $jabatan->minatKerja()->create([
                        'jabatan_id' => $jabatan->id,
                        'minat_kerja_id' => $i,
                    ]);
                }
            }

            for ($i = 1; $i < UpayaFisik::count(); $i++) {
                if (rand(0, 1)) {
                    $jabatan->upayaFisik()->create([
                        'jabatan_id' => $jabatan->id,
                        'upaya_fisik_id' => $i,
                    ]);
                }
            }

            for ($i = 1; $i < FungsiPekerjaan::count(); $i++) {
                if (rand(0, 1)) {
                    $jabatan->fungsiPekerjaan()->create([
                        'jabatan_id' => $jabatan->id,
                        'fungsi_pekerjaan_id' => $i,
                    ]);
                }
            }
        }
    }
}
