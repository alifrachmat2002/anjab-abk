<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Jabatan;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\JenisJabatan;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            BakatKerjaSeeder::class,
            TemperamenKerjaSeeder::class,
            MinatKerjaSeeder::class,
            FungsiPekerjaanSeeder::class,
            RolePermissionSeeder::class,
            UnsurSeeder::class,
            UnitKerjaSeeder::class,
            UserSeeder::class,
            JenisJabatanSeeder::class,
            JabatanSeeder::class,
            UpayaFisikSeeder::class,
            JabatanUnsurSeeder::class,
            // UraianTugasSeeder::class,
            TutamSeeder::class,

            AnjabSeeder::class,
        ]);
    }
}
