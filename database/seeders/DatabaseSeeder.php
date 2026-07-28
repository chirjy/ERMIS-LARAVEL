<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SysUptSeeder::class,
            SysRoleSeeder::class,
            RefKategoriRisikoSeeder::class,
            RefAreaDampakSeeder::class,
            RefMatriksRisikoSeeder::class,
            RefLevelRisikoSeeder::class,
            RefIstilahGlosariumSeeder::class,
            RefKriteriaKemungkinanSeeder::class,
            RefKriteriaDampakSeeder::class,
            RefKaidahPernyataanRisikoSeeder::class,
            RefMetodePenilaianSeeder::class,
            DemoUserSeeder::class,
        ]);
    }
}
