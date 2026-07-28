<?php

namespace Database\Seeders;

use App\Models\RefIstilahGlosarium;
use Illuminate\Database\Seeder;

/**
 * DATA RESMI — dipetakan dari Kepka BPOM LAMPIRAN I BAB I.C "Istilah dan
 * Definisi Manajemen Risiko" (40 istilah, nomor 1-40). Definisi ditulis ulang
 * ringkas dalam bahasa sendiri, bukan kutipan verbatim.
 */
class RefIstilahGlosariumSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['Tujuan Organisasi', 'Hasil yang ingin dicapai organisasi melalui peran yang diambil menuju masa depan, sebagaimana tergambar dalam visi dan misi.'],
            ['Risiko', 'Peluang terjadinya suatu peristiwa yang dapat memengaruhi pencapaian tujuan organisasi.'],
            ['Manajemen Risiko', 'Pendekatan sistematis yang meliputi budaya, proses, dan struktur untuk menentukan tindakan terbaik terkait risiko yang dihadapi dalam pencapaian tujuan organisasi.'],
            ['Proses Manajemen Risiko', 'Rangkaian kegiatan yang dilakukan secara berkesinambungan, sistematis, logis, dan terukur sebagai bagian dari pengelolaan risiko.'],
            ['Pengendalian Internal', 'Proses integral dalam kegiatan dan tindakan yang dilakukan terus-menerus oleh pimpinan dan pegawai untuk memberi keyakinan memadai atas tercapainya tujuan organisasi.'],
            ['Risiko Positif / Upside Risk', 'Peluang terjadinya peristiwa yang akan meningkatkan keberhasilan pencapaian tujuan organisasi.'],
            ['Risiko Negatif / Downside Risk', 'Peluang terjadinya peristiwa yang akan menghambat keberhasilan pencapaian tujuan organisasi.'],
            ['Kategori Risiko', 'Klasifikasi risiko berdasarkan pernyataan risikonya.'],
            ['Unit Pemilik Risiko (UPR)', 'Unit yang bertanggung jawab atas pelaksanaan pengelolaan manajemen risiko pada tingkatannya masing-masing.'],
            ['Sistem Manajemen POM Terintegrasi', 'Kerangka kerja acuan bagi seluruh unit kerja dalam menjalankan tugas dan fungsi untuk mencapai tujuan organisasi secara efektif, efisien, dan akuntabel.'],
            ['Audit Internal Sistem Manajemen POM Terintegrasi', 'Proses sistematis, independen, dan terdokumentasi untuk memperoleh dan mengevaluasi bukti objektif atas pemenuhan kriteria Sistem Manajemen POM.'],
            ['Unit Kepatuhan Risiko (UKR)', 'Unit kerja yang menyusun kebijakan teknis, pembinaan, pengendalian, pemantauan, dan pelaporan kepatuhan intern dan manajemen risiko pada unit organisasinya.'],
            ['Stakeholder', 'Pihak-pihak yang berinteraksi dengan organisasi dalam pencapaian sasaran.'],
            ['Tim Penyelenggara SPI BPOM', 'Tim integral yang bertugas mengelola pengendalian intern, manajemen risiko, dan pemeliharaan Sistem Manajemen POM Terintegrasi.'],
            ['Budaya Sadar Risiko', 'Kesadaran mulai dari pimpinan sampai individu pegawai dalam mengimplementasikan manajemen risiko pada tugas sehari-hari.'],
            ['Penilaian Risiko', 'Kegiatan mengidentifikasi dan menganalisis seluruh risiko atau potensi risiko yang memengaruhi pencapaian tujuan organisasi, secara sistematis dan terukur.'],
            ['Loss Event Database (LED)', 'Dokumen berisi catatan kejadian kerugian pada tahun berjalan maupun tahun sebelumnya, baik yang sudah maupun belum teridentifikasi dalam profil risiko.'],
            ['Identifikasi Risiko', 'Kegiatan mengidentifikasi seluruh risiko atau potensi risiko yang memengaruhi pencapaian tujuan organisasi, secara sistematis dan terukur.'],
            ['Analisis Risiko', 'Proses penilaian risiko dengan menggabungkan kemungkinan dan konsekuensi risiko, dengan mempertimbangkan keandalan sistem pengendalian yang ada.'],
            ['Kemungkinan Risiko', 'Proses menetapkan (mengukur) peluang terjadinya suatu risiko.'],
            ['Dampak Risiko', 'Proses menetapkan (mengukur) dampak potensial dari aktivitas proses bisnis kritis yang dapat terjadi.'],
            ['Area Dampak', 'Area pengelompokan yang disebabkan oleh adanya dampak dari suatu risiko.'],
            ['Kriteria Level Dampak', 'Kriteria penentuan level dampak dari risiko berdasarkan area dampak risiko.'],
            ['Level Risiko', 'Besaran risiko yang mendeskripsikan tingkat risiko, berlaku pada risiko inheren, residual, maupun mitigasi.'],
            ['Selera Risiko', 'Ambang batas besaran level risiko yang masih berada dalam area penerimaan risiko dan tidak memerlukan kegiatan pengendalian tambahan.'],
            ['Aktivitas Pengendalian', 'Pengendalian intern yang telah ada dan rutin dilakukan, yang dapat menurunkan level risiko inheren.'],
            ['Atribut Pengendalian', 'Karakteristik atau unsur yang melekat pada suatu aktivitas pengendalian, dipakai sebagai acuan menilai kecukupan rancangan dan pelaksanaannya.'],
            ['Pengujian Aktivitas Pengendalian', 'Penilaian efektivitas atas pengendalian yang telah ada dan rutin dilakukan dalam menurunkan level risiko.'],
            ['Risiko Inheren', 'Besarnya eksposur awal suatu risiko tanpa memperhitungkan kontrol/pengendalian internal yang dimiliki.'],
            ['Risiko Residual', 'Besaran/level risiko setelah mempertimbangkan adanya aktivitas pengendalian yang telah ada.'],
            ['Risiko Mitigasi', 'Proyeksi besarnya eksposur risiko setelah pelaksanaan rencana mitigasi risiko, dengan asumsi rencana mitigasi terlaksana secara utuh dan efektif.'],
            ['Prioritas Risiko', 'Tingkat urutan risiko dalam evaluasi dan pengujian aktivitas pengendalian.'],
            ['Risiko Utama', 'Risiko dengan level risiko residual tinggi/sangat tinggi yang memerlukan adanya mitigasi risiko.'],
            ['Maturitas Manajemen Risiko', 'Tingkat kematangan UPR dalam menerapkan manajemen risiko.'],
            ['Evaluasi Risiko', 'Upaya mengidentifikasi perubahan atas pergeseran tingkat level risiko yang dikaitkan dengan mitigasi atau faktor lain yang memengaruhi.'],
            ['Rencana Tindak Pengendalian (RTP)', 'Rencana tindakan yang disusun untuk menangani/memitigasi risiko ke tingkat yang dapat diterima oleh UPR.'],
            ['Pemantauan dan Reviu', 'Kegiatan penilaian secara berkala terhadap efektivitas penerapan manajemen risiko, untuk memastikan proses pengelolaan risiko berjalan sesuai rencana.'],
            ['Profil Risiko', 'Kumpulan hasil identifikasi risiko, analisis risiko, evaluasi risiko, dan respon risiko yang ditetapkan oleh UPR.'],
            ['Peta Risiko', 'Gambaran dari berbagai risiko yang diidentifikasi berdasarkan tingkat kemungkinan dan dampak terjadinya.'],
            ['Pegawai', 'ASN, CASN, PPPK, dan pegawai lainnya yang digaji dari anggaran pendapatan dan belanja negara.'],
        ];

        foreach ($data as $i => [$istilah, $definisi]) {
            RefIstilahGlosarium::updateOrCreate(
                ['nomor_urut' => $i + 1],
                ['istilah' => $istilah, 'definisi' => $definisi, 'referensi_bab' => 'BAB I.C']
            );
        }
    }
}
