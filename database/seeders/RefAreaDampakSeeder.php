<?php

namespace Database\Seeders;

use App\Models\RefAreaDampak;
use Illuminate\Database\Seeder;

class RefAreaDampakSeeder extends Seeder
{
    public function run(): void
    {
        $downside = [
            ['nama' => 'Beban Keuangan Negara', 'prioritas' => 1],
            ['nama' => 'Reputasi', 'prioritas' => 2],
            ['nama' => 'Sanksi Pidana/Perdata/Administratif', 'prioritas' => 3],
            ['nama' => 'Kecelakaan & Keselamatan Kerja', 'prioritas' => 4],
            ['nama' => 'Gangguan Layanan Organisasi', 'prioritas' => 5],
            ['nama' => 'Penurunan Kinerja', 'prioritas' => 6],
        ];

        $upside = [
            ['nama' => 'Peningkatan Reputasi', 'prioritas' => 1],
            ['nama' => 'Peningkatan Layanan', 'prioritas' => 2],
            ['nama' => 'Peningkatan Kinerja', 'prioritas' => 3],
        ];

        foreach ($downside as $row) {
            RefAreaDampak::updateOrCreate(
                ['nama' => $row['nama'], 'jenis_risiko' => 'DOWNSIDE'],
                array_merge($row, ['jenis_risiko' => 'DOWNSIDE'])
            );
        }

        foreach ($upside as $row) {
            RefAreaDampak::updateOrCreate(
                ['nama' => $row['nama'], 'jenis_risiko' => 'UPSIDE'],
                array_merge($row, ['jenis_risiko' => 'UPSIDE'])
            );
        }
    }
}
