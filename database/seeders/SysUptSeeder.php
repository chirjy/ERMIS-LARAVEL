<?php

namespace Database\Seeders;

use App\Models\SysUpt;
use Illuminate\Database\Seeder;

class SysUptSeeder extends Seeder
{
    public function run(): void
    {
        SysUpt::updateOrCreate(
            ['kode' => 'LOKA-KOTBAR'],
            [
                'nama' => 'Loka Pengawas Obat dan Makanan di Kabupaten Kotawaringin Barat',
                'jenjang' => 'UNIT_PELAKSANA_TEKNIS',
                'provinsi' => 'Kalimantan Tengah',
                'aktif' => true,
            ]
        );

        SysUpt::updateOrCreate(
            ['kode' => 'PUSAT-INSPEKTORAT'],
            [
                'nama' => 'Inspektorat Utama BPOM',
                'jenjang' => 'ESELON_I',
                'provinsi' => 'DKI Jakarta',
                'aktif' => true,
            ]
        );
    }
}
