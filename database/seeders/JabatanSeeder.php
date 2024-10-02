<?php

namespace Database\Seeders;

use App\Models\Jabatan;
use App\Models\KondisiLingkunganKerja;
use App\Models\KualifikasiJabatan;
use App\Models\SyaratBakat;
use App\Models\SyaratJabatan;
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
            ]);
        }
    }
}
