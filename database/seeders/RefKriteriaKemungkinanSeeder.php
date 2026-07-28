<?php

namespace Database\Seeders;

use App\Models\RefKriteriaKemungkinan;
use Illuminate\Database\Seeder;

/** DATA RESMI — Kepka BPOM LAMPIRAN I BAB V.D.5 "Kriteria Kemungkinan". */
class RefKriteriaKemungkinanSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'level' => 1, 'label' => 'Hampir Tidak Terjadi',
                'probabilitas_min_persen' => 0, 'probabilitas_max_persen' => 1,
                'kriteria_jumlah_frekuensi_non_low' => '< 2 kali dalam 1 tahun terakhir',
                'kriteria_frekuensi_low' => '< 1 kejadian dalam lebih dari 60 bulan terakhir',
                'contoh_kejadian' => 'Kejadian sangat jarang, misal bencana alam atau kebakaran gedung yang belum pernah/hampir tidak pernah terjadi.',
            ],
            [
                'level' => 2, 'label' => 'Jarang Terjadi',
                'probabilitas_min_persen' => 1, 'probabilitas_max_persen' => 10,
                'kriteria_jumlah_frekuensi_non_low' => '2 s.d 5 kali dalam 1 tahun terakhir',
                'kriteria_frekuensi_low' => 'Minimal 1 kejadian dalam 60 bulan terakhir',
                'contoh_kejadian' => 'Kecelakaan kerja ringan yang sesekali pernah terjadi dalam 5 tahun terakhir.',
            ],
            [
                'level' => 3, 'label' => 'Kadang-Kadang Terjadi',
                'probabilitas_min_persen' => 10, 'probabilitas_max_persen' => 20,
                'kriteria_jumlah_frekuensi_non_low' => '6 s.d 9 kali dalam 1 tahun terakhir',
                'kriteria_frekuensi_low' => 'Minimal 1 kejadian dalam 36 bulan terakhir',
                'contoh_kejadian' => 'Keluhan layanan yang muncul beberapa kali dalam setahun namun belum rutin.',
            ],
            [
                'level' => 4, 'label' => 'Sering Terjadi',
                'probabilitas_min_persen' => 20, 'probabilitas_max_persen' => 50,
                'kriteria_jumlah_frekuensi_non_low' => '10 s.d 12 kali dalam 1 tahun terakhir',
                'kriteria_frekuensi_low' => 'Minimal 1 kejadian dalam 24 bulan terakhir',
                'contoh_kejadian' => 'Keterlambatan layanan yang terjadi hampir tiap bulan.',
            ],
            [
                'level' => 5, 'label' => 'Hampir Pasti Terjadi',
                'probabilitas_min_persen' => 50, 'probabilitas_max_persen' => 100,
                'kriteria_jumlah_frekuensi_non_low' => '> 12 kali dalam 1 tahun terakhir',
                'kriteria_frekuensi_low' => 'Minimal 1 kejadian dalam 12 bulan terakhir',
                'contoh_kejadian' => 'Kegagalan sistem IT atau keterlambatan layanan yang terjadi lebih dari sekali sebulan.',
            ],
        ];

        foreach ($data as $row) {
            RefKriteriaKemungkinan::updateOrCreate(['level' => $row['level']], $row);
        }
    }
}
