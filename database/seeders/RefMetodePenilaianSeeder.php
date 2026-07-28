<?php

namespace Database\Seeders;

use App\Models\RefMetodePenilaian;
use Illuminate\Database\Seeder;

/** DATA RESMI — Kepka BPOM LAMPIRAN I BAB V.D.4 & D.6 (metode penentuan kemungkinan/dampak). */
class RefMetodePenilaianSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['kode' => 'FGD', 'nama' => 'Focus Group Discussion', 'cocok_untuk' => 'KEDUANYA',
                'deskripsi' => 'Diskusi kelompok terarah bersama pihak terkait dan/atau pakar, dipakai bila diperlukan dalam rangka identifikasi risiko atau penentuan level risiko.'],
            ['kode' => 'KUESIONER', 'nama' => 'Kuesioner', 'cocok_untuk' => 'KEDUANYA',
                'deskripsi' => 'Serangkaian pertanyaan tertulis kepada responden untuk mengumpulkan pendapat atau informasi terkait risiko.'],
            ['kode' => 'EXPERT_JUDGEMENT', 'nama' => 'Expert Judgement', 'cocok_untuk' => 'KEDUANYA',
                'deskripsi' => 'Referensi dari pendapat atau hasil penelitian ahli/akademisi, baik lewat diskusi langsung maupun studi literatur.'],
            ['kode' => 'KONSENSUS', 'nama' => 'Konsensus', 'cocok_untuk' => 'KEMUNGKINAN',
                'deskripsi' => 'Kesepakatan bersama antara Pimpinan UPR dan Pengelola Risiko, dipakai ketika seluruh metode estimasi lain tidak dapat dilakukan, dengan pertimbangan cermat dan konservatif.'],
            ['kode' => 'ANALISIS_DATA', 'nama' => 'Analisis Data', 'cocok_untuk' => 'DAMPAK',
                'deskripsi' => 'Estimasi berdasarkan dokumentasi historis seperti laporan audit/evaluasi/reviu, laporan LED, atau dokumen lain yang memuat detail dampak kejadian risiko.'],
            ['kode' => 'SIMULASI_PROYEKSI', 'nama' => 'Simulasi / Proyeksi', 'cocok_untuk' => 'DAMPAK',
                'deskripsi' => 'Estimasi dampak berdasarkan simulasi atau proyeksi kejadian risiko dengan asumsi-asumsi logis yang dapat dipertanggungjawabkan.'],
        ];

        foreach ($data as $row) {
            RefMetodePenilaian::updateOrCreate(['kode' => $row['kode']], $row);
        }
    }
}
