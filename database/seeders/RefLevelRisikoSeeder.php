<?php

namespace Database\Seeders;

use App\Models\RefLevelRisiko;
use Illuminate\Database\Seeder;

/**
 * DATA RESMI — diambil dari Kepka BPOM LAMPIRAN I:
 * - Rentang & label: BAB III.D.2.h.5 "Tabel Selera Risiko BPOM" (Matriks Level Risiko)
 * - wajib_pengujian_pengendalian: BAB VII.B, ruang lingkup pengujian aktivitas
 *   pengendalian dilaksanakan untuk level risiko inheren Tinggi (16-19) dan
 *   Sangat Tinggi (20-25); serta wajib untuk SELURUH kategori risiko fraud (BAB IX.B).
 */
class RefLevelRisikoSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'rentang_min' => 1, 'rentang_max' => 5, 'label' => 'Sangat Rendah',
                'simbol_warna' => 'Biru', 'warna_hex' => '#3b82f6',
                'tindakan' => 'Tidak diperlukan tindakan.',
                'wajib_pengujian_pengendalian' => false,
            ],
            [
                'rentang_min' => 6, 'rentang_max' => 11, 'label' => 'Rendah',
                'simbol_warna' => 'Hijau', 'warna_hex' => '#16a34a',
                'tindakan' => 'Diambil tindakan jika diperlukan.',
                'wajib_pengujian_pengendalian' => false,
            ],
            [
                'rentang_min' => 12, 'rentang_max' => 15, 'label' => 'Sedang',
                'simbol_warna' => 'Kuning', 'warna_hex' => '#eab308',
                'tindakan' => 'Diambil tindakan jika sumber daya tersedia.',
                'wajib_pengujian_pengendalian' => false,
            ],
            [
                'rentang_min' => 16, 'rentang_max' => 19, 'label' => 'Tinggi',
                'simbol_warna' => 'Jingga', 'warna_hex' => '#f97316',
                'tindakan' => 'Diperlukan tindakan untuk mengelola risiko.',
                'wajib_pengujian_pengendalian' => true,
            ],
            [
                'rentang_min' => 20, 'rentang_max' => 25, 'label' => 'Sangat Tinggi',
                'simbol_warna' => 'Merah', 'warna_hex' => '#dc2626',
                'tindakan' => 'Diperlukan tindakan segera untuk mengelola risiko.',
                'wajib_pengujian_pengendalian' => true,
            ],
        ];

        foreach ($data as $row) {
            RefLevelRisiko::updateOrCreate(
                ['rentang_min' => $row['rentang_min'], 'rentang_max' => $row['rentang_max']],
                $row
            );
        }
    }
}
