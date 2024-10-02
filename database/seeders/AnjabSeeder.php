<?php

namespace Database\Seeders;

use App\Models\AbkAnjab;
use App\Models\AbkUnitKerja;
use App\Models\Ajuan;
use App\Models\Jabatan;
use App\Models\JabatanDiajukan;
use App\Models\JabatanTugasTambahan;
use App\Models\JabatanUnsurDiajukan;
use App\Models\Role;
use App\Models\RoleVerifikasi;
use App\Models\UnitKerja;
use App\Models\Unsur;
use App\Models\User;
use App\Models\Verifikasi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AnjabSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create Anjab ajuan instance
        $ajuan = Ajuan::create([
            'tahun' => now()->year,
            'jenis' => 'anjab',
            'is_approved' => 1,
            'parent_id' => null,
        ]);
        $anjab = $ajuan;

        // After creating an ajuan, roles that can verify the ajuan, along with verification instance and set all is_approved to true
        RoleVerifikasi::create([
            'ajuan_id' => $ajuan->id,
            'role_id' => Role::where('name', 'Admin Kepegawaian')->first()->id,
            'is_approved' => true,
        ]);
        Verifikasi::create([
            'ajuan_id' => $ajuan->id,
            'user_id' => User::role('Admin Kepegawaian')->first()->id,
            'is_approved' => true,
            'catatan' => null,
        ]);

        RoleVerifikasi::create([
            'ajuan_id' => $ajuan->id,
            'role_id' => Role::where('name', 'Manajer Kepegawaian')->first()->id,
            'is_approved' => true,
        ]);
        Verifikasi::create([
            'ajuan_id' => $ajuan->id,
            'user_id' => User::role('Manajer Kepegawaian')->first()->id,
            'is_approved' => true,
            'catatan' => null,
        ]);

        RoleVerifikasi::create([
            'ajuan_id' => $ajuan->id,
            'role_id' => Role::where('name', 'Kepala BUK')->first()->id,
            'is_approved' => true,
        ]);
        Verifikasi::create([
            'ajuan_id' => $ajuan->id,
            'user_id' => User::role('Kepala BUK')->first()->id,
            'is_approved' => true,
            'catatan' => null,
        ]);

        RoleVerifikasi::create([
            'ajuan_id' => $ajuan->id,
            'role_id' => Role::where('name', 'Wakil Rektor 2')->first()->id,
            'is_approved' => true,
        ]);
        Verifikasi::create([
            'ajuan_id' => $ajuan->id,
            'user_id' => User::role('Wakil Rektor 2')->first()->id,
            'is_approved' => true,
            'catatan' => null,
        ]);

        // create a parent ABK
        $parentAbk = Ajuan::create([
            'tahun' => now()->year,
            'jenis' => 'abk',
            'is_approved' => true,
        ]);

        // define who can verify the parent ABK, along with verification instance and set all is_approved to true
        RoleVerifikasi::create([
            'ajuan_id' => $parentAbk->id,
            'role_id' => Role::where('name', 'Admin Kepegawaian')->first()->id,
            'is_approved' => true,
        ]);
        Verifikasi::create([
            'ajuan_id' => $ajuan->id,
            'user_id' => User::role('Admin Kepegawaian')->first()->id,
            'is_approved' => true,
            'catatan' => null,
        ]);
        RoleVerifikasi::create([
            'ajuan_id' => $parentAbk->id,
            'role_id' => Role::where('name', 'Wakil Rektor 2')->first()->id,
            'is_approved' => true,
        ]);
        Verifikasi::create([
            'ajuan_id' => $ajuan->id,
            'user_id' => User::role('Wakil Rektor 2')->first()->id,
            'is_approved' => true,
            'catatan' => null,
        ]);

        // get all unique unit kerja from the jabatan
        $unitKerjas = UnitKerja::all();
        // for each unit kerja,
        // create ABK with current year as 'tahun' and abk as 'jenis'
        foreach ($unitKerjas as $unitKerja) {
            $abk = Ajuan::create([
                'parent_id' => $parentAbk->id,
                'tahun' => now()->year,
                'jenis' => 'abk',
                'is_approved' => true,
            ]);

            // also create instance of abk_anjab to map which ones are the abk for an anjab
            AbkAnjab::create([
                'abk_id' => $abk->id,
                'anjab_id' => $anjab->id,
            ]);

            // also create instance of abk_unit_kerja to map which unit kerja the abk is for
            AbkUnitKerja::create([
                'abk_id' => $abk->id,
                'unit_kerja_id' => $unitKerja->id,
            ]);

            // also create role verifikasi for each abk based on unsur of the unit kerja along with verification instance and set all is_approved to true
            if (in_array($unitKerja->unsur->nama, ['Lembaga', 'Badan', 'Biro', 'Direktorat', 'Unit Pelaksana Teknis', 'Kantor', 'Satuan Pengawas Internal', 'Dewan Penasihat Universitas'])) {
                // create roles that can verify the ajuan
                RoleVerifikasi::create([
                    'ajuan_id' => $abk->id,
                    'role_id' => Role::where('name', 'Operator Unit Kerja')->first()->id,
                    'is_approved' => true,
                ]);
                Verifikasi::create([
                    'ajuan_id' => $abk->id,
                    'user_id' => User::role('Operator Unit Kerja')->first()->id,
                    'is_approved' => true,
                    'catatan' => null,
                ]);

                RoleVerifikasi::create([
                    'ajuan_id' => $abk->id,
                    'role_id' => Role::where('name', 'Manajer Unit Kerja')->first()->id,
                    'is_approved' => true,
                ]);
                Verifikasi::create([
                    'ajuan_id' => $abk->id,
                    'user_id' => User::role('Manajer Unit Kerja')->first()->id,
                    'is_approved' => true,
                    'catatan' => null,
                ]);

                RoleVerifikasi::create([
                    'ajuan_id' => $abk->id,
                    'role_id' => Role::where('name', 'Kepala Unit Kerja')->first()->id,
                    'is_approved' => true,
                ]);
                Verifikasi::create([
                    'ajuan_id' => $abk->id,
                    'user_id' => User::role('Kepala Unit Kerja')->first()->id,
                    'is_approved' => true,
                    'catatan' => null,
                ]);

                RoleVerifikasi::create([
                    'ajuan_id' => $abk->id,
                    'role_id' => Role::where('name', 'Admin Kepegawaian')->first()->id,
                    'is_approved' => true,
                ]);
                Verifikasi::create([
                    'ajuan_id' => $abk->id,
                    'user_id' => User::role('Admin Kepegawaian')->first()->id,
                    'is_approved' => true,
                    'catatan' => null,
                ]);

                RoleVerifikasi::create([
                    'ajuan_id' => $abk->id,
                    'role_id' => Role::where('name', 'Wakil Rektor 2')->first()->id,
                    'is_approved' => true,
                ]);
                Verifikasi::create([
                    'ajuan_id' => $abk->id,
                    'user_id' => User::role('Wakil Rektor 2')->first()->id,
                    'is_approved' => true,
                    'catatan' => null,
                ]);
            }

            if ($unitKerja->unsur->nama == 'Fakultas/Sekolah') {
                // create roles that can verify the ajuan along with verification instance and set all is_approved to true
                RoleVerifikasi::create([
                    'ajuan_id' => $abk->id,
                    'role_id' => Role::where('name', 'Operator Unit Kerja')->first()->id,
                    'is_approved' => true,
                ]);
                Verifikasi::create([
                    'ajuan_id' => $abk->id,
                    'user_id' => User::role('Operator Unit Kerja')->first()->id,
                    'is_approved' => true,
                    'catatan' => null,
                ]);

                RoleVerifikasi::create([
                    'ajuan_id' => $abk->id,
                    'role_id' => Role::where('name', 'Manajer Tata Usaha')->first()->id,
                    'is_approved' => true,
                ]);
                Verifikasi::create([
                    'ajuan_id' => $abk->id,
                    'user_id' => User::role('Manajer Tata Usaha')->first()->id,
                    'is_approved' => true,
                    'catatan' => null,
                ]);

                RoleVerifikasi::create([
                    'ajuan_id' => $abk->id,
                    'role_id' => Role::where('name', 'Wakil Dekan 2')->first()->id,
                    'is_approved' => true,
                ]);
                Verifikasi::create([
                    'ajuan_id' => $abk->id,
                    'user_id' => User::role('Wakil Dekan 2')->first()->id,
                    'is_approved' => true,
                    'catatan' => null,
                ]);

                RoleVerifikasi::create([
                    'ajuan_id' => $abk->id,
                    'role_id' => Role::where('name', 'Admin Kepegawaian')->first()->id,
                    'is_approved' => true,
                ]);
                Verifikasi::create([
                    'ajuan_id' => $abk->id,
                    'user_id' => User::role('Admin Kepegawaian')->first()->id,
                    'is_approved' => true,
                    'catatan' => null,
                ]);

                RoleVerifikasi::create([
                    'ajuan_id' => $abk->id,
                    'role_id' => Role::where('name', 'Wakil Rektor 2')->first()->id,
                    'is_approved' => true,
                ]);
                Verifikasi::create([
                    'ajuan_id' => $abk->id,
                    'user_id' => User::role('Wakil Rektor 2')->first()->id,
                    'is_approved' => true,
                    'catatan' => null,
                ]);
            }
        }

        $data_jabatan = json_decode(file_get_contents(database_path('seeders/data/anjab/fakultas_teknik.json')), true);

        foreach ($data_jabatan as $data) {
            
            $ajuanId = Ajuan::where('parent_id', $parentAbk->id)
                ->where('jenis', 'abk')
                ->whereHas('unitKerja', function ($query) {
                    $query->where('nama', 'Fakultas Teknik');
                })
                ->latest()
                ->first()->id;

            $jabatan = JabatanDiajukan::create([
                'ajuan_id' => $ajuan->id,
                'jabatan_id' => Jabatan::where('nama', $data['nama'])->first()->id,
                'nama' => $data['nama'],
                'kode' => $data['kode'],
                'ikhtisar' => $data['ikhtisar'],
                'prestasi' => $data['prestasi'],
            ]);

            $unsur = Unsur::where('nama',"Fakultas/Sekolah")->first();

            JabatanUnsurDiajukan::create([
                    'jabatan_diajukan_id' => $jabatan->id,
                    'unsur_id' => $unsur->id,
                ]);

            $abkJabatan1 = $jabatan->abkJabatan()->create([
                'abk_id' => $ajuanId,
                'jabatan_id' => $jabatan->id,
                'jabatan_tutam_id' => JabatanTugasTambahan::where('nama', 'Supervisor Akademik dan Kemahasiswaan')->first()->id,
            ]);

            $abkJabatan2 = $jabatan->abkJabatan()->create([
                'abk_id' => $ajuanId,
                'jabatan_id' => $jabatan->id,
                'jabatan_tutam_id' => JabatanTugasTambahan::where('nama', 'Supervisor Sumberdaya')->first()->id,
            ]);

            foreach ($data['tugas'] as $tugas) {
                if (!empty($tugas)) {
                    $uraianTugas = $jabatan->uraianTugas()->create([
                        'jabatan_diajukan_id' => $jabatan->id,
                        'nama_tugas' => $tugas['nama_tugas'],
                    ]);

                    $abkJabatan1->detailAbk()->create([
                        'ajuan_id' => $ajuanId,
                        'uraian_tugas_diajukan_id' => $uraianTugas->id,
                        'hasil_kerja' => $tugas['hasil_kerja'],
                        'waktu_penyelesaian' => $tugas['waktu_penyelesaian'] / 60,
                        'jumlah_hasil_kerja' => $tugas['jumlah_hasil_kerja'],
                    ]);

                    $abkJabatan2->detailAbk()->create([
                        'ajuan_id' => $ajuanId,
                        'uraian_tugas_diajukan_id' => $uraianTugas->id,
                        'hasil_kerja' => $tugas['hasil_kerja'],
                        'waktu_penyelesaian' => $tugas['waktu_penyelesaian'] / 60,
                        'jumlah_hasil_kerja' => $tugas['jumlah_hasil_kerja'],
                    ]);

                    
                }
            }
        }

    }
}
