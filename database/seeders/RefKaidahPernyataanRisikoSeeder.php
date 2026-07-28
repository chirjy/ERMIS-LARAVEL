<?php

namespace Database\Seeders;

use App\Models\RefKaidahPernyataanRisiko;
use Illuminate\Database\Seeder;

/**
 * K01-K08 dipetakan langsung dari 8 poin (huruf a-h) di Kepka BPOM LAMPIRAN I
 * BAB IV.D.2 "Selain prinsip-prinsip di atas, dalam melakukan identifikasi
 * risiko perlu memperhatikan hal-hal sebagai berikut". Kode K01-K08 adalah
 * penomoran internal ERMIS, bukan istilah resmi Kepka.
 */
class RefKaidahPernyataanRisikoSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'kode_kaidah' => 'K01',
                'judul' => 'Bukan Negasi dari Sasaran/Indikator',
                'deskripsi_kaidah' => 'Pernyataan risiko bukan merupakan negasi (lawan) dari Sasaran Strategis/Indikator Kinerja Organisasi (BAB IV.D.2.a).',
                'contoh_benar' => 'Keterlambatan penerbitan izin edar akibat antrean berkas pemeriksaan yang menumpuk.',
                'contoh_salah' => 'Tidak tercapainya target penerbitan izin edar tepat waktu.',
                'tipe_pemeriksaan' => 'OTOMATIS_NEGASI_SASARAN',
                'tingkat_pelanggaran' => 'ALERT_PERINGATAN',
            ],
            [
                'kode_kaidah' => 'K02',
                'judul' => 'Minimal 1 Downside Risk per Sasaran/Indikator',
                'deskripsi_kaidah' => 'Terhadap setiap Sasaran Strategis dan Indikator Kinerja wajib diidentifikasi minimal 1 (satu) downside risk; upside risk sifatnya tambahan (BAB IV.D.2.b).',
                'contoh_benar' => null,
                'contoh_salah' => null,
                'tipe_pemeriksaan' => 'OTOMATIS_MINIMAL_DOWNSIDE',
                'tingkat_pelanggaran' => 'ALERT_PERINGATAN',
            ],
            [
                'kode_kaidah' => 'K03',
                'judul' => 'Pernyataan Kondisi Peristiwa, Jangan Campur Sebab/Dampak',
                'deskripsi_kaidah' => 'Pernyataan risiko ditulis sebagai kondisi peristiwa saja, tanpa mencantumkan penyebab dan/atau dampaknya — keduanya punya kolom terpisah (BAB IV.D.2.c).',
                'contoh_benar' => 'Keterlambatan pelayanan penerbitan sertifikat.',
                'contoh_salah' => 'Keterlambatan pelayanan karena kurangnya SDM sehingga menyebabkan komplain pelanggan meningkat.',
                'tipe_pemeriksaan' => 'OTOMATIS_CAMPUR_SEBAB_DAMPAK',
                'parameter_pemeriksaan' => ['kata' => ['karena', 'akibat', 'disebabkan', 'sehingga', 'menyebabkan', 'berdampak', 'dampaknya', 'mengakibatkan']],
                'tingkat_pelanggaran' => 'ALERT_PERINGATAN',
            ],
            [
                'kode_kaidah' => 'K04',
                'judul' => 'Satu Kode Risiko = Satu Pernyataan Risiko',
                'deskripsi_kaidah' => 'Setiap kode risiko hanya boleh merepresentasikan satu pernyataan risiko (BAB IV.D.2.d). Dijamin struktural lewat unique key (upt_id, tahun_anggaran, kode_risiko).',
                'tipe_pemeriksaan' => 'MANUAL_JUDGMENT',
                'tingkat_pelanggaran' => 'ALERT_PERINGATAN',
            ],
            [
                'kode_kaidah' => 'K05',
                'judul' => 'Proses Bisnis/Kegiatan Sesuai SOP',
                'deskripsi_kaidah' => 'Penyajian pada kolom Kegiatan/Proses Bisnis diisi sesuai dengan SOP kegiatan yang berlaku (BAB IV.D.2.e).',
                'tipe_pemeriksaan' => 'MANUAL_JUDGMENT',
                'tingkat_pelanggaran' => 'ALERT_PERINGATAN',
            ],
            [
                'kode_kaidah' => 'K06',
                'judul' => 'Konsistensi Sumber Risiko Internal/Eksternal',
                'deskripsi_kaidah' => 'Sumber risiko eksternal harus berasal dari luar lingkup unit kerja, sedangkan sumber risiko internal berasal dari dalam lingkungan unit kerja (BAB IV.D.2.f).',
                'tipe_pemeriksaan' => 'MANUAL_JUDGMENT',
                'tingkat_pelanggaran' => 'ALERT_PERINGATAN',
            ],
            [
                'kode_kaidah' => 'K07',
                'judul' => 'Hindari Diksi Normatif',
                'deskripsi_kaidah' => 'Pernyataan risiko tidak bersifat normatif namun harus spesifik, walau peristiwanya belum/sudah tidak terjadi. Hindari diksi seperti "optimal", "memadai", "maksimal", "efektif" (BAB IV.D.2.g).',
                'contoh_benar' => 'Waktu tunggu layanan melebihi standar 15 menit yang ditetapkan.',
                'contoh_salah' => 'Pelayanan publik tidak optimal.',
                'tipe_pemeriksaan' => 'OTOMATIS_KATA_TERLARANG',
                'parameter_pemeriksaan' => ['kata' => ['optimal', 'memadai', 'maksimal', 'efektif']],
                'tingkat_pelanggaran' => 'ALERT_PERINGATAN',
            ],
            [
                'kode_kaidah' => 'K08',
                'judul' => 'Pemilik Risiko adalah Pimpinan Penanggung Jawab',
                'deskripsi_kaidah' => 'Pemilik Risiko adalah pimpinan yang bertanggung jawab melakukan manajemen risiko di lingkup kerjanya (BAB IV.D.2.h). Dijamin struktural lewat foreign key pemilik_risiko_id.',
                'tipe_pemeriksaan' => 'MANUAL_JUDGMENT',
                'tingkat_pelanggaran' => 'ALERT_PERINGATAN',
            ],
        ];

        foreach ($data as $row) {
            RefKaidahPernyataanRisiko::updateOrCreate(['kode_kaidah' => $row['kode_kaidah']], $row);
        }
    }
}
