<?php

namespace App\Services;

use App\Models\RefKaidahPernyataanRisiko;
use App\Models\TrnIdentifikasiRisiko;

/**
 * Memvalidasi teks "pernyataan_risiko" terhadap kaidah K01-K08 yang dipetakan
 * dari BAB IV.D.2 Kepka (prinsip identifikasi risiko huruf a-h). Kaidah yang
 * bertipe OTOMATIS_* dicek langsung di sini; kaidah MANUAL_JUDGMENT hanya
 * ditampilkan sebagai pengingat (checklist) di UI, tidak divalidasi sistem.
 */
class PernyataanRisikoValidationService
{
    /**
     * @return array<int, array{kode_kaidah:string, judul:string, pesan:string, tingkat_pelanggaran:string}>
     */
    public function validasiTeks(string $pernyataanRisiko, ?string $sasaranStrategis = null): array
    {
        $pelanggaran = [];
        $kaidahs = RefKaidahPernyataanRisiko::whereIn('tipe_pemeriksaan', [
            'OTOMATIS_KATA_TERLARANG', 'OTOMATIS_NEGASI_SASARAN', 'OTOMATIS_CAMPUR_SEBAB_DAMPAK',
        ])->get()->keyBy('tipe_pemeriksaan');

        // K07 — hindari diksi normatif (optimal, memadai, maksimal, efektif, dst)
        if ($kaidah = $kaidahs->get('OTOMATIS_KATA_TERLARANG')) {
            $kataTerlarang = $kaidah->parameter_pemeriksaan['kata'] ?? [];
            $ditemukan = [];
            foreach ($kataTerlarang as $kata) {
                if (preg_match('/\b'.preg_quote($kata, '/').'\b/i', $pernyataanRisiko)) {
                    $ditemukan[] = $kata;
                }
            }
            if ($ditemukan) {
                $pelanggaran[] = $this->format($kaidah, 'Ditemukan diksi normatif: "'.implode('", "', $ditemukan).'". Gunakan pernyataan yang spesifik dan terukur.');
            }
        }

        // K01 — pernyataan risiko bukan negasi dari Sasaran/Indikator
        if ($sasaranStrategis && ($kaidah = $kaidahs->get('OTOMATIS_NEGASI_SASARAN'))) {
            if ($this->terdeteksiNegasiSasaran($pernyataanRisiko, $sasaranStrategis)) {
                $pelanggaran[] = $this->format($kaidah, 'Pernyataan risiko tampak sekadar menegasikan (melawan) Sasaran Strategis. Tuliskan sebagai peristiwa/kondisi konkret, bukan lawan kata dari sasaran.');
            }
        }

        // K03 — pernyataan risiko jangan mencampur sebab/dampak
        if ($kaidah = $kaidahs->get('OTOMATIS_CAMPUR_SEBAB_DAMPAK')) {
            $kataSebabDampak = $kaidah->parameter_pemeriksaan['kata'] ?? [];
            $ditemukan = [];
            foreach ($kataSebabDampak as $kata) {
                if (preg_match('/\b'.preg_quote($kata, '/').'\b/i', $pernyataanRisiko)) {
                    $ditemukan[] = $kata;
                }
            }
            if ($ditemukan) {
                $pelanggaran[] = $this->format($kaidah, 'Ditemukan kata penghubung sebab-akibat ("'.implode('", "', $ditemukan).'"). Pisahkan penyebab ke kolom Penyebab Risiko dan dampaknya ke kolom Dampak Risiko.');
            }
        }

        return $pelanggaran;
    }

    /**
     * K02 — Setiap Sasaran Strategis & Indikator Kinerja wajib minimal 1 downside risk.
     * Dijalankan sebagai laporan agregat per UPT+tahun, bukan per satu pernyataan risiko.
     *
     * @return array<int, array{sasaran_strategis:string, indikator_kinerja:string, jumlah_risiko:int}>
     */
    public function cekMinimalDownside(int $uptId, int $tahunAnggaran): array
    {
        $grup = TrnIdentifikasiRisiko::where('upt_id', $uptId)
            ->where('tahun_anggaran', $tahunAnggaran)
            ->get()
            ->groupBy(fn ($r) => $r->sasaran_strategis.'|'.$r->indikator_kinerja);

        $tanpaDownside = [];
        foreach ($grup as $key => $risikos) {
            $adaDownside = $risikos->contains('jenis_risiko', 'DOWNSIDE');
            if (! $adaDownside) {
                [$sasaran, $indikator] = explode('|', $key, 2);
                $tanpaDownside[] = [
                    'sasaran_strategis' => $sasaran,
                    'indikator_kinerja' => $indikator,
                    'jumlah_risiko' => $risikos->count(),
                ];
            }
        }

        return $tanpaDownside;
    }

    private function terdeteksiNegasiSasaran(string $pernyataan, string $sasaran): bool
    {
        $kataNegasi = ['tidak tercapainya', 'tidak terlaksananya', 'tidak terwujudnya', 'gagalnya', 'kegagalan'];
        $adaNegasi = false;
        foreach ($kataNegasi as $kata) {
            if (stripos($pernyataan, $kata) !== false) {
                $adaNegasi = true;
                break;
            }
        }
        if (! $adaNegasi) {
            return false;
        }

        // Kemiripan sederhana: hitung overlap kata bermakna (>=4 huruf) antara
        // pernyataan risiko dan sasaran strategis. Overlap tinggi + ada kata
        // negasi => indikasi pernyataan risiko hanya "melawan" sasaran.
        $normalisasi = fn (string $s) => array_unique(array_filter(
            preg_split('/[^a-z0-9]+/i', strtolower($s)),
            fn ($w) => strlen($w) >= 4
        ));

        $kataSasaran = $normalisasi($sasaran);
        $kataPernyataan = $normalisasi($pernyataan);

        if (empty($kataSasaran)) {
            return false;
        }

        $overlap = count(array_intersect($kataSasaran, $kataPernyataan));

        return ($overlap / count($kataSasaran)) >= 0.5;
    }

    private function format(RefKaidahPernyataanRisiko $kaidah, string $pesan): array
    {
        return [
            'kode_kaidah' => $kaidah->kode_kaidah,
            'judul' => $kaidah->judul,
            'pesan' => $pesan,
            'tingkat_pelanggaran' => $kaidah->tingkat_pelanggaran,
        ];
    }
}
