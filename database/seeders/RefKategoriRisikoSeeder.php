<?php

namespace Database\Seeders;

use App\Models\RefKategoriRisiko;
use Illuminate\Database\Seeder;

class RefKategoriRisikoSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['kode' => 'KB', 'nama' => 'Risiko Kebijakan', 'prioritas' => 1,
                'penjelasan' => 'Risiko perumusan, penetapan, kekosongan/ketiadaan hukum, dan kepatuhan kebijakan internal/eksternal.',
                'contoh_kasus' => 'Keterlambatan penerbitan Peraturan BPOM turunan menyebabkan unit kerja tidak memiliki dasar hukum untuk menindak pelanggaran baru di lapangan.'],
            ['kode' => 'RP', 'nama' => 'Risiko Reputasi', 'prioritas' => 2,
                'penjelasan' => 'Persepsi/tingkat kepercayaan stakeholder eksternal terhadap organisasi.',
                'contoh_kasus' => 'Pemberitaan negatif media nasional terkait temuan produk mengandung BKO yang beredar tanpa terdeteksi sejak awal.'],
            ['kode' => 'FR', 'nama' => 'Risiko Fraud', 'prioritas' => 3,
                'penjelasan' => 'Perbuatan sengaja untuk menguntungkan diri/pihak lain secara tidak sah.',
                'contoh_kasus' => 'Oknum petugas menerima gratifikasi dari pelaku usaha agar proses sertifikasi dipercepat tanpa pemeriksaan lengkap.'],
            ['kode' => 'HK', 'nama' => 'Risiko Hukum', 'prioritas' => 4,
                'penjelasan' => 'Ketidakmampuan mengelola persoalan hukum, tuntutan/gugatan.',
                'contoh_kasus' => 'Gugatan perdata dari pelaku usaha atas pembatalan izin edar yang dianggap tidak sesuai prosedur.'],
            ['kode' => 'KM', 'nama' => 'Risiko Kemitraan', 'prioritas' => 5,
                'penjelasan' => 'Pola hubungan BPOM dengan stakeholder/antar unit kerja.',
                'contoh_kasus' => 'Ketidaksinkronan data antara Balai Besar POM dan Pusat menyebabkan duplikasi pengawasan sarana produksi.'],
            ['kode' => 'SP', 'nama' => 'Risiko SPBE', 'prioritas' => 6,
                'penjelasan' => 'Ketidakmemadaian TI (software/hardware) yang memengaruhi capaian SPBE.',
                'contoh_kasus' => 'Server aplikasi registrasi pangan mengalami downtime lebih dari 8 jam saat periode puncak pengajuan.'],
            ['kode' => 'KK', 'nama' => 'Risiko Kesehatan & Keselamatan Kerja', 'prioritas' => 7,
                'penjelasan' => 'Keselamatan kerja, kesehatan, dan keamanan lingkungan kerja pegawai.',
                'contoh_kasus' => 'Petugas pengawas mengalami kecelakaan lalu lintas dalam perjalanan dinas sidak sarana produksi.'],
            ['kode' => 'OP', 'nama' => 'Risiko Operasional', 'prioritas' => 8,
                'penjelasan' => 'Ketidakcukupan proses internal (SDM, aset, kinerja, layanan, keuangan) atau kejadian eksternal.',
                'contoh_kasus' => 'Keterlambatan penyelesaian pengujian laboratorium akibat kekurangan reagen sehingga melampaui SLA layanan.'],
        ];

        foreach ($data as $row) {
            RefKategoriRisiko::updateOrCreate(['kode' => $row['kode']], $row);
        }
    }
}
