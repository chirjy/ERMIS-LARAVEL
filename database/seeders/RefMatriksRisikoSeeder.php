<?php

namespace Database\Seeders;

use App\Models\RefMatriksRisiko;
use Illuminate\Database\Seeder;

/**
 * DATA RESMI — diambil dari Kepka BPOM tentang Petunjuk Pelaksanaan Penerapan
 * Manajemen Risiko, LAMPIRAN I BAB V.D.7 "Tabel Peta Risiko Inheren" (Matriks
 * Analisis Risiko). Nilai besaran_risiko BUKAN hasil perkalian kemungkinan x
 * dampak — dampak sengaja diberi bobot lebih tinggi daripada kemungkinan.
 */
class RefMatriksRisikoSeeder extends Seeder
{
    public function run(): void
    {
        // [level_kemungkinan][level_dampak] => besaran_risiko
        $matriks = [
            5 => [1 => 9,  2 => 15, 3 => 18, 4 => 23, 5 => 25], // Hampir Pasti Terjadi
            4 => [1 => 6,  2 => 12, 3 => 16, 4 => 19, 5 => 24], // Sering Terjadi
            3 => [1 => 4,  2 => 10, 3 => 14, 4 => 17, 5 => 22], // Kadang-Kadang Terjadi
            2 => [1 => 2,  2 => 7,  3 => 11, 4 => 13, 5 => 21], // Jarang Terjadi
            1 => [1 => 1,  2 => 3,  3 => 5,  4 => 8,  5 => 20], // Hampir Tidak Terjadi
        ];

        foreach ($matriks as $kemungkinan => $baris) {
            foreach ($baris as $dampak => $besaran) {
                RefMatriksRisiko::updateOrCreate(
                    ['level_kemungkinan' => $kemungkinan, 'level_dampak' => $dampak],
                    ['besaran_risiko' => $besaran]
                );
            }
        }
    }
}
