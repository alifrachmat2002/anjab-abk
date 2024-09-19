<?php

namespace Database\Seeders;

use App\Models\JabatanTugasTambahan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TutamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        JabatanTugasTambahan::create([
            'jenis_jabatan_id' => 1,
            'unsur_id' => 2,
            'parent_id' => null,
            'nama' => 'Rektor',
            'kode' => null
        ]);

        JabatanTugasTambahan::create([
            'jenis_jabatan_id' => 2,
            'unsur_id' => 2,
            'parent_id' => 1,
            'nama' => 'Dekan',
            'kode' => null
        ]);

        JabatanTugasTambahan::create([
            'jenis_jabatan_id' => 3,
            'unsur_id' => 2,
            'parent_id' => 2,
            'nama' => 'Wakil Dekan Akademik dan Kemahasiswaan',
            'kode' => null
        ]);

        JabatanTugasTambahan::create([
            'jenis_jabatan_id' => 3,
            'unsur_id' => 2,
            'parent_id' => 2,
            'nama' => 'Wakil Dekan Sumberdaya',
            'kode' => null
        ]);

        JabatanTugasTambahan::create([
            'jenis_jabatan_id' => 3,
            'unsur_id' => 2,
            'parent_id' => 2,
            'nama' => 'Manajer Tata Usaha',
            'kode' => null
        ]);

        JabatanTugasTambahan::create([
            'jenis_jabatan_id' => 4,
            'unsur_id' => 2,
            'parent_id' => 5,
            'nama' => 'Supervisor Akademik dan Kemahasiswaan',
            'kode' => null
        ]);

        JabatanTugasTambahan::create([
            'jenis_jabatan_id' => 4,
            'unsur_id' => 2,
            'parent_id' => 5,
            'nama' => 'Supervisor Sumberdaya',
            'kode' => null
        ]);

        JabatanTugasTambahan::create([
            'jenis_jabatan_id' => 2,
            'unsur_id' => 9,
            'parent_id' => 1,
            'nama' => 'Ketua Satuan Pengawas Internal',
            'kode' => null
        ]);

        JabatanTugasTambahan::create([
            'jenis_jabatan_id' => 4,
            'unsur_id' => 9,
            'parent_id' => 1,
            'nama' => 'Supervisor Satuan Pengawas Internal',
            'kode' => null
        ]);
    }
}
